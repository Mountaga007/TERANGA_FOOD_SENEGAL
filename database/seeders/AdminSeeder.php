<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer un rôle
        $role = Role::create([
            'nom_role' => 'admin'
        ]);

        // Créer un utilisateur et le lier au rôle créé
        $user = User::create([
            'prenom' => 'Mountaga',
            'nom' => 'BA',
            'telephone' => '771663714',
            'adresse' => 'Cité Millionnaire',
            'email' => 'mountaga889@gmail.com',
            'password' => bcrypt('mountaga123'),
            'role_id' => $role->id,
        ]);
    }
}
