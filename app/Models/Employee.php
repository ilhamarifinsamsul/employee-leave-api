<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'department',
        'leave_balance'
    ];

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

}
