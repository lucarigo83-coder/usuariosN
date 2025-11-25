<?php

namespace App\Repositories;

use App\Models\Usuario as UsuarioModel;
use App\Services\Domain\UsuarioDomain;
use Illuminate\Support\Facades\DB;

class UsuarioRepository
{

    public function iniciarTransaccion(): void
    {
        DB::beginTransaction();
    }

    public function confirmarTransaccion(): void
    {
        DB::commit();
    }

    public function fallarTransaccion(): void
    {
        DB::rollBack();
    }

    public function findByCorreoForUpdate(string $correo): ?UsuarioDomain
    {
        $m = UsuarioModel::where('correo', $correo)->lockForUpdate()->first();
        return $m ? $this->toDomain($m) : null;
    }

    public function save(UsuarioDomain $u): void
    {
        $m = UsuarioModel::firstWhere('correo', $u->getCorreo());

        /*if (!$m) {
            $m = new UsuarioModel();
            $m->correo = $u->getCorreo();
            $m->nombre_usuario = $u->getNombreUsuario();
        }*/

        $m->sesion_activa = $u->tieneSesionActiva();
        $m->intentos_fallidos = $u->getIntentosFallidos();
        $m->bloqueado = $u->estaMarcadoComoBloqueado();
        //$m->bloqueado_hasta = $u->getBloqueadoHasta();
        $m->bloqueado_hasta = $u->getBloqueadoHasta() ? $u->getBloqueadoHasta()->format('Y-m-d H:i:s') : null;
        $m->ultimo_intento = $u->getUltimoIntento() ? $u->getUltimoIntento()->format('Y-m-d H:i:s') : null;
        $m->save();
    }

    private function toDomain(UsuarioModel $m): UsuarioDomain
    {
        return new UsuarioDomain(
            $m->correo,
            $m->nombre_usuario,
            $m->sesion_activa,
            $m->intentos_fallidos,
            $m->bloqueado_hasta ? new \DateTime($m->bloqueado_hasta) : null,
            $m->ultimo_intento ? new \DateTime($m->ultimo_intento) : null,
            $m->bloqueado,
            $m->nip
        );
    }
}