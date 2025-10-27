# Blog API Documentation

## Overview
This is a comprehensive blog API built with Laravel that provides CRUD operations for categories and posts, along with a beautiful admin interface.

## Features
- ✅ Full CRUD operations for Categories and Posts
- ✅ Beautiful responsive UI with Tailwind CSS
- ✅ Rich text editor (Quill.js) for post content
- ✅ Image upload support for categories and posts
- ✅ Hierarchical categories (parent-child relationships)
- ✅ Post views and likes tracking
- ✅ Search and filtering capabilities
- ✅ RESTful API endpoints
- ✅ Form validation
- ✅ Pagination support
- ✅ Firebase Cloud Messaging (FCM) push notifications
- ✅ FCM token management
- ✅ Admin notification system

## API Endpoints

### Public API (No Authentication Required)

#### Categories
- `GET /api/v1/categories` - Get all categories
- `GET /api/v1/categories/{id}` - Get specific category with posts

#### Posts
- `GET /api/v1/posts` - Get all posts with pagination
  - Query parameters:
    - `category_id` - Filter by category
    - `search` - Search in title, subtitle, and body
    - `sort_by` - Sort field (created_at, title, views, likes)
    - `sort_order` - Sort direction (asc, desc)
    - `per_page` - Items per page (default: 10)
- `GET /api/v1/posts/{id}` - Get specific post
- `POST /api/v1/posts/{id}/like` - Like a post

#### FCM Token Management
- `POST /api/v1/fcm/register` - Register FCM token
- `POST /api/v1/fcm/unregister` - Unregister FCM token

### Protected API (Authentication Required)

#### Categories
- `GET /api/v1/admin/categories` - List all categories
- `POST /api/v1/admin/categories` - Create new category
- `GET /api/v1/admin/categories/{id}` - Get specific category
- `PUT /api/v1/admin/categories/{id}` - Update category
- `DELETE /api/v1/admin/categories/{id}` - Delete category

#### Posts
- `GET /api/v1/admin/posts` - List all posts
- `POST /api/v1/admin/posts` - Create new post
- `GET /api/v1/admin/posts/{id}` - Get specific post
- `PUT /api/v1/admin/posts/{id}` - Update post
- `DELETE /api/v1/admin/posts/{id}` - Delete post
- `POST /api/v1/admin/posts/{id}/like` - Like a post

#### FCM Notification Management (Admin Only)
- `POST /api/v1/admin/fcm/send` - Send notification to all users
- `POST /api/v1/admin/fcm/send-to-platform` - Send notification to specific platform
- `GET /api/v1/admin/fcm/stats` - Get FCM statistics
- `GET /api/v1/admin/fcm/tokens` - Get all FCM tokens

## Web Interface

### Admin Dashboard
- **URL**: `/dashboard`
- **Features**:
  - Quick stats overview
  - Navigation to categories and posts management
  - Recent posts display

### Categories Management
- **List**: `/categories` - View all categories in a beautiful grid layout
- **Create**: `/categories/create` - Create new category with image upload
- **Edit**: `/categories/{id}/edit` - Edit existing category
- **View**: `/categories/{id}` - View category details with posts

### Posts Management
- **List**: `/posts` - View all posts with search and filtering
- **Create**: `/posts/create` - Create new post with rich text editor
- **Edit**: `/posts/{id}/edit` - Edit existing post
- **View**: `/posts/{id}` - View post with like functionality

## Database Schema

### Categories Table
```sql
- id (primary key)
- name (string, required)
- slug (string, unique, auto-generated)
- image (string, nullable)
- parent_id (foreign key, nullable)
- created_at
- updated_at
```

### Posts Table
```sql
- id (primary key)
- title (string, required)
- subtitle (string, nullable)
- body (longtext, required)
- image (string, nullable)
- category_id (foreign key, required)
- user_id (foreign key, required)
- views (integer, default: 0)
- likes (integer, default: 0)
- created_at
- updated_at
```

## Installation & Setup

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Storage Link**
   ```bash
   php artisan storage:link
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Start Server**
   ```bash
   php artisan serve
   ```

## FCM Push Notifications

### Setup
1. **Firebase Configuration**
   Add these environment variables to your `.env` file:
   ```
   FIREBASE_SERVER_KEY=your_firebase_server_key
   FIREBASE_PROJECT_ID=your_firebase_project_id
   ```

2. **Register FCM Token**
   ```bash
   curl -X POST http://localhost:8000/api/v1/fcm/register \
     -H "Content-Type: application/json" \
     -d '{
       "token": "fcm_token_from_device",
       "device_id": "device_unique_id",
       "platform": "android",
       "app_version": "1.0.0"
     }'
   ```

3. **Send Notification (Admin)**
   ```bash
   curl -X POST http://localhost:8000/api/v1/admin/fcm/send \
     -H "Content-Type: application/json" \
     -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
     -d '{
       "title": "New Blog Post",
       "body": "Check out our latest article!",
       "data": {
         "post_id": "123",
         "type": "new_post"
       },
       "image": "https://example.com/image.jpg"
     }'
   ```

### FCM API Endpoints

#### Register FCM Token
- **Endpoint**: `POST /api/v1/fcm/register`
- **Authentication**: None required
- **Body**:
  ```json
  {
    "token": "string (required)",
    "device_id": "string (optional)",
    "platform": "android|ios|web (optional)",
    "app_version": "string (optional)"
  }
  ```

#### Unregister FCM Token
- **Endpoint**: `POST /api/v1/fcm/unregister`
- **Authentication**: None required
- **Body**:
  ```json
  {
    "token": "string (required)"
  }
  ```

#### Send Notification to All Users
- **Endpoint**: `POST /api/v1/admin/fcm/send`
- **Authentication**: Required (Admin)
- **Body**:
  ```json
  {
    "title": "string (required)",
    "body": "string (required)",
    "data": "object (optional)",
    "platform": "android|ios|web (optional)",
    "image": "string (optional)"
  }
  ```

#### Send Notification to Platform
- **Endpoint**: `POST /api/v1/admin/fcm/send-to-platform`
- **Authentication**: Required (Admin)
- **Body**:
  ```json
  {
    "platform": "android|ios|web (required)",
    "title": "string (required)",
    "body": "string (required)",
    "data": "object (optional)",
    "image": "string (optional)"
  }
  ```

#### Get FCM Statistics
- **Endpoint**: `GET /api/v1/admin/fcm/stats`
- **Authentication**: Required (Admin)
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "total_tokens": 150,
      "active_tokens": 120,
      "platforms": {
        "android": 80,
        "ios": 30,
        "web": 10
      }
    }
  }
  ```

#### Get FCM Tokens
- **Endpoint**: `GET /api/v1/admin/fcm/tokens`
- **Authentication**: Required (Admin)
- **Query Parameters**:
  - `platform`: Filter by platform
  - `active`: Filter by active status (true/false)
- **Response**: Paginated list of FCM tokens

## Usage Examples

### Creating a Category via API
```bash
curl -X POST http://localhost:8000/api/v1/admin/categories \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "name": "Technology",
    "slug": "technology",
    "parent_id": null
  }'
```

### Creating a Post via API
```bash
curl -X POST http://localhost:8000/api/v1/admin/posts \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "title": "Getting Started with Laravel",
    "subtitle": "A comprehensive guide to Laravel development",
    "body": "Laravel is a powerful PHP framework...",
    "category_id": 1
  }'
```

### Searching Posts
```bash
curl "http://localhost:8000/api/v1/posts?search=laravel&category_id=1&sort_by=created_at&sort_order=desc"
```

## Features Highlights

### UI Features
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile
- **Dark Mode Support**: Automatic dark/light theme switching
- **Image Upload**: Drag & drop image upload with preview
- **Real-time Validation**: Client-side and server-side validation
- **Interactive Elements**: Hover effects, transitions, and animations
- **Accessibility**: Proper ARIA labels and keyboard navigation

### Backend Features
- **Eloquent Relationships**: Proper model relationships and eager loading
- **Form Validation**: Comprehensive validation rules with custom messages
- **File Storage**: Secure image upload and storage management
- **API Responses**: Consistent JSON response format
- **Error Handling**: Graceful error handling and user feedback
- **Security**: CSRF protection, input sanitization, and authorization

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
