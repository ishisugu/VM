<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered tablesorter" style="width: 100%;">
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
                            <img src="{{ asset($value->img_path) }}" alt="商品画像" class="img-fixed">
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