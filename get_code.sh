#!/bin/bash

###############################################################################
# Script: get_code.sh
# Description: Extracts project code from a Symfony exercise directory into
#              3 organized text files:
#              - exXX_project_config.txt (config/ + migrations/)
#              - exXX_project_backend.txt (src/ + public/)
#              - exXX_project_frontend.txt (templates/)
#
# Usage: ./get_code.sh exXX
# Example: ./get_code.sh ex00
###############################################################################

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Check if exercise number is provided
if [ $# -eq 0 ]; then
    echo -e "${RED}❌ Usage: ./get_code.sh exXX${NC}"
    echo -e "${RED}Example: ./get_code.sh ex00${NC}"
    exit 1
fi

EXERCISE=$1
EXERCISE_DIR="./$EXERCISE"

# Validate exercise directory exists
if [ ! -d "$EXERCISE_DIR" ]; then
    echo -e "${RED}❌ Error: Directory '$EXERCISE_DIR' not found!${NC}"
    exit 1
fi

# Output files
CONFIG_FILE="${EXERCISE}_project_config.txt"
BACKEND_FILE="${EXERCISE}_project_backend.txt"
FRONTEND_FILE="${EXERCISE}_project_frontend.txt"

# Clear previous files if they exist
> "$CONFIG_FILE"
> "$BACKEND_FILE"
> "$FRONTEND_FILE"

echo -e "${BLUE}📁 Processing exercise: $EXERCISE${NC}"

###############################################################################
# 1. PROJECT STRUCTURE OVERVIEW
###############################################################################

print_project_structure() {
    local file=$1
    local exercise=$2
    
    echo "================================================================================" >> "$file"
    echo "PROJECT STRUCTURE OVERVIEW - $exercise" >> "$file"
    echo "================================================================================" >> "$file"
    echo "" >> "$file"
    echo "Root Directory: ./$exercise/" >> "$file"
    echo "" >> "$file"
    
    if [ -d "./$exercise" ]; then
        tree -L 3 "./$exercise" 2>/dev/null >> "$file" || find "./$exercise" -type f | head -50 >> "$file"
    fi
    
    echo "" >> "$file"
    echo "================================================================================" >> "$file"
    echo "" >> "$file"
}

###############################################################################
# 2. GENERATE CONFIG FILE (config/ + migrations/)
###############################################################################

echo -e "${YELLOW}📝 Generating $CONFIG_FILE...${NC}"

print_project_structure "$CONFIG_FILE" "$EXERCISE"

# Config files
if [ -d "$EXERCISE_DIR/config" ]; then
    echo "📂 CONFIGURATION FILES (config/) - Location: $EXERCISE/config/" >> "$CONFIG_FILE"
    echo "================================================================================" >> "$CONFIG_FILE"
    echo "" >> "$CONFIG_FILE"
    
    find "$EXERCISE_DIR/config" -type f | sort | while read file; do
        rel_path="${file#$EXERCISE_DIR/}"
        echo "" >> "$CONFIG_FILE"
        echo "---" >> "$CONFIG_FILE"
        echo "File: $rel_path" >> "$CONFIG_FILE"
        echo "---" >> "$CONFIG_FILE"
        echo "" >> "$CONFIG_FILE"
        cat "$file" >> "$CONFIG_FILE"
        echo "" >> "$CONFIG_FILE"
    done
else
    echo "📂 CONFIGURATION FILES (config/)" >> "$CONFIG_FILE"
    echo "================================================================================" >> "$CONFIG_FILE"
    echo "⚠️  No 'config' folder found in this exercise." >> "$CONFIG_FILE"
    echo "" >> "$CONFIG_FILE"
fi

echo "" >> "$CONFIG_FILE"
echo "================================================================================" >> "$CONFIG_FILE"
echo "" >> "$CONFIG_FILE"

# Migrations files
if [ -d "$EXERCISE_DIR/migrations" ] && [ "$(ls -A $EXERCISE_DIR/migrations)" ]; then
    echo "📂 MIGRATION FILES (migrations/) - Location: $EXERCISE/migrations/" >> "$CONFIG_FILE"
    echo "================================================================================" >> "$CONFIG_FILE"
    echo "" >> "$CONFIG_FILE"
    
    find "$EXERCISE_DIR/migrations" -type f | sort | while read file; do
        rel_path="${file#$EXERCISE_DIR/}"
        echo "" >> "$CONFIG_FILE"
        echo "---" >> "$CONFIG_FILE"
        echo "File: $rel_path" >> "$CONFIG_FILE"
        echo "---" >> "$CONFIG_FILE"
        echo "" >> "$CONFIG_FILE"
        cat "$file" >> "$CONFIG_FILE"
        echo "" >> "$CONFIG_FILE"
    done
else
    echo "📂 MIGRATION FILES (migrations/)" >> "$CONFIG_FILE"
    echo "================================================================================" >> "$CONFIG_FILE"
    echo "⚠️  No migration files found in this exercise." >> "$CONFIG_FILE"
    echo "" >> "$CONFIG_FILE"
fi

echo -e "${GREEN}✅ $CONFIG_FILE created successfully!${NC}"

###############################################################################
# 3. GENERATE BACKEND FILE (src/ + public/)
###############################################################################

echo -e "${YELLOW}📝 Generating $BACKEND_FILE...${NC}"

print_project_structure "$BACKEND_FILE" "$EXERCISE"

# Source files
if [ -d "$EXERCISE_DIR/src" ]; then
    echo "📂 SOURCE CODE (src/) - Location: $EXERCISE/src/" >> "$BACKEND_FILE"
    echo "================================================================================" >> "$BACKEND_FILE"
    echo "" >> "$BACKEND_FILE"
    
    find "$EXERCISE_DIR/src" -type f \( -name "*.php" -o -name "*.yaml" -o -name "*.yml" -o -name "*.json" \) | sort | while read file; do
        rel_path="${file#$EXERCISE_DIR/}"
        echo "" >> "$BACKEND_FILE"
        echo "---" >> "$BACKEND_FILE"
        echo "File: $rel_path" >> "$BACKEND_FILE"
        echo "---" >> "$BACKEND_FILE"
        echo "" >> "$BACKEND_FILE"
        cat "$file" >> "$BACKEND_FILE"
        echo "" >> "$BACKEND_FILE"
    done
else
    echo "📂 SOURCE CODE (src/)" >> "$BACKEND_FILE"
    echo "================================================================================" >> "$BACKEND_FILE"
    echo "⚠️  No 'src' folder found in this exercise." >> "$BACKEND_FILE"
    echo "" >> "$BACKEND_FILE"
fi

echo "" >> "$BACKEND_FILE"
echo "================================================================================" >> "$BACKEND_FILE"
echo "" >> "$BACKEND_FILE"

# Public files
if [ -d "$EXERCISE_DIR/public" ] && [ "$(ls -A $EXERCISE_DIR/public)" ]; then
    echo "📂 PUBLIC FILES (public/) - Location: $EXERCISE/public/" >> "$BACKEND_FILE"
    echo "================================================================================" >> "$BACKEND_FILE"
    echo "" >> "$BACKEND_FILE"
    
    find "$EXERCISE_DIR/public" -type f | sort | while read file; do
        rel_path="${file#$EXERCISE_DIR/}"
        echo "" >> "$BACKEND_FILE"
        echo "---" >> "$BACKEND_FILE"
        echo "File: $rel_path" >> "$BACKEND_FILE"
        echo "---" >> "$BACKEND_FILE"
        echo "" >> "$BACKEND_FILE"
        
        # For binary files, just indicate they exist
        if file "$file" | grep -q "binary"; then
            echo "[BINARY FILE - Content cannot be displayed]" >> "$BACKEND_FILE"
        else
            cat "$file" >> "$BACKEND_FILE"
        fi
        echo "" >> "$BACKEND_FILE"
    done
else
    echo "📂 PUBLIC FILES (public/)" >> "$BACKEND_FILE"
    echo "================================================================================" >> "$BACKEND_FILE"
    echo "⚠️  No 'public' folder or empty 'public' folder found in this exercise." >> "$BACKEND_FILE"
    echo "" >> "$BACKEND_FILE"
fi

echo -e "${GREEN}✅ $BACKEND_FILE created successfully!${NC}"

###############################################################################
# 4. GENERATE FRONTEND FILE (templates/)
###############################################################################

echo -e "${YELLOW}📝 Generating $FRONTEND_FILE...${NC}"

print_project_structure "$FRONTEND_FILE" "$EXERCISE"

if [ -d "$EXERCISE_DIR/templates" ] && [ "$(ls -A $EXERCISE_DIR/templates)" ]; then
    echo "📂 TEMPLATE FILES (templates/) - Location: $EXERCISE/templates/" >> "$FRONTEND_FILE"
    echo "================================================================================" >> "$FRONTEND_FILE"
    echo "" >> "$FRONTEND_FILE"
    
    find "$EXERCISE_DIR/templates" -type f \( -name "*.html.twig" -o -name "*.twig" -o -name "*.html" \) | sort | while read file; do
        rel_path="${file#$EXERCISE_DIR/}"
        echo "" >> "$FRONTEND_FILE"
        echo "---" >> "$FRONTEND_FILE"
        echo "File: $rel_path" >> "$FRONTEND_FILE"
        echo "---" >> "$FRONTEND_FILE"
        echo "" >> "$FRONTEND_FILE"
        cat "$file" >> "$FRONTEND_FILE"
        echo "" >> "$FRONTEND_FILE"
    done
else
    echo "📂 TEMPLATE FILES (templates/)" >> "$FRONTEND_FILE"
    echo "================================================================================" >> "$FRONTEND_FILE"
    echo "⚠️  No template files found in this exercise." >> "$FRONTEND_FILE"
    echo "" >> "$FRONTEND_FILE"
fi

echo -e "${GREEN}✅ $FRONTEND_FILE created successfully!${NC}"

###############################################################################
# 5. SUMMARY
###############################################################################

echo ""
echo -e "${GREEN}================================================================================${NC}"
echo -e "${GREEN}✨ SUCCESS! All files have been generated:${NC}"
echo -e "${GREEN}================================================================================${NC}"
echo ""
echo -e "  📄 ${BLUE}$CONFIG_FILE${NC}"
echo -e "     └─ Configuration & Migrations"
echo ""
echo -e "  📄 ${BLUE}$BACKEND_FILE${NC}"
echo -e "     └─ Source Code & Public Files"
echo ""
echo -e "  📄 ${BLUE}$FRONTEND_FILE${NC}"
echo -e "     └─ Templates"
echo ""
echo -e "${GREEN}All files are ready to share or review!${NC}"
echo ""