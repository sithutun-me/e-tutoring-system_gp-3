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
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Assuming a post belongs to a user
    }
    public function documents()
    {
        return $this->hasMany(Document::class, 'post_id');
    }
    
    
}
