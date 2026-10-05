<?php

namespace App\Repository;

use App\Entity\Album;
use PDO;

class AlbumRepository extends BaseRepository
{
    protected string  $table = 'album';
    protected string  $primaryKey = 'idAlbum';                  // Exactement comme dans le .sql
    protected array   $columns = ['Titre', 'idArtist'];         // Colonnes exactes
    protected ?string $entityClass = Album::class;

    /**
     * Récupérer tous les albums d'un artiste
     */
    public function findByArtistId(int $artistId): array
    {
        $sth = $this->db->prepare("SELECT * FROM `album` WHERE `idArtist` = :artistId");
        $sth->execute(['artistId' => $artistId]);
        return array_map([$this, 'hydrate'], $sth->fetchAll(PDO::FETCH_ASSOC));
    }
}