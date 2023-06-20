<?php

require_once 'Helado.php';

if(isset($_POST['Sabor']) && isset($_POST['Precio']) && 
    isset($_POST['Tipo']) && isset($_POST['Vaso']) && isset($_POST['Stock']))
{
    $hSabor= $_POST['Sabor'];
    $hPrecio = floatval($_POST['Precio']);
    $hTipo = $_POST['Tipo'];
    $hVaso = $_POST['Vaso'];
    $hStock = intVal($_POST['Stock']);


    $heladosArray = Helado::LeerJson();
    $tamanio = sizeof($heladosArray);
    if($tamanio > 0)
    {
        $hId = $heladosArray[$tamanio-1]->_id + 1;
    }
    else
    {
        $hId = 1;
    }
    
    try
    {
        if(Helado::ValidarDatos($hSabor, $hPrecio, $hTipo, $hVaso, $hStock))
        {
            $heladoNuevo = new Helado($hId,$hSabor, $hPrecio, $hTipo, $hVaso, $hStock);
            if($heladoNuevo->BuscarHelado($heladosArray))
            {
                Helado::ActualizarHelados($heladoNuevo);
                echo "\n Helado Actualizado";
            }
            else
            {
                array_push($heladosArray, $heladoNuevo);
                Helado::GuardarJson($heladosArray, 'helados.json');
                Helado::GuardarImagen($heladoNuevo);
                echo "\n Helado dado de alta";
            }
        }
        else
        {
            echo "\nError en los datos cargados";
        }

     }
    catch(Throwable $th)
    {
        echo $th->getMessage();
    }

}else{
echo "\n Falta al menos un dato";
}


?>