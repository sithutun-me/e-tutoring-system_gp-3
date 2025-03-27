<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
  //  use HasFactory;
    protected $table = 'post';

    protected $fillable = [
        'post_title',
        'post_description',
        'post_status',
        'post_create_by',
        'post_received_by',
        'is_meeting'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'post_create_by', 'id'); // Assuming a post belongs to a user
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'post_received_by', 'id'); // Assuming a post belongs to a user
    }
    public function documents()
    {
        return $this->hasMany(Document::class, 'post_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

}
