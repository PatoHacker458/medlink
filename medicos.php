<?php
    require_once ('admin/config.php');
    require_once ('admin/models/medico.php');

    $medico_model = new Medico();
    $medicos = $medico_model->leer();
    require_once __DIR__ . '/header.php';
?>


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