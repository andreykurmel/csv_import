<?php


namespace App\Repositories;


class RepositoryFactory
{
    /**
     * We can use Laravel Service Provider and then make interfaces via 'app()'.
     * But this way provides IDE autocomplete.
     *
     * @return ProductFieldsInterface
     */
    public static function ProductFields()
    {
        return new ProductFieldsRepository();
    }

    /**
     * @return ProductRepository
     */
    public static function Products()
    {
        return new ProductRepository();
    }
}