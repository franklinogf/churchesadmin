<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

dataset('languages', [
    'Spanish' => 'es',
]);

it('has :dataset counterparts for all English translations keys (php files)', function (string $lang): void {
    $esFiles = File::files(lang_path('en'));
    $errors = [];
    foreach ($esFiles as $esFile) {
        $filename = $esFile->getFilename();
        $esTranslations = require $esFile->getPathname();
        $file = lang_path("{$lang}/{$filename}");

        expect(File::exists($file))->toBeTrue("File: {$filename} does not exist for language: {$lang}");

        $langTranslations = require $file;
        $esKeys = array_keys($esTranslations);
        $langKeys = array_keys($langTranslations);

        $missingKeys = array_diff($esKeys, $langKeys);

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
    $esTranslations = File::get(lang_path('en.json'));
    $esKeys = array_keys(json_decode($esTranslations, true));
    $file = lang_path("{$lang}.json");

    expect(File::exists($file))->toBeTrue("File: {$lang}.json does not exist for language: {$lang}");

    $langTranslations = File::get($file);
    $langKeys = array_keys(json_decode($langTranslations, true));
    $missingKeys = array_diff($esKeys, $langKeys);

    expect($missingKeys)->toBeEmpty(
        "\n\nTranslation issues found in JSON file:\n\n".implode(', ', $missingKeys)
    );

})->with('languages');
