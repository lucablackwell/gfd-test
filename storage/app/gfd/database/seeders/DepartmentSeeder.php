<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create(['name' => 'Sales']);
        Department::create(['name' => 'DevOps']);
        Department::create(['name' => 'Support']);
        Department::create(['name' => 'Brand']);
        Department::create(['name' => 'Order Processing']);
        Department::create(['name' => 'Quality Engineering']);
    }
}
