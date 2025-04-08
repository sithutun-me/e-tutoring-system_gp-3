<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $table = 'page_views';

    protected $fillable = [
        'page_name',
        'view_count'
    ];
    public $timestamps = true;
}
