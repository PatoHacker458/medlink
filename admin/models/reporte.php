<?php
require_once (__DIR__.'/../model.php');
require_once (__DIR__.'/cita.php');

class Reporte extends Model{

    function encabezado(){
            $encabezado = '';
            return $encabezado;
        }

        function pie(){
            $pie = '';
            return $pie;
        }

        function a4(){
            return ['mode' => 'utf-8',
                    'format' => 'A4',
                    'default_font_size' => 12,
                    'default_font' => 'Arial',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 10,
                    'margin_bottom' => 10,
                    'orientation' => 'P'];
        }

        function letter(){
            return ['mode' => 'utf-8',
                    'format' => 'letter',
                    'default_font_size' => 12,
                    'default_font' => 'Arial',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 10,
                    'margin_bottom' => 10,
                    'orientation' => 'P'];
        }

    public function generarHtmlCita($id_cita) {
        $web = new Cita();
        $cita = $web->leerCitaCompleta($id_cita); 

        if (!$cita) {
            return "<p>Error: No se encontró la cita con ID {$id_cita}.</p>";
        }

        $html = "<style>
                    body { font-family: Arial, sans-serif; font-size: 12px; }
                    .report-title { text-align: center; color: #333; margin-bottom: 20px; }
                    .section-title { font-size: 14px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; color: #007bff; }
                    .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    .data-table th, .data-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    .data-table th { background-color: #f2f2f2; }
                 </style>";

       

        $html .= "<br><div class='section-title'>Información General de la Cita</div>";
        $html .= "<table class='data-table'>";
        $html .= "<tr><th>ID Cita:</th><td>" . htmlspecialchars($cita['id_cita']) . "</td></tr>";
        $html .= "<tr><th>Fecha:</th><td>" . htmlspecialchars(date("d/m/Y", strtotime($cita['fecha']))) . "</td></tr>";
        $html .= "<tr><th>Hora:</th><td>" . htmlspecialchars(date("h:i A", strtotime($cita['hora']))) . "</td></tr>";
        $html .= "<tr><th>Descripción:</th><td>" . nl2br(htmlspecialchars($cita['descripcion'] ?? 'N/A')) . "</td></tr>";
        $html .= "<tr><th>Precio:</th><td>$" . htmlspecialchars(number_format($cita['precio'] ?? 0, 2)) . " MXN</td></tr>";
        $html .= "</table>";

        $html .= "<div class='section-title'>Información del Paciente</div>";
        $html .= "<table class='data-table'>";
        $html .= "<tr><th>Nombre:</th><td>" . htmlspecialchars($cita['paciente_nombre_completo'] ?? 'N/A') . "</td></tr>";
        $html .= "<tr><th>Teléfono:</th><td>" . htmlspecialchars($cita['paciente_telefono'] ?? 'N/A') . "</td></tr>";
        $html .= "<tr><th>Correo:</th><td>" . htmlspecialchars($cita['paciente_correo'] ?? 'N/A') . "</td></tr>";
        $html .= "</table>";

        $html .= "<div class='section-title'>Información del Médico</div>";
        $html .= "<table class='data-table'>";
        $html .= "<tr><th>Nombre:</th><td>" . htmlspecialchars($cita['medico_nombre_completo'] ?? 'N/A') . "</td></tr>";
        $html .= "<tr><th>Especialidad:</th><td>" . htmlspecialchars($cita['medico_especialidad'] ?? 'N/A') . "</td></tr>";
        $html .= "<tr><th>Licencia:</th><td>" . htmlspecialchars($cita['medico_licencia'] ?? 'N/A') . "</td></tr>";
        $html .= "</table>";

        $html .= "<div class='section-title'>Información del Consultorio</div>";
        $html .= "<table class='data-table'>";
        $html .= "<tr><th>Descripción:</th><td>" . htmlspecialchars($cita['consultorio_descripcion'] ?? 'N/A') . "</td></tr>";
        $html .= "<tr><th>Ubicación:</th><td>Piso " . htmlspecialchars($cita['consultorio_piso'] ?? 'N/A') . ", Habitación " . htmlspecialchars($cita['consultorio_habitacion'] ?? 'N/A') . "</td></tr>";
        $html .= "</table>";

        return $html;
    }
}
?>