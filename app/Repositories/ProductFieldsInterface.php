<?php


namespace App\Repositories;


use App\Entities\ProductField;
use Illuminate\Support\Collection;

interface ProductFieldsInterface
{
    public function all(array $columns = ['*']): Collection;

    public function update(int $id, array $data): bool;

    public function insert(array $data): ProductField;

    public function makeDbField(string $name): string;
}