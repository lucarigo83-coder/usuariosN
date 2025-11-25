<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UsuarioService;

class LoginController extends Controller
{
    private UsuarioService $service;

    public function __construct(UsuarioService $service)
    {
        $this->service = $service;
    }

    public function showLoginForm(Request $request)
    {
        if ($request->session()->has('usuario_correo')) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => [
                    'required',
                    'max:100',
                    'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
                ],
                'nip' => [
                    'required',
                    'digits_between:4,8', 
                ],
            ],
            [
                'email.required' => 'El correo es obligatorio.',
                'email.max' => 'El correo no puede tener más de 100 caracteres.',
                'email.regex' => 'Ingresa un correo válido, por ejemplo: usuario@example.com.',
                'nip.required' => 'El NIP es obligatorio.',
                'nip.digits_between' => 'El NIP debe tener entre 4 y 8 dígitos.',
            ]
        );

        $resultado = $this->service->login($request->email, $request->nip);

        if ($resultado['success']) {
            $usuario = $resultado['usuario'];

            session([
                'usuario_correo' => $usuario->getCorreo(),
                'usuario_nombre' => $usuario->getNombreUsuario(),
                'ultimo_acceso' => $usuario->getUltimoIntento(),
            ]);

            return redirect()->route('dashboard')->with('success', 'Bienvenido');
        }

        return back()->withErrors(['error' => $resultado['message']])->withInput();
    }

    public function dashboard(Request $request)
    {
        $correo = session('usuario_correo');
        $nombre = session('usuario_nombre');
        $ultimoAcceso = session('ultimo_acceso');

        if (!$correo) {
            return redirect()->route('login')->withErrors(['error' => 'Debes iniciar sesión primero']);
        }

        return view('dashboard', [
            'usuarioCorreo' => $correo,
            'usuarioNombre' => $nombre,
            'ultimoAcceso' => $ultimoAcceso,
        ]);
    }

    public function logout(Request $request)
    {
        $correo = session('usuario_correo');
        if ($correo) {
            $this->service->logoutPorCorreo($correo);
        }

        $request->session()->forget(['usuario_correo', 'usuario_nombre', 'ultimo_acceso']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}