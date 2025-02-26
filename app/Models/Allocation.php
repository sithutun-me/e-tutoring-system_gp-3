<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    //
  //  use HasFactory;
    protected $table = 'allocation'; 
   
    protected $fillable = [
        'student_id',
        'tutor_id',
        'allocation_date_time',
        'staff_id',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Assuming a post belongs to a user
    }
   
}
