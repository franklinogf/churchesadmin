<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Church;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\OfferingType;
use App\Models\TenantUser;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Spatie\Translatable\Facades\Translatable;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureDates();
        $this->configureModels();
        $this->configureValidations();
        $this->configureJsonResources();
        Translatable::fallback(
            fallbackAny: true
        );
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(app()->isProduction());
    }

    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    private function configureModels(): void
    {

        Model::unguard();
        Model::shouldBeStrict(! app()->isProduction());
        Model::automaticallyEagerLoadRelationships();
        Relation::enforceMorphMap([
            'member' => Member::class,
            'missionary' => Missionary::class,
            'user' => TenantUser::class,
            'church' => Church::class,
            'offering_type' => OfferingType::class,
        ]);
    }

    private function configureValidations(): void
    {
        Password::defaults(fn () => app()->isProduction()
        ? Password::min(8)->letters()
            ->mixedCase()
            ->numbers()
        : Password::min(6));

    }

    private function configureJsonResources(): void
    {
        JsonResource::withoutWrapping();
    }
}
