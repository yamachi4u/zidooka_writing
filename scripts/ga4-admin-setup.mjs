/**
 * GA4 Admin API: Auto-register Key Events (Conversions)
 *
 * Usage:
 *   node scripts/ga4-admin-setup.mjs --property 344037190 [--dry-run]
 *
 * Prerequisites:
 *   - Service account must have "Editor" or "Admin" role on the GA4 property
 *   - GOOGLE_SERVICE_ACCOUNT_KEY_PATH must point to a valid service account JSON
 *
 * Events registered:
 *   calendar_date_confirmed
 *   calendar_share
 *   calendar_next_action_link_click
 */

import {
  fetchJson,
  getAccessToken,
  parseArgs,
} from './google-api-common.mjs';

const CONVERSION_EVENTS = [
  {
    eventName: 'calendar_date_confirmed',
    description: 'User confirmed a specific date (opened one detail and stayed)',
  },
  {
    eventName: 'calendar_share',
    description: 'User shared a date via LINE, X, or copy URL',
  },
  {
    eventName: 'calendar_next_action_link_click',
    description: 'User clicked a next-action link inside a recommendation card',
  },
];

async function listExistingConversionEvents({ property, token }) {
  const url = `https://analyticsadmin.googleapis.com/v1alpha/properties/${property}/conversionEvents`;
  const data = await fetchJson(url, { token });
  return (data.conversionEvents || []).map((e) => e.eventName);
}

async function createConversionEvent({ property, token, eventName }) {
  const url = `https://analyticsadmin.googleapis.com/v1alpha/properties/${property}/conversionEvents`;
  return fetchJson(url, {
    method: 'POST',
    token,
    body: { eventName },
  });
}

async function main() {
  const args = parseArgs(process.argv.slice(2));
  const property = args.property || process.env.GOOGLE_GA4_PROPERTY_ID;
  const dryRun = args['dry-run'] || false;

  if (!property) {
    console.error('Missing GA4 property ID. Set GOOGLE_GA4_PROPERTY_ID or pass --property');
    process.exitCode = 1;
    return;
  }

  console.log(`GA4 property: ${property}`);
  console.log(`Mode: ${dryRun ? 'DRY-RUN' : 'LIVE'}`);

  const { accessToken } = await getAccessToken({
    keyFile: args['key-file'],
    scopes: ['https://www.googleapis.com/auth/analytics.edit'],
  });

  console.log('Fetching existing key events...');
  const existing = await listExistingConversionEvents({
    property,
    token: accessToken,
  });
  console.log(`Existing key events: ${existing.length > 0 ? existing.join(', ') : '(none)'}`);

  for (const ev of CONVERSION_EVENTS) {
    if (existing.includes(ev.eventName)) {
      console.log(`  SKIP (already exists): ${ev.eventName}`);
      continue;
    }

    if (dryRun) {
      console.log(`  WOULD CREATE: ${ev.eventName} — ${ev.description}`);
      continue;
    }

    try {
      const result = await createConversionEvent({
        property,
        token: accessToken,
        eventName: ev.eventName,
      });
      console.log(`  CREATED: ${result.eventName} (${result.createTime})`);
    } catch (error) {
      console.error(`  FAILED: ${ev.eventName} — ${error.message}`);
    }
  }

  console.log('\nDone.');
}

main().catch((error) => {
  console.error(error.message);
  process.exitCode = 1;
});
