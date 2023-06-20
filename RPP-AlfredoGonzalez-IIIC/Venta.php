<?php
class Venta
{
    public $_id;
    public $_fecha;
    public $_email;
    public $_saborHelado;
    public $_tipoHelado;
    public $_stock;
    public $_tipoVaso;


    public function __construct($_id,$_fecha, $_email, $_saborHelado, $_tipoHelado, $_stock,$_tipoVaso)
    {
        $this->_id = $_id;
        $this->_fecha = $_fecha;
        $this->_email = $_email;
        $this->_saborHelado = $_saborHelado;
        $this->_tipoHelado = $_tipoHelado;
        $this->_stock = $_stock;
        $this->_tipoVaso = $_tipoVaso;

    }
    
    //Lee archivo Json y carga el array de ventas en memoria
    public static function LeerJson($archivoNombre = "Ventas.json")
    {
        $ventas = array();
        if(file_exists($archivoNombre))
        {
            $archivo = fopen($archivoNombre, "r");
            if($archivo)
            {
                $archivoJson = fread($archivo, filesize($archivoNombre));
                $ventasArchivo = json_decode($archivoJson, true);
                foreach($ventasArchivo as $venta)
                {
                    array_push($ventas, new Venta($venta["_id"],$venta["_fecha"], $venta["_email"],
                    $venta["_saborHelado"], $venta["_tipoHelado"], $venta["_stock"], $venta["_tipoVaso"]));
                }
            }
            fclose($archivo);
        }
        return $ventas;
    }

    

    
    //Crea el directorio que se pasa como parametro si no existe
    public static function crearCarpetaSiNoExiste($carpeta = "./ImagenesDeLaVenta2023/"){
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
    }

    //Guarda en un archivo json el array de ventas que llega como parametro
    public static function GuardarJson($ventas, $archivoNombre = "Ventas.json")
    {
        $archivo = fopen($archivoNombre, "w");
        if($archivo)
        {
            $ventasJson = json_encode($ventas,JSON_PRETTY_PRINT);
            fwrite($archivo, $ventasJson);
            
        }

        fclose($archivo);
    }

    //Guarda la imagen con los datos de la venta que llega como parametro
    public static function GuardarImagen($venta)
    {
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenNombre = $_FILES['imagen']['name'];
            $imagenTmp = $_FILES['imagen']['tmp_name'];
            $cadenaSeparada = explode("@",$venta->_email);
 
            $extensionesPermitidas = array('jpg', 'jpeg', 'png');
            $extension = strtolower(pathinfo($imagenNombre, PATHINFO_EXTENSION));
            if (in_array($extension, $extensionesPermitidas)) {
                self::crearCarpetaSiNoExiste('./ImagenesVentas2023/');
                $carpetaDestino = './ImagenesVentas2023/';      
                // Crear un nombre único para la imagen utilizando el tipo y el nombre
                $imagenNombreUnico = $venta->_tipoHelado . '_' . $venta->_saborHelado . '_' . $venta->_tipoVaso . '_' . $cadenaSeparada[0] .'_'. $venta->_fecha . '.'.$extension;
        
                // Mover la imagen a la carpeta de destino
                move_uploaded_file($imagenTmp, $carpetaDestino . $imagenNombreUnico);
                echo "\nLa imagen se ha guardado correctamente.";
            } else {
                echo "\nError: El formato de imagen no es valido. Se permiten solo archivos JPG, JPEG y PNG.";
            }
        } else {
            echo "\nError: No se ha proporcionado una imagen valida.";
        }
      
    }

    //Guarda la iamgen con los datos de la venta y el cupon
    public static function GuardarImagenConDescuento($venta, $cupon)
    {
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenNombre = $_FILES['imagen']['name'];
            $imagenTmp = $_FILES['imagen']['tmp_name'];
            $cadenaSeparada = explode("@",$venta->_email);
 
            $extensionesPermitidas = array('jpg', 'jpeg', 'png');
            $extension = strtolower(pathinfo($imagenNombre, PATHINFO_EXTENSION));
            if (in_array($extension, $extensionesPermitidas)) {
                self::crearCarpetaSiNoExiste('./ImagenesVentas2023/');
                $carpetaDestino = './ImagenesVentas2023/';      
                // Crear un nombre único para la imagen utilizando el tipo y el nombre
                $imagenNombreUnico = $venta->_tipoHelado . '_' . $venta->_saborHelado . '_' . $venta->_tipoVaso . '_' . $cadenaSeparada[0] . '_' . $cupon->GetPrecioFinal() . '_' . $cupon->GetDescuento() . '.' . $extension;
                // Mover la imagen a la carpeta de destino
                move_uploaded_file($imagenTmp, $carpetaDestino . $imagenNombreUnico);
                echo "\nLa imagen se ha guardado correctamente.";
            } else {
                echo "\nError: El formato de imagen no es valido. Se permiten solo archivos JPG, JPEG y PNG.";
            }
        } else {
            echo "\nError: No se ha proporcionado una imagen valida.";
        }
      
    }


    //Muestra todas las ventas segun la fecha que recibe como parametro
    public static function VentaSegunFecha($ventas, $fecha)
    {
        if ($ventas == null) {
            return null;
        }
        return array_filter($ventas, function ($venta) use ($fecha) {
            if ($fecha != null) {
                return $venta->_fecha == $fecha;
            }
            else {
                return strtotime($venta->_fecha) == date('Y-m-d', strtotime("-1 days"));
            }
        });
    }

    //Muestra todas las ventas segun el usuario que recibe como parametro
    public static function VentaSegunUsuario($ventas, $mail)
    {
        if ($ventas == null) {
            return null;
        }
        return array_filter($ventas, function ($venta) use ($mail) {
            if ($mail != null) {
                return $venta->_email == $mail;
            }
        });
    }
    
    
    //Muestra todas las ventas segun dos fechas que recibe como parametro
    public static function VentaSegunDosFechas($ventas, $fecha1, $fecha2)
    {
        $arrayVentasEntreFechas = array();
        
        if ($fecha1 != null && $fecha2 != null && $ventas != null) {
            $arrayVentasEntreFechas = array_filter($ventas, function ($venta) use ($fecha1, $fecha2) {
                return strtotime($venta->_fecha) >= strtotime($fecha1)
                &&
                strtotime($venta->_fecha) <= strtotime($fecha2);
            });
            usort($arrayVentasEntreFechas, function ($venta1, $venta2) {
                $cadenaSeparadaUno = explode("@",$venta1->_email);
                $cadenaSeparadaDos = explode("@",$venta2->_email);
                return $cadenaSeparadaUno[0] > $cadenaSeparadaDos[0];
            });
            foreach($arrayVentasEntreFechas as $ventas)
            {
                var_dump($ventas);
            }
        }
    }

    //Muestra todas las ventas segun el vaso que recibe como parametro
    public static function VentaSegunVaso($ventas, $vaso)
    {
        if ($ventas != null) {
            return array_filter($ventas, function ($venta) use ($vaso) {
                if ($vaso != null && ($vaso == 'Cucurucho' || $vaso == 'Plastico')) {
                    return $venta->_tipoVaso == $vaso;
                }
                else
                {
                    echo "\nError, el tipo de vaso debe ser cucurucho o plastico";
                }
            });
        }
    }

    //Valida si existe ese sabor de helado en el array de ventas, devuelve un bool
    public static function VentaSegunSabor($ventas, $saborHelado)
    {
        if ($ventas != null) {
            return array_filter($ventas, function ($venta) use ($saborHelado) {
                if ($saborHelado != null) {
                    return $venta->_saborHelado == $saborHelado;
                }
            });
        }
    }


    //Modifica la venta con los datos recibidos como parametro
    public static function ModificarVenta($ventas, $id, $sabor, $email,  $tipoHelado, $tipoVaso,  $stock)
    {
        $retorno = false;
        foreach ($ventas as $venta) {
            if ($venta->_id != $id)
            {
                continue;
            }      
            
            $venta->_email = $email;
            $venta->_tipoHelado = $tipoHelado;
            $venta->_tipoVaso = $tipoVaso;
            $venta->_saborHelado = $sabor;
            $venta->_stock = $stock;
            $retorno = true;
        }

        return $retorno;
    }

    //Devuelve un booleano si existe la venta con el id pasado como parametro
    public static function DevolverHelado($ventas, $id)
    {
        if(!empty($ventas))
        {
            foreach($ventas as $venta)
            {
                if($venta->_id == $id)
                {
                    return true;
                }
            }
        }
        return false;
    }


    //Devuelve la venta que corresponda al id pasado como parametro, de lo contrario devuelve null
    public static function VentaPorId($ventas, $id)
    {
        if(!empty($ventas))
        {
            foreach($ventas as $venta)
            {
                if($venta->_id == $id)
                {
                    return $venta;
                }
            }
        }
    }

    //Borra la venta segun el id que se pasa como parametro
    public static function BorrarVentaPorId($id)
    {
        $ventas = self::LeerJson('Ventas.json');
        if($ventas != null)
        {
            foreach($ventas as $key => $value)
            {
                if($value->_id == $id)
                {          
                    unset($ventas[$key]);
                    break;
                }
            }
        }
        return $ventas;
    }


    //Valida los datos y devuleve true si los datos son validos
    public static function ValidarDatos($id,$email,$sabor,  $tipo, $vaso, $stock)
    {
        if(is_numeric($id) && $id > 0 && $email != null && $email != '' && $sabor != null && $sabor != '' 
         && ($tipo == 'Crema' || $tipo == 'Agua')  && ($vaso == 'Cucurucho' || $vaso == 'Plastico') 
         && is_numeric($stock) && $stock > 0)
        {
            return true;
        }
        return false;
    }


    //Mueve la imagen segun las direcciones pasadas como parametro
    public function moverImagen($direccionAntigua, $nuevaDireccion){
        self::crearCarpetaSiNoExiste($nuevaDireccion);
        $cadenaSeparada = explode("@",$this->_email);
        $imagenNombreUnico = $this->_tipoHelado . '_' . $this->_saborHelado . '_' . $this->_tipoVaso . '_' . $cadenaSeparada[0] .'_'. $this->_fecha . '.jpg';
        return rename($direccionAntigua.$imagenNombreUnico, $nuevaDireccion.$imagenNombreUnico);
    }

}






?>