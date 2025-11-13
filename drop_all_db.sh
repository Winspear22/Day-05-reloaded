# ============================================================================
# MySQL Drop ALL Databases Script
# ⚠️ ATTENTION : Ce script supprime TOUTES les databases (sauf system)
# Usage:
#   ./drop_all_databases.sh
# ============================================================================

# MySQL credentials
MYSQL_USER="admin"
MYSQL_PASSWORD="adminadmin"
MYSQL_HOST="127.0.0.1"

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# ============================================================================
# Vérifier la connexion
# ============================================================================

echo -e "${BLUE}Vérification de la connexion MySQL...${NC}"
if ! mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SELECT 1" > /dev/null 2>&1; then
  echo -e "${RED}❌ Erreur : Impossible de se connecter à MySQL${NC}"
  exit 1
fi
echo -e "${GREEN}✓ Connexion réussie${NC}\n"

# ============================================================================
# Confirmation
# ============================================================================

echo -e "${RED}⚠️  ATTENTION ! Vous êtes sur le point de supprimer TOUTES les databases !${NC}"
echo -e "${RED}Cette action est irréversible.${NC}\n"

echo "Les databases système suivantes ne seront PAS supprimées :"
echo "  - information_schema"
echo "  - mysql"
echo "  - performance_schema"
echo "  - sys"
echo ""

read -p "Êtes-vous sûr ? (tapez 'oui' pour continuer) : " CONFIRM

if [ "$CONFIRM" != "oui" ]; then
  echo -e "${YELLOW}Opération annulée.${NC}"
  exit 0
fi

# ============================================================================
# Supprimer toutes les databases (sauf système)
# ============================================================================

echo -e "\n${BLUE}Suppression des databases...${NC}\n"

# Récupérer toutes les databases et les supprimer
DATABASES=$(mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SHOW DATABASES;" | grep -v "Database" | grep -v "information_schema" | grep -v "mysql" | grep -v "performance_schema" | grep -v "sys")

if [ -z "$DATABASES" ]; then
  echo -e "${YELLOW}Aucune database utilisateur à supprimer.${NC}"
  exit 0
fi

COUNT=0
for DB in $DATABASES; do
  echo -ne "${YELLOW}   Suppression de '$DB'...${NC}"
  if mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "DROP DATABASE \`$DB\`;" 2>/dev/null; then
    echo -e " ${GREEN}✓${NC}"
    ((COUNT++))
  else
    echo -e " ${RED}✗${NC}"
  fi
done

echo ""
echo -e "${GREEN}✓ $COUNT database(s) supprimée(s) !${NC}\n"