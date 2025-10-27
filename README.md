# Blog API with Beautiful UI

A comprehensive blog management system built with Laravel, featuring a beautiful admin interface and RESTful API for categories and posts management.

## 🚀 Features

### Core Functionality
- ✅ **Full CRUD Operations** for Categories and Posts
- ✅ **Beautiful Responsive UI** with Tailwind CSS
- ✅ **Rich Text Editor** (Quill.js) for post content
- ✅ **Image Upload Support** for categories and posts
- ✅ **Hierarchical Categories** (parent-child relationships)
- ✅ **Post Analytics** (views, likes tracking)
- ✅ **Search and Filtering** capabilities
- ✅ **RESTful API** endpoints
- ✅ **Form Validation** with custom error messages
- ✅ **Pagination** support
- ✅ **Dark Mode** support

### UI Features
- 🎨 **Modern Design** with clean, professional interface
- 📱 **Fully Responsive** - works on all devices
- 🌙 **Dark/Light Mode** automatic switching
- 🖼️ **Drag & Drop** image upload with preview
- ✍️ **Rich Text Editor** with Quill.js for content creation
- ⚡ **Real-time Validation** and feedback
- 🎯 **Interactive Elements** with smooth animations
- ♿ **Accessibility** features and keyboard navigation

### API Features
- 🔗 **RESTful API** with consistent JSON responses
- 🔍 **Advanced Search** and filtering
- 📊 **Analytics Endpoints** for views and likes
- 🔐 **Authentication** support
- 📄 **Comprehensive Documentation**

## 📋 Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL/PostgreSQL/SQLite
- Laravel 10.x

## 🛠️ Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd blog_api
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=blog_api
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the server**
   ```bash
   php artisan serve
   ```

## 🎯 Usage

### Web Interface

1. **Access the application**: `http://localhost:8000`
2. **Login** with the seeded admin account:
   - Email: `admin@blog.com`
   - Password: `password`
3. **Navigate** through the dashboard to manage categories and posts

### API Endpoints

#### Public API (No Authentication)
```bash
# Get all categories
GET /api/v1/categories

# Get specific category
GET /api/v1/categories/{id}

# Get all posts with filtering
GET /api/v1/posts?search=laravel&category_id=1&sort_by=created_at&sort_order=desc

# Get specific post
GET /api/v1/posts/{id}

# Like a post
POST /api/v1/posts/{id}/like
```

#### Protected API (Authentication Required)
```bash
# Categories CRUD
GET /api/v1/admin/categories
POST /api/v1/admin/categories
GET /api/v1/admin/categories/{id}
PUT /api/v1/admin/categories/{id}
DELETE /api/v1/admin/categories/{id}

# Posts CRUD
GET /api/v1/admin/posts
POST /api/v1/admin/posts
GET /api/v1/admin/posts/{id}
PUT /api/v1/admin/posts/{id}
DELETE /api/v1/admin/posts/{id}
```

## 📁 Project Structure

```
blog_api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CategoryController.php
│   │   │   └── PostController.php
│   │   └── Requests/
│   │       ├── StoreCategoryRequest.php
│   │       ├── UpdateCategoryRequest.php
│   │       ├── StorePostRequest.php
│   │       └── UpdatePostRequest.php
│   └── Models/
│       ├── Category.php
│       └── Post.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── BlogSeeder.php
├── resources/
│   └── views/
│       ├── categories/
│       ├── posts/
│       └── layouts/
├── routes/
│   ├── web.php
│   └── api.php
└── public/
    └── storage/ (symlink)
```

## 🎨 UI Screenshots

### Dashboard
- Quick stats overview
- Navigation cards for categories and posts
- Recent posts display

### Categories Management
- Grid layout with category cards
- Image preview and upload
- Hierarchical category support
- Bulk operations

### Posts Management
- List view with search and filtering
- Rich text editor for content
- Image upload with preview
- Analytics display (views, likes)

## 🔧 Configuration

### File Upload
Images are stored in `storage/app/public/` and accessible via `/storage/` URL.

### Pagination
Default pagination is set to 10 items per page, configurable via API parameters.

### Validation
Comprehensive validation rules for all forms with custom error messages.

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 📚 API Documentation

Detailed API documentation is available in `API_DOCUMENTATION.md`.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

If you encounter any issues or have questions, please open an issue on GitHub.

---

**Built with ❤️ using Laravel and Tailwind CSS**
