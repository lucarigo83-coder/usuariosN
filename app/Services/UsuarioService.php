<?php

namespace App\Services;

use App\Repositories\UsuarioRepository;
use App\Services\Domain\UsuarioDomain;
use Exception;
class UsuarioService
{
    private UsuarioRepository $repo;
    private int $maxIntentos = 3;
    private int $minutosBloqueo = 30;

    public function __construct(UsuarioRepository $repo)
    {
        $this->repo = $repo;
    }

    public function login(string $correo, string $nip): array
    {
        $this->repo->iniciarTransaccion();
        try {
            $usuario = $this->repo->findByCorreoForUpdate($correo);

            if (!$usuario) {
                throw new Exception('Usuario no encontrado');            
            }

            if ($usuario->estaMarcadoComoBloqueado() && !$usuario->estaBloqueado()) {
                $usuario->desbloquear();
                $this->repo->save($usuario);
            }

            if ($usuario->estaBloqueado()) {
                throw new Exception('Cuenta bloqueada temporalmente');
            }

            if ($usuario->tieneSesionActiva()) {
                throw new Exception('Ya hay una sesión activa');
            }

            if ($usuario->verificarNip($nip)) {
                $usuario->registrarExito();
                $this->repo->save($usuario);
                $this->repo->confirmarTransaccion();
                return ['success' => true, 'usuario' => $usuario];
            }

            //Si el NIP es incorrecto
            $usuario->incrementarIntentos();

            if ($usuario->getIntentosFallidos() >= $this->maxIntentos) {
                $usuario->bloquearPorMinutos($this->minutosBloqueo);
                $this->repo->save($usuario);
                throw new Exception('Cuenta bloqueada por intentos fallidos');
            }

            $this->repo->save($usuario);
            $this->repo->confirmarTransaccion();
            return ['success' => false, 'message' => 'NIP incorrecto'];
        }
         catch (Exception $e) {
            $this->repo->fallarTransaccion();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function logoutPorCorreo(string $correo): bool
    {
        $this->repo->iniciarTransaccion();
        try {
            $usuario = $this->repo->findByCorreoForUpdate($correo);
            if (!$usuario){
                throw new Exception('Usuario no encontrado');
            }

            $usuario->desactivarSesion();
            $this->repo->save($usuario);
            $this->repo->confirmarTransaccion();
            return true;
        } catch (Exception $e) {
            $this->repo->fallarTransaccion();
            return false;
        }
    }
}