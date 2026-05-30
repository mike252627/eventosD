<x-layouts::bootstrap>
    <x-slot:title>Panel de Administración - Eventos Deportivos</x-slot:title>

    @if(auth()->user()->hasRole('admin'))
        <!-- Admin Dashboard Wrapper -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <span class="badge bg-danger px-3 py-2 rounded-3 mb-2 text-uppercase fw-semibold"><i class="bi bi-shield-fill-check me-1"></i>Administrador General</span>
                <h1 class="fw-extrabold text-slate-800">Consola del Administrador</h1>
                <p class="text-muted">Tienes acceso total a todas las herramientas del sistema. Alterna entre los módulos de abajo.</p>
                
                <div class="d-flex justify-content-center flex-wrap gap-2 mt-3">
                    <button class="btn btn-premium px-4 py-2" type="button" onclick="toggleSection('secretaria')">
                        <i class="bi bi-shield-fill me-2"></i>Módulo de Equipos (Secretaria)
                    </button>
                    <button class="btn btn-secondary px-4 py-2 text-white" type="button" onclick="toggleSection('arbitro')">
                        <i class="bi bi-whistle-fill me-2 text-warning"></i>Módulo de Marcadores (Árbitro)
                    </button>
                    <button class="btn btn-dark px-4 py-2" type="button" onclick="toggleSection('partidos')">
                        <i class="bi bi-calendar-event-fill me-2 text-info"></i>Módulo de Partidos (Admin)
                    </button>
                    <button class="btn btn-info px-4 py-2 text-white" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border: none;" type="button" onclick="toggleSection('usuarios')">
                        <i class="bi bi-people-fill me-2"></i>Módulo de Usuarios (Admin)
                    </button>
                    <button class="btn btn-success px-4 py-2 text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;" type="button" onclick="toggleSection('disciplinas')">
                        <i class="bi bi-collection-fill me-2"></i>Módulo de Disciplinas (Admin)
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Secretaria Section (Collapsible) -->
                <div class="collapse show" id="secretariaSection">
                    <div class="p-4 border rounded-4 bg-white shadow-sm mb-4">
                        <h4 class="fw-bold mb-4 text-primary border-bottom pb-2"><i class="bi bi-gear-fill me-2"></i>Vista de Secretaria (Admin)</h4>
                        <livewire:secretaria-dashboard />
                    </div>
                </div>

                <!-- Arbitro Section (Collapsible) -->
                <div class="collapse" id="arbitroSection">
                    <div class="p-4 border rounded-4 bg-white shadow-sm mb-4">
                        <h4 class="fw-bold mb-4 text-secondary border-bottom pb-2"><i class="bi bi-whistle-fill me-2 text-warning"></i>Vista de Árbitro (Admin)</h4>
                        <livewire:arbitro-dashboard />
                    </div>
                </div>

                <!-- Partidos Section (Collapsible) -->
                <div class="collapse" id="partidosSection">
                    <div class="p-4 border rounded-4 bg-white shadow-sm mb-4">
                        <h4 class="fw-bold mb-4 text-dark border-bottom pb-2"><i class="bi bi-calendar-event-fill text-info me-2"></i>Administración de Partidos (Admin)</h4>
                        <livewire:manage-games />
                    </div>
                </div>

                <!-- Usuarios Section (Collapsible) -->
                <div class="collapse" id="usuariosSection">
                    <div class="p-4 border rounded-4 bg-white shadow-sm mb-4">
                        <h4 class="fw-bold mb-4 text-dark border-bottom pb-2"><i class="bi bi-people-fill text-info me-2"></i>Administración de Usuarios (Admin)</h4>
                        <livewire:manage-users />
                    </div>
                </div>

                <!-- Disciplinas Section (Collapsible) -->
                <div class="collapse" id="disciplinasSection">
                    <div class="p-4 border rounded-4 bg-white shadow-sm mb-4">
                        <h4 class="fw-bold mb-4 text-dark border-bottom pb-2"><i class="bi bi-collection-fill text-success me-2"></i>Administración de Disciplinas (Admin)</h4>
                        <livewire:manage-disciplines />
                    </div>
                </div>
            </div>
        </div>

        <!-- Inline script to toggle panels cleanly -->
        <script>
            function toggleSection(active) {
                const secSec = document.getElementById('secretariaSection');
                const arbSec = document.getElementById('arbitroSection');
                const parSec = document.getElementById('partidosSection');
                const usrSec = document.getElementById('usuariosSection');
                const dspSec = document.getElementById('disciplinasSection');
                const bootstrap = window.bootstrap;
                
                const bsSec = bootstrap.Collapse.getOrCreateInstance(secSec, {toggle: false});
                const bsArb = bootstrap.Collapse.getOrCreateInstance(arbSec, {toggle: false});
                const bsPar = bootstrap.Collapse.getOrCreateInstance(parSec, {toggle: false});
                const bsUsr = bootstrap.Collapse.getOrCreateInstance(usrSec, {toggle: false});
                const bsDsp = bootstrap.Collapse.getOrCreateInstance(dspSec, {toggle: false});

                // Hide all
                bsSec.hide();
                bsArb.hide();
                bsPar.hide();
                bsUsr.hide();
                bsDsp.hide();

                // Show active
                if (active === 'secretaria') {
                    bsSec.show();
                } else if (active === 'arbitro') {
                    bsArb.show();
                } else if (active === 'partidos') {
                    bsPar.show();
                } else if (active === 'usuarios') {
                    bsUsr.show();
                } else if (active === 'disciplinas') {
                    bsDsp.show();
                }
            }
        </script>

    @elseif(auth()->user()->hasRole('secretaria'))
        <!-- Secretary View -->
        <livewire:secretaria-dashboard />

    @elseif(auth()->user()->hasRole('arbitro'))
        <!-- Referee View -->
        <livewire:arbitro-dashboard />

    @else
        <!-- Unassigned Role fallback -->
        <div class="card p-5 text-center shadow-sm max-w-md mx-auto rounded-4 border-0">
            <div class="card-body">
                <i class="bi bi-person-exclamation display-1 text-warning mb-3"></i>
                <h3 class="fw-bold">Acceso Restringido</h3>
                <p class="text-muted">Tu cuenta se ha autenticado con éxito, pero no posees un rol asignado para administrar datos en este portal.</p>
                <div class="mt-4">
                    <a href="{{ route('home') }}" class="btn btn-premium"><i class="bi bi-house me-1"></i>Volver al Inicio</a>
                </div>
            </div>
        </div>
    @endif
</x-layouts::bootstrap>
