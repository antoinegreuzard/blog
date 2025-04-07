#!/bin/bash

# Fichier ou dossier à traiter
TARGET_DIR="src/Entity"

# Parcours tous les fichiers PHP dans les entités
find "$TARGET_DIR" -name "*.php" | while read -r file; do
  echo "Traitement de $file"

  # Remplace la syntaxe openapiContext par la nouvelle avec Operation
  sed -i -E '/openapiContext:/ {
    N;N;
    s/^[[:space:]]*openapiContext: \[\n[[:space:]]*'\''description'\'' => '\''([^'\'']+)'\''[[:space:]]*,\n[[:space:]]*'\''operationId'\'' => '\''([^'\'']+)'\''[[:space:]]*,?\n?[[:space:]]*\]/openapi: new Operation(\n                description: '\1',\n                operationId: '\2',\n            )/
  }' "$file"
done

echo "Migration terminée."
