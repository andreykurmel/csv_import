<?php


namespace App\Repositories;


use App\Entities\Product;
use App\Models\DbProduct;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ProductRepository implements ProductsInterface
{
    /**
     * @param array $rows
     * @return bool
     */
    public function insertMass(array $rows): bool
    {
        if (!$rows) {
            return false;
        }

        foreach ($rows as &$row) {
            $row['created_at'] = Carbon::now();
            $row['updated_at'] = Carbon::now();
        }

        return DbProduct::insert($rows);
    }

    /**
     * @param array $columns
     * @param int|null $limit
     * @return Collection
     */
    public function all(array $columns = ['*'], int $limit = null): Collection
    {
        $sql = DbProduct::getQuery();
        if ($limit) {
            $sql->limit($limit);
        }
        return $sql->get($columns)
            ->map(function ($el) {
                return new Product((array)$el);
            });
    }

    /**
     * @param int $id
     * @return Product
     */
    public function one(int $id): Product
    {
        $el = DbProduct::getQuery()->where('id', '=', $id)->first();
        return new Product((array)$el);
    }


}