<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Comment extends Model
{
    use HasFactory;


    protected $table = 'comment';


    protected $fillable = [
        'text',
        'post_id',
        'user_id',
    ];

    // Define the relationship with the  model
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
