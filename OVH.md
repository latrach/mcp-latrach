# Instructions pour OVH

## ⚠️ Important : Version PHP

Symfony 7.3 nécessite **PHP >= 8.2**. Sur OVH, vous devez utiliser explicitement une version PHP >= 8.2.

### Vérifier la version PHP

```bash
php -v
```

### Utiliser une version PHP spécifique

```bash
# Essayer PHP 8.4
/usr/bin/php8.4 composer.phar install

# Ou PHP 8.3
/usr/bin/php8.3 composer.phar install

# Ou PHP 8.2
/usr/bin/php8.2 composer.phar install
```

### Installation automatique (recommandé)

Utilisez le script `install-ovh.sh` qui détecte automatiquement la bonne version :

```bash
./install-ovh.sh
```

## Utilisation de composer.phar

Le fichier `composer.phar` (version 2.9.1) est inclus dans le projet pour garantir la même version de Composer sur tous les environnements.

### Commandes sur OVH

Au lieu d'utiliser `composer`, utilisez :

```bash
# Avec la version PHP par défaut (peut ne pas fonctionner si < 8.2)
php composer.phar install

# Avec une version PHP spécifique (recommandé)
/usr/bin/php8.4 composer.phar install
```

### Vérification

Pour vérifier que composer.phar fonctionne correctement :

```bash
php composer.phar diagnose
```

### Installation des dépendances

```bash
# Avec version PHP spécifique (recommandé)
/usr/bin/php8.4 composer.phar install --no-dev --optimize-autoloader

# Ou avec le script automatique
./install-ovh.sh
```

### Mise à jour des dépendances

```bash
/usr/bin/php8.4 composer.phar update --no-dev --optimize-autoloader
```

## Résolution des problèmes PHP

Si vous obtenez l'erreur "Your Composer dependencies require a PHP version >= 8.2.0", consultez le fichier **OVH-PHP-FIX.md** pour les solutions détaillées.

## Notes

- Le fichier `composer.phar` est versionné dans le projet pour garantir la cohérence
- Version actuelle : 2.9.1 (identique à votre installation locale via Homebrew)
- Taille : ~3.1 MB

