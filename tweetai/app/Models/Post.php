<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['autobot_id', 'title', 'body'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
