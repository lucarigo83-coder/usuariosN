<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #ffffff, #ffffff);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .card {
            background-color: #ffffff;
            color: #212529;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            max-width: 450px;
            width: 100%;
            text-align: center;
        }
        .avatar {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #007bff, #aa99c5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #fff;
            margin: 0 auto 1rem auto;
            font-weight: bold;
        }
        .user-info h2 {
            margin-bottom: 0.25rem;
            font-weight: 700;
        }
        .user-info p {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }
        .last-login {
            background-color: #f8f9fa;
            padding: 0.75rem;
            border-radius: 10px;
            font-size: 0.9rem;
            color: #495057;
            margin-bottom: 1.5rem;
        }
        .logout-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #bb2d3b;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="avatar">
            <?php echo e(strtoupper(substr($usuarioNombre, 0, 1))); ?>

        </div>

        <div class="user-info">
            <h2><?php echo e($usuarioNombre); ?></h2>
            <p><?php echo e($usuarioCorreo); ?></p>
        </div>

        <div class="last-login">
            <strong> Último acceso:</strong><br>
            <?php echo e($ultimoAcceso ? \Carbon\Carbon::parse($ultimoAcceso)->format('d/m/Y H:i:s') : 'Sin registros'); ?>

        </div>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn logout-btn w-100">
                 Cerrar sesión
            </button>
        </form>
    </div>

</body>
</html><?php /**PATH C:\Users\Jose Garcia\Downloads\SeguridadUsuariosN\resources\views/dashboard.blade.php ENDPATH**/ ?>