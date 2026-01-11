import { WpClient } from './src/services/wpClient.js';
import axios from 'axios';

async function deleteDuplicates() {
    const wp = new WpClient();
    
    const idsToDelete = [2863];
    
    for (const id of idsToDelete) {
        try {
            console.log(`Deleting post ${id}...`);
            await axios.delete(`${wp.baseUrl}/wp/v2/posts/${id}?force=true`, { headers: wp.authHeader });
            console.log(`Deleted post ${id}`);
        } catch (error) {
            console.error(`Failed to delete post ${id}:`, error.message);
        }
    }
}

deleteDuplicates();
