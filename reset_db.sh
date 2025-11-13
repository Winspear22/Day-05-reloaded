#!/bin/bash

# ============================================================================
# MySQL Database Reset Script for Piscine PHP
# Usage:
#   ./reset_databases.sh all        # Reset all databases
#   ./reset_databases.sh ex00       # Reset only Day_05db
#   ./reset_databases.sh ex01       # Reset only Day05db_ex01
#   ./reset_databases.sh ex03       # Reset only Day05db_ex03
#   etc...
# ============================================================================

# MySQL credentials - À adapter selon ta config
MYSQL_USER="admin"
MYSQL_PASSWORD="adminadmin"
MYSQL_HOST="127.0.0.1"

# Liste des databases
declare -A DATABASES=(
  ["ex00"]="Day_05db"
  ["ex01"]="Day05db_ex01"
  ["ex03"]="Day05db_ex03"
  ["ex05"]="Day05db_ex05"
  ["ex07"]="Day05db_ex07"
  ["ex09"]="Day05db_ex09"
  ["ex10"]="Day05db_ex10"
  ["ex12"]="Day05db_ex12"
  ["ex13"]="Day05db_ex13"
)

# Couleurs pour le terminal
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# ============================================================================
# Fonctions
# ============================================================================

# Vérifier la connexion MySQL
check_mysql_connection() {
  if ! mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SELECT 1" > /dev/null 2>&1; then
    echo -e "${RED}❌ Erreur : Impossible de se connecter à MySQL${NC}"
    echo "Vérifiez les paramètres : MYSQL_USER, MYSQL_PASSWORD, MYSQL_HOST"
    exit 1
  fi
  echo -e "${GREEN}✓ Connexion à MySQL réussie${NC}\n"
}

# Supprimer une database
drop_database() {
  local db=$1
  echo -ne "${YELLOW}   Suppression de '$db'...${NC}"
  mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "DROP DATABASE IF EXISTS \`$db\`;" 2>/dev/null
  if [ $? -eq 0 ]; then
    echo -e " ${GREEN}✓${NC}"
  else
    echo -e " ${RED}✗${NC}"
    return 1
  fi
}

# Créer une database
create_database() {
  local db=$1
  echo -ne "${YELLOW}   Création de '$db'...${NC}"
  mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "CREATE DATABASE \`$db\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
  if [ $? -eq 0 ]; then
    echo -e " ${GREEN}✓${NC}"
  else
    echo -e " ${RED}✗${NC}"
    return 1
  fi
}

# Réinitialiser une database
reset_database() {
  local ex=$1
  local db=$2
  
  echo -e "${BLUE}Réinitialisation de $ex ($db)${NC}"
  drop_database "$db"
  create_database "$db"
  echo ""
}

# Réinitialiser toutes les databases
reset_all() {
  echo -e "${BLUE}========================================${NC}"
  echo -e "${BLUE}   RÉINITIALISATION DE TOUTES LES BASES${NC}"
  echo -e "${BLUE}========================================${NC}\n"
  
  for ex in "${!DATABASES[@]}"; do
    reset_database "$ex" "${DATABASES[$ex]}"
  done
  
  echo -e "${GREEN}✓ Toutes les bases ont été réinitialisées !${NC}\n"
}

# Afficher l'aide
show_help() {
  echo -e "${BLUE}MySQL Database Reset Script${NC}"
  echo ""
  echo "Usage:"
  echo "  ./reset_databases.sh all        Reset all databases"
  echo "  ./reset_databases.sh ex00       Reset only Day_05db"
  echo "  ./reset_databases.sh ex01       Reset only Day05db_ex01"
  echo "  ./reset_databases.sh ex03       Reset only Day05db_ex03"
  echo "  ./reset_databases.sh ex05       Reset only Day05db_ex05"
  echo "  ./reset_databases.sh ex07       Reset only Day05db_ex07"
  echo "  ./reset_databases.sh ex09       Reset only Day05db_ex09"
  echo "  ./reset_databases.sh ex10       Reset only Day05db_ex10"
  echo "  ./reset_databases.sh ex12       Reset only Day05db_ex12"
  echo "  ./reset_databases.sh ex13       Reset only Day05db_ex13"
  echo ""
  echo "Exercices disponibles:"
  for ex in "${!DATABASES[@]}"; do
    printf "  - %-5s → %s\n" "$ex" "${DATABASES[$ex]}"
  done | sort
  echo ""
}

# ============================================================================
# MAIN
# ============================================================================

# Vérifier s'il y a au moins un argument
if [ $# -eq 0 ]; then
  show_help
  exit 0
fi

# Vérifier la connexion MySQL
check_mysql_connection

# Traiter l'argument
ARGUMENT=$1

if [ "$ARGUMENT" == "all" ]; then
  reset_all
elif [ -n "${DATABASES[$ARGUMENT]}" ]; then
  reset_database "$ARGUMENT" "${DATABASES[$ARGUMENT]}"
  echo -e "${GREEN}✓ La base '$ARGUMENT' a été réinitialisée !${NC}\n"
else
  echo -e "${RED}❌ Erreur : Exercice '$ARGUMENT' non reconnu${NC}\n"
  show_help
  exit 1
fi