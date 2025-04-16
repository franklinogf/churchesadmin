<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;
use Rector\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap/app.php',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withSkip([
        ReturnBinaryOrToEarlyReturnRector::class,
        RemoveUselessReturnTagRector::class,
        RemoveUnreachableStatementRector::class => [
            __DIR__.'/app/Policies',
        ],
        RemoveAlwaysTrueIfConditionRector::class => [
            __DIR__.'/app/Policies',
        ],

    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
        strictBooleans: true,
    )
    ->withPhpSets();
