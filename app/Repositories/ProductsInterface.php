<?php


namespace App\Repositories;


use App\Entities\Product;
use Illuminate\Support\Collection;

interface ProductsInterface
{
    public function insertMass(array $rows): bool;

    public function all(array $columns = ['*'], int $limit = null): Collection;

    public function one(int $id): Product;
}