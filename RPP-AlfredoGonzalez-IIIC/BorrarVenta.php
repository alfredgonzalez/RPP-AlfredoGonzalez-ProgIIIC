<?php
require_once 'Helado.php';
require_once 'Venta.php';

    
    $data = json_decode(file_get_contents('php://input'), true);
    var_dump($data);
    $carpetaVieja = './ImagenesVentas2023/';
    $carpetaNueva = './ImagenesBackupVentas2023/';
    $ventas = Venta::LeerJSON('Ventas.json');

        if(isset($data['id'])){
 
            try
            {
                $id = intval($data['id']);
            $ventaBorrar = Venta::VentaPorId($ventas, $id);
            
            if($ventaBorrar != null)
            {
                Venta::crearCarpetaSiNoExiste('./ImagenesBackupVentas2023/');
                $ventaBorrar->moverImagen($carpetaVieja, $carpetaNueva);
                $nuevoArray = Venta::BorrarVentaPorId($id);
                Venta::GuardarJson($nuevoArray,'Ventas.json');
                echo "\nVenta eliminada con exito para el id: " . $id;
            }
            else{
                
                echo "\nLa Venta a Borrar no existe";
            }
            } catch (\Throwable $th) {
                echo $th->getMessage();
            }
            
        }else{
            echo "\nFavor de mandar un ID";
        }

?>