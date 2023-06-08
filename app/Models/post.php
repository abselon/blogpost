<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;

    protected $table = 'post'; // specify the table name
    protected $fillable = ['title', 'body', 'users_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
