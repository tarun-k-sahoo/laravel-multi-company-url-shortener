<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'URL Shortener')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }

        .sidebar {
            min-height: calc(100vh - 56px);
            background: #212529;
        }

        .sidebar a {
            display: block;
            padding: 12px 15px;
            color: #ddd;
            text-decoration: none;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background: #343a40;
            color: white;
        }

        .main-content {
            padding: 25px;
        }
    </style>

    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            URL Shortener
        </a>

        @auth
            <div class="d-flex align-items-center gap-3">
                <span class="text-white">
                    {{ auth()->user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">
                        Logout
                    </button>
                </form>
            </div>
        @endauth
    </nav>

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-2 sidebar p-3">

                <a href="{{ route('dashboard') }}">Dashboard</a>

                @if (auth()->user()->hasPermission('company.view'))
                    <a href="{{ route('companies.index') }}">Companies</a>
                @endif
                @if (auth()->user()->hasPermission('user.view'))
                    <a href="{{ route('invitations.index') }}" class="nav-link">
                        Invitations
                    </a>
                @endif
                @if (auth()->check() && auth()->user()->hasPermission('short_url.view'))
                    <a href="{{ route('short-urls.index') }}" class="nav-link">
                        Short URLs
                    </a>
                @endif

            </div>

            <div class="col-md-10 main-content">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>

</html>
