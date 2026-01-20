# Database Credentials Deployment Guide

This guide explains how database credentials are automatically updated during GitHub Actions deployment.

## Overview

The deployment process automatically updates database credentials in production `database.php` files using GitHub Secrets. This ensures:
- ✅ Local credentials stay private (never committed to git)
- ✅ Production credentials are automatically configured during deployment
- ✅ No manual file editing required on the server
- ✅ Secure credential management via GitHub Secrets

## Required GitHub Secrets

Add these secrets to your GitHub repository for deployment:

### Production Database Secrets (for main branch)

1. **`PROD_DB_USERNAME`**
   - Production database username
   - Example: `traveltechnology_traveltech`

2. **`PROD_DB_PASSWORD`**
   - Production database password
   - Example: `6Oeb**~gcOl_nxRJ`

3. **`PROD_DB_NAME`**
   - Production database name
   - Example: `traveltechnology_tmx_v1`

4. **`PROD_DB_HOST`** (Optional)
   - Database hostname
   - Defaults to `localhost` if not set
   - Example: `localhost` or `db.example.com`

### Development Database Secrets (for develop branch)

1. **`DEV_DB_USERNAME`**
   - Development database username
   - Example: `dev_user`

2. **`DEV_DB_PASSWORD`**
   - Development database password
   - Example: `dev_password`

3. **`DEV_DB_NAME`**
   - Development database name
   - Example: `traveltechnology_dev`

4. **`DEV_DB_HOST`** (Optional)
   - Database hostname
   - Defaults to `localhost` if not set
   - Example: `localhost` or `dev-db.example.com`

## How to Add Secrets

1. Go to your GitHub repository
2. Navigate to **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add each secret with the exact names listed above
5. Enter the corresponding values
6. Click **Add secret**

## Deployment Process

### Production Deployment (main branch)

When you push to the `main` branch, the workflow:

1. **Checks out the code**
2. **Updates database credentials** (runs `scripts/update-db-credentials.sh production`)
   - Updates `agent/application/config/production/database.php`
   - Updates `b2c/config/production/database.php`
   - Updates `supervision/application/config/production/database.php`
3. **Creates deployment package** (with updated credentials)
4. **Deploys to production server** via FTP

### Development Deployment (develop branch)

When you push to the `develop` branch, the workflow:

1. **Checks out the code**
2. **Updates database credentials** (runs `scripts/update-db-credentials.sh development`)
   - Updates `agent/application/config/development/database.php`
   - Updates `b2c/config/development/database.php`
   - Updates `supervision/application/config/development/database.php`
3. **Creates deployment package** (with updated credentials)
4. **Deploys to development server** via FTP

## What Gets Updated

The script updates these lines in each `database.php` file (production or development):

**For Production:**
```php
$db['default']['hostname'] = 'localhost';  // or PROD_DB_HOST
$db['default']['username'] = 'YOUR_PROD_USERNAME';
$db['default']['password'] = 'YOUR_PROD_PASSWORD';
$db['default']['database'] = 'YOUR_PROD_DB_NAME';
```

**For Development:**
```php
$db['default']['hostname'] = 'localhost';  // or DEV_DB_HOST
$db['default']['username'] = 'YOUR_DEV_USERNAME';
$db['default']['password'] = 'YOUR_DEV_PASSWORD';
$db['default']['database'] = 'YOUR_DEV_DB_NAME';
```

## Module-Specific Credentials

If different modules need different database credentials, you can:

1. **Option 1: Use the same credentials for all modules** (recommended)
   - Set `PROD_DB_USERNAME`, `PROD_DB_PASSWORD`, `PROD_DB_NAME` once
   - All three modules (agent, b2c, supervision) will use the same credentials

2. **Option 2: Modify the script for module-specific secrets**
   - Add secrets like `PROD_DB_USERNAME_AGENT`, `PROD_DB_USERNAME_B2C`, etc.
   - Update `scripts/update-db-credentials.sh` to use module-specific secrets

## Verification

After deployment, verify the credentials were updated correctly:

1. **Check GitHub Actions logs:**
   - Look for "Updating database credentials for production deployment..."
   - Should see "✅ Database credentials updated successfully!"

2. **Verify on server:**
   - Check the production `database.php` files on the server
   - Credentials should match your GitHub Secrets

## Troubleshooting

### Credentials Not Updated

If credentials aren't being updated:

1. **Check GitHub Secrets:**
   - Verify all required secrets are set
   - Check secret names match exactly (case-sensitive)

2. **Check workflow logs:**
   - Look for errors in the "Update database credentials" step
   - Verify the script ran successfully

3. **Verify script exists:**
   - Ensure `scripts/update-db-credentials.sh` is committed to git
   - Check file permissions (should be executable)

### Wrong Credentials Deployed

If wrong credentials are deployed:

1. **Update GitHub Secrets:**
   - Go to Settings → Secrets and variables → Actions
   - Update the secret values
   - Redeploy (push to main branch again)

2. **Manual override:**
   - If needed, manually edit `database.php` files on the server
   - Next deployment will overwrite with GitHub Secrets values

## Security Best Practices

1. ✅ **Never commit** actual database credentials to git
2. ✅ **Use GitHub Secrets** for all sensitive credentials
3. ✅ **Rotate passwords** regularly
4. ✅ **Use strong passwords** for production databases
5. ✅ **Limit access** to GitHub Secrets (only repository admins)
6. ✅ **Review logs** to ensure credentials are updated correctly

## Example Workflow

```yaml
# The workflow automatically includes this step:
- name: Update database credentials for production
  env:
    PROD_DB_HOST: ${{ secrets.PROD_DB_HOST || 'localhost' }}
    PROD_DB_USERNAME: ${{ secrets.PROD_DB_USERNAME }}
    PROD_DB_PASSWORD: ${{ secrets.PROD_DB_PASSWORD }}
    PROD_DB_NAME: ${{ secrets.PROD_DB_NAME }}
  run: |
    ./scripts/update-db-credentials.sh
```

## Support

If you encounter issues:
1. Check the GitHub Actions workflow logs
2. Verify all secrets are configured correctly
3. Test the script locally: `./scripts/update-db-credentials.sh`
