# Résolution du problème PHP sur OVH

## Problème
Composer détecte que votre version PHP est inférieure à 8.2.0 alors que Symfony 7.3 nécessite PHP >= 8.2.

## Solution 1 : Vérifier la version PHP actuelle

Sur OVH, exécutez :

```bash
php -v
```

## Solution 2 : Utiliser explicitement PHP 8.2 ou supérieur

Sur OVH, les versions PHP sont souvent disponibles via des alias. Essayez :

```bash
# Vérifier les versions disponibles
ls -la /usr/bin/php*

# Utiliser PHP 8.2 explicitement
/usr/bin/php8.2 composer.phar install

# Ou PHP 8.3
/usr/bin/php8.3 composer.phar install

# Ou PHP 8.4
/usr/bin/php8.4 composer.phar install
```

## Solution 3 : Créer un alias dans votre shell

Ajoutez dans votre `~/.bashrc` ou `~/.zshrc` :

```bash
alias php='/usr/bin/php8.4'
alias composer='php composer.phar'
```

Puis rechargez :
```bash
source ~/.bashrc
# ou
source ~/.zshrc
```

## Solution 4 : Vérifier et mettre à jour .ovhconfig

Votre `.ovhconfig` devrait contenir :

```
environment=production
app.engine.version=8.4
app.engine=php
container.image=stable64
http.firewall=security
```

Si ce n'est pas le cas, mettez à jour le fichier et redéployez.

## Solution 5 : Script d'installation automatique

Créez un script `install-ovh.sh` :

```bash
#!/bin/bash
# Trouver la version PHP >= 8.2
PHP_BIN=$(which php8.4 || which php8.3 || which php8.2 || which php)

if [ -z "$PHP_BIN" ]; then
    echo "Erreur: Aucune version PHP >= 8.2 trouvée"
    exit 1
fi

echo "Utilisation de: $PHP_BIN"
$PHP_BIN -v

# Installer les dépendances
$PHP_BIN composer.phar install --no-dev --optimize-autoloader
```

## Solution 6 : Forcer la plateforme (temporaire, non recommandé)

⚠️ **Attention** : Cette solution peut causer des problèmes de compatibilité.

```bash
php composer.phar install --ignore-platform-reqs
```

## Vérification

Après installation, vérifiez :

```bash
php bin/mcp-server <<< '{"jsonrpc":"2.0","id":1,"method":"tools/list"}'
```

## Commandes utiles sur OVH

```bash
# Vérifier la version PHP
php -v

# Vérifier où se trouve PHP
which php

# Lister toutes les versions PHP disponibles
ls -la /usr/bin/php*

# Tester avec une version spécifique
/usr/bin/php8.4 -v

# Installer avec une version spécifique
/usr/bin/php8.4 composer.phar install --no-dev --optimize-autoloader
```

