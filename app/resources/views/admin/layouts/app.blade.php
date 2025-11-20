<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>管理者ページ - @yield('title')</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    {{-- Optional: Admin用CSS --}}
    <style>
        body { background: #f7f7f7; }
        header { background: #343a40; color: white; padding: 15px; }
        header a { color: white; text-decoration: none; }
    </style>
</head>
<body>

    {{-- 管理者ヘッダー --}}
    <header class="d-flex justify-content-between align-items-center">
        <h2 class="m-0">管理者画面</h2>

        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button class="btn btn-danger btn-sm">ログアウト</button>
        </form>
    </header>

    <div class="container mt-4">
        {{-- メインコンテンツ --}}
        @yield('content')
    </div>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>