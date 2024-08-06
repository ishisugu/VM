<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function token()
    {
        return csrf_token();
    }

    public function purchase(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // トランザクションを開始
        DB::beginTransaction();

        try {
            $product = Product::find($productId);

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            if ($product->stock < $quantity) {
                return response()->json(['error' => 'Insufficient stock'], 400);
            }

            // ① salesテーブルにレコードを追加する
            Sales::create([
                'product_id' => $productId,
            ]);

            // ② productsテーブルの在庫数を減算する
            $product->stock -= $quantity;
            $product->save();

            // トランザクションをコミット
            DB::commit();

            return response()->json(['success' => 'Purchase completed'], 200);
        } catch (\Exception $e) {
            // トランザクションをロールバック
            DB::rollBack();
            return response()->json(['error' => 'Purchase failed', 'message' => $e->getMessage()], 500);
        }
    }
}
