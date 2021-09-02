<?php

namespace Laraneat\Core\Abstracts\Models;

use Laraneat\Core\Traits\FactoryLocatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as LaravelEloquentModel;

abstract class Model extends LaravelEloquentModel
{
    use HasFactory, FactoryLocatorTrait {
        FactoryLocatorTrait::newFactory insteadof HasFactory;
    }
}
