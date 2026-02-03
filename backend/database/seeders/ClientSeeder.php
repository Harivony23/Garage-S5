<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run()
    {
        DB::table('clients')->insert([
            ['nom'=>'Jean Dupont','email'=>'jean@example.com','telephone'=>'0341234567','created_at'=>now(),'updated_at'=>now()],
            ['nom'=>'Marie Claire','email'=>'marie@example.com','telephone'=>'0327654321','created_at'=>now(),'updated_at'=>now()],
            ['nom'=>'Paul Martin','email'=>'paul@example.com','telephone'=>'0339876543','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
