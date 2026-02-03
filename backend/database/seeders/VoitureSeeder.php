<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoitureSeeder extends Seeder
{
    public function run()
    {
        DB::table('voitures')->insert([
            ['client_id'=>1,'marque'=>'Toyota','modele'=>'Corolla','statut'=>'en_attente','created_at'=>now(),'updated_at'=>now()],
            ['client_id'=>2,'marque'=>'Peugeot','modele'=>'208','statut'=>'en_attente','created_at'=>now(),'updated_at'=>now()],
            ['client_id'=>3,'marque'=>'Renault','modele'=>'Clio','statut'=>'en_attente','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
