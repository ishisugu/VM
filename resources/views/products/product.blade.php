<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧画面</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom_p.css') }}" rel="stylesheet"> <!-- 外部CSSファイルを読み込み -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="p-3 mb-2">
        <div class="container">
            <h1 class="text-3xl font-bold">商品一覧画面</h1>
        </div>
    </header>

    <div class="container">
        <div class="search-form">
            <form method="GET" action="{{ route('products.index') }}" class="form-inline">
                @csrf
                <input type="text" name="product_name" placeholder="検索キーワード" value="{{ request('product_name') }}" class="form-control mr-2">
                <select name="company_id" class="form-control mr-2">
                    <option value="">メーカー名</option>
                    @foreach($companies as $company)
                    <option value="{{$company->id}}">{{$company->company_name}}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">検索</button>
            </form>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>商品画像</th>
                                <th>商品名</th>
                                <th>価格</th>
                                <th>在庫数</th>
                                <th>メーカー名</th>
                                <th class="text-right">
                                    <a class="btn btn-success" href="{{ route('products.create') }}">新規登録</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($values as $value)
                            <tr>
                                <td>{{ $value->id }}</td>
                                <td>
                                    @if ($value->img_path)
                                    <div class="img-container">
                                        <img src="{{ asset($value->img_path) }}" alt="商品画像" class="img-fluid img-thumbnail">
                                    </div>
                                    @else
                                    <p>画像なし</p>
                                    @endif
                                </td>
                                <td>{{ $value->product_name }}</td>
                                <td>¥{{ number_format($value->price) }}</td>
                                <td>{{ $value->stock }}</td>
                                <td>{{ $value->company->company_name }}</td>
                                <td class="text-center">
                                    <a class="btn btn-info" href="{{ route('products.show', $value->id) }}">詳細</a>
                                    <form action="{{ route('products.destroy', $value->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">削除</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="pagination">
            {{$values->links()}}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>