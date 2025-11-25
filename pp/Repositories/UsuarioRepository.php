<?php

namespace App\Repositories;

use App\Models\Usuario;

class UsuarioRepository
{
    public function findByCorreoForUpdate(string $correo): ?Usuario
    {
        return Usuario::where('correo', $correo)->lockForUpdate()->first();
    }

    public function actualizarIntentos(Usuario $usuario, bool $reset = false)
    {
        if ($reset) {
            $usuario->intentos_fallidos = 0;
            $usuario->bloqueado = false;
            $usuario->bloqueado_hasta = null;
        }
        $usuario->ultimo_intento = now();
        $usuario->save();
    }

    public function bloquearUsuario(Usuario $usuario, int $minutosBloqueo = 30)
    {
        $usuario->bloqueado = true;
        $usuario->bloqueado_hasta = now()->addMinutes($minutosBloqueo);
        $usuario->intentos_fallidos = 0;
        $usuario->save();
    }

    public function marcarSesionActiva(Usuario $usuario, bool $activa)
    {
        $usuario->sesion_activa = $activa;
        $usuario->save();
    }
}