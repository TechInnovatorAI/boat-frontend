# Gmail SMTP Configuration Guide

## Setting up Gmail for your Laravel Contact Form

### 1. Enable 2-Factor Authentication
- Go to your Google Account settings
- Enable 2-Factor Authentication if not already enabled

### 2. Generate App Password
- Go to Google Account → Security → 2-Step Verification
- Scroll down to "App passwords"
- Generate a new app password for "Mail"
- Copy the 16-character password (you'll need this)

### 3. Update your .env file
Add these lines to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=mt@cat0.app
MAIL_PASSWORD=your-16-character-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=mt@cat0.app
MAIL_FROM_NAME="Boat Website"
```

### 4. Important Notes
- Use the **App Password**, not your regular Gmail password
- The App Password is 16 characters without spaces
- Make sure 2-Factor Authentication is enabled
- The MAIL_FROM_ADDRESS should match your MAIL_USERNAME

### 5. Test Configuration
After updating your .env file:
1. Clear Laravel cache: `php artisan config:clear`
2. Test the contact form on your website
3. Check if emails are being sent successfully

### 6. Troubleshooting
- If emails fail, check Laravel logs: `storage/logs/laravel.log`
- Verify the App Password is correct
- Ensure 2FA is enabled on your Google account
- Check that "Less secure app access" is not blocking the connection

### 7. Security Best Practices
- Never commit your .env file to version control
- Use App Passwords instead of your main password
- Regularly rotate your App Passwords
- Monitor your Gmail account for any suspicious activity
