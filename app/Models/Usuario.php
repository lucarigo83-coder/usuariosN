<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model {


    protected $table = 'usuarios';
    public $timestamps = false; 

    protected $fillable = [
        'correo',
        'nombre_usuario',
        'nip',
        'sesion_activa',
        'intentos_fallidos',
        'ultimo_intento',
        'bloqueado',
        'bloqueado_hasta',
    ];

    protected $hidden = ['nip', 'remember_token'];

}
