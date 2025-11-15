<?php

namespace App\Entity;

class Sinistre
{
    private ?string $id = null;
    private ?string $numero = null;
    private ?string $dateCreation = null;
    private ?string $assure = null;
    private ?string $description = null;
    private ?string $statut = null;
    private ?float $montant = null;

    public function __construct()
    {
        $this->id = uniqid('sin_', true);
        $this->dateCreation = date('Y-m-d H:i:s');
        $this->statut = 'ouvert';
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;
        return $this;
    }

    public function getDateCreation(): ?string
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?string $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getAssure(): ?string
    {
        return $this->assure;
    }

    public function setAssure(?string $assure): self
    {
        $this->assure = $assure;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'dateCreation' => $this->dateCreation,
            'assure' => $this->assure,
            'description' => $this->description,
            'statut' => $this->statut,
            'montant' => $this->montant,
        ];
    }

    public static function fromArray(array $data): self
    {
        $sinistre = new self();
        $sinistre->id = $data['id'] ?? null;
        $sinistre->numero = $data['numero'] ?? null;
        $sinistre->dateCreation = $data['dateCreation'] ?? null;
        $sinistre->assure = $data['assure'] ?? null;
        $sinistre->description = $data['description'] ?? null;
        $sinistre->statut = $data['statut'] ?? null;
        $sinistre->montant = $data['montant'] ?? null;
        return $sinistre;
    }
}

