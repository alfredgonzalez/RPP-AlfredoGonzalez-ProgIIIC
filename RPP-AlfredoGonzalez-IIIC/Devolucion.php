<?php

class Devolucion
{
    public $_idVenta;
    public $_causa;

    public function __construct($idVenta, $causa) {
        $this->_idVenta = $idVenta;
        $this->_causa = $causa;
    }


    //Lee el archivo json y carga el array de devoluciones
    public static function LeerJson($archivoNombre = "Devoluciones.json")
    {
        $devoluciones = array();
        if(file_exists($archivoNombre))
        {
            $archivo = fopen($archivoNombre, "r");
            if($archivo)
            {
                $archivoJson = fread($archivo, filesize($archivoNombre));
                $devolucionesArchivo = json_decode($archivoJson, true);
                foreach($devolucionesArchivo as $devolucion)
                {
                    array_push($devoluciones, new Devolucion($devolucion["_idVenta"], $devolucion["_causa"]));
                }
            }
            fclose($archivo);
        }
        return $devoluciones;
    }

    //Guarda el array de devoluciones en un archivo json
    public static function GuardarJson($devoluciones, $archivoNombre = "Devoluciones.json")
    {
        $archivo = fopen($archivoNombre, "w");
        if($archivo)
        {
            $devolucionesJson = json_encode($devoluciones,JSON_PRETTY_PRINT);
            fwrite($archivo, $devolucionesJson);
            
        }

        fclose($archivo);
    }

    //Crea el directorio si es que no existe
    public static function crearCarpetaSiNoExiste($carpeta = './ImagenesDevoluciones2023/'){
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
    }


    //Recibe los datos de una devolucion y guarda la imagen correspondiente a la devolucion en la carpeta
    public static function GuardarImagen($devolucion)
    {
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenNombre = $_FILES['imagen']['name'];
            $imagenTmp = $_FILES['imagen']['tmp_name'];
 
            $extensionesPermitidas = array('jpg', 'jpeg', 'png');
            $extension = strtolower(pathinfo($imagenNombre, PATHINFO_EXTENSION));
            if (in_array($extension, $extensionesPermitidas)) {
                self::crearCarpetaSiNoExiste('./ImagenesDevoluciones2023/');
                $carpetaDestino = './ImagenesDevoluciones2023/';      
                $imagenNombreUnico = "DevolucionVenta" . '_'. $devolucion->_idVenta . '.' . $extension;
        
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


    //Muestra por pantalla las devoluciones que tengan cupones
    public static function MostrarDevolucionesConCupones($arrayDevoluciones, $cupones){

        foreach($arrayDevoluciones as $devolucion){
            $cuponConDevolucion = Cupon::GetCuponPorId($devolucion->_idVenta, $cupones);
            var_dump($cuponConDevolucion);
            if($cuponConDevolucion != null)
            {
                echo "\nId venta: " . $devolucion->_idVenta . " Causa: " . $devolucion->_causa . " " . "Cupon id: " . $cuponConDevolucion->_id . " Us: " . $cuponConDevolucion->_usuario ; 
            }
        }
    }

    //Muestra los datos de una devolucion
    public static function CargarDevoluciones($arrayDevoluciones){

        foreach($arrayDevoluciones as $devolucion){
            echo "\nId venta: " . $devolucion->_idVenta . "Causa: " . $devolucion;
        }
    }

    //Muestra todo el array de devoluciones
    public static function MostrarArrayDevoluciones($arrayDevoluciones){

        foreach($arrayDevoluciones as $devolucion){
            $devolucion->MostrarDevolucion();
        }
    }

    //Muestra los datos de esta devolucion
    private function MostrarDevolucion()
    {
  
        echo "\nId: " .  $this->_idVenta . " Causa: " . $this->_causa; 
      

    }


}




?>