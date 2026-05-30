<x-layouts::bootstrap>
    <x-slot:title>Inicio - Gestión de Eventos Deportivos</x-slot:title>

    <!-- Hero Section -->
    <div class="p-5 mb-5 rounded-4 text-white shadow-sm" style="background: var(--dark-gradient);">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-primary px-3 py-2 rounded-3 mb-3 text-uppercase fw-semibold tracking-wider">INTERTEC Maestros 2026</span>
                <h1 class="display-4 fw-extrabold mb-3">Portal de Eventos Deportivos</h1>
                <p class="lead text-slate-300 mb-0">Resultados en tiempo real, equipos participantes e inscripciones a disciplinas del torneo actual.</p>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <i class="bi bi-trophy text-warning" style="font-size: 8rem; filter: drop-shadow(0 10px 20px rgba(245, 158, 11, 0.3)); animate: pulse 2s infinite;"></i>
            </div>
        </div>
    </div>

    <!-- Disciplinas Activas (Deportes) -->
    <div class="card-premium p-4 mb-5">
        <h5 class="fw-bold mb-3 text-slate-800"><i class="bi bi-collection-play-fill me-2 text-primary"></i>Disciplinas Deportivas Activas</h5>
        <div class="row g-3">
            @foreach($disciplines as $discipline)
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-3 border rounded-3 bg-light text-center hover-lift h-100 d-flex flex-column justify-content-center">
                        <div class="fs-4 text-primary mb-2">
                            @if(Str::slug($discipline->name) === 'futbol')
                                <i class="bi bi-dribbble text-success"></i>
                            @elseif(Str::slug($discipline->name) === 'basquetbol')
                                <i class="bi bi-dribbble text-warning"></i>
                            @elseif(Str::slug($discipline->name) === 'voleibol')
                                <i class="bi bi-dribbble text-info"></i>
                            @elseif(Str::slug($discipline->name) === 'beisbol')
                                <i class="bi bi-dribbble text-danger"></i>
                            @elseif(Str::slug($discipline->name) === 'ajedrez')
                                <i class="bi bi-grid-3x3-gap-fill text-secondary"></i>
                            @elseif(Str::slug($discipline->name) === 'atletismo')
                                <i class="bi bi-lightning-charge-fill text-warning"></i>
                            @else
                                <i class="bi bi-trophy-fill text-primary"></i>
                            @endif
                        </div>
                        <h6 class="fw-bold mb-0 text-slate-700 small">{{ $discipline->name }}</h6>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row g-4">
        <!-- Partidos y Resultados -->
        <div class="col-lg-7">
            <div class="card-premium h-100">
                <div class="card-header-premium d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Calendario y Resultados</h5>
                    <span class="badge bg-white text-dark rounded-pill">{{ $games->count() }} partidos</span>
                </div>
                <div class="card-body p-4" style="flex: 1 1 auto;">
                    @if($games->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-info-circle display-4 mb-3 d-block"></i>
                            <p class="mb-0">No hay partidos programados aún.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Disciplina</th>
                                        <th class="text-center">Encuentro</th>
                                        <th class="text-center">Estado</th>
                                        <th>Fecha</th>
                                        <th>Árbitro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($games as $game)
                                        <tr>
                                            <td>
                                                <span class="badge-discipline">
                                                    {{ $game->discipline->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center gap-3">
                                                    <!-- Home Team -->
                                                    <div class="d-flex align-items-center justify-content-end gap-2" style="width: 160px;">
                                                        <span class="fw-semibold text-end text-truncate" style="max-width: 110px;">{{ $game->homeTeam->name }}</span>
                                                        @if($game->homeTeam->logo)
                                                            <img src="{{ asset('storage/' . $game->homeTeam->logo) }}" alt="Logo" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-primary-subtle text-primary rounded-circle border d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: bold;">
                                                                {{ substr($game->homeTeam->name, 0, 2) }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Score / VS -->
                                                    <span class="fs-5 px-3 py-1 rounded bg-slate-100 border text-center fw-bold" style="min-width: 60px;">
                                                        @if($game->status === 'completed')
                                                            {{ $game->home_team_score }} - {{ $game->away_team_score }}
                                                        @else
                                                            v - s
                                                        @endif
                                                    </span>

                                                    <!-- Away Team -->
                                                    <div class="d-flex align-items-center justify-content-start gap-2" style="width: 160px;">
                                                        @if($game->awayTeam->logo)
                                                            <img src="{{ asset('storage/' . $game->awayTeam->logo) }}" alt="Logo" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-primary-subtle text-primary rounded-circle border d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: bold;">
                                                                {{ substr($game->awayTeam->name, 0, 2) }}
                                                            </div>
                                                        @endif
                                                        <span class="fw-semibold text-start text-truncate" style="max-width: 110px;">{{ $game->awayTeam->name }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($game->status === 'completed')
                                                    <span class="badge badge-status-completed px-3 py-2 rounded-pill">Finalizado</span>
                                                @elseif($game->status === 'in_progress')
                                                    <span class="badge badge-status-playing px-3 py-2 rounded-pill"><span class="spinner-grow spinner-grow-sm text-primary me-1" role="status"></span>En Vivo</span>
                                                @else
                                                    <span class="badge badge-status-pending px-3 py-2 rounded-pill">Pendiente</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap text-muted small">
                                                {{ $game->match_date ? \Carbon\Carbon::parse($game->match_date)->format('d/m/Y H:i') : 'Sin fecha' }}
                                            </td>
                                            <td class="small text-muted">
                                                <i class="bi bi-person-badge me-1"></i>
                                                {{ $game->referee ? $game->referee->name : 'No asignado' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Equipos y Participantes -->
        <div class="col-lg-5">
            <div class="card-premium h-100">
                <div class="card-header-premium d-flex justify-content-between align-items-center" style="background: var(--secondary-gradient);">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Equipos Participantes</h5>
                    <span class="badge bg-white text-dark rounded-pill">{{ $teams->count() }} equipos</span>
                </div>
                <div class="card-body p-4" style="overflow-y: auto; flex: 1 1 auto;">
                                                    @if($teams->isEmpty())
                                                        <div class="text-center py-5 text-muted">
                                                            <i class="bi bi-shield-slash display-4 mb-3 d-block"></i>
                                                            <p class="mb-0">No hay equipos registrados aún.</p>
                                                        </div>
                                                    @else
                                                        <ul class="list-group list-group-flush">
                                                            @foreach($teams as $team)
                                                                <li class="list-group-item d-flex align-items-center py-3 border-bottom border-light">
                                                                    @if($team->logo)
                                                                        <img src="{{ asset('storage/' . $team->logo) }}" alt="Logo {{ $team->name }}" class="rounded-circle me-3 border" style="width: 45px; height: 45px; object-fit: cover;">
                                                                    @else
                                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; font-weight: bold; font-family: 'Outfit';">
                                                                            {{ substr($team->name, 0, 2) }}
                                                                        </div>
                                                                    @endif
                                                                    <div>
                                                                        <span class="fw-bold text-slate-800 d-block fs-5">{{ $team->name }}</span>
                                                                    </div>
                                                                    <a href="{{ route('teams.show', $team->id) }}" class="btn btn-sm btn-outline-primary ms-auto rounded-pill px-3">
                                                                        Ver Detalles
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
            </div>
        </div>
    </div>
</x-layouts::bootstrap>
