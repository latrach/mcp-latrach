#!/bin/bash
# Script pour vérifier la version PHP sur OVH

echo "=== Vérification de la version PHP ==="
php -v
echo ""
echo "=== Version PHP utilisée par défaut ==="
which php
echo ""
echo "=== Versions PHP disponibles ==="
ls -la /usr/bin/php* 2>/dev/null || echo "Pas de versions PHP dans /usr/bin/"
echo ""
echo "=== Configuration OVH (.ovhconfig) ==="
cat .ovhconfig 2>/dev/null || echo "Fichier .ovhconfig non trouvé"
echo ""
echo "=== Test avec différentes versions PHP ==="
if [ -f "/usr/bin/php8.2" ]; then
    echo "PHP 8.2 trouvé:"
    /usr/bin/php8.2 -v
fi
if [ -f "/usr/bin/php8.3" ]; then
    echo "PHP 8.3 trouvé:"
    /usr/bin/php8.3 -v
fi
if [ -f "/usr/bin/php8.4" ]; then
    echo "PHP 8.4 trouvé:"
    /usr/bin/php8.4 -v
fi

