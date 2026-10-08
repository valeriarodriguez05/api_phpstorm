<?php

namespace App\Repository;

use App\Entity\Artist;
use PDO;

class ArtistRepository extends BaseRepository
{
    protected string $table = 'artists';
    protected string $primaryKey = 'idArtist';
    protected array  $columns = ['Name', 'Annee', 'Description', 'Ville'];
    protected ?string $entityClass = Artist::class;

    // Méthode spécifique aux artistes (le CRUD est hérité)
    public function findByYear(int $annee): array
    {
        $sth = $this->db->prepare("SELECT * FROM `artists` WHERE Annee = :annee");
        $sth->execute(['annee' => $annee]);
        return array_map([$this, 'hydrate'], $sth->fetchAll(PDO::FETCH_ASSOC));
    }

    // Liste de toutes les années (sans doublons)
    public function findAllAnnees(): array
    {
        $sth = $this->db->query("SELECT DISTINCT Annee FROM `artists` WHERE Annee IS NOT NULL ORDER BY Annee");
        return $sth->fetchAll(PDO::FETCH_COLUMN);
    }

    // Liste de toutes les villes (sans doublons)
    public function findAllVilles(): array
    {
        $sth = $this->db->query("SELECT DISTINCT Ville FROM `artists` WHERE Ville IS NOT NULL ORDER BY Ville");
        return $sth->fetchAll(PDO::FETCH_COLUMN);
    }

    // La ville d'un artiste précis, selon son id
    public function findVilleById(int $id): ?string
    {
        $sth = $this->db->prepare("SELECT Ville FROM `artists` WHERE idArtist = :id");
        $sth->execute(['id' => $id]);
        $ville = $sth->fetchColumn();
        return $ville !== false ? $ville : null;
    }

    // L'année d'un artiste précis, selon son id
    public function findAnneeById(int $id): ?int
    {
        $sth = $this->db->prepare("SELECT Annee FROM `artists` WHERE idArtist = :id");
        $sth->execute(['id' => $id]);
        $annee = $sth->fetchColumn();
        return $annee !== false ? (int) $annee : null;
    }
}