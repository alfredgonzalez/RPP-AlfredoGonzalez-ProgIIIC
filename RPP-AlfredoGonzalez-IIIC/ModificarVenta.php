<?php

include './Helado.php';
include './Venta.php';



//parse_str(file_get_contents('php://input'), $put_vars);
$put_vars = file_get_contents('php://input');
echo parse_str(file_get_contents('php://input'), $put_vars);

$ventas= Venta::LeerJson('Ventas.json');

if ($put_vars != null) {

    if (
        isset($put_vars['id']) &&isset($put_vars['email']) 
        && isset($put_vars['tipo']) &&isset($put_vars['vaso']) 
        && isset($put_vars['stock']) &&isset($put_vars['sabor'])) 
        
        {
        $id = intVal($put_vars['id']);
        $email =$put_vars['email'];
        $tipo = $put_vars['tipo'];
        $vaso =$put_vars['vaso'];
        $stock = intVal($put_vars['stock']);
        $sabor = $put_vars['sabor'];
        if(Venta::ValidarDatos($id, $email,$sabor, $tipo, $vaso, $stock))
        {
            if (Venta::ModificarVenta($ventas,$id, $sabor,$email, $tipo,$vaso,$stock)) 
            {
                echo "\nVenta modificada correctamente";
                Venta::GuardarJson($ventas, 'ventas.json');
            } else {
                echo "\nLa venta no existe";
            }
        }
        else
        {
            echo "\nPor favor verificar los datos a modificar";
        }
            

    }
    else{

        echo "faltan datos";
    }



}
?>