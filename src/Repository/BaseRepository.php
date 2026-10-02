<?php

namespace App\Repository;

use PDO;

abstract class BaseRepository
{
    protected string $table;              // ex. 'artists'
    protected string $primaryKey;          // ex. 'idArtist'
    protected array  $columns;             // colonnes autorisées (liste blanche)
    protected ?string $entityClass = null; // ex. Artist::class → renvoie des objets

    public function __construct(protected PDO $db) {}  // PDO injecté par PHP-DI

    // Ligne SQL → entité (si $entityClass est défini), sinon on garde le tableau
    protected function hydrate(array $row): array|object
    {
        return $this->entityClass ? $this->entityClass::fromArray($row) : $row;
    }

    public function findAll(): array
    {
        $rows = $this->db->query("SELECT * FROM `{$this->table}`")->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'hydrate'], $rows);
    }

    public function findById(int $id): array|object|null
    {
        $sth = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id");
        $sth->execute(['id' => $id]);
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;   // null si l'élément n'existe pas
    }

    public function insert(array $data): int
    {
        $data = array_intersect_key($data, array_flip($this->columns)); // colonnes autorisées seulement
        if (!$data) throw new \InvalidArgumentException('Aucune colonne valide');
        $cols   = implode('`, `', array_keys($data));          // `Name`, `Annee`
        $params = ':' . implode(', :', array_keys($data));     // :Name, :Annee
        $this->db->prepare("INSERT INTO `{$this->table}` (`$cols`) VALUES ($params)")->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $data = array_intersect_key($data, array_flip($this->columns));
        if (!$data) throw new \InvalidArgumentException('Aucune colonne valide');
        $set = implode(', ', array_map(fn ($c) => "`$c` = :$c", array_keys($data)));
        $sql = "UPDATE `{$this->table}` SET $set WHERE `{$this->primaryKey}` = :id";
        return $this->db->prepare($sql)->execute($data + ['id' => $id]);
    }

    public function delete(int $id): bool
    {
        $sth = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id");
        $sth->execute(['id' => $id]);
        return $sth->rowCount() > 0;     // false si rien n'a été supprimé
    }
}