<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaiementSeeder extends Seeder
{
    public function run()
    {
        DB::table('paiements')->insert([
            ['reparation_id'=>1,'montant'=>230000,'statut'=>'en_attente','paid_at'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['reparation_id'=>2,'montant'=>50000,'statut'=>'en_attente','paid_at'=>null,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
