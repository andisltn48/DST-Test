<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

use App\Models\Product;

class ProductController extends Controller
{
    public function storeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|required|max:255',
            'type' => 'string|required|max:255',
            'price' => 'string|required|max:255',
            'quantity' => 'string|required|max:255',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $key => $value) {
                $errors[] = $value;
            }

            return response()->json([
               'statusCode' => 400,
               'message' => 'Bad request',
               'data' => $errors 
            ], 400);
        }

        $product = Product::create([
            'uuid' => Str::random(5),
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        if ($product) {
            return response()->json([
                'statusCode' => 201,
                'message' => 'create product successfully',
                'data' => $product
            ], 201);
        }
    }

    public function getAllProduct(Request $request)
    {
        $limit = $request->limit;
        $sortBy = $request->sort;
        $orderBy = $request->order;
        $orderByOption = $request->order_option;

        $allProduct = Product::when($orderBy, function($query) use ($orderBy,$orderByOption)
        {
            $query->orderBy($orderBy, $orderByOption);
        })
        ->when($sortBy, function($query) use ($sortBy)
        {
            $query->sortBy($sortBy);
        })
        ->when($limit, function($query) use ($limit)
        {
            $query->limit($limit);
        })
        ->get();
        
        if ($allProduct) {
            return response()->json([
                'statusCode' => 200,
                'data' => $allProduct
            ], 200);
        } else {
            
            return response()->json([
                'statusCode' => 404,
                'message' => 'product not found',
                'data' => null
            ], 404);
        }
    }

    public function productDetail($productUuid)
    {
        $product = Product::where('uuid',$productUuid)->first();

        if ($product) {
            return response()->json([
                'statusCode' => 200,
                'data' => $product
            ], 200);
        } else {
            
            return response()->json([
                'statusCode' => 404,
                'message' => 'product not found',
                'data' => null
            ], 404);
        }
    }

    public function updateProduct(Request $request, $productUuid)
    {
        $product = Product::where('uuid',$productUuid)->first();

        if ($product) {
            $product->fill($request->all())->save();

            return response()->json([
                'statusCode' => 201,
                'data' => $product
            ], 201);
        } else {
            
            return response()->json([
                'statusCode' => 404,
                'message' => 'product not found',
                'data' => null
            ], 404);
        }
    }

    public function deleteProduct($productUuid)
    {
        $product = Product::where('uuid',$productUuid)->first();

        if ($product) {
            $product->delete();

            return response()->json([
                'statusCode' => 201,
                'message' => 'delete product successfully'
            ], 201);
        } else {
            
            return response()->json([
                'statusCode' => 404,
                'message' => 'product not found',
                'data' => null
            ], 404);
        }
    }
}
