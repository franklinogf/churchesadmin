<?php

declare(strict_types=1);

namespace App\Console\Commands;

use BackedEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

final class SyncEnums extends Command
{
    protected $signature = 'enums:sync {fileName?}';

    protected $description = 'Generate TypeScript enums from PHP enums';

    public function handle(): int
    {
        $fileName = $this->argument('fileName');

        $fileName = $fileName !== null ? Str::of($fileName)->trim()->whenEmpty(fn (): null => null) : null;

        $enumsPath = app_path('enums');

        $enums = file_exists($enumsPath)
                    ? collect(scandir($enumsPath))
                        ->filter(fn (string $file): bool => $fileName !== null ? $file === $fileName->value() && str_ends_with($file, '.php') : str_ends_with($file, '.php'))
                        ->mapWithKeys(fn (string $file): array => [
                            pathinfo($file, PATHINFO_FILENAME) => 'App\\Enums\\'.pathinfo($file, PATHINFO_FILENAME),
                        ])
                        ->toArray()
                    : [];
        $this->info('Found '.count($enums).' enum(s) to sync.');
        $outputPath = resource_path('js/enums');

        if (! is_dir($outputPath)) {
            mkdir($outputPath, 0755, true);
        }

        foreach ($enums as $name => $enum) {
            /** @var class-string<BackedEnum> $enum */
            $cases = collect($enum::cases())
                ->map(fn (BackedEnum $case): string => "  {$case->name} = '{$case->value}',")
                ->implode("\n");

            $ts = <<<TS
// Auto-generated from {$enum}. DO NOT EDIT MANUALLY.
export enum {$name} {
{$cases}
}

TS;

            file_put_contents("{$outputPath}/{$name}.ts", $ts);
        }

        $this->info('✅ TypeScript enums synced!');

        return self::SUCCESS;
    }
}
