#!/bin/bash
# Script to update database credentials in database.php files
# This script is used during GitHub Actions deployment
# Supports both development and production environments

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Determine environment (development or production)
ENVIRONMENT="${1:-production}"

if [ "$ENVIRONMENT" = "development" ]; then
    # Development database credentials
    DB_HOST="${DEV_DB_HOST:-localhost}"
    DB_USERNAME="${DEV_DB_USERNAME}"
    DB_PASSWORD="${DEV_DB_PASSWORD}"
    DB_NAME="${DEV_DB_NAME}"
    CONFIG_DIR="development"
else
    # Production database credentials
    DB_HOST="${PROD_DB_HOST:-localhost}"
    DB_USERNAME="${PROD_DB_USERNAME}"
    DB_PASSWORD="${PROD_DB_PASSWORD}"
    DB_NAME="${PROD_DB_NAME}"
    CONFIG_DIR="production"
fi

# Validate required variables
if [ -z "$DB_USERNAME" ] || [ -z "$DB_PASSWORD" ] || [ -z "$DB_NAME" ]; then
    echo -e "${RED}❌ Error: Missing required database credentials for ${ENVIRONMENT} environment${NC}"
    echo "Required environment variables:"
    if [ "$ENVIRONMENT" = "development" ]; then
        echo "  - DEV_DB_USERNAME"
        echo "  - DEV_DB_PASSWORD"
        echo "  - DEV_DB_NAME"
        echo "Optional:"
        echo "  - DEV_DB_HOST (defaults to localhost)"
    else
        echo "  - PROD_DB_USERNAME"
        echo "  - PROD_DB_PASSWORD"
        echo "  - PROD_DB_NAME"
        echo "Optional:"
        echo "  - PROD_DB_HOST (defaults to localhost)"
    fi
    exit 1
fi

echo -e "${GREEN}📝 Updating database credentials for ${ENVIRONMENT} deployment...${NC}"
echo "Database Host: $DB_HOST"
echo "Database Username: $DB_USERNAME"
echo "Database Name: $DB_NAME"

# Function to update database credentials in a file
update_db_file() {
    local file_path="$1"
    local module_name="$2"
    
    if [ ! -f "$file_path" ]; then
        echo -e "${YELLOW}⚠️  Warning: File not found: $file_path${NC}"
        return 1
    fi
    
    echo -e "${GREEN}  → Updating $module_name${NC}"
    
    # Use sed with pipe delimiter to avoid quote issues
    # This approach is more compatible and handles special characters better
    
    # Update hostname (match: $db['default']['hostname'] = 'anything';
    sed -i.bak "s|\$db\['default'\]\['hostname'\] = '[^']*';|\$db['default']['hostname'] = '${DB_HOST}';|" "$file_path"
    
    # Update username (match: $db['default']['username'] = 'anything';
    sed -i.bak "s|\$db\['default'\]\['username'\] = '[^']*';|\$db['default']['username'] = '${DB_USERNAME}';|" "$file_path"
    
    # Update password (match: $db['default']['password'] = 'anything';
    # Using pipe delimiter avoids issues with special characters in password
    sed -i.bak "s|\$db\['default'\]\['password'\] = '[^']*';|\$db['default']['password'] = '${DB_PASSWORD}';|" "$file_path"
    
    # Update database name (match: $db['default']['database'] = 'anything';
    sed -i.bak "s|\$db\['default'\]\['database'\] = '[^']*';|\$db['default']['database'] = '${DB_NAME}';|" "$file_path"
    
    # Remove backup files
    rm -f "${file_path}.bak"
    
    echo -e "${GREEN}    ✓ Updated successfully${NC}"
    return 0
}

# Update database files for each module
echo ""
echo -e "${GREEN}Updating ${ENVIRONMENT} database configurations...${NC}"

# Agent module
if [ -f "agent/application/config/${CONFIG_DIR}/database.php" ]; then
    update_db_file "agent/application/config/${CONFIG_DIR}/database.php" "Agent Module"
else
    echo -e "${YELLOW}⚠️  Agent ${ENVIRONMENT} database.php not found${NC}"
fi

# B2C module - always uses development config directory
if [ -f "b2c/config/development/database.php" ]; then
    update_db_file "b2c/config/development/database.php" "B2C Module"
else
    echo -e "${YELLOW}⚠️  B2C development database.php not found${NC}"
fi

# Supervision module
if [ -f "supervision/application/config/${CONFIG_DIR}/database.php" ]; then
    update_db_file "supervision/application/config/${CONFIG_DIR}/database.php" "Supervision Module"
else
    echo -e "${YELLOW}⚠️  Supervision ${ENVIRONMENT} database.php not found${NC}"
fi

echo ""
echo -e "${GREEN}✅ Database credentials updated successfully!${NC}"
