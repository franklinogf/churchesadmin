<?php

namespace Tests\Unit\Support;

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;

final class TestModel extends Model
{
    protected $fillable = ['id', 'name', 'title', 'description'];

    public function getMorphClass()
    {
        return 'TestModel';
    }
}
