<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DeleteScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $constants = (new \ReflectionClass($model::class))->getConstants();
        if (isset($constants['DELETE_FLAG']) && $constants['DELETE_FLAG'] !== "") {
            $deleteFlag = $constants['DELETE_FLAG'];
            $builder->where($model->getTable() . '.' . $deleteFlag, "=", 0);
        }
    }

}
