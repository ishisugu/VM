<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/custom_e.css') }}" rel="stylesheet"> <!-- 外部CSSファイルを読み込み -->
    <title>商品情報編集画面</title>
</head>

<body>
    <div class="container">
        <h2>商品情報編集画面</h2>

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

        <form action="{{ route('products.update', ['id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="product_name"><strong>商品名</strong></label>
                <input type="text" id="product_name" name="product_name" class="form-control" placeholder="商品名" value="{{ $product->product_name }}">
            </div>
            <div class="form-group">
                <label for="company_id"><strong>メーカー名</strong></label>
                <select id="company_id" name="company_id" class="form-select">
                    <option>メーカー名</option>
                    @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $company->id == $selectedCompanyId ? 'selected' : '' }}>{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="price"><strong>価格</strong></label>
                <input type="text" id="price" name="price" class="form-control" placeholder="価格" value="{{ $product->price }}">
            </div>
            <div class="form-group">
                <label for="stock"><strong>在庫数</strong></label>
                <input type="text" id="stock" name="stock" class="form-control" placeholder="在庫数" value="{{ $product->stock }}">
            </div>
            <div class="form-group">
                <label for="comment"><strong>コメント</strong></label>
                <input type="text" id="comment" name="comment" class="form-control" placeholder="コメント" value="{{ $product->comment }}">
            </div>
            <div class="form-group">
                <label for="img_path"><strong>商品画像</strong></label>
                <div class="img-container">
                    <img src="{{ asset($product->img_path) }}" alt="商品画像" class="img-fluid img-thumbnail" style="height:100px;">
                </div>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="button-group">
                <input class="btn btn-info" type="submit" value="更新">
                <a class="btn btn-edit-success" href="{{ route('products.show', $product->id) }}">戻る</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>