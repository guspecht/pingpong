<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'is_online',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
