<?php
 
namespace App\Providers;
 
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Gate;
 
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }
 
    public function boot(): void
    {
        $this->configureDefaults();
 
        Gate::policy(\App\Models\Game::class, \App\Policies\JuegoPolicy::class);
        Gate::policy(\App\Models\Participant::class, \App\Policies\ParticipantePolicy::class);
        Gate::policy(\App\Models\Team::class, \App\Policies\EquipoPolicy::class);
    }
 
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);
 
        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );
 
        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
