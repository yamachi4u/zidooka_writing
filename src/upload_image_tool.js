
import { WpClient } from './services/wpClient.js';
import fs from 'fs/promises';
import path from 'path';

const args = process.argv.slice(2);
const filePath = args[0];

if (!filePath) {
    console.error('Please specify a file path');
    process.exit(1);
}

async function uploadSingleImage() {
    try {
        const wp = new WpClient();
        console.log('Checking authentication...');
        const auth = await wp.checkAuth();
        if (!auth.success) {
            throw new Error(`Authentication failed: ${auth.error}`);
        }
        console.log(`Authenticated as ${auth.user.name}`);

        const buffer = await fs.readFile(filePath);
        const filename = path.basename(filePath);
        //簡易的なMIMEタイプ判定
        const ext = path.extname(filePath).toLowerCase();
        let mimeType = 'image/jpeg';
        if (ext === '.png') mimeType = 'image/png';
        if (ext === '.gif') mimeType = 'image/gif';
        if (ext === '.webp') mimeType = 'image/webp';

        console.log(`Uploading ${filename}...`);
        const result = await wp.uploadMedia(buffer, filename, mimeType);
        
        console.log('Upload successful!');
        console.log('URL:', result.source_url);

    } catch (error) {
        console.error('Error:', error.message);
        if (error.response) {
            console.error('API Response:', error.response.data);
        }
    }
}

uploadSingleImage();
