# Localhost Webhook Setup for Jira Integration

This guide helps you expose your localhost development server to the internet so Jira can send webhooks to it.

## 🚀 Quick Start (Recommended)

### Step 1: Install ngrok
```bash
# Option 1: Using snap (easiest)
snap install ngrok

# Option 2: Download directly
wget https://bin.equinox.io/c/bNyj1mQVY4c/ngrok-v3-stable-linux-amd64.tgz
tar xvzf ngrok-v3-stable-linux-amd64.tgz
sudo mv ngrok /usr/local/bin/
```

### Step 2: Run the setup script
```bash
./setup-webhook-tunnel.sh
```

### Step 3: Configure Jira Webhook
1. Copy the HTTPS URL from ngrok (e.g., `https://abc123.ngrok.io`)
2. Go to your Jira instance → System → Webhooks
3. Create a new webhook with URL: `https://abc123.ngrok.io/webhook/jira`
4. Select events: Issue created, Issue updated, Issue commented

## 🔧 Alternative Methods

### Method 1: Using localtunnel
```bash
# Install localtunnel
npm install -g localtunnel

# Start Laravel server
php artisan serve --port=8000

# In another terminal, expose it
lt --port 8000
```

### Method 2: Using Cloudflare Tunnel
```bash
# Install cloudflared
wget https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb
sudo dpkg -i cloudflared-linux-amd64.deb

# Create tunnel
cloudflared tunnel --url http://localhost:8000
```

## 🧪 Testing Your Webhook

### Test the webhook endpoint:
```bash
# Test if your webhook is accessible
curl -X POST https://YOUR-NGROK-URL.ngrok.io/webhook/jira \
  -H "Content-Type: application/json" \
  -d '{"test": "webhook"}'
```

### Test with Jira:
1. Submit a contact form on your website
2. Check if a Jira ticket is created
3. Update the ticket in Jira
4. Check if you receive email notifications

## 📝 Important Notes

### For Development:
- **ngrok URLs change** each time you restart ngrok (unless you have a paid account)
- **Update Jira webhook URL** when ngrok restarts
- **Use the setup script** to automate the process

### For Production:
- **Use your actual domain** instead of ngrok
- **Set up proper SSL certificates**
- **Configure webhook authentication** for security

## 🐛 Troubleshooting

### Common Issues:

1. **"Connection refused"**
   - Make sure Laravel server is running on port 8000
   - Check if ngrok is properly exposing the port

2. **"Webhook not receiving events"**
   - Verify the webhook URL in Jira settings
   - Check Laravel logs: `tail -f storage/logs/laravel.log`

3. **"ngrok command not found"**
   - Install ngrok properly: `snap install ngrok`
   - Or add to PATH: `export PATH=$PATH:/path/to/ngrok`

### Debug Commands:
```bash
# Check if Laravel is running
curl http://localhost:8000

# Check ngrok status
ngrok http 8000 --log=stdout

# Check webhook endpoint
curl -X POST https://YOUR-URL.ngrok.io/webhook/jira -v
```

## 🎯 Next Steps

1. **Set up your Jira credentials** in `.env` file
2. **Test the complete flow**:
   - Submit contact form → Jira ticket created
   - Update ticket in Jira → Email sent to customer
3. **Deploy to production** when ready
4. **Update webhook URL** to your production domain

Your Jira integration is now ready for localhost development! 🚀
