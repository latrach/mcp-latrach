#!/bin/bash

# Script de test pour le serveur MCP

echo "=== Test 1: Lister les outils disponibles ==="
echo '{"jsonrpc":"2.0","id":1,"method":"tools/list"}' | php bin/mcp-server
echo -e "\n"

echo "=== Test 2: Créer un dossier sinistre ==="
echo '{
  "jsonrpc": "2.0",
  "id": 2,
  "method": "tools/call",
  "params": {
    "name": "creer_dossier_sinistre",
    "arguments": {
      "assure": "Jean Dupont",
      "description": "Accident de voiture sur l\'autoroute A1",
      "montant": 5000.00,
      "statut": "ouvert"
    }
  }
}' | php bin/mcp-server
echo -e "\n"

echo "=== Test 3: Consulter un dossier sinistre par numéro ==="
echo '{
  "jsonrpc": "2.0",
  "id": 3,
  "method": "tools/call",
  "params": {
    "name": "consulter_dossier_sinistre",
    "arguments": {
      "numero": "SIN-2024-000001"
    }
  }
}' | php bin/mcp-server
echo -e "\n"

