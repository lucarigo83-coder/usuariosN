<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario; 

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            ['correo' => 'juan.perez@example.com', 'nombre_usuario' => 'juanp'],
            ['correo' => 'maria.lopez@example.com', 'nombre_usuario' => 'marial'],
            ['correo' => 'pedro.garcia@example.com', 'nombre_usuario' => 'pedrog'],
        ];

        foreach ($usuarios as $usuario) {
            $nip = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

            DB::table('usuarios')->insert([
                'correo' => $usuario['correo'],
                'nombre_usuario' => $usuario['nombre_usuario'],
                'nip' => Hash::make($nip), 
                'sesion_activa' => false,
                'intentos_fallidos' => 0,
                'ultimo_intento' => null,
                'bloqueado' => false,
                'bloqueado_hasta' => null,
            ]);
            $this->command->info("Usuario {$usuario['nombre_usuario']} creado con NIP: $nip");
        }
    }
}