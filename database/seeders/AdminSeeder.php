<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin; // Ou Admin, dependendo do seu modelo

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crie o usuário admin
        Admin::create([ // Ou Admin::create() se você estiver usando um modelo Admin
            'name' => 'Seara',
            'email' => 'seara@gmail.com',
            'password' => bcrypt('123456'), // Criptografa a senha
            
        ]);
    }
}
