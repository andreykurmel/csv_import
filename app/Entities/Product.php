<?php


namespace App\Entities;


class Product implements \ArrayAccess, \JsonSerializable
{
    use AttributeObject;
}