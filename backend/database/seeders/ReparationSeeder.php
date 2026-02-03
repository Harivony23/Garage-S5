<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReparationSeeder extends Seeder
{
    public function run()
    {
        DB::table('reparations')->insert([
            ['voiture_id'=>1,'statut'=>'en_cours','slot'=>1,'started_at'=>now(),'ended_at'=>null,'total'=>0,'created_at'=>now(),'updated_at'=>now()],
            ['voiture_id'=>2,'statut'=>'en_attente','slot'=>2,'started_at'=>null,'ended_at'=>null,'total'=>0,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
