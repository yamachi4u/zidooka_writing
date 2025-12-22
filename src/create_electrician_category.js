import { WpClient } from './services/wpClient.js';

async function main() {
  const client = new WpClient();
  try {
    console.log('Creating category "電気工事士"...');
    const cat = await client.createCategory('電気工事士');
    console.log('Category created/found:', cat);
    
    await client.syncMetadata();
    console.log('Metadata synced.');
  } catch (error) {
    console.error('Error:', error.response ? error.response.data : error.message);
  }
}

main();
