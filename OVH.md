# Instructions pour OVH

## Utilisation de composer.phar

Le fichier `composer.phar` (version 2.9.1) est inclus dans le projet pour garantir la même version de Composer sur tous les environnements.

### Commandes sur OVH

Au lieu d'utiliser `composer`, utilisez :

```bash
php composer.phar install
php composer.phar update
php composer.phar require [package]
php composer.phar --version
```

### Vérification

Pour vérifier que composer.phar fonctionne correctement :

```bash
php composer.phar diagnose
```

### Installation des dépendances

```bash
php composer.phar install --no-dev --optimize-autoloader
```

### Mise à jour des dépendances

```bash
php composer.phar update --no-dev --optimize-autoloader
```

## Notes

- Le fichier `composer.phar` est versionné dans le projet pour garantir la cohérence
- Version actuelle : 2.9.1 (identique à votre installation locale via Homebrew)
- Taille : ~3.1 MB

