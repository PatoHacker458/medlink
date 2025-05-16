<?php
require_once(__DIR__ . '/../model.php');
require_once(__DIR__ . '/consultorio.php');
require_once(__DIR__ . '/medico.php');
require_once(__DIR__ . '/especialidad.php');

class Excel extends Model
{
    public function obtenerConsultoriosCompletos()
    {
        $consultorioModel = new Consultorio();
        return $consultorioModel->leer();
    }

    public function obtenerMedicosCompletos()
    {
        $medicoModel = new Medico();
        return $medicoModel->leer();
    }

    public function obtenerEspecialidadesCompletas()
    {
        $especialidadModel = new Especialidad();
        return $especialidadModel->leer(); //
    }
}
?>