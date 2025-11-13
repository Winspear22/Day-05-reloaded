#!/bin/bash

# ============================================================================
# MySQL Database Content Inspector
# Shows table content, descriptions, and creates a .txt report
# Usage:
#   ./inspect_db.sh all       # Inspect all databases
#   ./inspect_db.sh ex00      # Inspect only ex00 (Day_05db)
#   ./inspect_db.sh ex01      # Inspect only ex01 (Day05db_ex01)
# ============================================================================

# MySQL credentials
MYSQL_USER="admin"
MYSQL_PASSWORD="adminadmin"
MYSQL_HOST="127.0.0.1"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

# Function to get database name from exercise number
get_database_name() {
  local ex=$1
  case "$ex" in
    ex00) echo "Day_05db" ;;
    ex01) echo "Day05db_ex01" ;;
    ex03) echo "Day05db_ex03" ;;
    ex05) echo "Day05db_ex05" ;;
    ex07) echo "Day05db_ex07" ;;
    ex09) echo "Day05db_ex09" ;;
    ex10) echo "Day05db_ex10" ;;
    ex12) echo "Day05db_ex12" ;;
    ex13) echo "Day05db_ex13" ;;
    *) echo "" ;;
  esac
}

# Check MySQL connection
check_mysql_connection() {
  if ! mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SELECT 1" > /dev/null 2>&1; then
    echo -e "${RED}❌ Erreur : Impossible de se connecter à MySQL${NC}"
    exit 1
  fi
}

# Get all tables from a database
get_tables() {
  local db=$1
  mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -N -e "SHOW TABLES FROM \`$db\`;" 2>/dev/null
}

# Check if database exists
database_exists() {
  local db=$1
  mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "USE \`$db\`;" 2>/dev/null
  return $?
}

# Inspect a single database
inspect_database() {
  local ex=$1
  local db=$2
  local output_file=$3

  echo -e "${BLUE}========================================${NC}"
  echo -e "${BLUE}  Inspection: $ex ($db)${NC}"
  echo -e "${BLUE}========================================${NC}\n"

  # Write to file
  {
    echo "========================================"
    echo "  Inspection: $ex ($db)"
    echo "========================================"
    echo ""
  } >> "$output_file"

  # Check if database exists
  if ! database_exists "$db"; then
    echo -e "${RED}❌ Base de données '$db' n'existe pas${NC}\n"
    {
      echo "❌ Base de données '$db' n'existe pas"
      echo ""
    } >> "$output_file"
    return
  fi

  # Get list of tables
  TABLES=$(get_tables "$db")

  if [ -z "$TABLES" ]; then
    echo -e "${YELLOW}⚠️  Aucune table dans la base de données${NC}\n"
    {
      echo "⚠️  Aucune table dans la base de données"
      echo ""
    } >> "$output_file"
    return
  fi

  # For each table
  while IFS= read -r TABLE; do
    echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${CYAN}Table: $TABLE${NC}"
    echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}\n"

    {
      echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
      echo "Table: $TABLE"
      echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
      echo ""
    } >> "$output_file"

    # DESCRIBE TABLE
    echo -e "${YELLOW}📋 DESCRIBE \`$TABLE\`:${NC}"
    {
      echo "📋 DESCRIBE \`$TABLE\`:"
      echo ""
    } >> "$output_file"

    DESCRIBE=$(mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "DESCRIBE \`$db\`.\`$TABLE\`;" 2>/dev/null)
    echo "$DESCRIBE"
    echo "$DESCRIBE" >> "$output_file"
    echo "" >> "$output_file"
    echo ""

    # COUNT ROWS
    ROWCOUNT=$(mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -N -e "SELECT COUNT(*) FROM \`$db\`.\`$TABLE\`;" 2>/dev/null)
    echo -e "${YELLOW}📊 Nombre de lignes: ${GREEN}$ROWCOUNT${NC}\n"
    {
      echo "📊 Nombre de lignes: $ROWCOUNT"
      echo ""
    } >> "$output_file"

    # SELECT ALL CONTENT
    if [ "$ROWCOUNT" -gt 0 ]; then
      echo -e "${YELLOW}📄 CONTENU DE LA TABLE:${NC}"
      {
        echo "📄 CONTENU DE LA TABLE:"
        echo ""
      } >> "$output_file"

      SELECT_CONTENT=$(mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SELECT * FROM \`$db\`.\`$TABLE\`;" 2>/dev/null)
      echo "$SELECT_CONTENT"
      echo "$SELECT_CONTENT" >> "$output_file"
    else
      echo -e "${YELLOW}📄 Aucune donnée dans cette table${NC}"
      echo "📄 Aucune donnée dans cette table" >> "$output_file"
    fi

    echo ""
    echo "" >> "$output_file"

  done <<< "$TABLES"
}

# Show help
show_help() {
  echo -e "${BLUE}MySQL Database Content Inspector${NC}"
  echo ""
  echo "Usage:"
  echo "  ./inspect_db.sh all        Inspect all databases"
  echo "  ./inspect_db.sh ex00       Inspect only ex00"
  echo "  ./inspect_db.sh ex01       Inspect only ex01"
  echo "  ./inspect_db.sh ex03       Inspect only ex03"
  echo "  ./inspect_db.sh ex05       Inspect only ex05"
  echo "  ./inspect_db.sh ex07       Inspect only ex07"
  echo "  ./inspect_db.sh ex09       Inspect only ex09"
  echo "  ./inspect_db.sh ex10       Inspect only ex10"
  echo "  ./inspect_db.sh ex12       Inspect only ex12"
  echo "  ./inspect_db.sh ex13       Inspect only ex13"
  echo ""
  echo "Output: Creates ex00_database_content.txt, ex01_database_content.txt, etc."
  echo ""
}

# List of all exercises
EXERCISES="ex00 ex01 ex03 ex05 ex07 ex09 ex10 ex12 ex13"

# ============================================================================
# MAIN
# ============================================================================

# Check connection first
check_mysql_connection
echo -e "${GREEN}✓ Connexion à MySQL réussie${NC}\n"

# Get argument
ARGUMENT=$1

if [ -z "$ARGUMENT" ]; then
  show_help
  exit 0
fi

# Process based on argument
if [ "$ARGUMENT" = "all" ]; then
  # Inspect all databases
  for ex in $EXERCISES; do
    DB=$(get_database_name "$ex")
    if [ -n "$DB" ]; then
      OUTPUT_FILE="${ex}_database_content.txt"
      
      # Clear file if exists
      > "$OUTPUT_FILE"
      
      echo -e "${GREEN}➜ Création du fichier: $OUTPUT_FILE${NC}"
      inspect_database "$ex" "$DB" "$OUTPUT_FILE"
    fi
  done
  
  echo -e "\n${GREEN}✓ Tous les fichiers ont été générés !${NC}"
  ls -la *_database_content.txt 2>/dev/null || true

else
  # Check if exercise is valid
  DB=$(get_database_name "$ARGUMENT")
  
  if [ -n "$DB" ]; then
    # Inspect specific database
    OUTPUT_FILE="${ARGUMENT}_database_content.txt"
    
    # Clear file if exists
    > "$OUTPUT_FILE"
    
    echo -e "${GREEN}➜ Création du fichier: $OUTPUT_FILE${NC}\n"
    inspect_database "$ARGUMENT" "$DB" "$OUTPUT_FILE"
    
    echo -e "\n${GREEN}✓ Fichier généré: $OUTPUT_FILE${NC}\n"
    ls -lah "$OUTPUT_FILE"

  else
    echo -e "${RED}❌ Erreur : Exercice '$ARGUMENT' non reconnu${NC}\n"
    show_help
    exit 1
  fi
fi