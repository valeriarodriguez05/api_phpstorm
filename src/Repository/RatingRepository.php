<?php

namespace App\Repository;

use App\Entity\Rating;
use PDO;

class RatingRepository extends BaseRepository
{
    protected string  $table = 'ratings';
    protected string  $primaryKey = 'idRatings';                         // Exactement comme dans le .sql
    protected array   $columns = ['Grade', 'Albums_idAlbums'];          // Colonnes exactes
    protected ?string $entityClass = Rating::class;

    /**
     * Récupérer toutes les notes d'un album précis
     */
    public function findByAlbumId(int $albumId): array
    {
        $sth = $this->db->prepare("SELECT * FROM `ratings` WHERE `Albums_idAlbums` = :albumId");
        $sth->execute(['albumId' => $albumId]);
        return array_map([$this, 'hydrate'], $sth->fetchAll(PDO::FETCH_ASSOC));
    }
}