<?php

class Helado
{
    public $_id;
    public $_sabor;
    public $_precio;
    public $_tipo;
    public $_vaso;
    public $_stock;

    public function __construct($_id, $_sabor, $_precio,$_tipo, $_vaso, $_stock=0)
    {
        $this->SetId($_id);
        $this->SetSabor($_sabor);
        $this->SetPrecio($_precio);
        $this->SetTipo($_tipo);
        $this->SetVaso($_vaso);
        $this->SetStock($_stock);
    }

    public function SetPrecio($precio)
    {
        if(isset($precio) && is_numeric($precio))
        {
            $this->_precio = $precio;
        }
    }
    public function SetId($id)
    {
        if(isset($id) && is_numeric($id))
        {
            $this->_id = $id;
        }
    }
    public function SetSabor($sabor)
    {
        if(isset($sabor))
        {
            $this->_sabor = $sabor;
        }
    }
    public function SetTipo($tipo)
    {
        if(isset($tipo) && ($tipo == 'Crema' || $tipo == 'Agua'))
        {
            $this->_tipo = $tipo;
        }
    }
    public function SetVaso($vaso)
    {
        if(isset($vaso) && ($vaso == 'Cucurucho' || $vaso == 'Plastico'))
        {
            $this->_vaso = $vaso;
        }
    }
    public function SetStock($stock)
    {
        if(!empty($stock) && is_numeric($stock))
        {
            $this->_stock = $stock;
        }
    }
    public function GetPrecio()
    {
        return $this->_precio;
    }
    public function GetId()
    {
        return $this->_id;
    }
    public function GetSabor()
    {
        return $this->_sabor;
    }
    public function GetVaso()
    {
        return $this->_vaso;
    }
    public function GetTipo()
    {
        return $this->_tipo;
    }
    public function GetStock()
    {
        return $this->_stock;
    }

    //Guarda el array de Helados en un archivo tipo Json
    public static function GuardarJson($helados, $archivoNombre = "helados.json")
    {
        $archivo = fopen($archivoNombre, "w");
        if($archivo)
        {
            $heladeriaJson = json_encode($helados,JSON_PRETTY_PRINT);
            fwrite($archivo, $heladeriaJson);
            
        }

        fclose($archivo);
    }


    //Lee el archivo y carga el array de helados en memoria
    public static function LeerJson($archivoNombre = "helados.json")
    {
        $helados = array();
        if(file_exists($archivoNombre))
        {
            $archivo = fopen($archivoNombre, "r");
            if($archivo)
            {
                $archivoJson = fread($archivo, filesize($archivoNombre));
                $heladosArchivo = json_decode($archivoJson, true);
                foreach($heladosArchivo as $helado)
                {
                    if($helado["_stock"] == null)
                    {
                        $helado["_stock"] = 0;
                    }
                    array_push($helados, new Helado($helado["_id"], $helado["_sabor"],$helado["_precio"],
                    $helado["_tipo"], $helado["_vaso"], $helado["_stock"]));
                }
            }
            fclose($archivo);
        }
        return $helados;
    }


    //Actualiza los datos del helado del array si es igual al que llega como parametro
    public static function ActualizarHelados($helado)
    {
        $ruta = 'helados.json';
        $helados = Helado::LeerJson($ruta);
        /*if(!$helado->BuscarHelado($helados))
        {
            array_push($helados, $helado);
        }*/
            foreach($helados as $heladoArray)
            {
                if($heladoArray->Equals($helado))
                {
                    $heladoArray->_stock = $heladoArray->_stock + $helado->_stock;
                    //$heladoArray->SetStock(($heladoArray->GetStock()) + ($helado->GetStock()));
                    $heladoArray->SetPrecio($heladoArray->GetPrecio());
                    $heladoArray->_precio = $helado->_precio;
                    break;        
                }
                

        }
        //Helado::GuardarImagen($helado);
        Helado::GuardarJson($helados, $ruta);

    }


    //Valida que el helado sea igual segun tipo y sabor
    public function Equals($helado)
    {
        if ($this->GetTipo() == $helado->GetTipo() && $helado->GetSabor() == $this->GetSabor()) 
        {
            return true;
        }
        return false;
    }

    //Busca el helado en el array, devuelve true si encontro
    public function BuscarHelado($helados)
    {
        if(!empty($helados))
        {
            foreach($helados as $helado)
            {
                if($this->Equals($helado))
                {
                    echo "\nse encuentra en el array";
                    return true;
                }
            }
        }
        return false;
    }


    //Guarda la imagen con los datos del helado que llega como parametro
    public static function GuardarImagen($helado)
    {
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenNombre = $_FILES['imagen']['name'];
            $imagenTmp = $_FILES['imagen']['tmp_name'];
        
            $extensionesPermitidas = array('jpg', 'jpeg', 'png');
            $extension = strtolower(pathinfo($imagenNombre, PATHINFO_EXTENSION));
            if (in_array($extension, $extensionesPermitidas)) {
                self::crearCarpetaSiNoExiste("./ImagenesDeHelados2023/");
                $carpetaDestino = './ImagenesDeHelados2023/';         
                $imagenNombreUnico = $helado->GetSabor() . '_' . $helado->GetTipo() . '.' . $extension;
  
                move_uploaded_file($imagenTmp, $carpetaDestino . $imagenNombreUnico);

                echo "\nLa imagen se ha guardado correctamente.";
            } else {
                echo "\nError: El formato de imagen no es valido. Se permiten solo archivos JPG, JPEG y PNG.";
            }
        } else {
            echo "\nError: No se ha proporcionado una imagen valida.";
        }
    }

    //Busca en el array los helados que correspondan a los parametros
    public static function BuscarEnArray($array, $sabor, $tipo){
        $retorno;
        $boolTipo = false;
        $boolSabor = false;
        $boolAmbos = false;
        foreach ($array as $helado){
            if($helado->_sabor == $sabor && $helado->_tipo == $tipo)
            {
                $boolAmbos = true;
            }
            else if($helado->_sabor == $sabor){
                $boolSabor = true;
            }
            else if($helado->_tipo == $tipo){
                $boolTipo= true;
            }
        }
        if($boolAmbos){
            $retorno=  'existe';
        }else if($boolTipo){
            $retorno =  'Solo hay de tipo: '.$tipo.'';
        }else if($boolSabor){
            $retorno =  'Solo hay de sabor: '.$sabor.'';
        }else{
            $retorno =  'No hay helados '.$tipo.' ni de sabor '.$sabor.'';
        }

        return $retorno;
    }

    //Verifica que el helado se encuentre en el array y actualiza el stock del helado vendido, por ultimo guarda el array con los cambios
    public static function VerificarVenta($helado, $stock)
    {
        $retorno = '';
        $ruta = 'helados.json';
        $helados = Helado::LeerJson($ruta);
        //if($helado->BuscarHelado($helados))
        //{
            foreach($helados as $heladoArray)
            {
                if($heladoArray->Equals($helado))
                {
                    if($heladoArray->GetStock() == $stock || $heladoArray->GetStock() >= $stock)
                    {
                        $heladoArray->_stock = $heladoArray->_stock - $stock;
                        $retorno = $heladoArray->GetVaso();
                        echo "\nVenta realizada con exito";
                        Helado::GuardarJson($helados, 'helados.json');
                        return $retorno;
                    }
                    else{
                        echo "\nNo hay suficiente stock";
                        $retorno = null;
                    }
                }
            }
        //}
        return $retorno;
    }

    //Crea el directorio si es que no existe
    public static function crearCarpetaSiNoExiste($carpeta = "./ImagenesDeHelados2023/"){
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
    }
    

    //Valida que los datos sean correctos y devuelve true n caso de que lo sean
    public static function ValidarDatos($sabor, $precio, $tipo, $vaso, $stock)
    {
        if($sabor != null && $sabor != '' && is_numeric($precio)  && $precio > 0
        && ($tipo == 'Crema' || $tipo == 'Agua')  && ($vaso == 'Cucurucho' || $vaso == 'Plastico') 
        && is_numeric($stock) && $stock > 0)
        {
            return true;
        }
        return false;
    }

}




?>