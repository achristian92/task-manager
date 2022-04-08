<?php

namespace Database\Seeders;

use App;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Illuminate\Database\Seeder;

class JgaSeeder extends SpreadsheetSeeder
{

    public function run()
    {
        $this->file = ['/database/seeds/jgav2.xls'];

        parent::run();
    }
}
