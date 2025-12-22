import { WpClient } from './services/wpClient.js';

async function main() {
  const client = new WpClient();
  try {
    console.log('Creating category "アクセシビリティ"...');
    const cat = await client.createCategory('アクセシビリティ');
    console.log('Category created/found:', cat);
    
    await client.syncMetadata();
    console.log('Metadata synced.');
  } catch (error) {
    console.error('Error:', error.response ? error.response.data : error.message);
  }
}

main();
