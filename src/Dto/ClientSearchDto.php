<?php

namespace App\Dto;

class ClientSearchDto
{
    public $surname;
    public $telephone;
    public function __construct() {
        $this->surname = '';
        $this->telephone = '';
    }
}