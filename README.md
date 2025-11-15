# MCP Server - Gestion de Dossiers Sinistres

Serveur MCP (Model Context Protocol) basé sur Symfony 7.3 pour la gestion de dossiers sinistres.

## Installation

1. Installer les dépendances :
```bash
composer install
```

2. Créer le fichier `.env` (copier depuis `.env.example` si disponible) :
```bash
APP_ENV=dev
APP_SECRET=change-this-secret-key-in-production
```

3. Rendre le script MCP exécutable :
```bash
chmod +x bin/mcp-server
```

## Structure du Projet

```
mcp-latrach/
├── bin/
│   ├── console          # Console Symfony
│   └── mcp-server       # Script principal du serveur MCP
├── config/              # Configuration Symfony
├── src/
│   ├── Entity/
│   │   └── Sinistre.php # Entité représentant un dossier sinistre
│   ├── Service/
│   │   └── SinistreService.php # Service de gestion des sinistres
│   ├── Mcp/
│   │   └── McpServer.php # Serveur MCP principal
│   └── Kernel.php
├── var/
│   └── data/            # Stockage des dossiers sinistres (JSON)
└── public/
```

## Utilisation

### Exécution du serveur MCP

Le serveur MCP lit les requêtes depuis stdin et écrit les réponses sur stdout :

```bash
php bin/mcp-server
```

### Actions disponibles

#### 1. Créer un dossier sinistre

**Tool:** `creer_dossier_sinistre`

**Paramètres:**
- `assure` (requis): Nom de l'assuré
- `description` (requis): Description du sinistre
- `montant` (optionnel): Montant du sinistre
- `numero` (optionnel): Numéro du sinistre (généré automatiquement si non fourni)
- `statut` (optionnel): Statut du sinistre (par défaut: "ouvert")

**Exemple de requête:**
```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "method": "tools/call",
  "params": {
    "name": "creer_dossier_sinistre",
    "arguments": {
      "assure": "Jean Dupont",
      "description": "Accident de voiture sur l'autoroute A1",
      "montant": 5000.00,
      "statut": "ouvert"
    }
  }
}
```

#### 2. Consulter un dossier sinistre

**Tool:** `consulter_dossier_sinistre`

**Paramètres:**
- `id` (optionnel): ID du sinistre
- `numero` (optionnel): Numéro du sinistre

Au moins un des deux paramètres doit être fourni.

**Exemple de requête:**
```json
{
  "jsonrpc": "2.0",
  "id": 2,
  "method": "tools/call",
  "params": {
    "name": "consulter_dossier_sinistre",
    "arguments": {
      "numero": "SIN-2024-000001"
    }
  }
}
```

## Format des réponses

### Succès
```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "result": {
    "content": [
      {
        "type": "text",
        "text": "{\"success\": true, \"sinistre\": {...}}"
      }
    ]
  }
}
```

### Erreur
```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "error": {
    "code": -32603,
    "message": "Description de l'erreur"
  }
}
```

## Stockage des données

Les dossiers sinistres sont stockés dans le répertoire `var/data/` sous forme de fichiers JSON individuels. Chaque fichier est nommé `sinistre_{id}.json`.

## Développement

### Tests

Pour tester le serveur MCP manuellement :

```bash
echo '{"jsonrpc":"2.0","id":1,"method":"tools/list"}' | php bin/mcp-server
```

### Structure d'un dossier sinistre

Un dossier sinistre contient les champs suivants :
- `id`: Identifiant unique du sinistre
- `numero`: Numéro du sinistre (format: SIN-YYYY-NNNNNN)
- `dateCreation`: Date de création (format: YYYY-MM-DD HH:MM:SS)
- `assure`: Nom de l'assuré
- `description`: Description du sinistre
- `statut`: Statut du sinistre (ouvert, en_cours, cloture, rejete)
- `montant`: Montant du sinistre

## Licence

MIT

