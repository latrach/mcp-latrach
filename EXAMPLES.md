# Exemples d'utilisation du serveur MCP

## Configuration pour Claude Desktop

Ajoutez la configuration suivante dans votre fichier de configuration MCP (généralement `~/Library/Application Support/Claude/claude_desktop_config.json` sur macOS) :

```json
{
  "mcpServers": {
    "sinistre": {
      "command": "php",
      "args": [
        "/chemin/vers/mcp-latrach/bin/mcp-server"
      ],
      "env": {
        "APP_ENV": "dev"
      }
    }
  }
}
```

Remplacez `/chemin/vers/mcp-latrach` par le chemin absolu vers votre projet.

## Exemples de requêtes JSON-RPC

### 1. Initialiser le serveur

```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "method": "initialize",
  "params": {
    "protocolVersion": "2024-11-05",
    "capabilities": {},
    "clientInfo": {
      "name": "test-client",
      "version": "1.0.0"
    }
  }
}
```

### 2. Lister les outils disponibles

```json
{
  "jsonrpc": "2.0",
  "id": 2,
  "method": "tools/list"
}
```

### 3. Créer un dossier sinistre

```json
{
  "jsonrpc": "2.0",
  "id": 3,
  "method": "tools/call",
  "params": {
    "name": "creer_dossier_sinistre",
    "arguments": {
      "assure": "Marie Martin",
      "description": "Incendie dans le garage",
      "montant": 15000.50,
      "statut": "ouvert"
    }
  }
}
```

### 4. Consulter un dossier sinistre par ID

```json
{
  "jsonrpc": "2.0",
  "id": 4,
  "method": "tools/call",
  "params": {
    "name": "consulter_dossier_sinistre",
    "arguments": {
      "id": "sin_67890abcdef"
    }
  }
}
```

### 5. Consulter un dossier sinistre par numéro

```json
{
  "jsonrpc": "2.0",
  "id": 5,
  "method": "tools/call",
  "params": {
    "name": "consulter_dossier_sinistre",
    "arguments": {
      "numero": "SIN-2024-000001"
    }
  }
}
```

## Test en ligne de commande

Vous pouvez tester le serveur directement depuis la ligne de commande :

```bash
# Tester la liste des outils
echo '{"jsonrpc":"2.0","id":1,"method":"tools/list"}' | php bin/mcp-server

# Créer un sinistre
echo '{
  "jsonrpc": "2.0",
  "id": 2,
  "method": "tools/call",
  "params": {
    "name": "creer_dossier_sinistre",
    "arguments": {
      "assure": "Test User",
      "description": "Test de création",
      "montant": 1000
    }
  }
}' | php bin/mcp-server
```

Ou utilisez le script de test fourni :

```bash
./examples/test-mcp.sh
```

## Format de réponse

### Réponse de succès pour création

```json
{
  "jsonrpc": "2.0",
  "id": 3,
  "result": {
    "content": [
      {
        "type": "text",
        "text": "{\n    \"success\": true,\n    \"message\": \"Dossier sinistre créé avec succès\",\n    \"sinistre\": {\n        \"id\": \"sin_67890abcdef\",\n        \"numero\": \"SIN-2024-000001\",\n        \"dateCreation\": \"2024-12-19 10:30:00\",\n        \"assure\": \"Marie Martin\",\n        \"description\": \"Incendie dans le garage\",\n        \"statut\": \"ouvert\",\n        \"montant\": 15000.5\n    }\n}"
      }
    ]
  }
}
```

### Réponse de succès pour consultation

```json
{
  "jsonrpc": "2.0",
  "id": 4,
  "result": {
    "content": [
      {
        "type": "text",
        "text": "{\n    \"success\": true,\n    \"sinistre\": {\n        \"id\": \"sin_67890abcdef\",\n        \"numero\": \"SIN-2024-000001\",\n        \"dateCreation\": \"2024-12-19 10:30:00\",\n        \"assure\": \"Marie Martin\",\n        \"description\": \"Incendie dans le garage\",\n        \"statut\": \"ouvert\",\n        \"montant\": 15000.5\n    }\n}"
      }
    ]
  }
}
```

### Réponse d'erreur

```json
{
  "jsonrpc": "2.0",
  "id": 5,
  "error": {
    "code": -32603,
    "message": "Description de l'erreur"
  }
}
```

