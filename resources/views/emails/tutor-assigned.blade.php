<p>Dear {{ $tutor->first_name }} {{ $tutor->last_name }},</p>
<p>The following students have been assigned to you:</p>
<ul>
@foreach($students as $student)
    <li>{{ $student->first_name }} {{ $student->last_name }} ({{ $student->email }})</li>
@endforeach
</ul>
<p>Thank you for your dedication.</p>
<p>Best regards,<br>TripleEDU Team</p>
