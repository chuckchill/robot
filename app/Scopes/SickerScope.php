<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/9/19
 * Time: 9:40
 */

namespace App\Scopes;


use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SickerScope implements Scope
{

    /**
     * @param Builder $builder
     * @param Model $model
     * @return $this|void
     */
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where('status', '=', 1);
    }
}