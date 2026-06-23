import 'dotenv/config';

const key = process.env.BING_API_KEY;
const siteUrl = 'https://www.zidooka.com';

async function getCall(method, extra = {}) {
  const url = new URL(`https://ssl.bing.com/webmaster/api.svc/json/${method}`);
  url.searchParams.set('apikey', key);
  for (const [k, v] of Object.entries(extra)) url.searchParams.set(k, v);
  const res = await fetch(url.toString());
  const text = await res.text();
  console.log(`GET ${method}: [${res.status}] ${text.substring(0, 500)}`);
}

async function postCall(method, body) {
  const url = new URL(`https://ssl.bing.com/webmaster/api.svc/json/${method}`);
  url.searchParams.set('apikey', key);
  const res = await fetch(url.toString(), {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body),
  });
  const text = await res.text();
  console.log(`POST ${method}: [${res.status}] ${text.substring(0, 500)}`);
}

// Submit sitemap via POST
await postCall('SubmitSitemap', {
  siteUrl,
  sitemapUrl: 'https://www.zidooka.com/sitemap.xml'
});

// Remove http URL
await postCall('RemoveUrlFromIndex', {
  siteUrl,
  url: 'http://www.zidooka.com/archives/2838'
});
