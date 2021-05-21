<?php


namespace App\Repositories;


use App\DbProductField;
use App\Entities\ProductField;
use Illuminate\Support\Collection;

class ProductFieldsRepository implements ProductFieldsInterface
{
    /**
     * Return as Entity class instead of Eloquent Model,
     * because in large projects can be difficult to watch over DB interactions.
     * It can be difficult to add logs (or something else) if Product::where()->update() and $product->save() are used in the whole project.
     *
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return DbProductField::getQuery() // don't get as Eloquent Model (save resources)
            ->get($columns)
            ->map(function ($el) {
                return new ProductField((array)$el);
            });
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return DbProductField::where('id', '=', $id)->update([
            'name' => $data['name'],
            'type' => $data['type'] ?? 'string',
        ]);
    }

    /**
     * @param array $data
     * @return ProductField
     */
    public function insert(array $data): ProductField
    {
        $new_object = DbProductField::create([
            'field' => $this->makeDbField($data['name']),
            'name' => $data['name'],
            'type' => $data['type'] ?? 'string',
        ]);
        return new ProductField($new_object->toArray());
    }

    /**
     * @param string $name
     * @return bool|string
     */
    public function makeDbField(string $name): string
    {
        return strtolower( substr( preg_replace('/\W/i', '', $name) ,0, 64) );
    }

}