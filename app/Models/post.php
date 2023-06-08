<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class post extends Model
{
    use Searchable;
    use HasFactory;

    protected $table = 'post'; // specify the table name
    protected $fillable = ['title', 'body', 'users_id'];

    public function toSearchableArray() {
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
