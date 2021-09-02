<?php

namespace Laraneat\Core\Abstracts\Models;

use Laraneat\Core\Traits\FactoryLocatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as LaravelAuthenticatableUser;
use Spatie\Permission\Traits\HasRoles;

abstract class UserModel extends LaravelAuthenticatableUser
{
    use HasRoles;
    use HasFactory, FactoryLocatorTrait {
        FactoryLocatorTrait::newFactory insteadof HasFactory;
    }
}
