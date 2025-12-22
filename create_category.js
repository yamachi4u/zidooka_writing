
import { WpClient } from './src/wp.js';

async function createSummaryCategory() {
  const wp = new WpClient();
  console.log('Creating "summary" category...');
  try {
    const cat = await wp.createCategory('summary');
    console.log('Category created/found:', cat);
  } catch (e) {
    console.error('Error creating category:', e.message);
  }
}

createSummaryCategory();
