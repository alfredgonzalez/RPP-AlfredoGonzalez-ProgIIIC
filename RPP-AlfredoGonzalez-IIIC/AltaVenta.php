<?php
require_once 'Helado.php';
require_once 'Venta.php';
require_once 'AltaVenta.php';
require_once 'Cupon.php';
    
    if(isset($_POST['Sabor']) && isset($_POST['Email']) && 
        isset($_POST['Tipo']) && isset($_POST['Stock'])){
        $hSabor = $_POST['Sabor'];
        $vEmail = $_POST['Email'];
        $hTipo = $_POST['Tipo'];
        $hStock = (int)($_POST['Stock']);
        var_dump($hStock);


        try
        {
            $nuevoHelado = new Helado(null, $hSabor, null, $hTipo, null,$hStock);
            $ventasArray = Venta::LeerJSON('Ventas.json');

            $tamanio = sizeof($ventasArray);        
            if($tamanio > 0)
            {
                $vId = $ventasArray[$tamanio-1]->_id + 1;
            }
            else
            {
                $hId = 1;
            }
            
    
            $arrayHelados = Helado::LeerJSON('helados.json');

        

            if($nuevoHelado->BuscarHelado($arrayHelados)){
                $vaso = Helado::VerificarVenta($nuevoHelado, $hStock);
                if($vaso != null)
                {
                    $venta = new Venta($vId,date('d-m-Y'), $vEmail, $hSabor, $hTipo, $hStock, $vaso);

                    $cupon = Cupon::BuscarCuponEnVenta($venta);
                    if($cupon != null)
                    {
                        array_push($ventasArray, $venta);
                        Venta::GuardarImagenConDescuento($venta, $cupon);
                        Venta::GuardarJson($ventasArray,'Ventas.json');
                        echo "\nVenta con descuento Cargada con exito";
                    }
                    else
                    {
                        array_push($ventasArray, $venta);
                        Venta::GuardarImagen($venta);
                        Venta::GuardarJson($ventasArray,'Ventas.json');
                        echo "\nVenta con descuento Cargada con exito";
                    }
                }
                
            }else{
                echo "\nNo hay stock de Helado o no existe";
            }
        }
        catch(Throwable $th)
        {
            echo $th->getMessage();
        }
        
    }else{
        echo "\nFalta al menos un dato";
    }



?>