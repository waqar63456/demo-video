<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

class newusers extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use HasFactory;
    protected $table='newusers';
    Protected $guarded=[];

    public function orderPackageProcesses()
    {
        return $this->hasMany(PackagesProcess::class, 'user_id');
    }

    public function userPackageProcesses()
    {
        return $this->hasMany(UserPackages::class, 'user_id');
    }
    public function userPaymentProcesses()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

}
