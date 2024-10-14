<?php

namespace App\Model;

class ClientSearch
{
    public ?string $telephone = null;
    public ?string $surname = null;
    public bool $compte = false; // Par défaut, false

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    public function getCompte(): bool
    {
        return $this->compte;
    }

    public function setCompte(bool $compte): void
    {
        $this->compte = $compte;
    }
}
