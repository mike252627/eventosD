<x-layouts::bootstrap>
    <x-slot:title>Equipos Participantes - Torneo Deportivo</x-slot:title>

    <div class="row mb-5">
        <div class="col-12 text-center">
            <span class="badge bg-primary px-3 py-2 rounded-3 mb-2 text-uppercase fw-semibold tracking-wider">Liga Oficial</span>
            <h1 class="display-5 fw-extrabold text-slate-800">Equipos Participantes</h1>
            <p class="text-muted lead max-w-2xl mx-auto">Conoce los clubes y delegaciones deportivas que compiten en las distintas disciplinas de este torneo.</p>
        </div>
    </div>

    @if($teams->isEmpty())
        <div class="card p-5 text-center shadow-sm rounded-4 border-0 bg-white">
            <div class="card-body">
                <i class="bi bi-shield-slash display-1 text-muted mb-3"></i>
                <h3>No hay equipos registrados</h3>
                <p class="text-muted">Pronto comenzarán a registrarse los primeros competidores.</p>
                <a href="{{ route('home') }}" class="btn btn-premium mt-3"><i class="bi bi-arrow-left me-1"></i>Volver al Inicio</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($teams as $team)
                <div class="col-md-6 col-lg-4">
                    <div class="card-premium h-100 p-4 d-flex flex-column align-items-center text-center">
                        <!-- Team Logo or Fallback -->
                        <div class="mb-3 position-relative">
                            @if($team->logo)
                                <img src="{{ asset('storage/' . $team->logo) }}" alt="Logo {{ $team->name }}" class="rounded-circle border shadow-sm img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border shadow-sm" style="width: 120px; height: 120px; font-size: 2.5rem; font-weight: 800; font-family: 'Outfit';">
                                    {{ substr($team->name, 0, 2) }}
                                </div>
                            @endif
                            <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-dark border border-white" style="font-size: 0.8rem;">
                                <i class="bi bi-people-fill me-1"></i>{{ $team->participants_count }}
                            </span>
                        </div>

                        <!-- Team Info -->
                        <h4 class="fw-bold text-slate-800 mb-2">{{ $team->name }}</h4>
                        <p class="text-muted small mb-4">Miembro activo del torneo 2026. Inscrito en múltiples disciplinas competitivas.</p>

                        <!-- Action -->
                        <div class="mt-auto w-100">
                            <a href="{{ route('teams.show', $team->id) }}" class="btn btn-premium w-100">
                                <i class="bi bi-eye me-1"></i>Conocer más del Equipo
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts::bootstrap>
