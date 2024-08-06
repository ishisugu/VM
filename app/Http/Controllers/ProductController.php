<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // ソート条件を取得
        $sort_by = $request->input('sort_by', 'id');
        $sort_order = $request->input('sort_order', 'desc');

        // 検索条件とソート条件を適用
        $products = Product::query();

        // ... (既存の検索条件の適用)

        $products->orderBy($sort_by, $sort_order);

        $values = $products->paginate(10);
        $companies = Company::all();

        if ($request->ajax()) {
            return view('products.table', compact('values'))->render();
        }

        return view('products.product', compact('values', 'companies', 'sort_by', 'sort_order'));
    }


    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = $image->getClientOriginalName();
                $imagePath = 'storage/images/' . $fileName;
                $image->storeAs('public/images', $fileName);
            }

            Product::create([
                'product_name' => $request->product_name,
                'company_id' => (int) $request->company_id,
                'stock' => (int) $request->stock,
                'price' => (int) $request->price,
                'comment' => $request->comment,
                'img_path' => $imagePath
            ]);

            return redirect()->route('products.index')->with('success', '商品が正常に作成されました');
        } catch (\Exception $e) {
            return redirect()->route('products.create')->withErrors(['error' => '商品作成に失敗しました: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with('company')->findOrFail($id);
        $companies = Company::all();
        $selectedCompanyId = $product->company_id;

        return view('products.edit', compact('companies', 'product', 'selectedCompanyId'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::find($id);
            $product->company_id = $request->company_id;
            $product->product_name = $request->input('product_name');
            $product->price = $request->input('price');
            $product->stock = $request->input('stock');
            $product->comment = $request->input('comment');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = $image->getClientOriginalName();
                $imagePath = 'storage/images/' . $fileName;
                $image->storeAs('public/images', $fileName);
                $product->img_path = $imagePath;
            }

            $product->save();

            return redirect()->route('products.edit', $product->id)->with('success', '商品が正常に更新されました');
        } catch (\Exception $e) {
            return redirect()->route('products.edit', $product->id)->withErrors(['error' => '商品更新に失敗しました: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json(['message' => '商品が正常に削除されました'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => '商品削除に失敗しました: ' . $e->getMessage()], 500);
        }
    }
}
