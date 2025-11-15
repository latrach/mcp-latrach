#!/bin/bash
# Script d'installation pour OVH
# Trouve automatiquement la bonne version de PHP et installe les dépendances

set -e

echo "=== Recherche de la version PHP >= 8.2 ==="

# Essayer différentes versions PHP dans l'ordre de préférence
PHP_BIN=""
for version in "8.4" "8.3" "8.2"; do
    if command -v "php${version}" &> /dev/null; then
        PHP_BIN="php${version}"
        break
    elif [ -f "/usr/bin/php${version}" ]; then
        PHP_BIN="/usr/bin/php${version}"
        break
    fi
done

# Si aucune version spécifique trouvée, utiliser php par défaut
if [ -z "$PHP_BIN" ]; then
    PHP_BIN="php"
fi

echo "Utilisation de: $PHP_BIN"
$PHP_BIN -v

# Vérifier la version
PHP_VERSION=$($PHP_BIN -r 'echo PHP_VERSION;')
echo "Version PHP détectée: $PHP_VERSION"

# Vérifier si la version est >= 8.2
if ! $PHP_BIN -r 'exit(version_compare(PHP_VERSION, "8.2.0", ">=") ? 0 : 1)'; then
    echo "⚠️  ATTENTION: La version PHP ($PHP_VERSION) est inférieure à 8.2.0"
    echo "L'installation peut échouer. Utilisez --ignore-platform-reqs si nécessaire."
    read -p "Continuer quand même? (y/N) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
    IGNORE_PLATFORM="--ignore-platform-reqs"
else
    IGNORE_PLATFORM=""
fi

echo ""
echo "=== Installation des dépendances ==="

if [ ! -f "composer.phar" ]; then
    echo "Téléchargement de composer.phar..."
    curl -o composer.phar https://getcomposer.org/download/2.9.1/composer.phar
    chmod +x composer.phar
fi

$PHP_BIN composer.phar install --no-dev --optimize-autoloader $IGNORE_PLATFORM

echo ""
echo "=== Vérification de l'installation ==="
if [ -d "vendor" ]; then
    echo "✅ Installation réussie!"
    echo ""
    echo "Pour tester le serveur MCP:"
    echo "  $PHP_BIN bin/mcp-server <<< '{\"jsonrpc\":\"2.0\",\"id\":1,\"method\":\"tools/list\"}'"
else
    echo "❌ Erreur lors de l'installation"
    exit 1
fi

