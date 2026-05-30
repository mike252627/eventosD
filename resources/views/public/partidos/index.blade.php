<x-layouts::bootstrap>
    <x-slot:title>Calendario y Resultados - Torneo Deportivo</x-slot:title>

    <div class="row mb-5">
        <div class="col-12 text-center">
            <span class="badge bg-success px-3 py-2 rounded-3 mb-2 text-uppercase fw-semibold tracking-wider">Cronograma del Torneo</span>
            <h1 class="display-5 fw-extrabold text-slate-800">Partidos y Encuentros</h1>
            <p class="text-muted lead max-w-2xl mx-auto">Sigue los partidos programados, los encuentros en vivo y los resultados finales de todas las disciplinas.</p>
        </div>
    </div>

    <!-- Filter Section (Disciplinas) -->
    <div class="card-premium p-4 mb-4">
        <h5 class="fw-bold mb-3 text-slate-700"><i class="bi bi-funnel-fill me-2 text-primary"></i>Filtrar por Disciplina</h5>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('games.index') }}" class="btn btn-sm {{ is_null($selectedDiscipline) ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-3 py-2">
                <i class="bi bi-grid-fill me-1"></i>Todas las Disciplinas
            </a>
            @foreach($disciplines as $disc)
                <a href="{{ route('games.discipline', $disc->id) }}" class="btn btn-sm {{ (!is_null($selectedDiscipline) && $selectedDiscipline->id === $disc->id) ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-3 py-2 d-inline-flex align-items-center">
                    @if($disc->icon_type === 'image' && $disc->image_path)
                        <img src="{{ asset('storage/' . $disc->image_path) }}" alt="" style="width: 18px; height: 18px; object-fit: contain;" class="me-1">
                    @elseif($disc->icon_type === 'icon' && $disc->icon_class)
                        <i class="bi {{ $disc->icon_class }} me-1"></i>
                    @else
                        @if(Str::slug($disc->name) === 'futbol')
                            <i class="bi bi-dribbble me-1"></i>
                        @elseif(Str::slug($disc->name) === 'basquetbol')
                            <i class="bi bi-dribbble me-1"></i>
                        @elseif(Str::slug($disc->name) === 'voleibol')
                            <i class="bi bi-dribbble me-1"></i>
                        @else
                            <i class="bi bi-play-circle-fill me-1"></i>
                        @endif
                    @endif
                    {{ $disc->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Match Schedule Table -->
    <div class="card-premium">
        <div class="card-header-premium d-flex justify-content-between align-items-center" style="background: var(--dark-gradient);">
            <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>
                @if(is_null($selectedDiscipline))
                    Calendario General
                @else
                    Partidos de {{ $selectedDiscipline->name }}
                @endif
            </h5>
            <span class="badge bg-white text-dark rounded-pill">{{ $games->count() }} partidos</span>
        </div>
        <div class="card-body p-4">
            @if($games->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-info-circle display-4 mb-3 d-block"></i>
                    <p class="mb-0 lead">No hay partidos registrados en esta disciplina.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Disciplina</th>
                                <th class="text-center">Encuentro</th>
                                <th class="text-center">Estado</th>
                                <th>Fecha y Hora</th>
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
                                            <div class="d-flex align-items-center justify-content-end gap-2" style="width: 180px;">
                                                <span class="fw-semibold text-end text-truncate" style="max-width: 130px;">{{ $game->homeTeam->name }}</span>
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
                                            <div class="d-flex align-items-center justify-content-start gap-2" style="width: 180px;">
                                                @if($game->awayTeam->logo)
                                                    <img src="{{ asset('storage/' . $game->awayTeam->logo) }}" alt="Logo" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="bg-primary-subtle text-primary rounded-circle border d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: bold;">
                                                        {{ substr($game->awayTeam->name, 0, 2) }}
                                                    </div>
                                                @endif
                                                <span class="fw-semibold text-start text-truncate" style="max-width: 130px;">{{ $game->awayTeam->name }}</span>
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
</x-layouts::bootstrap>
