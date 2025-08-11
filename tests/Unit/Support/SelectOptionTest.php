<?php

declare(strict_types=1);

use App\Support\SelectOption;
use Illuminate\Database\Eloquent\Collection;
use Tests\Unit\Support\TestModel;

describe('SelectOption class', function (): void {
    it('returns an empty array if items is not a collection', function (): void {
        $result = SelectOption::create(null);

        expect($result)->toBe([]);
    });

    it('creates select options with default parameters', function (): void {
        $items = new Collection([
            new TestModel(['id' => 1, 'name' => 'Item 1']),
            new TestModel(['id' => 2, 'name' => 'Item 2']),
        ]);

        $result = SelectOption::create($items);

        expect($result)->toBe([
            ['value' => 1, 'label' => 'Item 1'],
            ['value' => 2, 'label' => 'Item 2'],
        ]);
    });

    it('creates select options with custom value and label fields', function (): void {
        $items = new Collection([
            new TestModel(['id' => 1, 'title' => 'Title 1']),
            new TestModel(['id' => 2, 'title' => 'Title 2']),
        ]);

        $result = SelectOption::create($items, 'id', 'title');

        expect($result)->toBe([
            ['value' => 1, 'label' => 'Title 1'],
            ['value' => 2, 'label' => 'Title 2'],
        ]);
    });

    it('creates select options with multiple label fields', function (): void {
        $items = new Collection([
            new TestModel(['id' => 1, 'name' => 'Name 1', 'title' => 'Title 1']),
            new TestModel(['id' => 2, 'name' => 'Name 2', 'title' => 'Title 2']),
        ]);

        $result = SelectOption::create($items, 'id', ['name', 'title'], ' - ');

        expect($result)->toBe([
            ['value' => 1, 'label' => 'Name 1 - Title 1'],
            ['value' => 2, 'label' => 'Name 2 - Title 2'],
        ]);
    });
});

describe('SelectOption createForMultiple', function (): void {
    it('returns an empty array if items is not a collection', function (): void {
        $result = SelectOption::createForMultiple('Test Heading', null);

        expect($result)->toBe([]);
    });

    it('creates multiple select options with default parameters', function (): void {
        $items = new Collection([
            new TestModel(['id' => 1, 'name' => 'Item 1']),
            new TestModel(['id' => 2, 'name' => 'Item 2']),
        ]);

        $result = SelectOption::createForMultiple('Test Heading', $items);

        expect($result)->toBe([
            'heading' => 'Test Heading',
            'model' => 'TestModel',
            'options' => [
                ['value' => 1, 'label' => 'Item 1'],
                ['value' => 2, 'label' => 'Item 2'],
            ],
        ]);
    });

    it('creates multiple select options with custom value and label fields', function (): void {
        $items = new Collection([
            new TestModel(['id' => 1, 'title' => 'Title 1']),
            new TestModel(['id' => 2, 'title' => 'Title 2']),
        ]);

        $result = SelectOption::createForMultiple('Test Heading', $items, 'id', 'title');

        expect($result)->toBe([
            'heading' => 'Test Heading',
            'model' => 'TestModel',
            'options' => [
                ['value' => 1, 'label' => 'Title 1'],
                ['value' => 2, 'label' => 'Title 2'],
            ],
        ]);
    });

    it('creates multiple select options with multiple label fields', function (): void {
        $items = new Collection([
            new TestModel(['id' => 1, 'name' => 'Name 1', 'description' => 'Desc 1']),
            new TestModel(['id' => 2, 'name' => 'Name 2', 'description' => 'Desc 2']),
        ]);

        $result = SelectOption::createForMultiple('Test Heading', $items, 'id', ['name', 'description'], ' | ');

        expect($result)->toBe([
            'heading' => 'Test Heading',
            'model' => 'TestModel',
            'options' => [
                ['value' => 1, 'label' => 'Name 1 | Desc 1'],
                ['value' => 2, 'label' => 'Name 2 | Desc 2'],
            ],
        ]);
    });
});
