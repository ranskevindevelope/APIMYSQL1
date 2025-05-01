<?php 

header('Content-Type: application/json'); 
header("Access-Control-Allow-Origin: *");

require_once("../config/conexion.php"); 
require_once("../model/Cliente.php"); 

$cliente = new Cliente(); 

$body = json_decode(file_get_contents("php://input"), true); 


if (isset($_GET["api"])) {
    switch ($_GET["api"]) {

        case "GetAll": 
            $datos = $cliente->get_clientes(); 
            echo json_encode($datos); 
            break;

        case "GetId": 
            $id = $body["id"] ?? null;
            if ($id !== null) {
                $datos = $cliente->get_cliente($id);
                echo json_encode($datos);  
            } else {
                echo json_encode(["error" => "ID no proporcionado"]);
            }
            break;

        case "Delete":
            $id = $body["id"] ?? null;
            if ($id !== null) {
                $cliente->delete_cliente($id);
                echo json_encode(["message" => "Cliente eliminado correctamente"]);  
            } else {
                echo json_encode(["error" => "ID no proporcionado para eliminar"]);
            }
            break;

            case "Insert":
                // Recepción de los datos
                $nit = $body["nit"] ?? null;
                $razon_social = $body["razon_social"] ?? null;
                $direccion = $body["direccion"] ?? null;
                $telefono = $body["telefono"] ?? null;
            
                // Verificación de los datos
                if ($nit && $razon_social && $direccion && $telefono) {
                    // Insertamos el cliente en la base de datos
                    $resultado = $cliente->insert_cliente($nit, $razon_social, $direccion, $telefono);
                    
                    // Retornamos el éxito
                    echo json_encode([
                        "success" => true,
                        "message" => "Cliente insertado correctamente",
                        "id_cliente" => $resultado
                    ]);
                } else {
                    // Si faltan datos
                    echo json_encode(["error" => "Faltan datos para insertar"]);
                }
                break;
                case "Update":
                    // Recibir los datos del cuerpo de la solicitud
                    $id_cliente = $body["id_cliente"] ?? null;
                    $nit = $body["nit"] ?? null;
                    $razon_social = $body["razon_social"] ?? null;
                    $direccion = $body["direccion"] ?? null;
                    $telefono = $body["telefono"] ?? null;
                
                    // Verificar que todos los campos necesarios estén presentes
                    if ($id_cliente && $nit && $razon_social && $direccion && $telefono) {
                        // Llamar al método para actualizar el cliente
                        $resultado = $cliente->update_cliente($id_cliente, $nit, $razon_social, $direccion, $telefono);
                        
                        // Retornar el mensaje de éxito
                        echo json_encode([
                            "success" => true,
                            "message" => "Cliente actualizado correctamente"
                        ]);
                    } else {
                        // Si faltan datos, retornar un error
                        echo json_encode(["error" => "Faltan datos para actualizar"]);
                    }
                    break;
                
        default:
            echo json_encode(["error" => "Acción no válida"]);
    }
} else {
    echo json_encode(["error" => "No se especificó ninguna acción (api)"]);
}
?>

