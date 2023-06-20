<?php

require_once 'Venta.php';
require_once 'Cupon.php';
require_once 'Devolucion.php';

if (isset($_POST['id']) && isset($_POST['causa'])) {

    $causa = $_POST['causa'];
    $id = intVal($_POST['id']);
    $descuento = 10;
    $ventas = Venta::LeerJSON('Ventas.json');
    $cuponesArray = Cupon::LeerJSON('Cupones.json');
    $devolucionesArray = Devolucion::LeerJSON('Devoluciones.json');
    

    
    /*if(sizeof($cuponesArray) > 0)
    {
        $tamanio = sizeof($cuponesArray);
        $cId = $cuponesArray[$tamanio-1]->_id + 1;
    }
    else
    {
        $cId = 1;
    }*/
    
    $venta = Venta::VentaPorId($ventas,$id);
        if($venta != null)
        {
            
            
            var_dump($venta);
            $cupon = new Cupon($id, $venta->_email, false,$descuento, 0);
            $devolucion = new Devolucion($id, $causa);
            array_push($devolucionesArray, $devolucion);
            array_push($cuponesArray, $cupon);
            Devolucion::GuardarImagen($devolucion);

            Cupon::GuardarJson($cuponesArray,'Cupones.json');
            Cupon::GuardarJson($devolucionesArray,'Devoluciones.json');
            
            echo "\nDevolucion resuelta correctamente";
            
        }
        else
        {
            echo "\nNo existe el numero de pedido";
        }    
}



?>



