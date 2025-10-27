# Firebase Cloud Messaging (FCM) Setup Guide

## Prerequisites
1. A Firebase project
2. Firebase Cloud Messaging enabled
3. Server key from Firebase Console

## Setup Steps

### 1. Create Firebase Project
1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Click "Create a project" or select existing project
3. Follow the setup wizard

### 2. Enable Cloud Messaging
1. In Firebase Console, go to your project
2. Click on "Cloud Messaging" in the left sidebar
3. If not enabled, click "Get started"

### 3. Get Authentication Credentials

#### Option A: HTTP v1 API (Recommended)

**Method 1: JSON File (Recommended)**
1. Go to Project Settings (gear icon)
2. Click on "Service accounts" tab
3. Click "Generate new private key"
4. Download the JSON file
5. Save it as `storage/app/firebase-service-account.json`
6. Set `FIREBASE_SERVICE_ACCOUNT_PATH=storage/app/firebase-service-account.json`

**Method 2: Environment Variable**
1. Go to Project Settings (gear icon)
2. Click on "Service accounts" tab
3. Click "Generate new private key"
4. Download the JSON file
5. Copy the entire JSON content to `FIREBASE_SERVICE_ACCOUNT_KEY`

#### Option B: Legacy API (Fallback)
1. Go to Project Settings (gear icon)
2. Click on "Cloud Messaging" tab
3. Copy the "Server key" (starts with `AAAA...`)

### 4. Configure Environment Variables
Add these to your `.env` file:

```env
# Firebase Configuration
FIREBASE_SERVER_KEY=your_server_key_here
FIREBASE_PROJECT_ID=your_project_id_here

# HTTP v1 API Configuration (Recommended)
FIREBASE_USE_HTTP_V1=true

# Method 1: JSON File (Recommended)
FIREBASE_SERVICE_ACCOUNT_PATH=storage/app/firebase-service-account.json

# Method 2: Environment Variable (Alternative)
# FIREBASE_SERVICE_ACCOUNT_KEY={"type":"service_account","project_id":"your-project-id",...}

# Legacy API Configuration (Fallback)
# FIREBASE_USE_HTTP_V1=false
```

### 5. Setup Service Account File (Recommended)
Use the built-in command to manage your service account:

```bash
# Setup service account file
php artisan firebase:service-account setup

# Validate service account file
php artisan firebase:service-account validate

# Show service account info
php artisan firebase:service-account info
```

### 6. Test the Setup
You can test the FCM integration using the API endpoints:

#### Register a test token:
```bash
curl -X POST http://localhost:8000/api/v1/fcm/register \
  -H "Content-Type: application/json" \
  -d '{
    "token": "test_token_123",
    "platform": "web",
    "app_version": "1.0.0"
  }'
```

#### Send a test notification (requires admin auth):
```bash
curl -X POST http://localhost:8000/api/v1/admin/fcm/send \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -d '{
    "title": "Test Notification",
    "body": "This is a test notification from your blog API"
  }'
```

## Client-Side Integration

### Android (Kotlin/Java)
```kotlin
// Get FCM token
FirebaseMessaging.getInstance().token.addOnCompleteListener { task ->
    if (!task.isSuccessful) {
        Log.w(TAG, "Fetching FCM registration token failed", task.exception)
        return@addOnCompleteListener
    }

    // Get new FCM registration token
    val token = task.result
    Log.d(TAG, "FCM Token: $token")
    
    // Send token to your API
    sendTokenToServer(token)
}
```

### iOS (Swift)
```swift
// Get FCM token
Messaging.messaging().token { token, error in
    if let error = error {
        print("Error fetching FCM registration token: \(error)")
    } else if let token = token {
        print("FCM registration token: \(token)")
        // Send token to your API
        sendTokenToServer(token: token)
    }
}
```

### Web (JavaScript)
```javascript
// Get FCM token
import { getMessaging, getToken } from "firebase/messaging";

const messaging = getMessaging();
getToken(messaging, { vapidKey: 'your-vapid-key' }).then((currentToken) => {
    if (currentToken) {
        console.log('FCM Token:', currentToken);
        // Send token to your API
        sendTokenToServer(currentToken);
    } else {
        console.log('No registration token available.');
    }
}).catch((err) => {
    console.log('An error occurred while retrieving token. ', err);
});
```

## Service Account Storage Methods

### JSON File (Recommended)
- ✅ **Better Security**: File permissions can be restricted
- ✅ **Version Control**: Can be excluded from Git with .gitignore
- ✅ **Easy Management**: Use built-in commands to validate and manage
- ✅ **No Environment Limits**: No size restrictions like environment variables
- ✅ **Backup Friendly**: Easy to backup and restore

### Environment Variable
- ⚠️ **Size Limits**: Large JSON may exceed environment variable limits
- ⚠️ **Security**: Visible in process lists and logs
- ⚠️ **Management**: Harder to validate and manage
- ✅ **Simple**: Easy to set up initially

## HTTP v1 API vs Legacy API

### HTTP v1 API Benefits
- ✅ **Better Security**: OAuth2 authentication instead of server keys
- ✅ **More Features**: Advanced targeting, analytics, and delivery options
- ✅ **Better Error Handling**: Detailed error messages and status codes
- ✅ **Future-Proof**: Google's recommended approach
- ✅ **Rate Limiting**: Better handling of high-volume sending

### Legacy API
- ⚠️ **Deprecated**: Google recommends migrating to HTTP v1
- ⚠️ **Limited Features**: Basic notification sending only
- ⚠️ **Security**: Server keys are less secure than OAuth2

### Migration Guide
1. **Enable HTTP v1**: Set `FIREBASE_USE_HTTP_V1=true`
2. **Add Service Account**: Configure `FIREBASE_SERVICE_ACCOUNT_KEY`
3. **Test**: Send a test notification to verify it works
4. **Monitor**: Check logs for any issues

## Troubleshooting

### Common Issues
1. **Invalid Server Key**: Make sure you're using the Server Key, not the Web API Key
2. **Token Registration Fails**: Check if the token format is correct
3. **Notifications Not Received**: Verify the token is active and platform is correct
4. **HTTP v1 Authentication**: Ensure service account key is valid JSON
5. **OAuth2 Errors**: Check if service account has FCM permissions

### Debug Steps
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify Firebase configuration in `config/services.php`
3. Test with Firebase Console's "Send test message" feature
4. Use the FCM statistics endpoint to check token counts

### Logs
The FCM service logs all operations. Check `storage/logs/laravel.log` for:
- Successful token registrations
- Failed notification attempts
- FCM API responses
