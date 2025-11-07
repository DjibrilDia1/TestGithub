<?php

namespace App;

use App\Database;
use Exception;

class Produit
{
    private ?int $id;
    private string $nom;
    private float $prix;
    private int $quantite;

    public function __construct(string $nom, float $prix, int $quantite = 0, ?int $id = null)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prix = $prix;
        $this->quantite = $quantite;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function getPrix(): ?float
    {
        return $this->prix;
    }
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }
    public function ajouterStock(int $quantite)
    {
        if ($quantite < 0) throw new Exception("Quantite invalide");
        $this->quantite += $quantite;
    }
    public function retirerStock(int $quantite)
    {
        if ($quantite > $this->quantite) throw new Exception("Stock insuffisant");
        $this->quantite -= $quantite;
    }
    public function save()
    {
        $pdo = Database::getTestConnection();
        if ($this->id) {
            $tmt = $pdo->prepare("UPDATE produits SET nom =?, prix=?, quantite=? WHERE id=?");
            $tmt->execute([$this->nom, $this->prix, $this->quantite, $this->id]);
        } else {
            $tmt = $pdo->prepare("INSERT INTO produits (nom,prix,quantite) VALUES (?,?,?)");
            $tmt->execute([$this->nom, $this->prix, $this->quantite]);
            $this->id = (int)$pdo->lastInsertId();
        }
    }

    public static function all()
    {
        $pdo = Database::getTestConnection();
        $tmt = $pdo->query("SELECT * FROM produits");
        $result = [];
        while ($row = $tmt->fetch()) {
            $result[] = new self(
                $row['nom'],
                (float)$row['prix'],
                (int)$row['quantite'],
                (int)$row['id']
            );
        }
        return $result;
    }

    public static function find(int $id)
    {
        $pdo = Database::getTestConnection();
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return new self($row['nom'], (float)$row['prix'], (int)$row['quantite'], (int)$row['id']);
    }

    public static function delete(int $id)
    {
        $pdo = Database::getTestConnection();
        $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
        $stmt->execute([$id]);
    }
}
