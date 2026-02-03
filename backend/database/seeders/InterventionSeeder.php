<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterventionSeeder extends Seeder
{
    public function run()
    {
        DB::table('interventions')->insert([
            ['nom'=>'Frein','prix'=>150000,'duree_minutes'=>720,'created_at'=>now(),'updated_at'=>now()],
            ['nom'=>'Vidange','prix'=>80000,'duree_minutes'=>540,'created_at'=>now(),'updated_at'=>now()],
            ['nom'=>'Filtre','prix'=>50000,'duree_minutes'=>360,'created_at'=>now(),'updated_at'=>now()],
            ['nom'=>'Batterie','prix'=>200000,'duree_minutes'=>1080,'created_at'=>now(),'updated_at'=>now()],
            ['nom'=>'Amortisseurs','prix'=>300000,'duree_minutes'=>1440,'created_at'=>now(),'updated_at'=>now()],
            ['nom'=>'Embrayage','prix'=>500000,'duree_minutes'=>1800,'created_at'=>now(),'updated_at'=>now()],
            ['nom'=>'Pneus','prix'=>250000,'duree_minutes'=>900,'created_at'=>now(),'updated_at'=>now()],
            ['nom'=>'Système de refroidissement','prix'=>350000,'duree_minutes'=>1260,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
