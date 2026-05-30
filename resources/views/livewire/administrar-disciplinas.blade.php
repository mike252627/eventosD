<div>
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <span class="badge bg-success px-3 py-2 rounded-3 mb-2 text-uppercase fw-semibold"
                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;">
                <i class="bi bi-collection-fill me-1"></i>Módulo de Disciplinas Deportivas
            </span>
            <h2 class="h3 mb-1 text-slate-800">Control de Disciplinas</h2>
            <p class="text-muted mb-0">Registra y administra las disciplinas y deportes oficiales que forman parte del
                torneo.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            @if(!$is_creating && !$is_editing)
                <button wire:click="startCreate" class="btn btn-premium px-4 py-2"
                    style="background: var(--secondary-gradient); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                    <i class="bi bi-plus-circle-fill me-2"></i>Registrar Nueva Disciplina
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
                <h5 class="mb-0"><i
                        class="bi {{ $is_creating ? 'bi-plus-circle-fill' : 'bi-pencil-square' }} me-2 text-success"></i>
                    {{ $is_creating ? 'Crear Nueva Disciplina Deportiva' : 'Modificar Nombre de Disciplina' }}
                </h5>
            </div>
            <div class="card-body p-4">
                <form wire:submit.prevent="{{ $is_creating ? 'createDiscipline' : 'updateDiscipline' }}">
                    <div class="row g-4">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Nombre de la Disciplina</label>
                            <input type="text" id="name" wire:model="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror"
                                placeholder="Ej. Fútbol Asociación, Baloncesto Femenil, Voleibol de Sala">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tipo de Distintivo (Radio Buttons) -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-block">Tipo de Distintivo</label>
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="icon_type" id="type_icon" value="icon" wire:model.live="icon_type">
                                    <label class="form-check-label fw-medium" for="type_icon">
                                        <i class="bi bi-palette-fill me-1 text-primary"></i>Icono de Bootstrap Icons
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="icon_type" id="type_image" value="image" wire:model.live="icon_type">
                                    <label class="form-check-label fw-medium" for="type_image">
                                        <i class="bi bi-image-fill me-1 text-success"></i>Imagen de Archivo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        <!-- Sección condicional de ICONO -->
                        @if($icon_type === 'icon')
                            <div class="col-12">
                                <label class="form-label fw-semibold">Seleccionar Icono Recomendado</label>
                                <div class="row g-2 mb-3">
                                    @foreach($recommended_icons as $value => $label)
                                        <div class="col-4 col-sm-3 col-md-2">
                                            <button type="button" 
                                                wire:click="$set('icon_class', '{{ $value }}')" 
                                                class="btn w-100 p-2 border rounded-3 text-center d-flex flex-column align-items-center justify-content-center hover-lift {{ $icon_class === $value ? 'btn-primary border-primary text-white shadow-sm' : 'btn-light bg-white text-dark border-light-subtle' }}" 
                                                style="min-height: 85px; transition: all 0.2s;"
                                                title="{{ $label }}">
                                                <i class="bi {{ $value }} fs-3 mb-1"></i>
                                                <span class="small text-truncate w-100" style="font-size: 0.65rem;">{{ $label }}</span>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="custom_icon" class="form-label fw-semibold small text-muted">¿Deseas otro? Escribe cualquier clase de Bootstrap Icons</label>
                                <input type="text" id="custom_icon" wire:model.live="icon_class" class="form-control rounded-3 @error('icon_class') is-invalid @enderror" placeholder="Ej. bi-trophy">
                                @error('icon_class')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 text-center d-flex flex-column align-items-center justify-content-center border rounded-3 p-3 bg-light">
                                <span class="text-muted small fw-semibold mb-2">Vista Previa del Icono Seleccionado</span>
                                <div class="p-3 bg-white rounded-circle shadow-sm border d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    @if(!empty($icon_class))
                                        <i class="bi {{ $icon_class }} fs-1 text-primary"></i>
                                    @else
                                        <i class="bi bi-question fs-1 text-muted"></i>
                                    @endif
                                </div>
                                <span class="mt-2 small text-primary fw-medium">{{ $icon_class }}</span>
                            </div>
                        @endif

                        <!-- Sección condicional de IMAGEN -->
                        @if($icon_type === 'image')
                            <div class="col-md-6">
                                <label for="image_file" class="form-label fw-semibold">Subir Archivo de Imagen</label>
                                <input type="file" id="image_file" wire:model="image" class="form-control rounded-3 @error('image') is-invalid @enderror" accept="image/png">
                                <small class="text-muted d-block mt-1">Formato permitido: PNG. Peso máx: 1MB.</small>
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 text-center d-flex flex-column align-items-center justify-content-center border rounded-3 p-3 bg-light">
                                <span class="text-muted small fw-semibold mb-2">Vista Previa de la Imagen</span>
                                
                                <div class="d-flex align-items-center justify-content-center border rounded bg-white p-2 shadow-sm" style="width: 100px; height: 100px; overflow: hidden;">
                                    @if ($image)
                                        <img src="{{ $image->temporaryUrl() }}" class="img-fluid" style="max-height: 80px; object-fit: contain;">
                                    @elseif ($existing_image_path)
                                        <img src="{{ asset('storage/' . $existing_image_path) }}" class="img-fluid" style="max-height: 80px; object-fit: contain;">
                                    @else
                                        <i class="bi bi-image display-6 text-muted"></i>
                                    @endif
                                </div>

                                <div wire:loading wire:target="image" class="mt-2 text-success small">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"></span> Cargando archivo...
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" wire:click="cancel" class="btn btn-outline-secondary rounded-3 px-4">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-premium px-4"
                            style="{{ $is_creating ? 'background: var(--secondary-gradient); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);' : '' }}">
                            <i class="bi bi-save me-1"></i>{{ $is_creating ? 'Guardar Disciplina' : 'Actualizar Disciplina' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Disciplines Table -->
    <div class="card-premium">
        <div class="card-header-premium d-flex justify-content-between align-items-center"
            style="background: var(--dark-gradient);">
            <h5 class="mb-0"><i class="bi bi-collection-fill me-2"></i>Disciplinas Registradas</h5>
            <span class="badge bg-white text-dark rounded-pill">{{ $disciplines->count() }} disciplinas</span>
        </div>
        <div class="card-body p-0">
            @if($disciplines->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-collection display-3 d-block mb-3"></i>
                    <p class="mb-0">No se han registrado disciplinas deportivas.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Distintivo</th>
                                <th>Nombre de la Disciplina</th>
                                <th>Partidos Programados</th>
                                <th>Árbitros Asignados</th>
                                <th class="pe-4 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disciplines as $disc)
                                <tr>
                                    <td class="ps-4 text-muted small">
                                        #{{ $disc->id }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center bg-light border rounded-3 p-1 shadow-xs" style="width: 42px; height: 42px; overflow: hidden;">
                                            @if($disc->icon_type === 'image' && $disc->image_path)
                                                <img src="{{ asset('storage/' . $disc->image_path) }}" alt="{{ $disc->name }}" style="max-width: 32px; max-height: 32px; object-fit: contain;">
                                            @elseif($disc->icon_type === 'icon' && $disc->icon_class)
                                                <i class="bi {{ $disc->icon_class }} fs-4 text-primary"></i>
                                            @else
                                                <i class="bi bi-trophy-fill fs-4 text-muted"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="fw-semibold text-slate-800">
                                        {{ $disc->name }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $disc->games_count > 0 ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-muted border' }} px-3 py-1 rounded-pill"
                                            style="font-size: 0.75rem;">
                                            <i class="bi bi-calendar-event me-1"></i>{{ $disc->games_count }}
                                            {{ Str::plural('partido', $disc->games_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $disc->referees_count > 0 ? 'bg-info-subtle text-info-emphasis border border-info-subtle' : 'bg-light text-muted border' }} px-3 py-1 rounded-pill"
                                            style="font-size: 0.75rem;">
                                            <i class="bi bi-person-badge me-1"></i>{{ $disc->referees_count }}
                                            {{ Str::plural('árbitro', $disc->referees_count) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end text-nowrap">
                                        <button wire:click="startEdit({{ $disc->id }})"
                                            class="btn btn-sm btn-outline-primary rounded-pill me-1" title="Editar Disciplina">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button
                                            onclick="confirm('¿Estás seguro de eliminar esta disciplina? Se borrarán todos los partidos y relaciones asociadas de forma irreversible.') || event.stopImmediatePropagation()"
                                            wire:click="deleteDiscipline({{ $disc->id }})"
                                            class="btn btn-sm btn-outline-danger rounded-pill" title="Eliminar Disciplina">
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