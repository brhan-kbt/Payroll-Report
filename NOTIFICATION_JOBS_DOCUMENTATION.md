# Notification Jobs Implementation

This document explains the notification job system implemented for the blog API.

## Overview

The notification system has been refactored to use Laravel jobs for asynchronous processing. This improves performance and reliability when sending notifications.

## Components

### 1. Job Classes

#### `SendNotificationJob`
- Sends notifications to specific tokens
- Used for targeted notifications
- Handles individual token validation and error handling

#### `SendNewPostNotificationJob`
- Automatically triggered when a new post is created
- Sends notifications to all active FCM tokens
- Includes post-specific data in the notification payload

#### `SendBulkNotificationJob`
- Handles bulk notifications to all users
- Processes tokens in batches to avoid rate limiting
- Used for admin notifications

### 2. NotificationService

The `NotificationService` class provides a clean interface for dispatching notification jobs:

```php
// Send notification to all users
$notificationService->sendToAll($notification, $data, $platform);

// Send notification to specific tokens
$notificationService->sendToTokens($tokens, $notification, $data);

// Send new post notification
$notificationService->sendNewPostNotification($post, $platform);

// Send test notification
$notificationService->sendTestNotification($token);
```

### 3. Updated Controllers

#### PostController
- Automatically dispatches `SendNewPostNotificationJob` when a new post is created
- No manual intervention required

#### FcmController
- Updated to use `NotificationService` instead of direct `FcmService` calls
- All notification methods now dispatch jobs asynchronously

## Queue Configuration

The system uses Laravel's database queue driver by default. The jobs table is already created and configured.

### Running the Queue Worker

To process notification jobs, run the queue worker:

```bash
php artisan queue:work
```

For production, consider using a process manager like Supervisor to keep the worker running.

### Queue Monitoring

Monitor the queue status:

```bash
# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

## Usage Examples

### 1. Automatic New Post Notifications

When a new post is created through the PostController, a notification job is automatically dispatched:

```php
// In PostController::store()
$post = Post::create($data);
$this->notificationService->sendNewPostNotification($post);
```

### 2. Manual Bulk Notifications

Send notifications through the FCM controller:

```php
// POST /api/fcm/send-notification
{
    "title": "New Blog Post",
    "body": "Check out our latest article!",
    "data": {
        "type": "announcement"
    },
    "platform": "android" // optional
}
```

### 3. Test Notifications

Send test notifications to validate the setup:

```php
// POST /api/fcm/test-notification
{
    "token": "optional_specific_token"
}
```

## Benefits

1. **Asynchronous Processing**: Notifications are sent in the background
2. **Better Performance**: No blocking of HTTP requests
3. **Error Handling**: Failed notifications are retried automatically
4. **Scalability**: Can handle large numbers of notifications
5. **Monitoring**: Queue status can be monitored and managed

## Error Handling

- Jobs are retried up to 3 times on failure
- Failed jobs are logged with detailed error information
- Critical failures (invalid tokens, unauthorized) fail the job permanently
- Network issues are retried automatically

## Logging

All notification activities are logged with:
- Job dispatch information
- Success/failure counts
- Error details
- Performance metrics

Check the Laravel logs for detailed information about notification processing.
