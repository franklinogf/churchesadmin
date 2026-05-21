<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

dataset('languages', [
    'Spanish' => 'es',
]);

it('has :dataset counterparts for all English translations keys (php files)', function (string $lang): void {
    if (! File::exists(lang_path($lang))) {
        $this->markTestSkipped("Directory for language: `{$lang}` does not exist.");
    }

    $enFiles = File::files(lang_path('en'));
    $errors = [];
    foreach ($enFiles as $enFile) {
        $filename = $enFile->getFilename();
        $enTranslations = require $enFile->getPathname();
        $file = lang_path("{$lang}/{$filename}");

        expect(File::exists($file))->toBeTrue("File: {$filename} does not exist for language: {$lang}");

        $langTranslations = require $file;
        $enKeys = array_keys(Arr::dot($enTranslations));
        $langKeys = array_keys(Arr::dot($langTranslations));

        $missingKeys = array_diff($enKeys, $langKeys);

        if ($missingKeys !== []) {
            $errors[] = sprintf(
                "File: %s\n  Missing keys (%d): %s",
                $filename,
                count($missingKeys),
                implode(', ', $missingKeys)
            );
        }
    }

    expect($errors)->toBeEmpty(
        "\n\nTranslation issues found in PHP files:\n\n".implode("\n\n", $errors)
    );
})->with('languages');

it('has :dataset counterparts for all English translations keys (json files)', function (string $lang): void {
    if (! File::exists(lang_path("{$lang}.json"))) {
        $this->markTestSkipped("JSON file for language: `{$lang}` does not exist.");
    }

    $enTranslations = File::get(lang_path('en.json'));

    $enKeys = array_keys(json_decode($enTranslations, true));
    $file = lang_path("{$lang}.json");

    expect(File::exists($file))->toBeTrue("File: {$lang}.json does not exist for language: {$lang}");

    $langTranslations = File::get($file);
    $langKeys = array_keys(json_decode($langTranslations, true));
    $missingKeys = array_diff($enKeys, $langKeys);

    expect($missingKeys)->toBeEmpty(
        "\n\nTranslation issues found in JSON file:\n\n".implode(', ', $missingKeys)
    );

})->with('languages');
