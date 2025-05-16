<?php
require_once (__DIR__.'/../vendor/autoload.php');
require_once (__DIR__.'/models/excel.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$excel = new Excel();

$consultorios = $excel->obtenerConsultoriosCompletos();
$medicos = $excel->obtenerMedicosCompletos();
$especialidades = $excel->obtenerEspecialidadesCompletas();

// Crear instancia de Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reporte MedLink');

// --- Estilos ---
$styleCentered = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleHeader = [
    'font' => [
        'bold' => true,
        'color' => ['argb' => 'FFFFFFFF'], // Color de texto blanco para contraste
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF4F81BD'], // Un azul estándar (Office Theme Blue)
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleSectionTitle = [
    'font' => [
        'bold' => true,
        'size' => 14,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$currentRow = 1;

// --- Sección de Consultorios ---
$sheet->mergeCells('A'.$currentRow.':C'.$currentRow);
$sheet->setCellValue('A'.$currentRow, 'CONSULTORIOS');
$sheet->getStyle('A'.$currentRow)->applyFromArray($styleSectionTitle);
$currentRow++;

// Encabezados Consultorios
$headerConsultorios = ['ID Consultorio', 'Piso', 'Habitación'];
$col = 'A';
foreach ($headerConsultorios as $header) {
    $sheet->setCellValue($col.$currentRow, $header);
    $sheet->getStyle($col.$currentRow)->applyFromArray($styleHeader);
    $col++;
}
$sheet->getRowDimension($currentRow)->setRowHeight(20); // Altura para la fila de encabezado
$currentRow++;

// Datos Consultorios
foreach ($consultorios as $consultorio) {
    $sheet->setCellValue('A'.$currentRow, $consultorio['id_consultorio']);
    $sheet->setCellValue('B'.$currentRow, $consultorio['piso']);
    $sheet->setCellValue('C'.$currentRow, $consultorio['habitacion']);
    // Aplicar estilo centrado a las celdas de datos
    $sheet->getStyle('A'.$currentRow.':C'.$currentRow)->applyFromArray($styleCentered);
    $currentRow++;
}

// Autoajustar tamaño de columnas para Consultorios
foreach (range('A', 'C') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$currentRow += 2; // Dejar dos filas vacías entre secciones

// --- Sección de Médicos ---
$sheet->mergeCells('A'.$currentRow.':H'.$currentRow); // Ajustar el rango de merge si tienes más columnas
$sheet->setCellValue('A'.$currentRow, 'MÉDICOS');
$sheet->getStyle('A'.$currentRow)->applyFromArray($styleSectionTitle);
$currentRow++;

// Encabezados Médicos
$headerMedicos = ['ID Médico', 'Nombre Completo', 'Correo', 'Licencia', 'Teléfono', 'Horario', 'Especialidad', 'Consultorio (Piso - Hab.)'];
$col = 'A';
$lastColMedicos = '';
foreach ($headerMedicos as $header) {
    $sheet->setCellValue($col.$currentRow, $header);
    $sheet->getStyle($col.$currentRow)->applyFromArray($styleHeader);
    $lastColMedicos = $col;
    $col++;
}
$sheet->getRowDimension($currentRow)->setRowHeight(20);
$currentRow++;

// Datos Médicos
foreach ($medicos as $medico) {
    $sheet->setCellValue('A'.$currentRow, $medico['id_medico']);
    $sheet->setCellValue('B'.$currentRow, $medico['nombre'] . ' ' . $medico['primer_apellido'] . ' ' . ($medico['segundo_apellido'] ?? ''));
    $sheet->setCellValue('C'.$currentRow, $medico['correo'] ?? 'N/A');
    $sheet->setCellValue('D'.$currentRow, $medico['licencia']);
    $sheet->setCellValue('E'.$currentRow, $medico['telefono']);
    $sheet->setCellValue('F'.$currentRow, $medico['horario']);
    $sheet->setCellValue('G'.$currentRow, $medico['especialidad']);

    $consultorioInfo = 'N/A';
    if (isset($medico['id_consultorio'])) {
        foreach ($consultorios as $c) { // Reutilizamos $consultorios ya que los tenemos
            if ($c['id_consultorio'] == $medico['id_consultorio']) {
                $consultorioInfo = 'Piso ' . $c['piso'] . ' - Hab. ' . $c['habitacion'];
                break;
            }
        }
    }
    $sheet->setCellValue('H'.$currentRow, $consultorioInfo);
    // Aplicar estilo centrado a las celdas de datos
    $sheet->getStyle('A'.$currentRow.':H'.$currentRow)->applyFromArray($styleCentered); // Ajustar el rango si tienes más columnas
    $currentRow++;
}

// Autoajustar tamaño de columnas para Médicos
foreach (range('A', $lastColMedicos) as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$currentRow += 2; // Dejar dos filas vacías

// --- Sección de Especialidades ---
$sheet->mergeCells('A'.$currentRow.':B'.$currentRow);
$sheet->setCellValue('A'.$currentRow, 'ESPECIALIDADES');
$sheet->getStyle('A'.$currentRow)->applyFromArray($styleSectionTitle);
$currentRow++;

// Encabezados Especialidades
$headerEspecialidades = ['ID Especialidad', 'Nombre Especialidad'];
$col = 'A';
$lastColEspecialidades = '';
foreach ($headerEspecialidades as $header) {
    $sheet->setCellValue($col.$currentRow, $header);
    $sheet->getStyle($col.$currentRow)->applyFromArray($styleHeader);
    $lastColEspecialidades = $col;
    $col++;
}
$sheet->getRowDimension($currentRow)->setRowHeight(20);
$currentRow++;

// Datos Especialidades
foreach ($especialidades as $especialidad) {
    $sheet->setCellValue('A'.$currentRow, $especialidad['id_especialidad']);
    $sheet->setCellValue('B'.$currentRow, $especialidad['especialidad']);
    // Aplicar estilo centrado a las celdas de datos
    $sheet->getStyle('A'.$currentRow.':B'.$currentRow)->applyFromArray($styleCentered);
    $currentRow++;
}

// Autoajustar tamaño de columnas para Especialidades
foreach (range('A', $lastColEspecialidades) as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Configurar cabeceras para descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_MedLink.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1'); // Para IE9
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Fecha en el pasado
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // Siempre modificado
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

?>