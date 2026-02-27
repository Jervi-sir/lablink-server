<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'fullname',
        'student_card_id',
        'department_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function format()
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'fullName' => $this->fullname,
            'studentCardId' => $this->student_card_id,
            'departmentId' => $this->department_id,
            'department' => $this->department ? [
                'id' => $this->department->id,
                'name' => $this->department->name,
                'university' => $this->department->university ? [
                    'id' => $this->department->university->id,
                    'name' => $this->department->university->name,
                    'wilaya' => $this->department->university->wilaya ? [
                        'id' => $this->department->university->wilaya->id,
                        'name' => $this->department->university->wilaya->name,
                    ] : null,
                ] : null,
            ] : null,
        ];
    }
}
