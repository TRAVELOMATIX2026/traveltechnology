# Deployment Setup Guide

This guide explains how to configure GitHub Secrets for automatic deployment to your servers.

## Required GitHub Secrets

You need to add the following secrets to your GitHub repository:

### For Development Environment (develop branch)

**Required Secrets:**
1. `DEV_FTP_HOST`: `154.221.33.114` (or `ftp.traveltechnology.co.in`)
2. `DEV_FTP_USER`: `traveltechnology`
3. `DEV_FTP_PASS`: `t1b[nMo[qPX_+@dH`
4. `DEV_FTP_PATH`: `/public_html` (or your actual development path)

**Optional Secrets:**
- `DEV_FTP_PORT`: FTP port number (defaults to 21 if not set, use 22 for SFTP)

### For Production Environment (main branch)

**Required Secrets:**
1. `PROD_FTP_HOST`: `154.221.33.114` (or `ftp.traveltechnology.co.in`)
2. `PROD_FTP_USER`: `traveltechnology`
3. `PROD_FTP_PASS`: `t1b[nMo[qPX_+@dH`
4. `PROD_FTP_PATH`: `/public_html` (or your actual production path)

**Optional Secrets:**
- `PROD_FTP_PORT`: FTP port number (defaults to 21 if not set, use 22 for SFTP)

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

The default FTP path is `/public_html`. If your application is deployed to a different directory, update the `FTP_PATH` secrets accordingly.

Common FTP paths:
- `/public_html` (default for cPanel/shared hosting)
- `/www` (some hosting providers)
- `/htdocs` (alternative path)
- `/home/traveltechnology/public_html` (full path)

Note: FTP paths are absolute paths starting with `/`, not relative paths like `~/public_html`.

## FTP Connection Details

### FTP vs SFTP

- **FTP (Port 21)**: Standard FTP protocol (default)
- **SFTP (Port 22)**: Secure FTP over SSH

The workflows support both. If your server uses SFTP, set `FTP_PORT` secret to `22`.

### FTP Host Format

You can use either:
- IP address: `154.221.33.114`
- Domain name: `ftp.traveltechnology.co.in` (if configured)

### Security Note

FTP credentials are transmitted over the network. For better security:
- Use SFTP (port 22) if available
- Consider using FTPS (FTP over SSL) if supported by your hosting provider

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

### FTP Connection Issues

If you get FTP connection errors:

1. **Check FTP credentials:**
   - Verify username and password are correct
   - Check if account is active and not locked

2. **Verify FTP server address:**
   - Test with IP: `154.221.33.114`
   - Or try domain: `ftp.traveltechnology.co.in` (if configured)

3. **Check FTP port:**
   - Default FTP port: `21`
   - SFTP port: `22`
   - Add `FTP_PORT` secret if using non-standard port

4. **Test FTP connection manually:**
   ```bash
   # Using lftp
   lftp -u traveltechnology,password ftp://154.221.33.114
   
   # Or using ftp command
   ftp 154.221.33.114
   ```

5. **Firewall Issues:**
   - Ensure FTP port (21) is open
   - Some hosting providers require passive mode FTP
   - Check if your hosting provider allows FTP from external IPs

6. **Passive Mode:**
   - The workflows use passive mode FTP by default
   - If issues persist, check if your firewall allows passive FTP connections

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
- Verify FTP credentials are correct
- Check if FTP port (21) is open on the server
- Verify the FTP server address is correct
- Test FTP connection manually using an FTP client

### Permission Issues
- Ensure the FTP user has write access to the deployment path
- Note: FTP typically cannot set file permissions directly
- File permissions are usually set automatically by the FTP server
- If needed, use cPanel or SSH to adjust permissions manually

### Deployment Path Issues
- Verify the `FTP_PATH` secret is correct (use absolute path starting with `/`)
- Check if the directory exists on the server
- Ensure the FTP user has access to the directory
- Common paths: `/public_html`, `/www`, `/htdocs`

## Server Information

- **Domain**: traveltechnology.co.in
- **IP Address**: 154.221.33.114
- **Username**: traveltechnology
- **CGI**: Disabled
