<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductTag;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::upsert([
            [
                'id' => 1,
                'name' => 'Product 1',
                'state' => 'Weapon', //'Weapon', 'Accessories', 'Other'
                'price' => 2000,
                'is_disable' => 0,
                'product_categories_id' => 8,//must be use valid category id
                'product_types_id' => 3,//must be use valid product types id, which is connected with given category id
                'product_brands_id' => 5,//must be use valid product brand id, which is connected with given category id
            ],
            [
                'id' => 2,
                'name' => 'Product 2',
                'state' => 'Weapon', //'Weapon', 'Accessories', 'Other'
                'price' => 1000,
                'is_disable' => 0,
                'product_categories_id' => 10,//must be use valid category id
                'product_types_id' => 18,//must be use valid product types id, which is connected with given category id
                'product_brands_id' => 65,//must be use valid product brand id, which is connected with given category id
            ],

        ], [], ['id']);

        ProductAttribute::upsert([
            [
                'id' => 1,
                'attributes_id' => 1,
                'products_id' => 1,
                'values' => json_encode([2,3])
            ],
            [
                'id' => 2,
                'attributes_id' => 2,
                'products_id' => 2,
                'values' => json_encode([55])
            ],

        ],[],['id']);

        /*ProductTag::upsert([ //use if needed
            [
                'id' => 1,
                'tags_id' => 2,
                'products_id' => 2
            ]
        ],[],['id']);*/
    }
}
