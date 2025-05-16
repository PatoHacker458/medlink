<?php
    require_once (__DIR__.'/model.php');
    require_once (__DIR__.'/models/medico.php');
    require_once (__DIR__.'/models/paciente.php');
    require_once (__DIR__.'/models/staff.php');
    require_once (__DIR__.'/models/consultorio.php');
    require_once (__DIR__.'/models/cita.php'); 

    $web = new Model(); 
    $medicoModel = new Medico();
    $pacienteModel = new Paciente();
    $staffModel = new Staff();
    $consultorioModel = new Consultorio();
    $citaModel = new Cita();

    $web->checar('Portada'); 

    $totalMedicos = $medicoModel->MedicoCount();
    $totalPacientes = $pacienteModel->PacienteCount();
    $totalStaff = $staffModel->StaffCount();
    $totalConsultorios = $consultorioModel->ConsultorioCount();
    $totalCitasHoy = $citaModel->leerCitasNow();

    include_once (__DIR__.'/views/header.php');
?>

<div class="container mt-4">
    <h1 class="mb-3">Panel de Administración MedLink</h1>
    <p class="lead">Bienvenido/a, <?php echo htmlspecialchars($_SESSION['correo'] ?? 'Admin'); ?>. Resumen general del sistema:</p>

    <h2 class="h4 mb-3">Estadísticas Clave</h2>
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Médicos Registrados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalMedicos; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-heart-pulse-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                     <a href="medico.php" class="stretched-link"></a> </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pacientes Registrados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalPacientes; ?></div>
                        </div>
                        <div class="col-auto">
                             <i class="bi bi-person-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                     <a href="paciente.php" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-info shadow h-100 py-2">
                 <div class="card-body">
                     <div class="row no-gutters align-items-center">
                         <div class="col mr-2">
                             <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Personal (Staff)</div>
                             <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalStaff; ?></div>
                         </div>
                         <div class="col-auto">
                             <i class="bi bi-person-workspace fs-2 text-gray-300"></i>
                         </div>
                     </div>
                      <a href="staff.php" class="stretched-link"></a>
                 </div>
             </div>
         </div>

        <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-warning shadow h-100 py-2">
                 <div class="card-body">
                     <div class="row no-gutters align-items-center">
                         <div class="col mr-2">
                             <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Consultorios</div>
                             <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalConsultorios; ?></div>
                         </div>
                         <div class="col-auto">
                              <i class="bi bi-door-open-fill fs-2 text-gray-300"></i>
                         </div>
                     </div>
                     <a href="consultorio.php" class="stretched-link"></a>
                 </div>
             </div>
         </div>

         <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-danger shadow h-100 py-2">
                 <div class="card-body">
                     <div class="row no-gutters align-items-center">
                         <div class="col mr-2">
                             <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Citas para Hoy</div>
                             <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalCitasHoy; ?></div>
                         </div>
                         <div class="col-auto">
                              <i class="bi bi-calendar-check-fill fs-2 text-gray-300"></i>
                         </div>
                     </div>
                      <a href="cita.php" class="stretched-link"></a> </div>
             </div>
         </div>

    </div><h2 class="h4 mb-3">Accesos Rápidos</h2>
    <div class="list-group mb-4 shadow-sm">
        <a href="usuario.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="bi bi-people-fill me-2"></i>Gestionar Usuarios</span>
            <i class="bi bi-arrow-right-short"></i>
        </a>
        <a href="medico.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="bi bi-heart-pulse-fill me-2"></i>Gestionar Médicos</span>
            <i class="bi bi-arrow-right-short"></i>
        </a>
        <a href="paciente.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="bi bi-person-fill me-2"></i>Gestionar Pacientes</span>
            <i class="bi bi-arrow-right-short"></i>
        </a>
        <a href="staff.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="bi bi-person-workspace me-2"></i>Gestionar Staff</span>
            <i class="bi bi-arrow-right-short"></i>
        </a>
        <a href="consultorio.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="bi bi-door-open-fill me-2"></i>Gestionar Consultorios</span>
            <i class="bi bi-arrow-right-short"></i>
        </a>
        <a href="especialidad.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="bi bi-bookmark-star-fill me-2"></i>Gestionar Especialidades</span>
            <i class="bi bi-arrow-right-short"></i>
        </a>
        <a href="cita.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="bi bi-calendar-check-fill me-2"></i>Gestionar Citas</span>
            <i class="bi bi-arrow-right-short"></i>
        </a>
        <a href="rol.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="bi bi-person-badge-fill me-2"></i>Gestionar Roles</span>
            <i class="bi bi-arrow-right-short"></i>
        </a>
        <a href="permiso.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="bi bi-key-fill me-2"></i>Gestionar Permisos</span>
            <i class="bi bi-arrow-right-short"></i>
        </a>
        <a href="excel.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" target="_blank">
            <span><i class="bi bi-file-earmark-excel-fill me-2"></i>Generar Reporte Excel</span>
            <i class="bi bi-download"></i>
        </a>
    </div>

</div>
<?php
    include_once (__DIR__.'/views/footer.php');
?>