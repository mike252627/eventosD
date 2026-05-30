<div>
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <span class="badge bg-success px-3 py-2 rounded-3 mb-2 text-uppercase fw-semibold"><i class="bi bi-person-fill-gear me-1"></i>Rol: {{ auth()->user()->hasRole('admin') ? 'Administrador' : 'Secretaria' }}</span>
            <h2 class="h1 mb-1 text-slate-800">
                @if(auth()->user()->hasRole('secretaria'))
                    Gestión de mi Equipo
                @else
                    Panel de Administración de Equipos
                @endif
            </h2>
            <p class="text-muted mb-0">
                @if(auth()->user()->hasRole('secretaria'))
                    Administra los datos de tu equipo designado, agrega competidores e inscríbelos en las disciplinas.
                @else
                    Registra y edita todos los equipos, da de alta participantes y asócialos a disciplinas.
                @endif
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

    <div class="row g-4">
        <!-- Columna de Formularios (Izquierda) -->
        <div class="col-lg-5">
            
            <!-- FORM 1: EQUIPOS (Crear o Editar según el caso) -->
            @if(auth()->user()->hasRole('admin') || (auth()->user()->hasRole('secretaria') && !$my_team))
                <!-- Crear Equipo -->
                <div class="card-premium mb-4">
                    <div class="card-header-premium" style="background: var(--dark-gradient);">
                        <h5 class="mb-0"><i class="bi bi-shield-plus me-2 text-info"></i>Registrar Nuevo Equipo</h5>
                    </div>
                    <div class="card-body p-4">
                        <form wire:submit.prevent="createTeam">
                            <div class="mb-3">
                                <label for="team_name" class="form-label fw-semibold">Nombre del Equipo</label>
                                <input type="text" id="team_name" wire:model="team_name" class="form-control rounded-3 @error('team_name') is-invalid @enderror" placeholder="Ej. Club Deportivo Fénix">
                                @error('team_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="team_logo" class="form-label fw-semibold">Logotipo (Imagen)</label>
                                <input type="file" id="team_logo" wire:model="team_logo" class="form-control rounded-3 @error('team_logo') is-invalid @enderror" accept="image/png">
                                <div class="form-text text-danger fw-semibold"><i class="bi bi-info-circle me-1"></i>Solo se aceptan imágenes en formato PNG (máx. 2MB).</div>
                                @error('team_logo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                @if ($team_logo)
                                    @php
                                        $previewUrl = null;
                                        try {
                                            $previewUrl = $team_logo->temporaryUrl();
                                        } catch (\Exception $e) {}
                                    @endphp
                                    @if($previewUrl)
                                        <div class="mt-2 text-center">
                                            <span class="text-muted small d-block mb-1">Previsualización:</span>
                                            <img src="{{ $previewUrl }}" class="rounded-circle border" style="width: 70px; height: 70px; object-fit: cover;">
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning py-2 rounded-3 text-center small mt-2 mb-0">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>No se ha seleccionado ninguna imagen. Se usará el ícono por defecto.
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-premium w-100 py-2"><i class="bi bi-plus-circle me-1"></i>Crear Equipo</button>
                        </form>
                    </div>
                </div>
            @elseif(auth()->user()->hasRole('secretaria') && $my_team)
                <!-- Editar Equipo -->
                <div class="card-premium mb-4">
                    <div class="card-header-premium" style="background: var(--dark-gradient);">
                        <h5 class="mb-0"><i class="bi bi-shield-shaded me-2 text-info"></i>Editar Datos del Equipo</h5>
                    </div>
                    <div class="card-body p-4">
                        <form wire:submit.prevent="updateTeam">
                            <!-- Logo Actual -->
                            <div class="text-center mb-4">
                                @if($current_team_logo)
                                    <img src="{{ asset('storage/' . $current_team_logo) }}" alt="Logo" class="rounded-circle border shadow-sm" style="width: 90px; height: 90px; object-fit: cover;">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 90px; height: 90px; font-size: 2rem; font-weight: bold; font-family: 'Outfit';">
                                        {{ substr($my_team->name, 0, 2) }}
                                    </div>
                                @endif
                                <span class="d-block mt-2 text-muted small">Equipo Designado</span>
                            </div>

                            <div class="mb-3">
                                <label for="team_name" class="form-label fw-semibold">Nombre del Equipo</label>
                                <input type="text" id="team_name" wire:model="team_name" class="form-control rounded-3 @error('team_name') is-invalid @enderror" placeholder="Ej. Club Deportivo Fénix">
                                @error('team_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="team_logo_update" class="form-label fw-semibold">Actualizar Logotipo (Opcional)</label>
                                <input type="file" id="team_logo_update" wire:model="team_logo" class="form-control rounded-3 @error('team_logo') is-invalid @enderror" accept="image/png">
                                <div class="form-text text-danger fw-semibold mb-1"><i class="bi bi-info-circle me-1"></i>Solo se aceptan imágenes en formato PNG (máx. 2MB).</div>
                                <div class="form-text">Dejar en blanco para conservar el logotipo actual.</div>
                                @error('team_logo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                @if ($team_logo)
                                    @php
                                        $previewUrl = null;
                                        try {
                                            $previewUrl = $team_logo->temporaryUrl();
                                        } catch (\Exception $e) {}
                                    @endphp
                                    @if($previewUrl)
                                        <div class="mt-2 text-center">
                                            <span class="text-muted small d-block mb-1">Previsualización del nuevo logo:</span>
                                            <img src="{{ $previewUrl }}" class="rounded-circle border" style="width: 70px; height: 70px; object-fit: cover;">
                                        </div>
                                    @endif
                                @elseif(!$current_team_logo)
                                    <div class="alert alert-warning py-2 rounded-3 text-center small mt-2 mb-0">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>Este equipo no tiene un logotipo cargado. Se muestra la imagen por defecto.
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-premium w-100 py-2"><i class="bi bi-save me-1"></i>Guardar Cambios del Equipo</button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- FORM 2: REGISTRAR/EDITAR PARTICIPANTE -->
            @if(auth()->user()->hasRole('admin') || (auth()->user()->hasRole('secretaria') && $my_team))
                <div class="card-premium mb-4">
                    <div class="card-header-premium" style="background: var(--secondary-gradient);">
                        <h5 class="mb-0"><i class="bi {{ $is_editing_participant ? 'bi-pencil-square' : 'bi-person-plus' }} me-2 text-warning"></i>
                            {{ $is_editing_participant ? 'Editar Integrante' : 'Registrar Nuevo Participante' }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form wire:submit.prevent="{{ $is_editing_participant ? 'updateParticipant' : 'createParticipant' }}">
                            <div class="mb-3">
                                <label for="participant_name" class="form-label fw-semibold">Nombre Completo</label>
                                <input type="text" id="participant_name" wire:model="participant_name" class="form-control rounded-3 @error('participant_name') is-invalid @enderror" placeholder="Ej. Carlos Mendoza">
                                @error('participant_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if(auth()->user()->hasRole('admin'))
                                <div class="mb-3">
                                    <label for="participant_team_id" class="form-label fw-semibold">Asignar a un Equipo</label>
                                    <select id="participant_team_id" wire:model="participant_team_id" class="form-select rounded-3 @error('participant_team_id') is-invalid @enderror" {{ $is_editing_participant ? 'disabled' : '' }}>
                                        <option value="">-- Seleccionar Equipo --</option>
                                        @foreach($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('participant_team_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @else
                                <!-- Secretaria: predefinido -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Equipo</label>
                                    <input type="text" class="form-control rounded-3 bg-light" value="{{ $my_team->name }}" readonly disabled>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="participant_photo" class="form-label fw-semibold">
                                    {{ $is_editing_participant ? 'Actualizar Foto (Opcional)' : 'Foto del Participante (Opcional)' }}
                                </label>
                                <input type="file" id="participant_photo" wire:model="participant_photo" class="form-control rounded-3 @error('participant_photo') is-invalid @enderror" accept="image/png">
                                <div class="form-text text-danger fw-semibold"><i class="bi bi-info-circle me-1"></i>Solo se aceptan imágenes en formato PNG (máx. 2MB).</div>
                                @error('participant_photo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                @if ($participant_photo)
                                    @php
                                        $previewUrl = null;
                                        try {
                                            $previewUrl = $participant_photo->temporaryUrl();
                                        } catch (\Exception $e) {}
                                    @endphp
                                    @if($previewUrl)
                                        <div class="mt-2 text-center">
                                            <span class="text-muted small d-block mb-1">Previsualización de la foto:</span>
                                            <img src="{{ $previewUrl }}" class="rounded border" style="width: 70px; height: 70px; object-fit: cover;">
                                        </div>
                                    @endif
                                @elseif($is_editing_participant && $current_participant_photo)
                                    <div class="mt-2 text-center">
                                        <span class="text-muted small d-block mb-1">Foto actual:</span>
                                        <img src="{{ asset('storage/' . $current_participant_photo) }}" class="rounded border" style="width: 70px; height: 70px; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="alert alert-warning py-2 rounded-3 text-center small mt-2 mb-0">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>No se ha seleccionado ninguna foto. Se usará el avatar por defecto.
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                @if($is_editing_participant)
                                    <button type="button" wire:click="cancelParticipantEdit" class="btn btn-outline-secondary w-50 py-2">Cancelar</button>
                                @endif
                                <button type="submit" class="btn btn-premium {{ $is_editing_participant ? 'w-50' : 'w-100' }} py-2" style="background: var(--secondary-gradient);">
                                    <i class="bi {{ $is_editing_participant ? 'bi-save' : 'bi-plus-circle' }} me-1"></i>
                                    {{ $is_editing_participant ? 'Guardar Cambios' : 'Dar de Alta Participante' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- FORM 3: ASOCIAR DISCIPLINAS -->
            @if(!$participants->isEmpty())
                <div class="card-premium">
                    <div class="card-header-premium">
                        <h5 class="mb-0"><i class="bi bi-journal-check me-2"></i>Asociar Participante a Disciplinas</h5>
                    </div>
                    <div class="card-body p-4">
                        <form wire:submit.prevent="assignDisciplines">
                            <div class="mb-3">
                                <label for="selected_participant_id" class="form-label fw-semibold">Seleccionar Participante</label>
                                <select id="selected_participant_id" wire:model.live="selected_participant_id" class="form-select rounded-3 @error('selected_participant_id') is-invalid @enderror">
                                    <option value="">-- Seleccionar Participante --</option>
                                    @foreach($participants as $part)
                                        <option value="{{ $part->id }}">{{ $part->name }} ({{ $part->team->name }})</option>
                                    @endforeach
                                </select>
                                @error('selected_participant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($selected_participant_id)
                                <div class="mb-4">
                                    <label class="form-label fw-semibold d-block">Disciplinas Deportivas <span class="text-danger fw-bold">(Máximo 2)</span></label>
                                    <div class="p-3 bg-light rounded-3 border">
                                        <div class="row g-2">
                                            @foreach($disciplines as $disc)
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input @error('selected_discipline_ids') is-invalid @enderror" type="checkbox" value="{{ $disc->id }}" id="disc-{{ $disc->id }}" wire:model="selected_discipline_ids">
                                                        <label class="form-check-label small" for="disc-{{ $disc->id }}">
                                                            {{ $disc->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('selected_discipline_ids')
                                            <div class="text-danger small mt-2 d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-premium w-100 py-2"><i class="bi bi-save me-1"></i>Guardar Asignación</button>
                            @else
                                <div class="alert alert-info py-2 rounded-3 text-center small mb-0">
                                    Selecciona un participante para ver e inscribirlo en disciplinas.
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Columna de Consulta (Derecha) -->
        <div class="col-lg-7">
            <!-- Lista de Roster e Información -->
            <div class="card-premium mb-4">
                <div class="card-header-premium d-flex justify-content-between align-items-center" style="background: var(--dark-gradient);">
                    <h5 class="mb-0"><i class="bi bi-list-stars me-2"></i>Rosters y Equipos Registrados</h5>
                    <span class="badge bg-info text-dark rounded-pill">{{ $teams->count() }} Equipos</span>
                </div>
                <div class="card-body p-4" style="max-height: 800px; overflow-y: auto;">
                    @if($teams->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-shield-slash display-4 d-block mb-3"></i>
                            <p class="mb-0">No hay equipos creados aún.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($teams as $team)
                                <div class="p-3 border rounded-3 mb-3 bg-light shadow-sm">
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            @if($team->logo)
                                                <img src="{{ asset('storage/' . $team->logo) }}" alt="Logo" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold; font-size: 0.9rem; font-family: 'Outfit';">
                                                    {{ substr($team->name, 0, 2) }}
                                                </div>
                                            @endif
                                            <h6 class="fw-bold text-slate-800 mb-0">{{ $team->name }}</h6>
                                        </div>
                                        <span class="badge bg-secondary rounded-pill small">{{ $team->participants->count() }} jugadores</span>
                                    </div>
                                    
                                    @if($team->participants->isEmpty())
                                        <p class="text-muted small mb-0 mt-1">Sin jugadores asignados aún.</p>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover align-middle mb-0 bg-white rounded overflow-hidden">
                                                <thead>
                                                    <tr class="table-secondary">
                                                        <th class="small py-1 ps-2" style="width: 50px;">Foto</th>
                                                        <th class="small py-1">Nombre</th>
                                                        <th class="small py-1 text-center" style="width: 140px;">Disciplinas</th>
                                                        <th class="small py-1 text-end pe-2" style="width: 90px;">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($team->participants as $participant)
                                                        <tr>
                                                            <td class="py-2 ps-2">
                                                                @if($participant->photo)
                                                                    <img src="{{ asset('storage/' . $participant->photo) }}" alt="Foto" class="rounded border" style="width: 32px; height: 32px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-secondary-subtle text-secondary rounded border d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                                        <i class="bi bi-person-fill"></i>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td class="small py-2 fw-medium text-slate-700">{{ $participant->name }}</td>
                                                            <td class="small py-2 text-center">
                                                                @forelse($participant->disciplines as $discipline)
                                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 0.7rem; display: inline-block; margin-bottom: 2px;">
                                                                        {{ $discipline->name }}
                                                                    </span>
                                                                @empty
                                                                    <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                                                        Ninguna
                                                                    </span>
                                                                @endforelse
                                                            </td>
                                                            <td class="small py-2 text-end pe-2 text-nowrap">
                                                                <button wire:click="startEditParticipant({{ $participant->id }})" class="btn btn-sm btn-outline-primary p-1 py-0 rounded-circle" title="Editar Integrante" style="font-size: 0.75rem;">
                                                                    <i class="bi bi-pencil-fill"></i>
                                                                </button>
                                                                <button onclick="confirm('¿Estás seguro de eliminar este integrante del equipo?') || event.stopImmediatePropagation()" wire:click="deleteParticipant({{ $participant->id }})" class="btn btn-sm btn-outline-danger p-1 py-0 rounded-circle" title="Eliminar Integrante" style="font-size: 0.75rem;">
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
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
