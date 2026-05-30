<x-layouts::bootstrap>
    <x-slot:title>{{ $team->name }} - Detalles del Equipo</x-slot:title>

    <!-- Header Details Card -->
    <div class="card-premium p-5 mb-5 text-white" style="background: var(--dark-gradient);">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-4 mb-md-0">
                @if($team->logo)
                    <img src="{{ asset('storage/' . $team->logo) }}" alt="Logo {{ $team->name }}" class="rounded-circle border border-4 shadow img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-4 shadow mx-auto" style="width: 150px; height: 150px; font-size: 3.5rem; font-weight: 800; font-family: 'Outfit';">
                        {{ substr($team->name, 0, 2) }}
                    </div>
                @endif
            </div>
            <div class="col-md-9 text-center text-md-start">
                <span class="badge bg-primary px-3 py-2 rounded-3 mb-2 text-uppercase fw-semibold"><i class="bi bi-shield-check me-1"></i>Club Registrado</span>
                <h1 class="display-4 fw-extrabold mb-2">{{ $team->name }}</h1>
                <p class="lead text-slate-300 mb-0">Roster de competidores oficiales y disciplinas en juego para esta temporada.</p>
                <div class="mt-3">
                    <a href="{{ route('teams.index') }}" class="btn btn-premium-outline btn-sm"><i class="bi bi-arrow-left me-1"></i>Ver todos los Equipos</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Participants / Roster -->
    <div class="card-premium p-4">
        <h3 class="fw-bold mb-4 text-slate-800 border-bottom pb-2">
            <i class="bi bi-people-fill text-primary me-2"></i>Plantilla de Jugadores (Roster)
        </h3>

        @if($team->participants->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-person-workspace display-4 mb-3"></i>
                <p class="lead mb-0">No hay jugadores registrados en este equipo actualmente.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($team->participants as $participant)
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 border rounded-3 bg-light hover-lift d-flex align-items-center gap-3">
                            <!-- Participant Photo -->
                            <div class="flex-shrink-0">
                                @if($participant->photo)
                                    <img src="{{ asset('storage/' . $participant->photo) }}" alt="Foto {{ $participant->name }}" class="rounded shadow-sm border" style="width: 65px; height: 65px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary-subtle text-secondary rounded shadow-sm border d-flex align-items-center justify-content-center" style="width: 65px; height: 65px; font-size: 1.5rem;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                @endif
                            </div>
                            <!-- Participant Info -->
                            <div class="flex-grow-1 min-w-0">
                                <h5 class="fw-bold text-slate-800 text-truncate mb-1">{{ $participant->name }}</h5>
                                <div class="d-flex gap-1 flex-wrap mt-1">
                                    @forelse($participant->disciplines as $discipline)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                            {{ $discipline->name }}
                                        </span>
                                    @empty
                                        <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                            Sin disciplina
                                        </span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts::bootstrap>
