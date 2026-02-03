<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ClientSeeder::class,
            InterventionSeeder::class,
            VoitureSeeder::class,
            ReparationSeeder::class,
            ReparationInterventionSeeder::class,
            PaiementSeeder::class,
        ]);
    }
}
