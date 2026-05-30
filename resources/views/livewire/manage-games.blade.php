<div>
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <span class="badge bg-danger px-3 py-2 rounded-3 mb-2 text-uppercase fw-semibold"><i class="bi bi-shield-fill-check me-1"></i>Módulo de Administración de Partidos</span>
            <h2 class="h3 mb-1 text-slate-800">Programación de Partidos y Encuentros</h2>
            <p class="text-muted mb-0">Registra nuevos encuentros deportivos, define marcadores iniciales, asigna árbitros y mantén actualizado el estado del torneo.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            @if(!$is_creating && !$is_editing)
                <button wire:click="startCreate" class="btn btn-premium px-4 py-2">
                    <i class="bi bi-plus-circle me-2"></i>Programar Nuevo Partido
                </button>
            @endif
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

    <!-- Form Section -->
    @if($is_creating || $is_editing)
        <div class="card-premium mb-4">
            <div class="card-header-premium" style="background: var(--dark-gradient);">
                <h5 class="mb-0"><i class="bi {{ $is_creating ? 'bi-plus-circle' : 'bi-pencil-square' }} me-2 text-info"></i>
                    {{ $is_creating ? 'Programar Nuevo Encuentro' : 'Modificar Detalles del Encuentro' }}
                </h5>
            </div>
            <div class="card-body p-4">
                <form wire:submit.prevent="{{ $is_creating ? 'createGame' : 'updateGame' }}">
                    <div class="row g-3">
                        <!-- Disciplina -->
                        <div class="col-md-6 col-lg-4">
                            <label for="discipline_id" class="form-label fw-semibold">Disciplina Deportiva</label>
                            <select id="discipline_id" wire:model="discipline_id" class="form-select rounded-3 @error('discipline_id') is-invalid @enderror">
                                <option value="">-- Seleccionar Disciplina --</option>
                                @foreach($disciplines as $disc)
                                    <option value="{{ $disc->id }}">{{ $disc->name }}</option>
                                @endforeach
                            </select>
                            @error('discipline_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fecha y Hora -->
                        <div class="col-md-6 col-lg-4">
                            <label for="match_date" class="form-label fw-semibold">Fecha y Hora del Partido</label>
                            <input type="datetime-local" id="match_date" wire:model="match_date" class="form-control rounded-3 @error('match_date') is-invalid @enderror">
                            @error('match_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Árbitro -->
                        <div class="col-md-6 col-lg-4">
                            <label for="referee_id" class="form-label fw-semibold">Árbitro Asignado (Opcional)</label>
                            <select id="referee_id" wire:model="referee_id" class="form-select rounded-3 @error('referee_id') is-invalid @enderror">
                                <option value="">-- Sin Árbitro Asignado --</option>
                                @foreach($referees as $ref)
                                    <option value="{{ $ref->id }}">{{ $ref->name }} ({{ $ref->discipline ? $ref->discipline->name : 'General' }})</option>
                                @endforeach
                            </select>
                            @error('referee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Equipo Local -->
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <label for="home_team_id" class="form-label fw-semibold text-primary"><i class="bi bi-house-door-fill me-1"></i>Equipo Local</label>
                                <select id="home_team_id" wire:model="home_team_id" class="form-select rounded-3 @error('home_team_id') is-invalid @enderror">
                                    <option value="">-- Seleccionar Equipo Local --</option>
                                    @foreach($teams as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                                @error('home_team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="mt-3">
                                    <label for="home_team_score" class="form-label fw-semibold">Marcador Local</label>
                                    <input type="number" id="home_team_score" wire:model="home_team_score" class="form-control rounded-3 @error('home_team_score') is-invalid @enderror" min="0">
                                    @error('home_team_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Equipo Visitante -->
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <label for="away_team_id" class="form-label fw-semibold text-danger"><i class="bi bi-airplane-fill me-1"></i>Equipo Visitante</label>
                                <select id="away_team_id" wire:model="away_team_id" class="form-select rounded-3 @error('away_team_id') is-invalid @enderror">
                                    <option value="">-- Seleccionar Equipo Visitante --</option>
                                    @foreach($teams as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                                @error('away_team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="mt-3">
                                    <label for="away_team_score" class="form-label fw-semibold">Marcador Visitante</label>
                                    <input type="number" id="away_team_score" wire:model="away_team_score" class="form-control rounded-3 @error('away_team_score') is-invalid @enderror" min="0">
                                    @error('away_team_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Estado del Encuentro -->
                        <div class="col-md-6 col-lg-4 mx-auto">
                            <label for="status" class="form-label fw-semibold">Estado del Partido</label>
                            <select id="status" wire:model="status" class="form-select rounded-3 @error('status') is-invalid @enderror">
                                <option value="pending">Pendiente</option>
                                <option value="in_progress">En Curso (En Vivo)</option>
                                <option value="completed">Finalizado</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" wire:click="cancel" class="btn btn-outline-secondary rounded-3 px-4">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-premium px-4">
                            <i class="bi bi-save me-1"></i>{{ $is_creating ? 'Registrar Partido' : 'Actualizar Partido' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Matches Table -->
    <div class="card-premium">
        <div class="card-header-premium d-flex justify-content-between align-items-center" style="background: var(--dark-gradient);">
            <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Historial General de Partidos</h5>
            <span class="badge bg-white text-dark rounded-pill">{{ $games->count() }} partidos programados</span>
        </div>
        <div class="card-body p-0">
            @if($games->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x display-3 d-block mb-3"></i>
                    <p class="mb-0">No se han registrado partidos en el sistema.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Disciplina</th>
                                <th class="text-center">Encuentro</th>
                                <th class="text-center">Estado</th>
                                <th>Fecha y Hora</th>
                                <th>Árbitro</th>
                                <th class="pe-4 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($games as $game)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge-discipline">
                                            {{ $game->discipline->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                            <!-- Local -->
                                            <div class="d-flex align-items-center justify-content-end gap-2" style="width: 150px;">
                                                <span class="fw-bold small text-slate-800 text-truncate" style="max-width: 100px;">{{ $game->homeTeam->name }}</span>
                                                @if($game->homeTeam->logo)
                                                    <img src="{{ asset('storage/' . $game->homeTeam->logo) }}" alt="Logo" class="rounded-circle border" style="width: 28px; height: 28px; object-fit: cover;">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border" style="width: 28px; height: 28px; font-size: 0.7rem; font-weight: bold;">
                                                        {{ substr($game->homeTeam->name, 0, 2) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- Marcador / VS -->
                                            <span class="fs-6 px-2 py-1 rounded bg-slate-100 border text-center fw-bold" style="min-width: 50px;">
                                                @if($game->status === 'completed')
                                                    {{ $game->home_team_score }} - {{ $game->away_team_score }}
                                                @else
                                                    v - s
                                                @endif
                                            </span>
                                            <!-- Visitante -->
                                            <div class="d-flex align-items-center justify-content-start gap-2" style="width: 150px;">
                                                @if($game->awayTeam->logo)
                                                    <img src="{{ asset('storage/' . $game->awayTeam->logo) }}" alt="Logo" class="rounded-circle border" style="width: 28px; height: 28px; object-fit: cover;">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border" style="width: 28px; height: 28px; font-size: 0.7rem; font-weight: bold;">
                                                        {{ substr($game->awayTeam->name, 0, 2) }}
                                                    </div>
                                                @endif
                                                <span class="fw-bold small text-slate-800 text-truncate" style="max-width: 100px;">{{ $game->awayTeam->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($game->status === 'completed')
                                            <span class="badge badge-status-completed px-3 py-1 rounded-pill" style="font-size: 0.75rem;">Finalizado</span>
                                        @elseif($game->status === 'in_progress')
                                            <span class="badge badge-status-playing px-3 py-1 rounded-pill" style="font-size: 0.75rem;"><span class="spinner-grow spinner-grow-sm text-primary me-1" role="status"></span>En Vivo</span>
                                        @else
                                            <span class="badge badge-status-pending px-3 py-1 rounded-pill" style="font-size: 0.75rem;">Pendiente</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted text-nowrap">
                                        {{ $game->match_date ? \Carbon\Carbon::parse($game->match_date)->format('d/m/Y H:i') : 'Sin fecha' }}
                                    </td>
                                    <td class="small text-muted text-truncate" style="max-width: 120px;">
                                        <i class="bi bi-person-badge me-1"></i>
                                        {{ $game->referee ? $game->referee->name : 'No asignado' }}
                                    </td>
                                    <td class="pe-4 text-end text-nowrap">
                                        <button wire:click="startEdit({{ $game->id }})" class="btn btn-sm btn-outline-primary rounded-pill me-1" title="Editar Partido">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button onclick="confirm('¿Estás seguro de eliminar este partido?') || event.stopImmediatePropagation()" wire:click="deleteGame({{ $game->id }})" class="btn btn-sm btn-outline-danger rounded-pill" title="Eliminar Partido">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
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
