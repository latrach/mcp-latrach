<?php

namespace App\Mcp;

use App\Service\SinistreService;
use Symfony\Component\Serializer\SerializerInterface;

class McpServer
{
    private SinistreService $sinistreService;
    private SerializerInterface $serializer;

    public function __construct(SinistreService $sinistreService, SerializerInterface $serializer)
    {
        $this->sinistreService = $sinistreService;
        $this->serializer = $serializer;
    }

    public function handleRequest(string $input): string
    {
        $request = json_decode($input, true);
        
        if (!$request || !isset($request['method'])) {
            return $this->errorResponse('Invalid request format');
        }

        $method = $request['method'];
        $params = $request['params'] ?? [];
        $id = $request['id'] ?? null;

        try {
            switch ($method) {
                case 'initialize':
                    return $this->initialize($id);
                
                case 'tools/list':
                    return $this->listTools($id);
                
                case 'tools/call':
                    return $this->callTool($id, $params);
                
                default:
                    return $this->errorResponse('Unknown method: ' . $method, $id);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $id);
        }
    }

    private function initialize(?string $id): string
    {
        return json_encode([
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => [
                'protocolVersion' => '2024-11-05',
                'capabilities' => [
                    'tools' => []
                ],
                'serverInfo' => [
                    'name' => 'mcp-sinistre',
                    'version' => '1.0.0'
                ]
            ]
        ]);
    }

    private function listTools(?string $id): string
    {
        return json_encode([
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => [
                'tools' => [
                    [
                        'name' => 'creer_dossier_sinistre',
                        'description' => 'Crée un nouveau dossier sinistre avec les informations fournies',
                        'inputSchema' => [
                            'type' => 'object',
                            'properties' => [
                                'assure' => [
                                    'type' => 'string',
                                    'description' => 'Nom de l\'assuré'
                                ],
                                'description' => [
                                    'type' => 'string',
                                    'description' => 'Description du sinistre'
                                ],
                                'montant' => [
                                    'type' => 'number',
                                    'description' => 'Montant du sinistre'
                                ],
                                'numero' => [
                                    'type' => 'string',
                                    'description' => 'Numéro du sinistre (optionnel, généré automatiquement si non fourni)'
                                ],
                                'statut' => [
                                    'type' => 'string',
                                    'description' => 'Statut du sinistre (par défaut: ouvert)',
                                    'enum' => ['ouvert', 'en_cours', 'cloture', 'rejete']
                                ]
                            ],
                            'required' => ['assure', 'description']
                        ]
                    ],
                    [
                        'name' => 'consulter_dossier_sinistre',
                        'description' => 'Consulte un dossier sinistre par son ID ou son numéro',
                        'inputSchema' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => [
                                    'type' => 'string',
                                    'description' => 'ID du sinistre'
                                ],
                                'numero' => [
                                    'type' => 'string',
                                    'description' => 'Numéro du sinistre'
                                ]
                            ],
                            'anyOf' => [
                                ['required' => ['id']],
                                ['required' => ['numero']]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }

    private function callTool(?string $id, array $params): string
    {
        $toolName = $params['name'] ?? null;
        $arguments = $params['arguments'] ?? [];

        if (!$toolName) {
            return $this->errorResponse('Tool name is required', $id);
        }

        switch ($toolName) {
            case 'creer_dossier_sinistre':
                return $this->creerDossierSinistre($id, $arguments);
            
            case 'consulter_dossier_sinistre':
                return $this->consulterDossierSinistre($id, $arguments);
            
            default:
                return $this->errorResponse('Unknown tool: ' . $toolName, $id);
        }
    }

    private function creerDossierSinistre(?string $id, array $arguments): string
    {
        try {
            $sinistre = $this->sinistreService->creerSinistre($arguments);
            
            return json_encode([
                'jsonrpc' => '2.0',
                'id' => $id,
                'result' => [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => json_encode([
                                'success' => true,
                                'message' => 'Dossier sinistre créé avec succès',
                                'sinistre' => $sinistre->toArray()
                            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Erreur lors de la création du dossier: ' . $e->getMessage(), $id);
        }
    }

    private function consulterDossierSinistre(?string $id, array $arguments): string
    {
        try {
            $sinistre = null;
            
            if (isset($arguments['id'])) {
                $sinistre = $this->sinistreService->consulterSinistre($arguments['id']);
            } elseif (isset($arguments['numero'])) {
                $sinistre = $this->sinistreService->consulterSinistreParNumero($arguments['numero']);
            } else {
                return $this->errorResponse('ID ou numéro du sinistre requis', $id);
            }

            if (!$sinistre) {
                return json_encode([
                    'jsonrpc' => '2.0',
                    'id' => $id,
                    'result' => [
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => json_encode([
                                    'success' => false,
                                    'message' => 'Dossier sinistre non trouvé'
                                ], JSON_PRETTY_PRINT)
                            ]
                        ]
                    ]
                ]);
            }

            return json_encode([
                'jsonrpc' => '2.0',
                'id' => $id,
                'result' => [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => json_encode([
                                'success' => true,
                                'sinistre' => $sinistre->toArray()
                            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Erreur lors de la consultation du dossier: ' . $e->getMessage(), $id);
        }
    }

    private function errorResponse(string $message, ?string $id = null): string
    {
        return json_encode([
            'jsonrpc' => '2.0',
            'id' => $id,
            'error' => [
                'code' => -32603,
                'message' => $message
            ]
        ]);
    }
}

