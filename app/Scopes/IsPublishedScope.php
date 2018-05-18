<?php

namespace App\Scopes;

use App\Domain;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class IsPublishedScope implements Scope
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
//        $company = $builder->pluck('user_id', '1');
//        dd($company);
//        dd($builder->where('user_id', Auth::id())->get();
//        dd($builder->where('company', 'company'));
//        $authUser = Auth::user()->company;
//        dd($authUser->company);
//        $builder->where('company', 'gmail');
    }
}