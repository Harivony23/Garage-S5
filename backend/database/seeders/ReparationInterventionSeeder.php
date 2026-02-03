<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReparationInterventionSeeder extends Seeder
{
    public function run()
    {
        DB::table('reparation_intervention')->insert([
            ['reparation_id'=>1,'intervention_id'=>1,'statut'=>'en_cours','progression'=>50,'created_at'=>now(),'updated_at'=>now()],
            ['reparation_id'=>1,'intervention_id'=>2,'statut'=>'en_attente','progression'=>0,'created_at'=>now(),'updated_at'=>now()],
            ['reparation_id'=>2,'intervention_id'=>3,'statut'=>'en_attente','progression'=>0,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
