# cPanel Deployment

1. In cPanel, select PHP 8.2 or newer and enable PDO MySQL, mbstring, OpenSSL, cURL, fileinfo, tokenizer, and XML.
2. Create a MySQL database and user, then grant the user all privileges on that database.
3. Upload and extract the deployment ZIP outside `public_html` when possible.
4. Point the domain document root to this project's `public` directory.
5. Copy `.env.cpanel.example` to `.env` and replace every placeholder. Use newly rotated Gmail and Gemini credentials.
6. Set `ADMIN_INITIAL_PASSWORD` to a unique password containing at least 12 characters.
7. From cPanel Terminal, run `bash deploy-cpanel.sh` in the project directory.
8. Ensure `storage` and `bootstrap/cache` are writable by PHP (usually 775 on shared hosting).
9. Open `/login`, sign in as `admin` with the password from `ADMIN_INITIAL_PASSWORD`, and test account creation, email, image upload, and the AI assistant.

Do not upload the local `.env`, `database/database.sqlite`, test caches, or local logs. Do not run PHPUnit on the production server.