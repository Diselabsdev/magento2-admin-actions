# Magento 2 Admin Actions Log

Enhanced security and monitoring for your Magento 2 store's admin panel. Track, monitor, and secure admin activities with comprehensive logging and real-time alerts.

## Features

🔒 **Enhanced Security**
- Track all admin panel activities
- Monitor login attempts and block suspicious IPs
- Real-time suspicious activity detection
- Geolocation tracking for login attempts

📊 **Comprehensive Logging**
- Detailed action logging
- Login attempt tracking
- Suspicious activity monitoring
- Session management

⚡ **Real-time Monitoring**
- Live activity updates
- Instant suspicious activity alerts
- Active session monitoring
- Quick action restoration

🛠 **Advanced Management**
- Terminate active sessions
- Restore recent changes
- Configure security settings
- Automated log cleanup

## Requirements

- Magento 2.4.x
- PHP 7.4 or higher
- MySQL 5.7 or higher

## Installation

### Via Composer (Recommended)

```bash
composer require protect/admin-actions
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:clean
```

### Manual Installation

1. Create directory `app/code/Protect/AdminActions`
2. Download the module files to the directory
3. Run the following commands:

```bash
php bin/magento module:enable Protect_AdminActions
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:clean
```

## Configuration

1. Go to **Stores → Configuration → Protection → Admin Security**
2. Configure the following settings:
   - Enable/Disable Module
   - Log Retention Period
   - Suspicious Activity Notifications
   - Maximum Login Failures
   - Block Duration

## Usage

### Viewing Admin Actions

1. Navigate to **System → Security → Admin Actions Log**
2. View detailed information about all admin actions
3. Use filters to find specific actions
4. Click "View Details" for more information

### Managing Login Attempts

1. Go to **System → Security → Login Attempts**
2. Monitor all login attempts
3. View geolocation data
4. Check blocked IPs

### Active Sessions

1. Access **System → Security → Active Sessions**
2. View all active admin sessions
3. Terminate suspicious sessions
4. Monitor real-time activity

### Restoring Changes

1. Navigate to the affected entity (product, order, etc.)
2. Go to the "History of Changes" tab
3. Select the change to restore
4. Click "Restore" to revert the changes

## Security Features

### IP-based Protection
- Automatic blocking of suspicious IPs
- Geolocation tracking
- Failed login attempt monitoring

### Activity Monitoring
- Real-time activity tracking
- Suspicious pattern detection
- Automated alerts

### Session Security
- Active session monitoring
- Forced session termination
- Session timeout management

### Data Protection
- Input sanitization
- Secure logging practices
- Automated log cleanup

## Support

For issues and feature requests, please:
1. Check the [documentation](docs/index.md)
2. Submit an issue on our [GitHub repository](https://github.com/protect/admin-actions/issues)
3. Contact our support team at support@protect.com

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This module is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
