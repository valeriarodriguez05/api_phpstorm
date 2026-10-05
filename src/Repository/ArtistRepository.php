<?php

namespace App\Repository;

use App\Entity\Artist;
use PDO;

class ArtistRepository extends BaseRepository
{
    protected string $table = 'artists';
    protected string $primaryKey = 'idArtist';
    protected array  $columns = ['Name', 'Annee', 'Description', 'Ville'];
    protected ?string $entityClass = Artist::class;  // les méthodes renvoient des objets Artist

    // Méthode spécifique aux artistes (le CRUD est hérité)
    public function findByYear(int $annee): array
    {
        $sth = $this->db->prepare("SELECT * FROM `artists` WHERE Annee = :annee");
        $sth->execute(['annee' => $annee]);
        return array_map([$this, 'hydrate'], $sth->fetchAll(PDO::FETCH_ASSOC));
    }

    // Fonction spécifique : juste la liste des années (sans doublons)
    public function findAllAnnees(): array
    {
        $sth = $this->db->query("SELECT DISTINCT Annee FROM `artists` WHERE Annee IS NOT NULL ORDER BY Annee");
        return $sth->fetchAll(PDO::FETCH_COLUMN);
    }

    // Fonction spécifique : juste la liste des villes (sans doublons)
    public function findAllVilles(): array
    {
        $sth = $this->db->query("SELECT DISTINCT Ville FROM `artists` WHERE Ville IS NOT NULL ORDER BY Ville");
        return $sth->fetchAll(PDO::FETCH_COLUMN);
    }
}