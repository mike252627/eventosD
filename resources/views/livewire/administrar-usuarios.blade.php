<div>
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <span class="badge bg-danger px-3 py-2 rounded-3 mb-2 text-uppercase fw-semibold"><i class="bi bi-shield-fill-check me-1"></i>Módulo de Administración de Usuarios</span>
            <h2 class="h3 mb-1 text-slate-800">Control de Secretarias y Árbitros</h2>
            <p class="text-muted mb-0">Registra y administra cuentas de usuario para el equipo de secretarias y el cuerpo técnico de árbitros del torneo.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            @if(!$is_creating && !$is_editing)
                <button wire:click="startCreate" class="btn btn-premium px-4 py-2">
                    <i class="bi bi-person-plus-fill me-2"></i>Registrar Nuevo Personal
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
                <h5 class="mb-0"><i class="bi {{ $is_creating ? 'bi-person-plus-fill' : 'bi-pencil-square' }} me-2 text-info"></i>
                    {{ $is_creating ? 'Crear Cuenta de Personal' : 'Modificar Datos de Cuenta' }}
                </h5>
            </div>
            <div class="card-body p-4">
                <form wire:submit.prevent="{{ $is_creating ? 'createUser' : 'updateUser' }}">
                    <div class="row g-3">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Nombre Completo</label>
                            <input type="text" id="name" wire:model="name" class="form-control rounded-3 @error('name') is-invalid @enderror" placeholder="Ej. Ana María Gómez">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                            <input type="email" id="email" wire:model="email" class="form-control rounded-3 @error('email') is-invalid @enderror" placeholder="Ej. secretaria1@eventos.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">Contraseña {{ $is_editing ? '(Dejar en blanco para no cambiar)' : '' }}</label>
                            <input type="password" id="password" wire:model="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="Mínimo 6 caracteres">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rol -->
                        <div class="col-md-3">
                            <label for="role" class="form-label fw-semibold">Rol Asignado</label>
                            <select id="role" wire:model.live="role" class="form-select rounded-3 @error('role') is-invalid @enderror">
                                <option value="">-- Seleccionar Rol --</option>
                                <option value="secretaria">Secretaria</option>
                                <option value="arbitro">Árbitro</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Disciplina (para Árbitros) -->
                        <div class="col-md-3">
                            <label for="discipline_id" class="form-label fw-semibold">Disciplina (Solo Árbitros)</label>
                            <select id="discipline_id" wire:model="discipline_id" class="form-select rounded-3 @error('discipline_id') is-invalid @enderror" {{ $role !== 'arbitro' ? 'disabled' : '' }}>
                                <option value="">-- Asignar Disciplina --</option>
                                @foreach($disciplines as $disc)
                                    <option value="{{ $disc->id }}">{{ $disc->name }}</option>
                                @endforeach
                            </select>
                            @error('discipline_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" wire:click="cancel" class="btn btn-outline-secondary rounded-3 px-4">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-premium px-4">
                            <i class="bi bi-save me-1"></i>{{ $is_creating ? 'Guardar Cuenta' : 'Actualizar Cuenta' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Users Table -->
    <div class="card-premium">
        <div class="card-header-premium d-flex justify-content-between align-items-center" style="background: var(--dark-gradient);">
            <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Personal de Soporte y Arbitraje</h5>
            <span class="badge bg-white text-dark rounded-pill">{{ $users->count() }} usuarios registrados</span>
        </div>
        <div class="card-body p-0">
            @if($users->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-people display-3 d-block mb-3"></i>
                    <p class="mb-0">No se han registrado usuarios del personal técnico.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Disciplina Asignada</th>
                                <th>Fecha de Alta</th>
                                <th class="pe-4 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                                <tr>
                                    <td class="ps-4 fw-semibold text-slate-800">
                                        {{ $u->name }}
                                    </td>
                                    <td>
                                        {{ $u->email }}
                                    </td>
                                    <td>
                                        @if($u->hasRole('secretaria'))
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">Secretaria</span>
                                        @elseif($u->hasRole('arbitro'))
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">Árbitro</span>
                                        @else
                                            <span class="badge bg-light text-muted border px-3 py-1 rounded-pill" style="font-size: 0.75rem;">Sin Rol</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($u->hasRole('arbitro'))
                                            <span class="badge-discipline">
                                                {{ $u->discipline ? $u->discipline->name : 'General (Sin asignar)' }}
                                            </span>
                                        @else
                                            <span class="text-muted small">No aplica</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        {{ $u->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="pe-4 text-end text-nowrap">
                                        <button wire:click="startEdit({{ $u->id }})" class="btn btn-sm btn-outline-primary rounded-pill me-1" title="Editar Usuario">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button onclick="confirm('¿Estás seguro de dar de baja este usuario?') || event.stopImmediatePropagation()" wire:click="deleteUser({{ $u->id }})" class="btn btn-sm btn-outline-danger rounded-pill" title="Eliminar Usuario">
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
