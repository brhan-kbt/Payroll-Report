# FCM Token Cleanup Documentation

This document explains the FCM token cleanup system implemented for removing failed and invalid tokens.

## Overview

The token cleanup system helps maintain a clean FCM token database by:
- Validating old tokens to check if they're still valid
- Removing tokens that have failed multiple times
- Providing statistics about token health
- Offering both automated and manual cleanup options

## Components

### 1. Job Classes

#### `CleanupFailedTokensJob`
- Validates old tokens by sending test notifications
- Deactivates invalid tokens
- Processes tokens in batches to avoid rate limiting
- Updates last_used_at for valid tokens

#### `RemoveFailedTokensJob`
- Permanently removes old inactive tokens
- Removes tokens that have been failing for a long time
- Safe cleanup of database storage

### 2. Artisan Commands

#### `fcm:cleanup-tokens`
```bash
# Basic cleanup
php artisan fcm:cleanup-tokens

# Custom parameters
php artisan fcm:cleanup-tokens --batch-size=100 --max-age=14

# Include old token removal
php artisan fcm:cleanup-tokens --remove-old

# Force without confirmation
php artisan fcm:cleanup-tokens --force
```

#### `fcm:remove-failed-tokens`
```bash
# Remove failed tokens
php artisan fcm:remove-failed-tokens

# Dry run to see what would be removed
php artisan fcm:remove-failed-tokens --dry-run

# Custom age threshold
php artisan fcm:remove-failed-tokens --max-age=14

# Force removal
php artisan fcm:remove-failed-tokens --force
```

### 3. API Endpoints

#### Cleanup Failed Tokens
```http
POST /api/v1/admin/fcm/cleanup
Content-Type: application/json

{
    "batch_size": 50,
    "max_age_days": 30
}
```

#### Remove Failed Tokens
```http
POST /api/v1/admin/fcm/remove-failed
Content-Type: application/json

{
    "failure_threshold": 3,
    "max_age_days": 7
}
```

#### Get Token Statistics
```http
GET /api/v1/admin/fcm/token-stats
```

#### Deactivate Specific Token
```http
POST /api/v1/admin/fcm/deactivate-token
Content-Type: application/json

{
    "token": "fcm_token_string"
}
```

## Usage Examples

### 1. Automated Cleanup

Set up a cron job to run cleanup automatically:

```bash
# Add to crontab
# Run cleanup every day at 2 AM
0 2 * * * cd /path/to/your/app && php artisan fcm:cleanup-tokens --force

# Run removal every week
0 3 * * 0 cd /path/to/your/app && php artisan fcm:remove-failed-tokens --force
```

### 2. Manual Cleanup

Check token statistics first:
```bash
php artisan fcm:cleanup-tokens --dry-run
```

Then run the actual cleanup:
```bash
php artisan fcm:cleanup-tokens
```

### 3. API Integration

Use the API endpoints in your admin panel:

```javascript
// Cleanup failed tokens
fetch('/api/v1/admin/fcm/cleanup', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
    },
    body: JSON.stringify({
        batch_size: 50,
        max_age_days: 30
    })
});

// Get token statistics
fetch('/api/v1/admin/fcm/token-stats', {
    headers: {
        'Authorization': 'Bearer ' + token
    }
});
```

## Token Lifecycle

### 1. Token Registration
- Tokens are registered when users open the app
- Old tokens for the same device are deactivated
- New tokens are marked as active

### 2. Token Validation
- Old tokens are periodically validated
- Invalid tokens are deactivated
- Valid tokens have their last_used_at updated

### 3. Token Cleanup
- Inactive tokens older than 7 days are removed
- Long-failed tokens are permanently deleted
- Statistics are maintained for monitoring

## Monitoring

### Token Statistics
The system provides detailed statistics:
- Total tokens
- Active vs inactive tokens
- Platform breakdown
- Age-based token counts
- Old inactive token counts

### Logging
All cleanup activities are logged with:
- Job dispatch information
- Token validation results
- Removal counts
- Error details

## Best Practices

1. **Regular Cleanup**: Run cleanup jobs regularly to maintain token health
2. **Monitor Statistics**: Check token statistics to understand user engagement
3. **Batch Processing**: Use appropriate batch sizes to avoid overwhelming FCM
4. **Error Handling**: Monitor failed jobs and retry as needed
5. **Testing**: Use dry-run options to test cleanup before execution

## Configuration

### Queue Worker
Make sure your queue worker is running:
```bash
php artisan queue:work
```

### Environment Variables
Configure queue settings in your `.env`:
```env
QUEUE_CONNECTION=database
DB_QUEUE_TABLE=jobs
```

### Scheduling
Add to your `app/Console/Kernel.php` for automatic cleanup:
```php
protected function schedule(Schedule $schedule)
{
    // Daily token cleanup
    $schedule->command('fcm:cleanup-tokens --force')
             ->daily()
             ->at('02:00');
    
    // Weekly failed token removal
    $schedule->command('fcm:remove-failed-tokens --force')
             ->weekly()
             ->at('03:00');
}
```

## Troubleshooting

### Common Issues

1. **Queue Not Processing**: Ensure queue worker is running
2. **High Failure Rate**: Check FCM configuration and credentials
3. **Memory Issues**: Reduce batch size for large token databases
4. **Rate Limiting**: Add delays between token validations

### Debug Commands

```bash
# Check queue status
php artisan queue:work --once

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

This cleanup system ensures your FCM token database stays healthy and performant!
