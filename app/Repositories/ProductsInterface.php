<?php


namespace App\Repositories;


interface ProductsInterface
{
    public function insertMass(array $rows): bool;
}