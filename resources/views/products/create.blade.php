<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/custom_c.css') }}" rel="stylesheet">
    <title>商品新規登録画面</title>
</head>

<body>
    <div class="container">
        <h2>商品新規登録画面</h2>

        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>おっと!</strong> 入力にいくつかの問題があります。<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="product_name">商品名</label>
                <input type="text" name="product_name" class="form-control" placeholder="商品名">
            </div>

            <div class="form-group">
                <label for="company_id">メーカー名</label>
                <select name="company_id" class="form-select">
                    <option>メーカー名</option>
                    @foreach($companies as $company)
                    <option value="{{$company->id}}">{{$company->company_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">価格</label>
                <input type="text" name="price" class="form-control" placeholder="価格">
            </div>

            <div class="form-group">
                <label for="stock">在庫数</label>
                <input type="text" name="stock" class="form-control" placeholder="在庫数">
            </div>

            <div class="form-group">
                <label for="comment">コメント</label>
                <input type="text" name="comment" class="form-control" placeholder="コメント">
            </div>

            <div class="form-group">
                <label for="image">商品画像</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-info">新規登録</button>
                <a class="btn btn-show-success" href="{{ route('products.index') }}">戻る</a>
            </div>
        </form>
    </div>
</body>

</html>