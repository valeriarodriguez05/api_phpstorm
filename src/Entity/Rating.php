<?php

namespace App\Entity;

class Rating implements \JsonSerializable      // une ligne de `ratings` = un objet
{
    public function __construct(
        private ?int $idRatings = null,
        private ?int $grade = null,
        private ?int $albumsIdAlbums = null,
    ) {}

    public static function fromArray(array $row): self      // Ligne SQL → objet
    {
        return new self(
            isset($row['idRatings']) ? (int) $row['idRatings'] : null,
            isset($row['Grade']) ? (int) $row['Grade'] : null,
            isset($row['Albums_idAlbums']) ? (int) $row['Albums_idAlbums'] : null,
        );
    }

    public function toArray(): array                        // objet → tableau
    {
        return ['idRatings' => $this->idRatings, 'Grade' => $this->grade,
            'Albums_idAlbums' => $this->albumsIdAlbums];
    }

    public function jsonSerialize(): array { return $this->toArray(); }  // pour json_encode()

    public function getId(): ?int { return $this->idRatings; }
    public function getGrade(): ?int { return $this->grade; }
    public function getAlbumsIdAlbums(): ?int { return $this->albumsIdAlbums; }

    public function setGrade(?int $grade): void { $this->grade = $grade; }
    public function setAlbumsIdAlbums(?int $id): void { $this->albumsIdAlbums = $id; }
}