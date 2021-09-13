<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    
    public function handle(Request $request)
    {

        $results = [];

        $q = $request->query("q");
        $query_in = $request->query("in");

        // Search

        $query = Product::with([ "department" ]);
        
        $query = $query->where("published", "1");

        $query = $query->where(function($query) use ($q) {

            $query->where("name", "like", "%" . $q . "%")
                    ->orWhere('description', 'like', '%' . $q . '%')
                    ->orWhere('meta_page_title', 'like', '%' . $q . '%')
                    ->orWhere('meta_description', 'like', '%' . $q . '%')
                    ->orWhere('meta_keywords', 'like', '%' . $q . '%');

        });
                            
        $products = $query->get(["id", "name", "department_id"]);
        
        $products_departments = $products->map(function($product) {
            return $product->department;
        });
        
        $products_departments = $products_departments->unique();
        
        $results[] = [
            "q" => $q,
            "in" => "$q in all departments",
            "matched" => "$q in all departments",
            "href" => "/products?q={$q}",
            "resource_type" => "products",
            "resource_id" => "",
            "thumbnail" => ""
        ];
        
        foreach($products_departments as $department) {

            if (is_null($department)) {
                continue;
            }

            $hashed_id = Hashids::encode($department->id);

            $results[] = [
                "q" => $q,
                "in" => "$q in {$department->name}",
                "matched" => "$q in {$department->name}",
                "href" => "/products?department={$hashed_id}&q={$q}",
                "resource_type" => "products",
                "resource_id" => $hashed_id,
                "thumbnail" => ""
            ];
        
        }

        foreach ($products as $product) {

            $product_id = Hashids::encode($product->id);

            $results[] = [
                "q" => $q,
                "in" => $product->name,
                "matched" => $product->name,
                "href" => "/products/{$product_id}",
                "resource_type" => "product",
                "resource_id" => $product_id,
                "thumbnail" => $product->thumnail
            ];

        }

        return response()->json([
            "status" => 1,
            "data" => $results
        ], 200);

    }

}
