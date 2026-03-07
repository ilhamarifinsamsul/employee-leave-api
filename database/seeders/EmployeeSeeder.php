<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'nik' => 'EMP001',
                'name' => 'Ahmad Wijaya',
                'department' => 'QA',
                'leave_balance' => 12,
            ],
            [
                'nik' => 'EMP002', 
                'name' => 'Siti Nurhaliza',
                'department' => 'Sewing',
                'leave_balance' => 12,
            ],
            [
                'nik' => 'EMP003',
                'name' => 'Budi Santoso',
                'department' => 'QA',
                'leave_balance' => 12,
            ],
            [
                'nik' => 'EMP004',
                'name' => 'Diana Putri',
                'department' => 'Cutting',
                'leave_balance' => 12,
            ],
            [
                'nik' => 'EMP005',
                'name' => 'Rudi Hartono',
                'department' => 'Finishing',
                'leave_balance' => 2,
            ],
            [
                'nik' => 'EMP006',
                'name' => 'Maya Anggraini',
                'department' => 'Sewing',
                'leave_balance' => 12,
            ],
            [
                'nik' => 'EMP007',
                'name' => 'Fajar Kurniawan',
                'department' => 'QA',
                'leave_balance' => 12,
            ],
            [
                'nik' => 'EMP008',
                'name' => 'Ratna Sari',
                'department' => 'Finishing',
                'leave_balance' => 12,
            ],
            [
                'nik' => 'EMP009',
                'name' => 'Hendra Pratama',
                'department' => 'Cutting',
                'leave_balance' => 12,
            ],
            [
                'nik' => 'EMP010',
                'name' => 'Lisa Permata',
                'department' => 'Sewing',
                'leave_balance' => 12,
            ],
        ];

        foreach ($employees as $employee) {
            Employee::firstOrCreate(
                ['nik' => $employee['nik']], // search criteria
                $employee // attributes to create if not found
            );
        }
    }
}
