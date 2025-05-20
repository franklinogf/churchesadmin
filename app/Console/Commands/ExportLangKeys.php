<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

final class ExportLangKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-lang-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate TypeScript translation keys';

    private ProgressBar $progressBar;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $separator = DIRECTORY_SEPARATOR;

        $this->info('Generating TypeScript translation keys...');
        $this->newLine(2);

        $keys = $this->getLangKeys();

        $this->newLine(2);

        $output = "export type TranslationKey =\n";
        $output .= collect($keys)
            ->map(fn (int|string $key): string => "  | '".str((string) $key)->replace("'", "\\'")."'")
            ->implode("\n");
        $output .= ";\n";

        $outputPath = resource_path("js{$separator}types{$separator}lang-keys.d.ts");
        file_put_contents($outputPath, $output);

        $this->info("TypeScript definition written to: $outputPath");
        $this->info('Done! Found '.count($keys).' unique translation keys.');

    }

    /**
     * Get the translation keys from the language files.
     *
     * @return list<int|string>
     */
    private function getLangKeys(): array
    {

        $this->progressBar = $this->output->createProgressBar();
        $this->progressBar->setFormat(' [%bar%] %percent:3s%% | %current%/%max% | %message%');
        $this->progressBar->start();

        $phpKeys = $this->getPhpFilesKeys();

        $jsonKeys = $this->getJsonFilesKeys();

        $keys = array_merge($jsonKeys, $phpKeys);
        $keys = array_unique($keys);
        sort($keys);
        $this->progressBar->setMessage('Finished processing files...');
        $this->progressBar->finish();

        return $keys;
    }

    /**
     * Get the translation keys from the JSON language file.
     *
     * @return list<int|string>
     */
    private function getJsonFilesKeys(): array
    {

        $jsonPath = lang_path('en.json');
        if (! file_exists($jsonPath)) {
            $this->info('JSON language file not found.');

            return [];
        }

        $this->progressBar->setMessage('Processing JSON files...');
        $keys = [];

        $jsonFile = file_get_contents($jsonPath);
        if ($jsonFile === false) {
            $this->error('Failed to read JSON language file.');

            return [];
        }
        /**
         * @var array<string, mixed> $json
         */
        $json = json_decode($jsonFile, true);
        $count = count(array_keys($json));
        $this->progressBar->setMaxSteps($this->progressBar->getMaxSteps() + $count);
        foreach (array_keys($json) as $key) {
            $keys[] = $key;
            $this->progressBar->advance();
        }

        return $keys;
    }

    /**
     * Get the translation keys from the PHP language files.
     *
     * @return array<int,string>
     */
    private function getPhpFilesKeys(): array
    {
        $this->progressBar->setMessage('Processing PHP files...');
        $keys = [];
        $separator = DIRECTORY_SEPARATOR;
        $excludedFiles = [
            'auth',
            'flash',
            'pagination',
            'passwords',
            'permission',
            'validation',
        ];
        $langPath = lang_path('en');

        $files = glob("{$langPath}{$separator}*.php");
        if ($files === false) {
            return [];
        }
        /**
         * @var string[] $phpFiles
         */
        $phpFiles = collect($files)
            ->filter(fn (string $file): bool => ! in_array(basename($file, '.php'), $excludedFiles))
            ->toArray();

        foreach ($phpFiles as $file) {
            $filename = basename($file, '.php');
            /**
             * @var array<mixed, mixed> $translations
             */
            $translations = include $file;
            $count = count(array_keys($translations));
            $this->progressBar->setMaxSteps($this->progressBar->getMaxSteps() + $count);
            $this->flattenLang($translations, $filename, $keys);
        }

        return $keys;
    }

    /**
     * Recursively flatten the language array.
     *
     * @param  array<mixed, mixed>  $array
     * @param  string  $prefix
     * @param  array<int, string>  $keys
     * @param  string  $parent
     */
    private function flattenLang(array $array, string $prefix, array &$keys, string $parent = ''): void
    {
        foreach ($array as $key => $value) {

            $fullKey = $parent !== '' && $parent !== '0' ? "$parent.$key" : "$prefix.$key";

            if (is_array($value)) {
                $this->flattenLang($value, $prefix, $keys, $fullKey);
            } else {
                $keys[] = $fullKey;
            }
            $this->progressBar->advance();
        }
    }
}
