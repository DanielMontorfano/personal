#!/bin/bash

# Colores para que se vea pro
GREEN='\033[0;32m'
NC='\033[0m' # Sin color

echo -e "${GREEN}🧹 Limpiando y regenerando Laravel...${NC}"

# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Volver a generar
php artisan config:cache
php artisan route:cache

echo -e "${GREEN}✅ Todo listo, Capitán. Laravel está como nuevo.${NC}"
