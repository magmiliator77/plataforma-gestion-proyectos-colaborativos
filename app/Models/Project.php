<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title', 'description', 'deadline', 'status', 'user_id'];

    // El propietario del proyecto
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Los usuarios que participan, con los datos extra de la tabla pivote
    public function participants()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'contribution_hours')
            ->withTimestamps();
    }
}
