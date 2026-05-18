<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class WarungScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        if ($user->role === 'super_admin') {
            return;
        }

        if (is_null($user->warung_id)) {
            return;
        }

        $builder->where($model->getTable() . '.warung_id', $user->warung_id);
    }
}
