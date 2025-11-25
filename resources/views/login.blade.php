<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .error-general {
            background-color: #f8d7da;
            color: #842029;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .text-error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card p-4">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">¡Bienvenido!</h2>
                        <h4 class="text-center mb-4 text-muted">Inicio de Sesión</h4>

   
                        @if($errors->has('error'))
                            <div class="error-general">
                                {{ $errors->first('error') }}
                            </div>
                        @endif

                        <form method="POST" action="/login">
                            @csrf
                            
  
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo:</label>
                                <input 
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}">
                                @error('email')
                                    <small class="text-error">{{ $message }}</small>
                                @enderror
                            </div>
                            

                            <div class="mb-4">
                                <label for="nip" class="form-label">NIP / Contraseña:</label>
                                <input type="password"
                                       class="form-control @error('nip') is-invalid @enderror"
                                       id="nip"
                                       name="nip">
                                @error('nip')
                                    <small class="text-error">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>