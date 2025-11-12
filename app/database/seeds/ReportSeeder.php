<?php

use Illuminate\Database\Seeder;
use App\Models\Report;

class ReportSeeder extends Seeder
{
    public function run()
    {
        factory(Report::class, 100)->create();
    }
}