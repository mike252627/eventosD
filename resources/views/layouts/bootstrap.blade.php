<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Eventos Deportivos' }} - {{ config('app.name', 'Laravel') }}</title>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            --secondary-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --dark-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            --card-hover-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        .navbar-custom {
            background: var(--dark-gradient);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand-custom {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(to right, #60a5fa, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link-custom {
            color: #cbd5e1 !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem !important;
            border-radius: 0.375rem;
        }

        .nav-link-custom:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .btn-premium {
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            color: white;
        }

        .btn-premium-outline {
            background: transparent;
            color: #cbd5e1;
            border: 2px solid #475569;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-premium-outline:hover {
            background-color: #334155;
            color: white;
            border-color: #334155;
        }

        .card-premium {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
            background: white;
            display: flex;
            flex-direction: column;
            height: auto;
        }

        .card-premium:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover-shadow);
        }

        .card-header-premium {
            background: var(--primary-gradient);
            color: white;
            padding: 1.25rem;
            font-weight: 600;
            border-bottom: none;
        }

        .badge-discipline {
            background-color: #e0e7ff;
            color: #4f46e5;
            font-weight: 600;
            padding: 0.35em 0.65em;
            border-radius: 0.375rem;
        }

        .badge-status-completed {
            background-color: #d1fae5;
            color: #065f46;
            font-weight: 600;
        }

        .badge-status-pending {
            background-color: #fef3c7;
            color: #92400e;
            font-weight: 600;
        }

        .badge-status-playing {
            background-color: #dbeafe;
            color: #1e40af;
            font-weight: 600;
        }

        .footer {
            margin-top: auto;
            background-color: #0f172a;
            color: #94a3b8;
            padding: 2rem 0;
            border-top: 1px solid #1e293b;
        }

        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-3px);
        }

        @livewireStyles
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom" href="{{ route('home') }}">
                <i class="bi bi-trophy-fill me-2 text-warning"></i>INTERTEC
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('teams.*') ? 'active' : '' }}" href="{{ route('teams.index') }}">
                            <i class="bi bi-shield me-1"></i>Equipos
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle {{ request()->routeIs('games.*') ? 'active' : '' }}" href="#" id="navbarDropdownPartidos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-calendar-event me-1"></i>Partidos
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark border-0 shadow-lg" aria-labelledby="navbarDropdownPartidos">
                            <li><a class="dropdown-item" href="{{ route('games.index') }}"><i class="bi bi-calendar2-week me-2"></i>Todos los Partidos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach(\App\Models\Discipline::orderBy('name')->get() as $disc)
                                <li>
                                    <a class="dropdown-item" href="{{ route('games.discipline', $disc->id) }}">
                                        <i class="bi bi-play-circle me-2"></i>{{ $disc->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-premium">
                            <i class="bi bi-speedometer2 me-1"></i>Panel de Control
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-premium-outline">
                                <i class="bi bi-box-arrow-right me-1"></i>Salir
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-premium">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <footer class="footer">
        <div class="container text-center">
            <p class="mb-1">&copy; {{ date('Y') }} EventosDeportivos. Todos los derechos reservados.</p>
            <small class="text-muted">Diseñado con <i class="bi bi-heart-fill text-danger"></i> para la gestión deportiva de alto nivel.</small>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    @livewireScripts
</body>
</html>
