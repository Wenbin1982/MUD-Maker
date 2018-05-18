<?php

namespace App;

use App\Scopes\IsPublishedScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class MudFile extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function controllerAccess()
    {
        return $this->hasMany(ControllerAccess::class, 'json_id', 'id');
    }

    public function controllersSpecific()
    {
        return $this->hasMany(ControllersSpecific::class, 'json_id', 'id');
    }

    public function device()
    {
        return $this->hasMany(Device::class, 'json_id', 'id');
    }

    public function internetCommunication()
    {
        return $this->hasMany(InternetCommunication::class, 'json_id', 'id');
    }

    public function localCommunication()
    {
        return $this->hasMany(LocalCommunication::class, 'json_id', 'id');
    }

    public function specificType()
    {
        return $this->hasMany(SpecificType::class, 'json_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopePopular($query, $domain)
    {
        return $query->where('domain', $domain);
    }

}
