<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BerandaSeeder extends Seeder
{
    public function run()
    {
        DB::table('beranda')->insert([
            'instagram'     => 'pbtcmasmansur',
            'email'         => 'pesma@ums.ac.id',
            'whatsapp'      => '+6282122687848',
            'location'      => 'Jl. Garuda Mas, Gonilan, Kartasura, Sukoharjo, Jawa Tengah, 577102',
            'maps_link'     => 'https://maps.app.goo.gl/afrXA7gQ1WnguH2LA',

            'slider1_image' => 'img/bg-masjid.jpg',
            'slider1_text'  => 'Pesma Inn
KH. Mas Mansur', 
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
    }
}