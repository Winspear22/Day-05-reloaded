#!/bin/bash

# Parcourir tous les dossiers commençant par 'ex' dans le répertoire courant
for dir in ex*/; do
    # Supprimer le slash final du nom du dossier
    dir_name="${dir%/}"
    
    # Vérifier si le dossier vendor existe
    if [ -d "$dir/vendor" ]; then
        echo "Le dossier '$dir_name' a déjà été correctement installé"
    else
        echo "Installation des dépendances pour '$dir_name'..."
        cd "$dir"
        composer install
        cd ..
    fi
done