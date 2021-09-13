<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Bundles;
use App\Models\CustomerBundle;
use App\Exceptions\CartException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class ReorderController extends Controller
{

    use ControllerTrait;

    public function reorder(Request $request)
    {
        
        $customer = $request->user();

        if (!$request->has("order")) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid!",
                "errors" => [
                    "order" => [
                        "Please provide the order identifier!"
                    ]
                ]
            ], 402);

        }

        $order_id = $request->input("order");

        $order = Orders::find($order_id);

        if (is_null($order)) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => [
                    "order" => [
                        "Order not found!"
                    ]
                ]
            ], 404);

        }

        $messages = "Items were successfully added to your cart.";

        $snapshot = unserialize($order->snapshots);
        
        $cart = Cart::find($customer->id);

        // Check products
        $product_ids = array_map(function($product) {
            return $product["id"];
        }, $snapshot["products"]);

        $products = Product::whereIn("id", $product_ids)
                            ->where("published", "1")
                            ->whereNull("deleted_at")
                            ->get(["id", "name", "valucart_price"]);

        foreach ($products as $product) {

            foreach ($snapshot["products"] as $p) {

                try {
                    
                    if ($p["id"] == $product->id && $cart->can_add($product)) {

                        $cart->add($product, $p["quantity"]);
                        
                        if ($product->valucart_price > $p["valucart_price"]) {
                            $messages = "Some prices may have changed since you last order.";
                        }

                    }

                } catch(CartException $e) {

                }

            }

        }


        // Check bundles
        $bundle_ids = array_map(function($bundle) {
            return $this->unhash_id($bundle["id"]);
        }, $snapshot["bundles"]);

        $bundles = Bundles::whereIn("id", $bundle_ids)
                            ->whereNull("deleted_at")
                            ->get(["id", "name", "valucart_price"]);

        foreach ($bundles as $bundle) {

            foreach ($snapshot["bundles"] as $b) {
                
                $snapshot_bundle_id = $this->unhash_id($b["id"]);

                if ($snapshot_bundle_id == $bundle->id) {

                    $bundle_products_ids = $b["products"]->map(function($p) {
                        return $p["id"];
                    });

                    $available_products = Product::whereIn("id", $bundle_products_ids)
                                                ->where("published", "1")
                                                ->whereNull("deleted_at")
                                                ->count("id");

                    if (count($b["products"]) == $available_products) {

                        try {
                        
                            if ($cart->can_add($bundle)) {
        
                                $cart->add($bundle, $b["quantity"], $bundle_products_ids);
                                
                                if ($bundle->valucart_price > $b["valucart_price"]) {
                                    $messages = "Some prices may have changed since you last order.";
                                }
        
                            }
        
                        } catch(CartException $e) {
        
                        }
    
                    }

                    continue;
                }

            }

        }

        // customer bundles
        $customer_bundle_ids = array_map(function($bundle) {
            return $bundle["id"];
        }, $snapshot["customer_bundles"]);

        $bundles = CustomerBundle::whereIn("id", $customer_bundle_ids)
                            ->whereNull("deleted_at")
                            ->get(["id"]);

        foreach ($bundles as $bundle) {

            foreach ($snapshot["customer_bundles"] as $b) {

                try {
                    
                    if ($b["id"] == $bundle->id && $cart->can_add($bundle)) {
                        $cart->add($bundle, $b["quantity"]);
                    }

                } catch(CartException $e) {

                }

            }

        }
        
        return response()->json([
            "status" => 1,
            "message" => $messages
        ], 200);

    }

}
