<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('admin/config.php');
require_once('admin/models/medico.php');

$medico_model = new Medico();
$medicos = $medico_model->leer();

$alerta = null; 
if (isset($_SESSION['alerta'])) {
    $alerta = $_SESSION['alerta'];
    unset($_SESSION['alerta']);
}

require_once __DIR__ . '/header.php';
?>

<main id="main-content">
    <div class="container mt-5">
        <?php
        if ($alerta && isset($alerta['mensaje']) && isset($alerta['tipo'])) {
            echo '<div class="alert alert-' . htmlspecialchars($alerta['tipo']) . ' alert-dismissible fade show" role="alert" style="margin-top: 20px;">';
            echo htmlspecialchars($alerta['mensaje']);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
        }
        ?>
        <h1 class="text-center mb-4 section-title">Nuestros Médicos</h1>

        <div class="row">
            <?php foreach ($medicos as $medico): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card doctor-card">
                        <img src="uploads/<?php echo htmlspecialchars($medico['fotografia']); ?>" class="card-img-top" alt="Img médico <?php echo htmlspecialchars($medico['nombre_completo'] ?? ''); ?>">
                        <div class="card-body">
                            <h5 class="card-title">Dr. <?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['primer_apellido'] . ' ' . ($medico['segundo_apellido'] ?? '')); ?></h5>
                            <p class="card-text specialty"><?php echo htmlspecialchars($medico['especialidad']); ?></p>
                            <p class="card-text">
                                <a href="tel:+52<?php echo htmlspecialchars($medico['telefono']); ?>" style="text-decoration: none; color: inherit;">
                                    <i class="bi bi-telephone"></i> <?php echo htmlspecialchars($medico['telefono']); ?>
                                </a> <br>
                            </p>
                            <p class="card-text schedule">
                                <i class="bi bi-clock"></i> Horario: <br> <?php echo htmlspecialchars($medico['horario']); ?>
                            </p>
                            <a href="mailto:<?php echo htmlspecialchars($medico['correo'] ?? ''); ?>" class="btn btn-warning mt-2 w-100" style="margin-right: 10px;">
                                <i class="bi bi-envelope"></i> Email
                            </a>
                            <a href="https://wa.me/52<?php echo htmlspecialchars($medico['telefono']); ?>" class="btn btn-success mt-2 w-100" target="_blank">
                                <i class="bi bi-whatsapp"></i> WhatsApp 
                            </a>
                            <a href="admin/login.php?id_medico=<?php echo htmlspecialchars($medico['id_medico']); ?>" class="btn btn-primary mt-2 w-100">
                                <i class="bi bi-calendar-plus"></i> Agendar Cita
                            </a>
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