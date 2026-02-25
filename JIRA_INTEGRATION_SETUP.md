# Jira Service Management Integration Setup

This document explains how to set up and configure the Jira Service Management integration for the boat website contact form.

## Overview

The integration allows:
- Contact form submissions to automatically create Jira Service Management tickets
- Service agents to respond to tickets, which sends emails to customers
- Automatic email notifications when ticket status changes or comments are added

## Prerequisites

1. **Jira Service Management Instance**: You need access to a Jira Service Management instance
2. **Jira Administrator Permissions**: Required for creating customers via API
3. **API Token**: Generate an API token in your Jira account
4. **Service Desk Configuration**: Set up a service desk with appropriate request types

## Configuration Steps

### 1. Environment Variables

Add the following variables to your `.env` file:

```env
# Jira Service Management Configuration
JIRA_BASE_URL=https://your-domain.atlassian.net
JIRA_USERNAME=your-email@example.com
JIRA_API_TOKEN=your-api-token-here
JIRA_PROJECT_KEY=BOAT-CSM
JIRA_SERVICE_DESK_ID=your-service-desk-id
JIRA_REQUEST_TYPE_ID=your-request-type-id
```

### 2. Get Required IDs

#### Service Desk ID
1. Go to your Jira Service Management instance
2. Navigate to your service desk
3. The Service Desk ID is in the URL: `/servicedesk/projects/BOAT-CSM/servicedesk/`**`SERVICE_DESK_ID`**`/`

#### Request Type ID
1. In your service desk, go to "Request types"
2. Click on the request type you want to use
3. The Request Type ID is in the URL: `/servicedesk/projects/BOAT-CSM/servicedesk/SERVICE_DESK_ID/requesttype/`**`REQUEST_TYPE_ID`**`/`

### 3. Generate API Token

1. Go to [Atlassian Account Settings](https://id.atlassian.com/manage-profile/security/api-tokens)
2. Click "Create API token"
3. Give it a label (e.g., "Boat Website Integration")
4. Copy the generated token

### 4. Test the Connection

Run the test command to verify your configuration:

```bash
php artisan jira:test-connection
```

This will test:
- Basic Jira connection
- Service desk access
- Request types retrieval

## Webhook Configuration

### 1. Set Up Jira Webhook (Localhost Development)

#### For Localhost Development:

**Option 1: Using ngrok (Recommended)**
1. Install ngrok: `snap install ngrok` or download from [ngrok.com](https://ngrok.com)
2. Run the setup script: `./setup-webhook-tunnel.sh`
3. Copy the HTTPS URL (e.g., `https://abc123.ngrok.io`)
4. Use this URL in Jira webhook settings: `https://abc123.ngrok.io/webhook/jira`

**Option 2: Using localtunnel**
1. Install: `npm install -g localtunnel`
2. Start Laravel: `php artisan serve --port=8000`
3. In another terminal: `lt --port 8000`
4. Use the provided URL: `https://YOUR-SUBDOMAIN.loca.lt/webhook/jira`

**Option 3: Using Cloudflare Tunnel**
1. Install cloudflared
2. Run: `cloudflared tunnel --url http://localhost:8000`
3. Use the provided HTTPS URL

#### For Production:

1. Go to your Jira instance → System → Webhooks
2. Click "Create a webhook"
3. Set the following:
   - **Name**: Boat Website Integration
   - **URL**: `https://your-domain.com/webhook/jira`
   - **Events**: Select the following events:
     - Issue created
     - Issue updated
     - Issue commented

### 2. Webhook Security (Optional but Recommended)

For production, consider adding webhook authentication:

1. Generate a secret key
2. Add it to your `.env`: `JIRA_WEBHOOK_SECRET=your-secret-key`
3. Update the webhook controller to verify the secret

## How It Works

### Contact Form Submission Flow

1. **User submits contact form** → Form data is validated
2. **JiraService creates customer** → New customer created in Jira with user's email
3. **JiraService creates ticket** → Service request created in BOAT-CSM project
4. **Email sent to admin** → Notification email sent to admin about new ticket
5. **Logging** → All actions are logged for debugging

### Ticket Update Flow

1. **Agent updates ticket** → Status change or comment added in Jira
2. **Webhook triggered** → Jira sends webhook to your application
3. **Email sent to customer** → Customer receives notification about the update
4. **Logging** → All webhook events are logged

## File Structure

```
app/
├── Services/
│   └── JiraService.php              # Main Jira API integration
├── Http/Controllers/
│   ├── ContactController.php        # Updated to create Jira tickets
│   └── JiraWebhookController.php    # Handles webhook notifications
└── Console/Commands/
    └── TestJiraConnection.php       # Test command for debugging

config/
└── services.php                     # Jira configuration

routes/
└── web.php                          # Webhook route added
```

## Troubleshooting

### Common Issues

1. **"Basic connection failed"**
   - Check your JIRA_BASE_URL format
   - Verify username and API token
   - Ensure the user has appropriate permissions

2. **"Service desk access failed"**
   - Verify JIRA_SERVICE_DESK_ID is correct
   - Check if the user has access to the service desk

3. **"Failed to retrieve request types"**
   - Verify JIRA_REQUEST_TYPE_ID is correct
   - Check if the request type exists and is active

4. **Webhook not receiving events**
   - Verify webhook URL is accessible
   - Check webhook configuration in Jira
   - Review application logs for errors

### Debugging

1. **Check logs**: `tail -f storage/logs/laravel.log`
2. **Test webhook**: Use tools like ngrok for local testing
3. **Verify API calls**: Check Jira audit logs

## Security Considerations

1. **API Token Security**: Store API tokens securely, never commit to version control
2. **Webhook Security**: Consider implementing webhook signature verification
3. **Rate Limiting**: Be aware of Jira API rate limits
4. **Error Handling**: Implement proper error handling and logging

## Testing

### Manual Testing

1. **Test contact form**: Submit the contact form and check if a ticket is created
2. **Test webhook**: Update a ticket in Jira and verify email is sent
3. **Test email delivery**: Check if emails are being sent successfully

### Automated Testing

You can create tests for the integration:

```php
// tests/Feature/JiraIntegrationTest.php
public function test_contact_form_creates_jira_ticket()
{
    // Test implementation
}
```

## Support

If you encounter issues:

1. Check the application logs
2. Run the test command: `php artisan jira:test-connection`
3. Verify all environment variables are set correctly
4. Test the webhook endpoint manually

## Next Steps

1. Configure your Jira instance with the required settings
2. Set up the environment variables
3. Test the connection using the provided command
4. Configure the webhook in Jira
5. Test the complete flow with a real contact form submission
