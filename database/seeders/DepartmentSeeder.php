<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Department::whereIn('name', ['DREPT', 'SMNHS'])->delete();

        $departments = [
            ['name' => 'CCSICT', 'map_label' => 'College of Computer Studies, Information and Communications Technology'],
            ['name' => 'CED', 'map_label' => 'College of Education'],
            ['name' => 'CBM', 'map_label' => 'College of Business and Management'],
            ['name' => 'PS', 'map_label' => 'PS'],
            ['name' => 'SAS', 'map_label' => 'Student Affairs Services'],
            ['name' => 'CCJE', 'map_label' => 'College of Criminal Justice Education'],
            ['name' => 'AGRI', 'map_label' => 'College of Agriculture'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['name' => $department['name']],
                ['map_label' => $department['map_label']]
            );
        }
    }
}