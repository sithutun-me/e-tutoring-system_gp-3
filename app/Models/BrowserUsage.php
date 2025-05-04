<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrowserUsage extends Model
{
    protected $table = 'browser_logs';

    protected $fillable = [
        'browser',
        
        'visited_at'
    ];
    public $timestamps = true;
}
