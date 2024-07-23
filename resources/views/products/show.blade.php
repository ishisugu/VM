<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/custom_s.css') }}" rel="stylesheet">
    <title>商品詳細画面</title>
</head>

<body>
    <div class="container">
        <h2>商品詳細画面</h2>
        <table class="details-table">
            <tr>
                <th>ID</th>
                <td>{{ $product->id }}</td>
            </tr>
            <tr>
                <th>商品名</th>
                <td>{{ $product->product_name }}</td>
            </tr>
            <tr>
                <th>メーカー</th>
                <td>{{ $product->company->company_name }}</td>
            </tr>
            <tr>
                <th>価格</th>
                <td>{{ $product->price }}</td>
            </tr>
            <tr>
                <th>在庫数</th>
                <td>{{ $product->stock }}</td>
            </tr>
            <tr>
                <th>コメント</th>
                <td>{{ $product->comment }}</td>
            </tr>
            <tr>
                <th>商品画像</th>
                <td>
                    @if ($product->img_path)
                    <div class="img-container">
                        <img src="{{ asset($product->img_path) }}" alt="商品画像">
                    </div>
                    @else
                    <p>画像はありません</p>
                    @endif
                </td>
            </tr>
        </table>
        <div class="button-group">
            <a class="btn btn-info" href="{{ route('products.edit', $product->id) }}">編集</a>
            <a class="btn btn-show-success" href="{{ route('products.index') }}">戻る</a>
        </div>
    </div>
</body>

</html>