<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    // Relationship to the student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    // Relationship to the tutor
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id', 'id');
    }

    // Relationship to the staff
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'id');
    }

}
