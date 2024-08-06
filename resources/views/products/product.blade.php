<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf" content="{{ csrf_token() }}">
    <title>商品一覧画面</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom_p.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.default.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <header class="p-3 mb-2">
        <div class="container">
            <h1 class="text-3xl font-bold">商品一覧画面</h1>
        </div>
    </header>

    <div class="container">
        <div class="search-form">
            <form id="searchForm">
                @csrf
                <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', 'id') }}">
                <input type="hidden" name="sort_order" id="sort_order" value="{{ request('sort_order', 'desc') }}">
                <div class="form-row">
                    <div class="form-group col-md-6 mb-2">
                        <label for="product_name">検索キーワード</label>
                        <input type="text" name="product_name" id="product_name" placeholder="検索キーワード" value="{{ request('product_name') }}" class="form-control">
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        <label for="company_id">メーカー名</label>
                        <select name="company_id" id="company_id" class="form-control">
                            <option value="">メーカー名</option>
                            @foreach($companies as $company)
                            <option value="{{$company->id}}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{$company->company_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3 mb-2">
                        <label for="price_max">価格上限</label>
                        <input type="number" name="price_max" id="price_max" placeholder="価格上限" value="{{ request('price_max') }}" class="form-control">
                    </div>
                    <div class="form-group col-md-3 mb-2">
                        <label for="price_min">価格下限</label>
                        <input type="number" name="price_min" id="price_min" placeholder="価格下限" value="{{ request('price_min') }}" class="form-control">
                    </div>
                    <div class="form-group col-md-3 mb-2">
                        <label for="stock_max">在庫数上限</label>
                        <input type="number" name="stock_max" id="stock_max" placeholder="在庫数上限" value="{{ request('stock_max') }}" class="form-control">
                    </div>
                    <div class="form-group col-md-3 mb-2">
                        <label for="stock_min">在庫数下限</label>
                        <input type="number" name="stock_min" id="stock_min" placeholder="在庫数下限" value="{{ request('stock_min') }}" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary mb-2">検索</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row" id="productTable">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered tablesorter">
                        <thead>
                            <tr>
                                <th data-sorter="true" class="sortable" data-column="id">ID</th>
                                <th data-sorter="false">商品画像</th>
                                <th data-sorter="true">商品名</th>
                                <th data-sorter="true">価格</th>
                                <th data-sorter="true">在庫数</th>
                                <th data-sorter="true">メーカー名</th>
                                <th class="text-center" data-sorter="false">
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
                                    <form action="{{ route('products.destroy', $value->id) }}" method="POST" class="delete-form" data-id="{{ $value->id }}" style="display:inline">
                                        @csrf
                                        <button type="button" class="btn btn-danger delete-button">削除</button>
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
            {{$values->appends(request()->except('page'))->links()}}
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function applyTableSorter() {
                $(".tablesorter").tablesorter({
                    headers: {
                        1: {
                            sorter: false
                        },
                        6: {
                            sorter: false
                        }
                    },
                    sortList: [
                        [0, 1]
                    ]
                });

                $('.sortable').on('click', function() {
                    var column = $(this).data('column');
                    var currentOrder = $('#sort_order').val();
                    var newOrder = (currentOrder === 'asc') ? 'desc' : 'asc';

                    $('#sort_by').val(column);
                    $('#sort_order').val(newOrder);

                    $('#searchForm').submit();
                });
            }

            applyTableSorter();

            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('products.index') }}",
                    method: 'GET',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#productTable').html($(response).find('#productTable').html());
                        applyTableSorter();
                        updatePaginationLinks();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            function updatePaginationLinks() {
                $('ul.pagination a').on('click', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    var page = url.split('page=')[1];
                    var formData = $('#searchForm').serialize() + '&page=' + page;

                    $.ajax({
                        url: "{{ route('products.index') }}",
                        method: 'GET',
                        data: formData,
                        success: function(response) {
                            $('#productTable').html($(response).find('#productTable').html());
                            applyTableSorter();
                            updatePaginationLinks();
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                });
            }

            updatePaginationLinks();

            // 非同期削除処理
            $('.delete-button').on('click', function() {
                if (confirm('本当に削除しますか？')) {
                    var form = $(this).closest('.delete-form');
                    var productId = form.data('id');

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            form.closest('tr').remove();
                            alert('商品が正常に削除されました');
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            alert('商品削除に失敗しました');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>