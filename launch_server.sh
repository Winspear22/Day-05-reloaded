#!/bin/bash

set -e  # Arrêter le script si une commande échoue

# ====== ÉTAPE 0 : Vérifier et traiter les arguments ======
if [ -z "$1" ]; then
    echo "📋 Exercices disponibles :"
    for dir in ex*/; do
        if [ -f "$dir/composer.json" ]; then
            echo "   ${dir%/}"
        fi
    done
    echo ""
    echo "Usage : ./launch_server.sh ex00"
    exit 0
fi

EXERCISE=$1
PROJECT_DIR="$EXERCISE"

# Vérifier que le dossier existe
if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ Erreur : Le dossier '$PROJECT_DIR' n'existe pas"
    exit 1
fi

# Vérifier que c'est un projet Symfony (présence du fichier composer.json)
if [ ! -f "$PROJECT_DIR/composer.json" ]; then
    echo "❌ Erreur : '$PROJECT_DIR' n'est pas un projet Symfony valide (pas de composer.json)"
    exit 1
fi

echo "📦 Projet détecté : $EXERCISE"

# ====== ÉTAPE 1 : Se placer dans le dossier du projet ======
cd "$PROJECT_DIR"

echo "🚀 Démarrage du serveur Symfony en mode PRODUCTION pour $EXERCISE..."

# ====== ÉTAPE 2 : Fermer les serveurs existants ======
echo "🛑 Arrêt des serveurs Symfony existants..."

pkill -f "symfony.*local:server:start" || true
sleep 1
pkill -9 -f "symfony.*local:server:start" || true

echo "✓ Serveurs fermés"

# ====== ÉTAPE 3 : Nettoyer le cache et fichiers temporaires ======
echo "🧹 Nettoyage du cache et fichiers temporaires..."

# Mode PRODUCTION
symfony console cache:clear --env=prod
symfony console cache:clear --env=prod --no-warmup

# Mode DÉVELOPPEMENT (au cas où)
symfony console cache:clear --env=dev || true

# Nettoyer les fichiers temporaires
rm -rf var/cache/* || true
rm -rf var/sessions/* || true
rm -rf var/log/* || true

echo "✓ Cache et fichiers temporaires nettoyés"

# ====== ÉTAPE 4 : Réchauffer le cache en production ======
echo "♨️  Réchauffage du cache pour la production..."
symfony console cache:warmup --env=prod

echo "✓ Cache préchauffé"

# ====== ÉTAPE 5 : Lancer le serveur en mode REMOTE ======
echo "🌐 Lancement du serveur Symfony pour $EXERCISE..."
echo "   (Compatible avec Symfony CLI 5.12.0)"

# Adapter à ta version de Symfony CLI
# --allow-all-ip permet l'accès depuis toutes les interfaces réseau (au lieu de localhost)
# --allow-http permet HTTP (sinon HTTPS obligatoire)
# --port=8000 pour le port
symfony local:server:start --port=8000 --allow-all-ip --allow-http

echo "✓ Serveur démarré !"
echo ""
echo "==============================================="
echo "📍 Serveur accessible à :"
echo "   http://192.168.68.103:8000"
echo "==============================================="
