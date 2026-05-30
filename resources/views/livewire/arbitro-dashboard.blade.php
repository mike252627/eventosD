<div>
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <span class="badge bg-primary px-3 py-2 rounded-3 mb-2 text-uppercase fw-semibold">
                <i class="bi bi-person-badge-fill me-1"></i>Rol: Árbitro
            </span>
            <h2 class="h1 mb-1 text-slate-800">Panel de Resultados y Arbitraje</h2>
            <p class="text-muted mb-0">
                Visualiza y registra los marcadores de los partidos asignados a tu disciplina: 
                <strong class="text-primary">{{ $disciplineName }}</strong>.
            </p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left me-1"></i>Ver Portal Público</a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('status'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Matches Table -->
    <div class="card-premium">
        <div class="card-header-premium d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-whistle-fill me-2 text-warning"></i>Lista de Partidos Asignados</h5>
            <span class="badge bg-white text-dark rounded-pill">{{ $games->count() }} Partidos</span>
        </div>
        <div class="card-body p-4">
            @if($games->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x display-4 mb-3 d-block"></i>
                    <p class="mb-0">No hay partidos asignados para esta disciplina.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                @if(auth()->user()->hasRole('admin'))
                                    <th>Disciplina</th>
                                @endif
                                <th class="text-center" style="width: 45%;">Encuentro / Marcador</th>
                                <th class="text-center">Estado</th>
                                <th>Fecha</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($games as $game)
                                <tr class="{{ $selected_game_id === $game->id ? 'table-warning-subtle' : '' }}">
                                    @if(auth()->user()->hasRole('admin'))
                                        <td>
                                            <span class="badge-discipline">{{ $game->discipline->name }}</span>
                                        </td>
                                    @endif
                                    <td>
                                        @if($selected_game_id === $game->id)
                                            <!-- Edit Form Mode -->
                                            <form wire:submit.prevent="updateGame">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <span class="fw-bold text-end" style="width: 120px;">{{ $game->homeTeam->name }}</span>
                                                    
                                                    <input type="number" wire:model="home_score" class="form-control form-control-sm text-center fw-bold rounded-2 @error('home_score') is-invalid @enderror" style="width: 60px;" min="0" max="100">
                                                    
                                                    <span class="px-1 text-muted">-</span>
                                                    
                                                    <input type="number" wire:model="away_score" class="form-control form-control-sm text-center fw-bold rounded-2 @error('away_score') is-invalid @enderror" style="width: 60px;" min="0" max="100">
                                                    
                                                    <span class="fw-bold text-start" style="width: 120px;">{{ $game->awayTeam->name }}</span>
                                                </div>
                                                @error('home_score')
                                                    <div class="text-danger text-center small mt-1">{{ $message }}</div>
                                                @enderror
                                                @error('away_score')
                                                    <div class="text-danger text-center small mt-1">{{ $message }}</div>
                                                @enderror
                                            </form>
                                        @else
                                            <!-- View Mode -->
                                            <div class="d-flex align-items-center justify-content-center gap-3">
                                                <span class="fw-semibold text-end" style="width: 120px;">{{ $game->homeTeam->name }}</span>
                                                <span class="fs-5 px-3 py-1 rounded bg-slate-100 border text-center fw-bold" style="min-width: 60px;">
                                                    @if($game->status === 'completed')
                                                        {{ $game->home_team_score }} - {{ $game->away_team_score }}
                                                    @else
                                                        {{ $game->home_team_score }} - {{ $game->away_team_score }}
                                                    @endif
                                                </span>
                                                <span class="fw-semibold text-start" style="width: 120px;">{{ $game->awayTeam->name }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($selected_game_id === $game->id)
                                            <!-- Edit Status Mode -->
                                            <select wire:model="game_status" class="form-select form-select-sm rounded-2 mx-auto @error('game_status') is-invalid @enderror" style="width: 130px;">
                                                <option value="pending">Pendiente</option>
                                                <option value="in_progress">En Vivo</option>
                                                <option value="completed">Finalizado</option>
                                            </select>
                                            @error('game_status')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        @else
                                            <!-- View Status Mode -->
                                            @if($game->status === 'completed')
                                                <span class="badge badge-status-completed px-3 py-2 rounded-pill">Finalizado</span>
                                            @elseif($game->status === 'in_progress')
                                                <span class="badge badge-status-playing px-3 py-2 rounded-pill"><span class="spinner-grow spinner-grow-sm text-primary me-1" role="status"></span>En Vivo</span>
                                            @else
                                                <span class="badge badge-status-pending px-3 py-2 rounded-pill">Pendiente</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-nowrap text-muted small">
                                        {{ $game->match_date ? \Carbon\Carbon::parse($game->match_date)->format('d/m/Y H:i') : 'Sin fecha' }}
                                    </td>
                                    <td class="text-end">
                                        @if($selected_game_id === $game->id)
                                            <!-- Edit Actions Mode -->
                                            <div class="d-flex justify-content-end gap-1">
                                                <button type="button" wire:click="updateGame" class="btn btn-success btn-sm rounded-2 px-3">
                                                    <i class="bi bi-check-lg"></i> Guardar
                                                </button>
                                                <button type="button" wire:click="cancelEdit" class="btn btn-outline-secondary btn-sm rounded-2 px-3">
                                                    Cancelar
                                                </button>
                                            </div>
                                        @else
                                            <!-- View Actions Mode -->
                                            <button type="button" wire:click="editGame({{ $game->id }})" class="btn btn-premium btn-sm rounded-2 px-3">
                                                <i class="bi bi-pencil-square me-1"></i> Registrar Marcador
                                            </button>
                                        @endif
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
