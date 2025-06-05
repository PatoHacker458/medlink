<?php

header('Content-Type: application/json; charset=UTF-8');

require_once(__DIR__.'/../config.php');
require_once(__DIR__.'/../model.php');
require_once(__DIR__.'/../models/cita.php');


$cita_model = new Cita();

$id_cita = isset($_GET['id_cita']) ? (int)$_GET['id_cita'] : null;

$request_method = $_SERVER['REQUEST_METHOD'];

$respuesta_api = null;

switch ($request_method) {
    case 'GET':
        if ($id_cita) {
            $datos_cita = $cita_model->leerUno($id_cita);
            if ($datos_cita) {
                http_response_code(200);
                $respuesta_api = $datos_cita;
            } else {
                http_response_code(404);
                $respuesta_api = ['error' => 'Cita no encontrada.'];
            }
        } else {
            $lista_citas = $cita_model->leer();
            http_response_code(200);
            $respuesta_api = $lista_citas;
        }
        break;

    case 'POST':
        $datos_entrada = json_decode(file_get_contents('php://input'), true);

        if ($datos_entrada) {
            $resultado_crear = $cita_model->crear($datos_entrada);
            if ($resultado_crear) {
                http_response_code(201);
                $respuesta_api = ['mensaje' => 'Cita creada correctamente.'];
            } else {
                http_response_code(400);
                $respuesta_api = ['error' => 'No se pudo crear la cita. Verifique los datos o la disponibilidad.'];
            }
        } else {
            http_response_code(400);
            $respuesta_api = ['error' => 'Datos de entrada inválidos o vacíos. Se espera un JSON.'];
        }
        break;

    case 'PUT':
        if ($id_cita) {
            $datos_entrada = json_decode(file_get_contents('php://input'), true);
            if ($datos_entrada) {
                $resultado_modificar = $cita_model->modificar($datos_entrada, $id_cita);
                if ($resultado_modificar) {
                    http_response_code(200);
                    $respuesta_api = ['mensaje' => 'Cita modificada correctamente.'];
                } else {
                    http_response_code(400); 
                    $respuesta_api = ['error' => 'No se pudo modificar la cita. Verifique los datos o la disponibilidad.'];
                }
            } else {
                http_response_code(400);
                $respuesta_api = ['error' => 'Datos de entrada inválidos o vacíos. Se espera un JSON.'];
            }
        } else {
            http_response_code(400);
            $respuesta_api = ['error' => 'Se requiere id_cita en la URL para modificar.'];
        }
        break;

    case 'DELETE':
        if ($id_cita) {
            $resultado_eliminar = $cita_model->eliminar($id_cita);
            if ($resultado_eliminar) {
                http_response_code(200);
                $respuesta_api = ['mensaje' => 'Cita eliminada correctamente.'];
            } else {
                http_response_code(404);
                $respuesta_api = ['error' => 'No se pudo eliminar la cita. Puede que no exista o tenga restricciones.'];
            }
        } else {
            http_response_code(400);
            $respuesta_api = ['error' => 'Se requiere id_cita en la URL para eliminar.'];
        }
        break;

    default:
        http_response_code(405);
        $respuesta_api = ['error' => 'Método HTTP no permitido para este endpoint.'];
        break;
}

echo json_encode($respuesta_api, JSON_PRETTY_PRINT);
?>