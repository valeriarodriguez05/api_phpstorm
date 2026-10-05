<?php

namespace App\Entity;

class Album implements \JsonSerializable      // une ligne de `album` = un objet
{
    public function __construct(
        private ?int $idAlbum = null,
        private string $titre = '',
        private ?int $idArtist = null,
    ) {}

    public static function fromArray(array $row): self      // Ligne SQL → objet
    {
        return new self(
            isset($row['idAlbum']) ? (int) $row['idAlbum'] : null,
            $row['Titre'] ?? '',
            isset($row['idArtist']) ? (int) $row['idArtist'] : null,
        );
    }

    public function toArray(): array                        // objet → tableau
    {
        return ['idAlbum' => $this->idAlbum, 'Titre' => $this->titre,
            'idArtist' => $this->idArtist];
    }

    public function jsonSerialize(): array { return $this->toArray(); }  // pour json_encode()

    public function getId(): ?int { return $this->idAlbum; }
    public function getTitre(): string { return $this->titre; }
    public function getIdArtist(): ?int { return $this->idArtist; }

    public function setTitre(string $titre): void { $this->titre = $titre; }
    public function setIdArtist(?int $id): void { $this->idArtist = $id; }
}