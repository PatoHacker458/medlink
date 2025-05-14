<?php
    require_once ('admin/config.php');
    require_once ('admin/models/medico.php');

    $medico_model = new Medico();
    $medicos = $medico_model->leer();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles-medicos.css">
    <title>MEDLINK - Médicos</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" id="Logo">MedLink</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="perfil.php">Mi Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="medicos.php">Médicos</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main id="main-content">
        <div class="container mt-5">
            <h1 class="text-center mb-4 section-title">Nuestros Médicos</h1>

            <div class="row">
                <?php foreach ($medicos as $medico): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card doctor-card">
                            <img src="../uploads/<?php echo $medico['fotografia']?>" class="card-img-top" alt="Img médico">
                            <div class="card-body">
                                <h5 class="card-title">Dr. <?php echo $medico['nombre'] . ' ' . $medico['primer_apellido'] . ' ' . $medico['segundo_apellido']; ?></h5>
                                <p class="card-text specialty"><?php echo $medico['especialidad']; ?></p>
                                <p class="card-text">
                                    <i class="bi bi-telephone"></i> <?php echo $medico['telefono']; ?> <br>
                                    </p>
                                <p class="card-text schedule">
                                    <i class="bi bi-clock"></i> Horario: <br> <?php echo $medico['horario']; ?>
                                </p>
                                <a href="#" class="btn btn-primary">Agendar Cita</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer class="bg-light text-center py-3 text-muted">
        <p class="mb-1">Contacto:</p>
        <p class="mb-1">Teléfono: +52 461 614 7341</p>
        <p class="mb-1">Dirección: Blvrd Adolfo López Mateos 1301, Renacimiento, 38040 Celaya, Gto.</p>
        <p class="mb-1">&copy; MediLink 2025 todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
        </script>
</body>

</html>