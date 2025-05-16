<?php
require_once(__DIR__ . '/../model.php');
require_once(__DIR__ . '/consultorio.php');
require_once(__DIR__ . '/medico.php');
require_once(__DIR__ . '/especialidad.php');

class Excel extends Model
{
    public function obtenerConsultoriosCompletos()
    {
        $web = new Consultorio();
        return $web->leer();
    }

    public function obtenerMedicosCompletos()
    {
        $web2 = new Medico();
        return $web2->leer();
    }

    public function obtenerEspecialidadesCompletas()
    {
        $web3 = new Especialidad();
        return $web3->leer();
    }
}
?>