<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $months = [
            ['name' => 'janvier'],
            ['name' => 'février'],
            ['name' => 'mars'],
            ['name' => 'avril'],
            ['name' => 'mai'],
            ['name' => 'juin'],
            ['name' => 'juillet'],
            ['name' => 'août'],
            ['name' => 'septembre'],
            ['name' => 'octobre'],
            ['name' => 'novembre'],
            ['name' => 'décembre'],
        ];
        DB::table('month_nanes')->insert($months);
    }
}
