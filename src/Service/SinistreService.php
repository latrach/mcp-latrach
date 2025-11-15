<?php

namespace App\Service;

use App\Entity\Sinistre;
use Symfony\Component\Filesystem\Filesystem;

class SinistreService
{
    private string $dataDir;
    private Filesystem $filesystem;

    public function __construct(string $dataDir)
    {
        $this->dataDir = $dataDir;
        $this->filesystem = new Filesystem();
        
        // Créer le répertoire de données s'il n'existe pas
        if (!$this->filesystem->exists($this->dataDir)) {
            $this->filesystem->mkdir($this->dataDir);
        }
    }

    public function creerSinistre(array $donnees): Sinistre
    {
        $sinistre = new Sinistre();
        
        if (isset($donnees['numero'])) {
            $sinistre->setNumero($donnees['numero']);
        } else {
            // Générer un numéro automatique
            $sinistre->setNumero('SIN-' . date('Y') . '-' . str_pad($this->getProchainNumero(), 6, '0', STR_PAD_LEFT));
        }
        
        $sinistre->setAssure($donnees['assure'] ?? null);
        $sinistre->setDescription($donnees['description'] ?? null);
        $sinistre->setMontant($donnees['montant'] ?? null);
        $sinistre->setStatut($donnees['statut'] ?? 'ouvert');

        // Sauvegarder le sinistre
        $this->sauvegarderSinistre($sinistre);

        return $sinistre;
    }

    public function consulterSinistre(string $id): ?Sinistre
    {
        $filePath = $this->getFilePath($id);
        
        if (!$this->filesystem->exists($filePath)) {
            return null;
        }

        $data = json_decode(file_get_contents($filePath), true);
        return Sinistre::fromArray($data);
    }

    public function consulterSinistreParNumero(string $numero): ?Sinistre
    {
        $sinistres = $this->listerTousLesSinistres();
        
        foreach ($sinistres as $sinistre) {
            if ($sinistre->getNumero() === $numero) {
                return $sinistre;
            }
        }

        return null;
    }

    public function listerTousLesSinistres(): array
    {
        $sinistres = [];
        
        if (!$this->filesystem->exists($this->dataDir)) {
            return $sinistres;
        }

        $files = glob($this->dataDir . '/sinistre_*.json');
        
        foreach ($files as $file) {
            $data = json_decode(file_get_contents($file), true);
            if ($data) {
                $sinistres[] = Sinistre::fromArray($data);
            }
        }

        return $sinistres;
    }

    private function sauvegarderSinistre(Sinistre $sinistre): void
    {
        $filePath = $this->getFilePath($sinistre->getId());
        $data = $sinistre->toArray();
        file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function getFilePath(string $id): string
    {
        return $this->dataDir . '/sinistre_' . $id . '.json';
    }

    private function getProchainNumero(): int
    {
        $sinistres = $this->listerTousLesSinistres();
        $maxNumero = 0;
        
        foreach ($sinistres as $sinistre) {
            if ($sinistre->getNumero() && preg_match('/SIN-\d{4}-(\d+)/', $sinistre->getNumero(), $matches)) {
                $numero = (int)$matches[1];
                if ($numero > $maxNumero) {
                    $maxNumero = $numero;
                }
            }
        }
        
        return $maxNumero + 1;
    }
}

