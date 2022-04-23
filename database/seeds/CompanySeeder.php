<?php

namespace Database\Seeders;

use App\Repositories\Companies\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run() {
        Company::create([
            'name'         => 'JGA CONSULTORES',
            'ruc'          => '20602987532',
            'address'      => 'AVENIDA MARISCAL LA MAR, 662 OFICINA 404 - MIRAFLORES',
            'telephone'    => '(511) 491-5250',
            'email'        => 'INFOPERU@JGA.PE',
            'password'     => bcrypt('password'),
            'raw_password' => 'password',
            'src_img'     => 'https://brainbox20201126.s3.amazonaws.com/general/CZSpbuiLAPjpL7aLOGOJBAA.png'
        ]);

        Company::create([
            'name'         => 'Brainbox',
            'address'      => 'Av. Javier Prado Oeste N° 1968',
            'telephone'    => '+51 989580857',
            'email'        => 'ventas@brainbox.pe',
            'password'     => bcrypt('password'),
            'raw_password' => 'password',
        ]);

        Company::create([
            'name'         => 'Tavera',
            'address'      => 'Av. Pachacutec 1234',
            'telephone'    => '979322304',
            'email'        => 'aruiz@tavera.pe',
            'password'     => bcrypt('password'),
            'raw_password' => 'password',
        ]);
    }

}
