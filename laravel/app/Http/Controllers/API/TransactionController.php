<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function createransaction(Request $request, $productUuid)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'numeric|required',
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

        $product = Product::where('uuid',$productUuid)->first();
        if ($product) {
            if ($product->quantity < $request->amount) {
                return response()->json([
                    'statusCode' => 4001,
                    'message' => 'product is not enough'
                ], 400);
            }

            $tax = ($product->price*10)/100;
            $adminFee = (($product->price+$tax)*5)/100;

            $transaction = Transaction::create([
                'uuid' => Str::random(5),
                'user_id' => Auth::user()->id,
                'product_id' => $product->id,
                'amount' => $request->amount,
                'tax' => $tax,
                'admin_fee' => $adminFee,
                'total' => $product->price+$tax+$adminFee,
            ]);

            $product->update([
                'quantity' => $product->quantity-$request->amount
            ]);

            return response()->json([
                'statusCode' => 201,
                'data' => $transaction
            ], 201);
        } else {
            
            return response()->json([
                'statusCode' => 404,
                'message' => 'product not found',
                'data' => null
            ], 404);
        }
        
    }

    public function getAllTransaction(Request $request)
    {
        $limit = $request->limit;
        $sortBy = $request->sort;
        $orderBy = $request->order;
        $orderByOption = $request->order_option;
        $userId = null;

        if (Auth::user()->role == 'User') {
            $userId = Auth::user()->id;
        }

        $allTransaction = Transaction::when($orderBy, function($query) use ($orderBy,$orderByOption)
        {
            $query->orderBy($orderBy, $orderByOption);
        })
        ->when($sortBy, function($query) use ($sortBy)
        {
            $query->sortBy($sortBy);
        })
        ->when($userId, function($query) use ($userId)
        {
            $query->where('user_id', $userId);
        })
        ->when($limit, function($query) use ($limit)
        {
            $query->limit($limit);
        })
        ->leftjoin('product','product.id','transaction.product_id')
        ->select('transaction.*'
        ,'product.name'
        ,'product.price')
        ->get();
        
        if ($allTransaction) {
            return response()->json([
                'statusCode' => 200,
                'data' => $allTransaction
            ], 200);
        } else {
            
            return response()->json([
                'statusCode' => 404,
                'message' => 'product not found',
                'data' => null
            ], 404);
        }
    }

    public function getDetailTransaction($transactionUuid)
    {
        $transaction = Transaction::where('transaction.uuid',$transactionUuid)
        ->leftjoin('product','product.id','transaction.product_id')
        ->select('transaction.*'
        ,'product.name'
        ,'product.price')
        ->first();

        if (Auth::user()->role == 'User') {
            if (Auth::user()->id != $transaction->user_id) {
                return response()->json([
                    'statusCode' => 401,
                    'message' => 'this transaction is not yours',
                ], 401);
            }
        }

        if ($transaction) {
            return response()->json([
                'statusCode' => 200,
                'data' => $transaction
            ], 200);
        } else {
            
            return response()->json([
                'statusCode' => 404,
                'message' => 'transaction not found',
                'data' => null
            ], 404);
        }

        $allTransaction = Transaction::when($orderBy, function($query) use ($orderBy,$orderByOption)
        {
            $query->orderBy($orderBy, $orderByOption);
        })
        ->when($sortBy, function($query) use ($sortBy)
        {
            $query->sortBy($sortBy);
        })
        ->when($userId, function($query) use ($userId)
        {
            $query->where('user_id', $userId);
        })
        ->when($limit, function($query) use ($limit)
        {
            $query->limit($limit);
        })
        ->leftjoin('product','product.id','transaction.product_id')
        ->select('transaction.*'
        ,'product.name'
        ,'product.price')
        ->get();
        
        if ($allTransaction) {
            return response()->json([
                'statusCode' => 200,
                'data' => $allTransaction
            ], 200);
        } else {
            
            return response()->json([
                'statusCode' => 404,
                'message' => 'product not found',
                'data' => null
            ], 404);
        }
    }
}
