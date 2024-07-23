<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::query();


        /* キーワードから検索処理 */
        // 任意の変数に受け取った送信された情報を代入します
        // htmlのinputタグにはname属性に対して'keyword'と設定されているため
        // $keywordへ$requestの中から、nameが'keyword'のinputを代入します

        $product_name = $request->input('product_name');
        if (!empty($product_name) && $product_name != "") { //もしも、$keywordの中身が空ではない場合に検索処理実行
            $products->where('product_name', 'LIKE', "%{$product_name}%");
        }
        $company_id = $request->input('company_id');
        if (!empty($company_id) && $company_id != null) { //もしも、$keywordの中身が空ではない場合に検索処理実行
            $products->where('company_id', $company_id);
        }

        $values = $products->paginate(10);
        $companies = Company::all();

        return view('products.product', compact('values', 'companies'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'company_id' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',

        ]);

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
    }

    public function show($id)
    {
        // Productと関連するCompanyを取得
        $product = Product::with('company')->findOrFail($id);

        return view('products.show', compact('product'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', '商品が正常に削除されました');
    }

    public function products()
    {
        $products = Product::all();
        // productsテーブルの全てのレコードを取得します
        //$任意の変数名 = モデル名::all();
        // モデル名は命名のルールとして頭文字が大文字になっています

        return view('user.index')->with('products', $products);
    }

    public function edit($id)
    {
        $product = Product::with('company')->findOrFail($id);

        $companies = Company::all();
        $selectedCompanyId = $product->company_id; // ここで選択されている会社IDを取得
        return view('products.edit', compact('companies', 'product', 'selectedCompanyId'));
    }
    public function update(Request $request, $id)
    {
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
        return redirect()->route('products.edit', $product->id)->with('successfull', '商品が正常に更新されました');
    }
}
