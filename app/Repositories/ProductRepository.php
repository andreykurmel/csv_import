<?php


namespace App\Repositories;


use App\DbProduct;
use Carbon\Carbon;

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
}