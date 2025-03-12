<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingSchedule extends Model
{
    //
  //  use HasFactory;
    protected $table = 'meeting_schedules';
    protected $fillable = [
        'meeting_title', 
        'meeting_description', 
        'meeting_type', 
        'meeting_platform', 
        'meeting_link',
        'meeting_location',
        'meeting_date',
        'meeting_start_time',
        'meeting_end_time',
        'meeting_status',
        'student_id',
        'tutor_id',
    ];
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    // Relationship to the tutor
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id', 'id');
    }



}
