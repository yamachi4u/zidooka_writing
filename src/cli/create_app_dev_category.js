
import { WpClient } from './src/services/wpClient.js';

async function createAppDevCategory() {
  const wp = new WpClient();
  console.log('Creating "アプリ開発" category...');
  try {
    const cat = await wp.createCategory('アプリ開発');
    console.log('Category created/found:', cat);
  } catch (e) {
    console.error('Error creating category:', e.message);
  }
}

createAppDevCategory();
