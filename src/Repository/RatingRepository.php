<?php

namespace App\Repository;

use App\Entity\Rating;
use PDO;

class RatingRepository extends BaseRepository
{
    protected string  $table = 'ratings';
    protected string  $primaryKey = 'idRating';
    protected array   $columns = ['stars', 'idArtist'];
    protected ?string $entityClass = Rating::class;

    /**
     * Récupérer toutes les notes d'un artiste précis
     */
    public function findByArtistId(int $artistId): array
    {
        $sth = $this->db->prepare("SELECT * FROM `ratings` WHERE `idArtist` = :artistId");
        $sth->execute(['artistId' => $artistId]);
        return array_map([$this, 'hydrate'], $sth->fetchAll(PDO::FETCH_ASSOC));
    }
}