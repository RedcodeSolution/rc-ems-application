# Laravel Blade Project

## 🚀 Setup Instructions

### 1. Clone the repository
```bash
git clone <your-repo-url>
cd <your-project-folder>
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Create the environment file
```bash
cp .env.example .env
```
Then, update `.env` with your database and app configuration.

### 4. Generate application key
```bash
php artisan key:generate
```

### 5. Run migrations
```bash
php artisan migrate
```

### 6. Compile assets
```bash
npm run dev
```

### 7. Start the local server
```bash
php artisan serve
```
Visit `http://127.0.0.1:8000` in your browser.
