<?php
namespace App\Http\Controllers\v1;
use Throwable;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Models\Brand;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use App\Models\Community;
use App\Models\MetricUnit;
use App\Models\Subcategory;
use App\Models\Departments;
use App\Models\ProductType;
use App\Models\ProductImage;
use App\Models\ProductsPackagingType;
use App\Models\Coupon;
ini_set('max_execution_time', 0);
class ProductsUploadController extends Controller
{

    public function bulkupload(Request $request){
        if ($request->hasFile('upload') && $request->file('upload')->isValid()){
            if($_FILES['upload']['name']){
                $filename = explode(".", $_FILES['upload']['name']);
                if(end($filename) == "csv"){
                    $handle = fopen($_FILES['upload']['tmp_name'], "r");
                    $count = 0;
                    while($data = fgetcsv($handle)){
                        
                        $name = $data[1];
                        $description = $data[2];
                        $sku = $data[3];
                        $department = '3';
                        $category = $data[5];
                        $subcategory = $data[6];
                        $brand = $data[7];
                        $packaging_quantity = $data[8];
                        $packaging_quantity_unit = $data[9];
                        $msp = $data[10];
                        $rsp = $data[11];
                        $valucart_price = $data[12];
                        $community = 'All';
                        $published = 1;
                        $cost_price = $rsp * 0.95;


                        $product_exists = Product::where("sku", $sku)->first();
                      //  if (is_null($product_exists)) {

                            $brand = Brand::where('name', $brand)->first();
                            $category = Category::where('name', $category)->first();
                            $subcategory = Subcategory::where('name', $subcategory)->first();
                            $packaging_quantity_unit_id = MetricUnit::where('name', $packaging_quantity_unit)->first();
                            $community = Community::where('name', $community)->first();
                            $unit = MetricUnit::where('name', $packaging_quantity_unit)->first();

                            $name_extract = substr($name, 0, 100); //first 5 chars "Hello"
                            $description_extract = substr($description, 0, 500); //first 5 chars "Hello"

                            $product = Product::create([
                                'sku' => $sku,
                                'department_id' => 2,
                                'category_id' => $category->id,
                                'subcategory_id' => $subcategory->id,
                                'brand_id' => $brand->id,
                                'name' => $name_extract,
                                'description' => $description_extract,
                                'packaging_quantity' => $packaging_quantity,
                                'packaging_quantity_unit_id' => $unit->id,
                                'packaging_type' => 1,
                                'maximum_selling_price' => $msp,
                                'published' => $published,
                                'valucart_price' => $valucart_price,
                                'is_admin_bundlable' => '1',
                                'admin_bundle_discount' => '0',
                                'is_customer_bundlable' => '0',
                                'customer_bundle_discount' => '0',
                                'minimum_inventory' => '10',
                                'is_bulk' => '0',
                                'is_featured' => '1',
                                'is_offer' => '0',
                                //'minimum_inventory' => 0,

                            ]);

                            DB::table('products_communities')->insert(
                                  ['product_id' => $product->id, 'community_id' => $community->id]
                             );

                           //   DB::table('products_images')->insert(
                           //      ['product_id' => $product->id, 'path' => 'test', 'is_thumb' => 1]
                           // );


                             DB::table('products_vendors')->insert(
                                  ['product_id' => $product->id,
                                   'vendor_id' => 2,
                                   'price' => $cost_price,
                                   'inventory' => 10
                                  ]
                             );


                            $count++;
                     //   }

                        
                    }
                    return response()->json([
                        'status' => 1,
                        'uploaded_products' => $count,
                    ], 200);
                }else{
                    return response()->json([
                        'status' => 0,
                        'message' => 'Something Wrong'
                    ], 404);
                }
            }else{
                return response()->json([
                    'status' => 0,
                    'message' => 'Something Wrong'
                ], 404);
            }
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Something Wrong'
            ], 404);
        }
    }
}