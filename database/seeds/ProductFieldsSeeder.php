<?php

use Illuminate\Database\Seeder;
use App\DbProductField;

class ProductFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DbProductField::where('field', '=', 'brand')->count()) {
            DbProductField::create([
                'field' => 'brand',
                'name' => 'Brand',
                'type' => 'string',
            ]);
        }
        if (!DbProductField::where('field', '=', 'variant')->count()) {
            DbProductField::create([
                'field' => 'variant',
                'name' => 'Variant',
                'type' => 'string',
            ]);
        }
        if (!DbProductField::where('field', '=', 'name')->count()) {
            DbProductField::create([
                'field' => 'name',
                'name' => 'Name',
                'type' => 'string',
            ]);
        }
        if (!DbProductField::where('field', '=', 'price')->count()) {
            DbProductField::create([
                'field' => 'price',
                'name' => 'Price',
                'type' => 'float',
            ]);
        }
        if (!DbProductField::where('field', '=', 'url')->count()) {
            DbProductField::create([
                'field' => 'url',
                'name' => 'URL',
                'type' => 'text',
            ]);
        }
        if (!DbProductField::where('field', '=', 'description')->count()) {
            DbProductField::create([
                'field' => 'description',
                'name' => 'Description',
                'type' => 'text',
            ]);
        }
    }
}
