<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;
use Rector\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector;
use Rector\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector;
use RectorLaravel\Set\LaravelSetProvider;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap/app.php',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withSkip([
        __DIR__.'/app/Filament',
        __DIR__.'/app/Providers/Filament',
        __DIR__.'/app/Providers/TenancyServiceProvider.php',
        ReturnBinaryOrToEarlyReturnRector::class,
        RemoveUselessReturnTagRector::class,
        RemoveUnreachableStatementRector::class => [
            __DIR__.'/app/Policies',
        ],
        RemoveAlwaysTrueIfConditionRector::class => [
            __DIR__.'/app/Policies',
        ],
        PrivatizeFinalClassMethodRector::class => [
            __DIR__.'/app/Models',
        ],
        SimplifyRegexPatternRector::class,
        EncapsedStringsToSprintfRector::class,
        SeparateMultiUseImportsRector::class,

    ])
    ->withImportNames(removeUnusedImports: true)
    ->withSetProviders(LaravelSetProvider::class)
    ->withComposerBased(laravel: true/** other options */)
    // ->withTypeCoverageDocblockLevel(1)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
        codingStyle: true,
        typeDeclarationDocblocks: true
    );
