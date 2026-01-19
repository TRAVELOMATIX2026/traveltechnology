# Deployment Setup Guide

This guide explains how to configure GitHub Secrets for automatic deployment to your servers.

## Required GitHub Secrets

You need to add the following secrets to your GitHub repository:

### For Development Environment (develop branch)

**Required Secrets:**
1. `DEV_SERVER_IP`: `154.221.33.114`
2. `DEV_SSH_USER`: `traveltechnology`
3. `DEV_SSH_PASS`: `t1b[nMo[qPX_+@dH`
4. `DEV_DEPLOY_PATH`: `~/public_html` (or your actual development path)

**Optional Secrets:**
- `DEV_SSH_KEY`: SSH private key for key-based authentication (more secure than password)

### For Production Environment (main branch)

**Required Secrets:**
1. `PROD_SERVER_IP`: `154.221.33.114`
2. `PROD_SSH_USER`: `traveltechnology`
3. `PROD_SSH_PASS`: `t1b[nMo[qPX_+@dH`
4. `PROD_DEPLOY_PATH`: `~/public_html` (or your actual production path)

**Optional Secrets:**
- `PROD_SSH_KEY`: SSH private key for key-based authentication (more secure than password)
- `PROD_SSH_PORT`: SSH port number (defaults to 22 if not set)

> **Note:** All secret names follow GitHub's naming rules:
> - Only alphanumeric characters `[a-z]`, `[A-Z]`, `[0-9]` and underscores `_`
> - Must start with a letter `[a-z]`, `[A-Z]` or underscore `_`
> - No spaces or special characters allowed

## How to Add Secrets to GitHub

1. Go to your GitHub repository
2. Click on **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add each secret with the exact names listed above
5. Enter the corresponding values
6. Click **Add secret**

## Deployment Paths

The default deployment path is `~/public_html`. If your application is deployed to a different directory, update the `DEPLOY_PATH` secrets accordingly.

Common paths:
- `~/public_html` (default for cPanel/shared hosting)
- `/var/www/html` (typical Apache setup)
- `/var/www/traveltechnology` (custom path)
- `/home/traveltechnology/public_html` (full path)

## Security Recommendations

### Option 1: SSH Key Authentication (Recommended)

For better security, set up SSH key authentication instead of password authentication:

1. Generate an SSH key pair (if you don't have one):
   ```bash
   ssh-keygen -t rsa -b 4096 -C "github-actions@traveltechnology"
   ```

2. Copy the public key to your server:
   ```bash
   ssh-copy-id -i ~/.ssh/id_rsa.pub traveltechnology@154.221.33.114
   ```

3. Copy the private key content and add it as `DEV_SSH_KEY` or `PROD_SSH_KEY` secret in GitHub

4. Update the workflow to use SSH key authentication (the workflows already support this)

### Option 2: Password Authentication (Current Setup)

The workflows are currently configured to use password authentication with `sshpass`. This works but is less secure than SSH keys.

## What Gets Deployed

### Included Files:
- All PHP files
- Configuration files
- Views, controllers, models
- Assets (if not excluded)

### Excluded Files:
- `.git` directory
- `.github` directory
- `logs/*` (existing log files)
- `cache/*` (existing cache files)
- `*.log` files
- `.env` files (should be manually configured on server)
- `node_modules` (if any)
- Development/testing config files (in production)


## Testing Deployment

### Test Development Deployment:
1. Push changes to the `develop` branch
2. The workflow will automatically:
   - Run syntax checks
   - Run code quality checks
   - Deploy to development server
   - Verify deployment

### Test Production Deployment:
1. Merge changes to the `main` branch
2. The workflow will automatically:
   - Run all checks
   - Deploy to production server
   - Verify deployment
   - Create a deployment tag

## Manual Deployment Trigger

You can also manually trigger the production workflow:
1. Go to **Actions** tab in GitHub
2. Select **Production CI/CD** workflow
3. Click **Run workflow**
4. Select the branch (usually `main`)
5. Click **Run workflow**

## Troubleshooting

### SSH Connection Timeout

If you get `ssh: connect to host *** port 22: Connection timed out`:

1. **Check if SSH port is open:**
   ```bash
   telnet 154.221.33.114 22
   # or
   nc -zv 154.221.33.114 22
   ```

2. **Verify server IP is correct:** `154.221.33.114`

3. **Check if server uses a different SSH port:**
   - If your server uses a non-standard port (not 22), add `PROD_SSH_PORT` secret with the correct port number

4. **Firewall Issues:**
   - GitHub Actions runs from various IP addresses
   - Your server firewall may be blocking connections from GitHub's IP ranges
   - You may need to whitelist GitHub Actions IP ranges or temporarily allow all IPs for SSH

5. **Test SSH connection manually:**
   ```bash
   ssh traveltechnology@154.221.33.114
   # or with specific port
   ssh -p 2222 traveltechnology@154.221.33.114
   ```

6. **Check server status:**
   - Ensure the server is online
   - Verify SSH service is running: `systemctl status sshd` (on Linux)

### HTTP 500 Error After Deployment

If your website shows "HTTP ERROR 500" after deployment:

1. **Check PHP errors:**
   - Look for PHP syntax errors in the logs
   - Check `logs/` directory on the server
   - Review Apache/Nginx error logs

2. **Verify file permissions:**
   - Files should be 644
   - Directories should be 755
   - Logs/cache directories may need 777

3. **Check configuration files:**
   - Ensure `b2c/config/production/` config files exist
   - Verify database connection settings
   - Check `.env` or configuration files haven't been overwritten

4. **Review deployment logs:**
   - Check GitHub Actions logs for any errors during deployment
   - Verify all files were copied correctly


### Connection Issues
- Verify SSH credentials are correct
- Check if SSH port (22) is open on the server
- Verify the server IP is correct
- Test SSH connection manually: `ssh traveltechnology@154.221.33.114`

### Permission Issues
- Ensure the deployment user has write access to the deployment path
- Check file permissions after deployment
- May need to adjust `chmod` commands in the workflow

### Deployment Path Issues
- Verify the `DEPLOY_PATH` secret is correct
- Check if the directory exists on the server
- Ensure the user has access to the directory

## Server Information

- **Domain**: traveltechnology.co.in
- **IP Address**: 154.221.33.114
- **Username**: traveltechnology
- **CGI**: Disabled
