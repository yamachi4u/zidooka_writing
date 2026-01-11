import { WpClient } from './src/services/wpClient.js';

async function main() {
  const client = new WpClient();
  try {
    console.log('Creating category "OCR"...');
    const cat = await client.createCategory('OCR');
    console.log('Category created/found:', cat);
    
    await client.syncMetadata();
    console.log('Metadata synced.');
  } catch (error) {
    console.error('Error:', error.response ? error.response.data : error.message);
  }
}

main();
