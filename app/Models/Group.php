<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
        'owner_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}