<?php
require_once(__DIR__ . '/models/reporte.php');
require_once(__DIR__ . '/../vendor/autoload.php');

$web = new Reporte;
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;
$id_cita = isset($_GET['id_cita']) ? filter_var($_GET['id_cita'], FILTER_VALIDATE_INT) : null;

$baseConfig = $web->a4();
if (!is_array($baseConfig)) {
    error_log("Error: La configuración base de mPDF no es un array.");
    $baseConfig = [
        'mode' => 'utf-8', 'format' => 'A4', 'default_font_size' => 12,
        'default_font' => 'Arial', 'margin_left' => 10, 'margin_right' => 10,
        'margin_top' => 10, 'margin_bottom' => 10, 'orientation' => 'P'
    ];
}
$config = array_merge($baseConfig, [
    'tempDir' => __DIR__ . '/../tmp'
]);

$mpdf = null;

switch ($accion){
    case 'cita_detalle':
        if (!$id_cita) {
            die('Error: ID de cita no proporcionado o no válido.');
        }
        $mpdf = new \Mpdf\Mpdf($config);
        $mpdf->SetHeader($web->encabezado() . '||Detalle de Cita');
        $mpdf->SetFooter($web->pie() . '||Página {PAGENO}');

        $htmlCita = $web->generarHtmlCita($id_cita);
        $mpdf->WriteHTML($htmlCita);
        break;

    default:
        die("Acción no válida para el reporte.");
        break;
}

if ($mpdf) {
    $mpdf->Output('reporte_cita.pdf', \Mpdf\Output\Destination::INLINE);
} else {
    echo "Error al generar el PDF. Verifique la acción o el ID proporcionado.";
}
exit;
?>