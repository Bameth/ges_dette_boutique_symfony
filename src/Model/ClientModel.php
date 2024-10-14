<?php

namespace App\Model;

use App\Model\Model;

define("OFFSET", 2);

class ClientModel extends Model
{
    public function __construct()
    {
        $this->ouvrirConnexion();
        $this->table = "client";
    }

    public function findAllWithPaginate(int $page = 0, int $offset = OFFSET): array
{
    $pageOffset = $page * $offset; // Multiplie le numéro de page par la taille de la page
    $result = $this->executeSelect("SELECT COUNT(*) as nbreclient FROM client", [], true); // Gardez 'nbreclient' ici

    // Vérifiez si la requête a réussi et contient une valeur
    $totalClients = $result ? (int)$result['nbreclient'] : 0; // Modifiez ici

    // Utilisez LEFT JOIN pour inclure les clients sans utilisateur
    $data = $this->executeSelect("
        SELECT c.id, c.surname, c.telephone, c.adresse, c.create_at, c.update_at, c.user_id, u.prenom, u.nom 
        FROM $this->table c
        LEFT JOIN \"user\" u ON c.user_id = u.id
        LIMIT $offset OFFSET $pageOffset
    ");

    return [
        "totalElements" => $totalClients,
        "data" => $data,
        "pages" => ceil($totalClients / $offset)
    ];
    
}
}
