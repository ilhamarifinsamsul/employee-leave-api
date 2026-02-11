<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employee Leave')</title>

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                Employee Leave
            </a>

            <div class="d-flex gap-2">
                <a href="{{ route('leave.index') }}" class="btn btn-outline-light btn-sm">
                    List Cuti
                </a>
                <a href="{{ route('leave.create') }}" class="btn btn-warning btn-sm">
                    Ajukan Cuti
                </a>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="container py-4">
        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
