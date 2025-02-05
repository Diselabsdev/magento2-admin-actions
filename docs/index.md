# Magento 2 Admin Actions Log Documentation

## Table of Contents

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Features](#features)
4. [Technical Details](#technical-details)
5. [Troubleshooting](#troubleshooting)
6. [API Reference](#api-reference)

## Installation

### Prerequisites
- Magento 2.4.x
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer

### Installation Steps

#### Via Composer
```bash
composer require protect/admin-actions
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:clean
```

#### Manual Installation
1. Create directory structure:
```bash
mkdir -p app/code/Protect/AdminActions
```

2. Download and extract module files
3. Enable module:
```bash
php bin/magento module:enable Protect_AdminActions
php bin/magento setup:upgrade
```

## Configuration

### Basic Configuration
1. Navigate to Admin Panel → Stores → Configuration → Protection → Admin Security
2. Configure basic settings:
   - Module Enable/Disable
   - Log Retention Period
   - Notification Settings

### Security Settings
1. Maximum Login Failures
   - Set maximum failed login attempts
   - Configure block duration
   - Set IP blocking rules

2. Suspicious Activity Detection
   - Configure activity thresholds
   - Set notification rules
   - Define suspicious patterns

### Log Management
1. Automatic Cleanup
   - Set retention period
   - Configure cleanup schedule
   - Manage log storage

## Features

### Admin Action Logging
- Tracks all admin panel activities
- Records user, action type, and details
- Maintains change history
- Provides restoration capability

### Login Security
- Monitors login attempts
- Tracks IP addresses
- Records geolocation data
- Implements automatic blocking

### Real-time Monitoring
- Live activity updates
- Instant notifications
- Session monitoring
- Quick action capabilities

### Session Management
- View active sessions
- Terminate suspicious sessions
- Monitor user activity
- Manage session timeouts

## Technical Details

### Database Schema
The module creates three main tables:
1. `protect_admin_actions_log`
   - Stores admin actions
   - Tracks changes
   - Maintains history

2. `protect_admin_login_attempts`
   - Records login attempts
   - Stores IP information
   - Tracks success/failure

3. `protect_admin_suspicious_activity`
   - Logs suspicious activities
   - Stores alert data
   - Maintains security records

### JavaScript Components
1. `monitor.js`
   - Real-time monitoring
   - Activity updates
   - Alert management

2. `session-manager.js`
   - Session handling
   - Termination management
   - Status monitoring

### PHP Classes
1. Security Helper
   - Input sanitization
   - Security checks
   - Utility functions

2. Observers
   - Action logging
   - Login monitoring
   - Session tracking

## Troubleshooting

### Common Issues

1. Installation Issues
   ```
   Solution: Clear cache and run setup:upgrade
   ```

2. Database Errors
   ```
   Solution: Check database permissions and schema
   ```

3. JavaScript Not Loading
   ```
   Solution: Deploy static content and clear browser cache
   ```

### Performance Optimization

1. Log Management
   - Regular cleanup
   - Index optimization
   - Query optimization

2. Real-time Monitoring
   - Adjust polling interval
   - Configure thresholds
   - Optimize queries

## API Reference

### REST APIs

#### Get Admin Actions
```
GET /V1/admin/actions
```

#### Get Login Attempts
```
GET /V1/admin/login-attempts
```

#### Terminate Session
```
POST /V1/admin/terminate-session
```

### Events

1. `admin_action_logged`
   - Triggered after logging action
   - Contains action details

2. `login_attempt_recorded`
   - Triggered on login attempts
   - Contains attempt details

3. `suspicious_activity_detected`
   - Triggered for suspicious activities
   - Contains alert details

## Support

For technical support:
- Email: support@protect.com
- GitHub Issues: [Submit Issue](https://github.com/protect/admin-actions/issues)
- Documentation: [Online Docs](https://protect.com/docs)
