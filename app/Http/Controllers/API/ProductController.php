<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function all(Request $request)
    {

        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $description= $request->input('description');
        $tags= $request->input('tags');
        $categories= $request->input('categories');

        $price_from= $request->input('price_form');
        $price_to= $request->input('price_to');

        if($id)
        {
            $product = Product::with(['category','galleries'])->find($id);

            if($product){
                return ResponseFormatter::success(
                    $product,
                    'Data Produk Berhasil Diambil'
                );
            }
            else{
                return ResponseFormatter::error(
                    null,
                    'Data Produk Tidak Ada',
                    404
                );
            }
        }

        $product = product::with(['category', 'galleries']);

        if($name){
            $product->where('name', 'Like', '%' . $name . '%');
        }

        if($description){
            $product->where('description', 'Like', '%' . $description . '%');
        }

        if($tags){
            $product->where('tags', 'Like', '%' . $tags . '%');
        }

        if($price_from){
            $product->where('price', '>=', $price_from);
        }

        if($price_to){
            $product->where('price', '<=', $price_to);
        }

        if($categories){
            $product->where('categories', $categories);
        }

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data Produk Berhasil Diambil'
        );

    }//public function all(request $request)
}
