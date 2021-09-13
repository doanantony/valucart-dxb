<?php

namespace App\Http\Controllers\v1;


use Hashids;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Product;
use App\Models\Subcategory;
use App\Rules\Hashexists;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class CreateProductController extends Controller
{

    use ControllerTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            $this->get_rules($request),
            $this->get_messages()
        );

        $validator->after(function($validator) use ($request) {

            $category = $this->unhash_id($request->input('category'));
            $subcategory = $this->unhash_id($request->input('subcategory'));

            if (Subcategory::where('id', $subcategory)->where('category_id', $category)->doesntExist()) {
                $validator->errors()->add('subcategory', 'The subcategory/Category conbination is invalid.');
            }

        });

        $validator->validate();

        $product = Product::create([
            'name' => $request->input('name'),
            'sku' => $request->input('sku'),
            'category_id' => $this->unhash_id($request->input('category')),
            'subcategory_id' => $this->unhash_id($request->input('subcategory')),
            'brand_id' => $this->unhash_id($request->input('brand')),
            'description' => $request->input('description'),
            'packaging_quantity' => $request->input('packaging_quantity'),
            'packaging_quantity_unit_id' => $this->unhash_id($request->input('packaging_quantity_unit')),
            'maximum_selling_price' => $request->input('maximum_selling_price'),
            'valucart_price' => $request->input('valucart_price'),
            'is_admin_bundlable' => $request->input('admin_bundlable'),
            'admin_bundle_discount' => $request->input('admin_bundle_discount'),
            'customer_bundle_discount' => $request->input('customer_bundle_discount'),
            'is_customer_bundlable' => $request->input('customer_bundlable'),
            'minimum_inventory' => $request->input('minimum_inventory')
        ]);
        
        return response()->json($product, 201);

    }

    protected function get_rules($request)
    {

        return [

            'name' => [
                'required',
                'string',
                'min:2',
                'max:128',
            ],

            'sku' => [
                'required',
                'string',
                'unique:products,sku',
            ],

            'category' => [
                'required',
                'string',
                new Hashexists('categories', 'id'),
            ],

            'subcategory' => [
                'required',
                'string',
                new Hashexists('subcategories', 'id'),
            ],

            'brand' => [
                'required',
                'string',
                new Hashexists('brands', 'id'),
            ],

            'description' => [
                'required',
                'string',
                'min:3',
                'max: 512',
            ],

            'packaging_quantity' => [
                'required',
                'numeric',
            ],

            'packaging_quantity_unit' => [
                'required',
                'string',
                new Hashexists('matric_units', 'id'),
            ],

            'maximum_selling_price' => [
                'required',
                'numeric',
            ],

            'valucart_price' => [
                'required',
                'numeric',
            ],

            'admin_bundlable' => [
                'nullable',
                'boolean'
            ],

            'admin_bundle_discount' => [
                'nullable',
                Rule::requiredIf(function () use ($request) {
                    $admin_bundlable = $request->input('admin_bundlable');

                    if (in_array($admin_bundlable, [1, '1', true, 'yes'])) {
                        return true;
                    }

                    return false;
                    
                }),
                'numeric',
            ],

            'customer_bundlable' => [
                'nullable',
                'boolean'
            ],

            'customer_bundle_discount' => [
                'nullable',
                Rule::requiredIf(function () use ($request) {
                    $customer_bundlable = $request->input('customer_bundlable');

                    if (in_array($customer_bundlable, [1, '1', true, 'yes'])) {
                        return true;
                    }

                    return false;

                }),
                'numeric',
            ],

            'minimum_inventory' => [
                'nullable',
                'integer'
            ],

        ];

    }

    protected function get_messages()
    {

        return [
            'name.required' => 'Please provide the name of the product.',
            'name.string' => 'The name must a string.',
            'name.min' => 'The name must be at least 3 characters long',
            'name.max' => 'The name should not be longer than 128 characters.',
            'sku.required' => 'The product barcode in required.',
            'sku.string' => 'The product barcode should be a string.',
            'sku.unique' => 'The product barcode ":input" already exists.',
            'category.required' => 'Please select a category for the product.',
            'category.string' => 'Catgeory seems to be invalid',
            'subcategory.required' => 'Please select a subcategory for the product.',
            'subcategory.string' => 'Subcatgeory seems to be invalid.',
            'brand.required' => 'Please select a brand for the product.',
            'brand.string' => 'Brand seems to be invalid.',
            'description.required' => 'Please provide a description of the product.',
            'description.string' => 'The product description should be a string.',
            'description.min' => 'Description should be at least 3 characters long.',
            'description.max' => 'Description should not be longer than 512 characters.',
            'packaging_quantity.required' => 'Packaging quantity is required.',
            'packaging_quantity.numeric' => 'Packaging quantity must be a number.',
            'packaging_quantity_unit.required' => 'Please a unit of measure for the packaging quantity.',
            'packaging_quantity_unit.string' => 'The aackaging quantity seem to be invalid',
            'maximum_selling_price.required' => 'Maximum selling price is required.',
            'maximum_selling_price.numeric' => 'Maximum selling price must a number.',
            'valucart_price.required' => 'Valucart price is required.',
            'valucart_price.numeric' => 'Valucart price must a number.',
            'admin_bundlable.boolean' => ':attribute must be boolean.',
            'admin_bundle_discount.numeric' => ':attribute must be a number.',
            'customer_bundlable.boolean' => ':attribute must be boolean.',
            'customer_bundle_discount.numeric' => ':attribute must be a number.',
            'minimum_inventory.integer' => 'Minimum inventory must an integer.',
        ];

    }

}
