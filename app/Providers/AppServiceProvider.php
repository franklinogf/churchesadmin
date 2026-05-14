<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\FlashMessageKey;
use App\Enums\ModelMorphName;
use App\Models\CalendarEvent;
use App\Models\Check;
use App\Models\CheckLayout;
use App\Models\Church;
use App\Models\ChurchWallet;
use App\Models\Email;
use App\Models\Expense;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\Offering;
use App\Models\OfferingType;
use App\Models\Scopes\CurrentYearScope;
use App\Models\TenantUser;
use App\Models\Visit;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\WalletConfigure;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Inertia\ExceptionResponse;
use Inertia\Inertia;
use Laravel\Pennant\Feature;
use Override;
use Spatie\Translatable\Facades\Translatable;

use function in_array;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Feature::resolveScopeUsing(fn (): ?Church => Church::current());
        $this->configureCommands();
        $this->configureDates();
        $this->configureModels();
        $this->configureValidations();
        $this->configureJsonResources();
        $this->configureMail();
        $this->configureInertiaExceptions();
        Translatable::fallback(
            fallbackAny: true
        );

        WalletConfigure::ignoreMigrations();

        URL::forceHttps(app()->isProduction());

        Transaction::addGlobalScope(CurrentYearScope::class);

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
            ModelMorphName::MEMBER->value => Member::class,
            ModelMorphName::MISSIONARY->value => Missionary::class,
            ModelMorphName::USER->value => TenantUser::class,
            ModelMorphName::CHURCH->value => Church::class,
            ModelMorphName::CHURCH_WALLET->value => ChurchWallet::class,
            ModelMorphName::OFFERING_TYPE->value => OfferingType::class,
            ModelMorphName::CHECK_LAYOUT->value => CheckLayout::class,
            ModelMorphName::EMAIL->value => Email::class,
            ModelMorphName::VISIT->value => Visit::class,
            ModelMorphName::EXPENSE->value => Expense::class,
            ModelMorphName::OFFERING->value => Offering::class,
            ModelMorphName::CHECK->value => Check::class,
            ModelMorphName::CALENDAR_EVENT->value => CalendarEvent::class,

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

    private function configureMail(): void
    {
        if (! app()->isProduction()) {
            Mail::alwaysTo('franklinomarflores@gmail.com');
        }
    }

    private function configureInertiaExceptions(): void
    {
        Inertia::handleExceptionsUsing(function (ExceptionResponse $response) {
            if (! app()->environment(['local', 'testing']) && in_array($response->statusCode(), [403, 404, 419, 500, 503], true)) {

                if ($response->statusCode() === 419) {
                    return back()->with(key: [
                        FlashMessageKey::MESSAGE->value => 'The page expired, please try again.',
                    ]);
                }

                return $response->render('error', [
                    'status' => $response->statusCode(),
                ]);
            }
        });
    }
}
