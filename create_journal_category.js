import { WpClient } from './src/services/wpClient.js';

async function main() {
  const client = new WpClient();
  try {
    console.log('Creating category "Journal"...');
    await client.createCategory('Journal');
    
    console.log('Creating category "Summary"...');
    await client.createCategory('Summary');

    await client.syncMetadata();
    console.log('Metadata synced.');
  } catch (error) {
    console.error('Error:', error.response ? error.response.data : error.message);
  }
}

main();
