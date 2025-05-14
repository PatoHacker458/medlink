<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/styles-index.css">
    <title>MEDLINK ADMIN</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin/views/marca/index.php">Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            CATÁLOGOS
                        </a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="consultorio.php">Consultorios</a></li>
                        <li><a class="dropdown-item" href="especialidad.php">Especialidades</a></li>
                        <li><a class="dropdown-item" href="cita.php">Citas</a></li>
                        <li><a class="dropdown-item" href="transaccion.php">Transacciones</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            USUARIOS
                        </a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="usuario.php">Usuarios</a></li>
                        <li><a class="dropdown-item" href="medico.php">Médicos</a></li>
                        <li><a class="dropdown-item" href="staff.php">Staff</a></li>
                        <li><a class="dropdown-item" href="paciente.php">Pacientes</a></li>
                        <li><a class="dropdown-item" href="rol.php">Roles</a></li>
                        <li><a class="dropdown-item" href="permiso.php">Permisos</a></li>

                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>