<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Document extends Model
{
    use HasFactory;

    // Define the table name (optional, Laravel assumes it's the plural of the model name)
    protected $table = 'document';

    // Define fillable fields
    protected $fillable = [
        'doc_file_path',
        'doc_size',
        'doc_name',
        'post_id',
    ];

    // Define the relationship with the Post model
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

}
