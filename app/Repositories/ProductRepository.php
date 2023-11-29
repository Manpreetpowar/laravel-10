<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for inventories
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use Log;

class ProductRepository {

    /** Product models */
    protected $product;

    /** Product Variant models */
    protected $productVariant;

    public function __construct(Product $product, ProductVariant $productVariant){
        // set valirables
        $this->product = $product;
        $this->productVariant = $productVariant;
    }


    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($id=null){

        //new object
        $query = $this->product->Query();

        //filters
        if($id){
            $query->where('id',$id);
        }

        $query->with(['variants']);

        //get
        return $query;
    }

    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get_variants($product_id=null){

        //new object
        $query = $this->productVariant->Query();

        //filters

        if($product_id){
            $query->where('product_id',$product_id);
        }

        $query->with(['product']);

        //get
        return $query;
    }

    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get_variant($variant_id){

        //new object
        $query = $this->productVariant->Query();

        //filters

        if($variant_id){
            $query->where('id',$variant_id);
        }

        $query->with(['product']);

        //get
        return $query;
    }

    /**
     * create
     * @param string $type the type of the category
     * @return object
     */
    public function create()
    {
        //new object
        $product = new $this->product;
        $product->name = request('name');
        $product->sku_code = request('sku_code');
        $product->category_id = request('category_id');
        $product->color_code = request('color_code');
        $product->price = request()['price']['standard'];
        $product->is_color_matching = request('is_color_matching');

        if($product->save()){
            if($product){
                foreach(request()['price'] as $key => $pricing){
                    $productVariant = new ProductVariant();
                    $productVariant->product_id = $product->id;
                    $productVariant->product_sku_code = request('sku_code').($key ? '-'.$key : '');
                    $productVariant->product_option_type = $key ? $key : 'standard';
                    $productVariant->option_price = $pricing;
                    $productVariant->save();
                }
            }
            return $product;
        }else{
            dd('log');
        }

    }

    /**
     * update
     * @param string $type the type of the category
     * @return object
     */
    public function update($id)
    {
        //new object
        $data = request()->only(['name', 'sku_code', 'category_id', 'color_code','is_color_matching']);

        if(isset(request()['price']['standard'])){
            $data[''] = request()['price']['standard'];
        }
        $product = $this->product->find($id);

        $product->fill($data);
        
        if ($product->save()) {
            
            if(isset(request()['price'])){
                foreach (request()['price'] as $key => $pricing) {
                    $productVariant = ProductVariant::where('product_id', $product->id)
                        ->where('product_option_type', $key)
                        ->first();

                    if ($productVariant) {
                        $productVariant->product_sku_code = $product->sku_code.($key ? '-'.$key : '');
                        $productVariant->option_price = $pricing;
                        $productVariant->save();
                    } else {
                        $newProductVariant = new ProductVariant();
                        $newProductVariant->product_id = $product->id;
                        $newProductVariant->product_sku_code = request('sku_code').($key ? '-'.$key : '');
                        $newProductVariant->product_option_type = $key ? $key : 'standard';
                        $newProductVariant->option_price = $pricing;
                        $newProductVariant->save();
                    }
                }
            }

            return $product;
        } else {
            dd('log');
        }
    }
}
