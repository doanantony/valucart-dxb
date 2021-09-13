<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FeaturedProductsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $featured_products = [];

        foreach ($this->collection as $featured_product) {
            array_push($featured_products, $featured_product->product);
        }

        return $featured_products;
    }
}
