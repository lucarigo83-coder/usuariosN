<?php

namespace App\Services\Domain;

use Illuminate\Support\Facades\Hash;


class UsuarioDomain
{
    private string $correo;
    private string $nombreUsuario;
    private bool $sesionActiva;
    private int $intentosFallidos;
    private ?\DateTime $bloqueadoHasta;
    private ?\DateTime $ultimoIntento;
    private bool $bloqueado;
    private ?string $nipHash;

    public function __construct(string $correo, string $nombreUsuario, bool $sesionActiva, int $intentosFallidos, ?\DateTime $bloqueadoHasta, ?\DateTime $ultimoIntento,
                                bool $bloqueado, ?string $nipHash = null) 
    {
        $this->correo = $correo;
        $this->nombreUsuario = $nombreUsuario;
        $this->sesionActiva = $sesionActiva;
        $this->intentosFallidos = $intentosFallidos;
        $this->bloqueadoHasta = $bloqueadoHasta;
        $this->ultimoIntento = $ultimoIntento;
        $this->bloqueado = $bloqueado;
        $this->nipHash = $nipHash;
    }

    // ===== Getters =====
    public function getCorreo(): string { 
        return $this->correo; 
    }
    public function getNipHash(): ?string {
         return $this->nipHash; 
    }

    public function getNombreUsuario(): string { 
        return $this->nombreUsuario; 
    }

    public function getIntentosFallidos(): int { 
        return $this->intentosFallidos; 
    }

    public function getBloqueadoHasta(): ?\DateTime { 
        return $this->bloqueadoHasta; 
    }

    public function getUltimoIntento(): ?\DateTime { 
        return $this->ultimoIntento; 
    }

    public function estaMarcadoComoBloqueado(): bool { 
        return $this->bloqueado; 
    }

    public function tieneSesionActiva(): bool { 
        return $this->sesionActiva; 
    }

    public function verificarNip(string $nip): bool
    {
        return $this->nipHash ? Hash::check($nip, $this->nipHash) : false;
    }

    public function estaBloqueado(): bool
    {
        if ($this->bloqueado && $this->bloqueadoHasta) {
            return new \DateTime() < $this->bloqueadoHasta;
        }
        return false;
    }

    public function registrarExito(): void
    {
        $this->intentosFallidos = 0;
        $this->bloqueado = false;
        $this->bloqueadoHasta = null;
        $this->sesionActiva = true;
        $this->ultimoIntento = new \DateTime();
    }

    public function incrementarIntentos(): void
    {
        $this->intentosFallidos++;
        $this->ultimoIntento = new \DateTime();
    }

    public function bloquearPorMinutos(int $minutos): void
    {
        $this->bloqueado = true;
        $this->bloqueadoHasta = (new \DateTime())->modify("+{$minutos} minutes");
        $this->intentosFallidos = 0;
        $this->ultimoIntento = new \DateTime();
    }

    public function desbloquear(): void
    {
        $this->bloqueado = false;
        $this->bloqueadoHasta = null;
    }

    public function setSesionActiva(bool $valor): void
    {
        $this->sesionActiva = $valor;
    }

    public function desactivarSesion(): void
    {
        $this->sesionActiva = false;
    }
}