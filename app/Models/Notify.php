<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Notify extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function  role()
    {
        return $this->belongsToMany(Role::class,'notify_roles','nottify_id','role_id');
    }
}
