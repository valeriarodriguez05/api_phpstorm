<?php

namespace App\Entity;

class Rating implements \JsonSerializable      // une ligne de `ratings` = un objet
{
    public function __construct(
        private ?int $idRating = null,
        private ?int $stars = null,
        private ?int $idArtist = null,
    ) {}

    public static function fromArray(array $row): self      // Ligne SQL → objet
    {
        return new self(
            isset($row['idRating']) ? (int) $row['idRating'] : null,
            isset($row['stars']) ? (int) $row['stars'] : null,
            isset($row['idArtist']) ? (int) $row['idArtist'] : null,
        );
    }

    public function toArray(): array                        // objet → tableau
    {
        return ['idRating' => $this->idRating, 'stars' => $this->stars,
            'idArtist' => $this->idArtist];
    }

    public function jsonSerialize(): array { return $this->toArray(); }  // pour json_encode()

    public function getId(): ?int { return $this->idRating; }
    public function getStars(): ?int { return $this->stars; }
    public function getIdArtist(): ?int { return $this->idArtist; }

    public function setStars(?int $stars): void { $this->stars = $stars; }
    public function setIdArtist(?int $id): void { $this->idArtist = $id; }
}