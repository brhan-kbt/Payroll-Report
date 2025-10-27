# App Configuration System Documentation

## Overview

The App Configuration System provides a comprehensive solution for managing application settings, version control, and feature flags. It includes both API endpoints for mobile apps and a web-based admin interface for configuration management.

## Features

### 🔧 Configuration Management
- **Dynamic Configuration**: Store and manage app settings in the database
- **Type Support**: String, Boolean, Integer, and JSON data types
- **Public/Private Configs**: Control which configurations are accessible via public API
- **Version Management**: Separate version settings for Android and iOS
- **Maintenance Mode**: Enable/disable maintenance mode with custom messages

### 📱 Mobile App Integration
- **Version Checking**: Check if app updates are required
- **Force Updates**: Force users to update to the latest version
- **Feature Flags**: Enable/disable features remotely
- **Client Configuration**: Get app-specific settings and information

### 🎛️ Admin Interface
- **Web-based CMS**: Manage configurations through a user-friendly interface
- **Bulk Operations**: Update multiple configurations at once
- **Version Management**: Dedicated interface for app version control
- **Maintenance Control**: Easy maintenance mode management

## API Endpoints

### Public API (No Authentication Required)

#### Get All Public Configurations
```http
GET /api/v1/app-config
```

**Response:**
```json
{
    "success": true,
    "data": {
        "app_name": "Blog App",
        "app_latest_version": "1.2.0",
        "feature_dark_mode": true,
        "maintenance_mode": false
    }
}
```

#### Get Client Configuration
```http
GET /api/v1/app-config/client
```

**Response:**
```json
{
    "success": true,
    "data": {
        "app_info": {
            "name": "Blog App",
            "description": "A modern blog application",
            "logo_url": "/images/logo.png"
        },
        "features": {
            "dark_mode": true,
            "notifications": true,
            "offline_mode": false,
            "social_login": true
        },
        "contact": {
            "email": "support@yourapp.com",
            "phone": "+1-555-0123"
        },
        "links": {
            "privacy_policy": "https://yourapp.com/privacy",
            "terms_of_service": "https://yourapp.com/terms",
            "social": {
                "facebook": "https://facebook.com/yourapp",
                "twitter": "https://twitter.com/yourapp"
            }
        },
        "maintenance": {
            "enabled": false,
            "message": "We are currently performing maintenance..."
        }
    }
}
```

#### Check App Version
```http
POST /api/v1/app-config/check-version
Content-Type: application/json

{
    "version": "1.0.0",
    "platform": "android"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "needs_update": true,
        "force_update": false,
        "current_version": "1.0.0",
        "min_version": "1.1.0",
        "latest_version": "1.2.0",
        "update_url": "https://play.google.com/store/apps/details?id=com.example.app",
        "platform": "android",
        "update_available": true,
        "message": "An update is available. Please update to version 1.2.0 for the best experience."
    }
}
```

#### Get Specific Configuration
```http
GET /api/v1/app-config/{key}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "key": "app_name",
        "value": "Blog App",
        "type": "string",
        "description": "Name of the application"
    }
}
```

### Admin API (Authentication Required)

#### Get All Configurations
```http
GET /api/v1/admin/app-config
Authorization: Bearer {token}
```

#### Create Configuration
```http
POST /api/v1/admin/app-config
Authorization: Bearer {token}
Content-Type: application/json

{
    "key": "new_feature",
    "value": "true",
    "type": "boolean",
    "description": "Enable new feature",
    "is_public": true
}
```

#### Update Configuration
```http
PUT /api/v1/admin/app-config/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "key": "new_feature",
    "value": "false",
    "type": "boolean",
    "description": "Disable new feature",
    "is_public": true
}
```

#### Delete Configuration
```http
DELETE /api/v1/admin/app-config/{id}
Authorization: Bearer {token}
```

#### Bulk Update Configurations
```http
POST /api/v1/admin/app-config/bulk-update
Authorization: Bearer {token}
Content-Type: application/json

{
    "configs": [
        {
            "id": 1,
            "value": "1.3.0"
        },
        {
            "id": 2,
            "value": "true"
        }
    ]
}
```

## Web Admin Interface

### Accessing the Admin Interface

1. **Login** to the admin panel
2. **Navigate** to "App Config" in the main navigation
3. **Manage** configurations through the web interface

### Available Pages

#### 1. Configuration List (`/admin/app-config`)
- View all configurations
- Create, edit, and delete configurations
- Quick access to version management and maintenance mode

#### 2. Version Management (`/admin/app-config/version-management`)
- Manage app versions for Android and iOS
- Set minimum required versions
- Configure force update settings
- Set update URLs

#### 3. Maintenance Mode (`/admin/app-config/maintenance-mode`)
- Enable/disable maintenance mode
- Set custom maintenance messages
- Quick toggle buttons

#### 4. Create/Edit Configuration
- Form-based configuration management
- Type-specific input fields
- Public/private access control

## Configuration Types

### String
- **Usage**: Text values, URLs, messages
- **Examples**: App name, update URLs, maintenance messages

### Boolean
- **Usage**: Feature flags, on/off settings
- **Examples**: Dark mode, notifications, maintenance mode

### Integer
- **Usage**: Numeric values, limits, timeouts
- **Examples**: API rate limits, timeouts, version numbers

### JSON
- **Usage**: Complex data structures
- **Examples**: Social media links, feature configurations

## Default Configurations

The system comes with pre-configured settings:

### Version Management
- `app_latest_version`: Latest app version
- `app_min_version`: Minimum required version
- `app_force_update`: Force update flag
- `app_update_url`: Update download URL
- Platform-specific versions (Android/iOS)

### App Information
- `app_name`: Application name
- `app_description`: Application description
- `app_logo_url`: Logo URL

### Feature Flags
- `feature_dark_mode`: Dark mode support
- `feature_notifications`: Push notifications
- `feature_offline_mode`: Offline functionality
- `feature_social_login`: Social media login

### Contact & Legal
- `contact_email`: Support email
- `contact_phone`: Support phone
- `privacy_policy_url`: Privacy policy URL
- `terms_of_service_url`: Terms of service URL

### Social Media
- `social_links`: JSON object with social media links

## Artisan Commands

### Update App Version
```bash
php artisan app:update-version 1.2.0 --platform=android --min-version=1.1.0 --force-update
```

**Options:**
- `--platform`: android, ios, or both (default: both)
- `--min-version`: Set minimum required version
- `--force-update`: Force users to update
- `--update-url`: Set update URL

### Toggle Maintenance Mode
```bash
php artisan app:maintenance --enable --message="Custom maintenance message"
```

**Options:**
- `--enable`: Enable maintenance mode
- `--disable`: Disable maintenance mode
- `--message`: Custom maintenance message

## Usage Examples

### Mobile App Integration

#### Check for Updates
```javascript
const checkForUpdates = async () => {
    const response = await fetch('/api/v1/app-config/check-version', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            version: '1.0.0',
            platform: 'android'
        })
    });
    
    const data = await response.json();
    
    if (data.data.needs_update) {
        if (data.data.force_update) {
            // Show force update dialog
            showForceUpdateDialog(data.data);
        } else {
            // Show optional update dialog
            showUpdateDialog(data.data);
        }
    }
};
```

#### Get App Configuration
```javascript
const getAppConfig = async () => {
    const response = await fetch('/api/v1/app-config/client');
    const data = await response.json();
    
    // Use configuration data
    const appName = data.data.app_info.name;
    const darkModeEnabled = data.data.features.dark_mode;
    const supportEmail = data.data.contact.email;
};
```

### Server-side Usage

#### Get Configuration Value
```php
use App\Models\AppConfig;

// Get a configuration value
$appName = AppConfig::getValue('app_name', 'Default App Name');

// Set a configuration value
AppConfig::setValue('feature_new_ui', true, 'boolean', 'Enable new UI', true);
```

#### Check Version Update
```php
use App\Services\AppVersionService;

$versionInfo = AppVersionService::checkVersionUpdate('1.0.0', 'android');

if ($versionInfo['needs_update']) {
    // Handle update logic
}
```

## Maintenance Mode

When maintenance mode is enabled:
- All public API endpoints return a 503 status with maintenance message
- Admin routes remain accessible
- Custom maintenance message is displayed

### Enable Maintenance Mode
```php
AppConfig::setValue('maintenance_mode', true, 'boolean', 'Enable maintenance mode', true);
AppConfig::setValue('maintenance_message', 'We are performing maintenance. Please try again later.', 'string', 'Maintenance message', true);
```

## Security Considerations

1. **Public vs Private Configs**: Only mark configurations as public if they should be accessible via API
2. **Admin Authentication**: All admin routes require authentication
3. **Input Validation**: All inputs are validated before storage
4. **Type Safety**: Configurations are typed and validated

## Best Practices

1. **Use Descriptive Keys**: Use clear, descriptive keys for configurations
2. **Add Descriptions**: Always provide descriptions for configurations
3. **Version Management**: Keep version numbers in semantic versioning format (e.g., 1.2.3)
4. **Feature Flags**: Use feature flags for gradual feature rollouts
5. **Testing**: Test configurations in staging before production deployment

## Troubleshooting

### Common Issues

1. **Dark Mode Not Working**
   - Ensure Tailwind config has `darkMode: 'class'`
   - Check that the theme toggle component is included
   - Verify localStorage is working in the browser

2. **API Endpoints Not Working**
   - Check route registration
   - Verify middleware configuration
   - Ensure database migrations are run

3. **Admin Interface Not Accessible**
   - Verify user has admin privileges
   - Check route middleware configuration
   - Ensure authentication is working

### Debug Commands

```bash
# Check route registration
php artisan route:list --name=app-config

# Check database tables
php artisan migrate:status

# Clear configuration cache
php artisan config:clear
php artisan cache:clear
```

## Support

For issues or questions regarding the App Configuration System, please refer to the Laravel documentation or contact the development team.
