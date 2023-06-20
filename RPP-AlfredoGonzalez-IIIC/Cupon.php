<?php

require_once 'Venta.php';


class Cupon
{
    public $_id;
    public $_usuario;
    public $_fueUsado;
    public $_descuento;
    public $_precioFinal;

    public function __construct($id, $usuario,  $fueUsado, $descuento, $precioFinal) {
        $this->_id = $id;
        $this->_usuario= $usuario;
        $this->_fueUsado = $fueUsado;
        $this->_descuento = $descuento;
        $this->_precioFinal = $precioFinal;
    }

    public function GetId(){
        return $this->_id;
    }

    public function GetUsuario(){
        return $this->_usuario;
    }

    public function GetFueUsado(){
        return $this->_fueUsado;
    }
    public function GetDescuento(){
        return $this->_descuento;
    }
    public function GetPrecioFinal(){
        return $this->_precioFinal;
    }


    public function setId($id){
        if (is_int($id)) {
            $this->_id = $id;
        }
    }

    public function setUsuario($usuario){
        if (isset($usuario)) {
            $this->_usuario = $usuario;
        }
    }



    public function setFueUsado($fueUsado){
        if (is_bool($fueUsado)) {
            $this->_fueUsado = $fueUsado;
        }
    }


    public function setDescuento($descuento){
        if (is_int($descuento)) {
            $this->_descuento = $descuento;
        }
    }


   public function setPrecioFinal($precioFinal){
        if (is_numeric($precioFinal)) {
            $this->_precioFinal = $precioFinal;
        }
    }

    //Lee el archivo Json y carga los cupones en el array de cupones
    public static function LeerJson($archivoNombre = "Cupones.json")
    {
        $cupones = array();
        if(file_exists($archivoNombre))
        {
            $archivo = fopen($archivoNombre, "r");
            if($archivo)
            {
                $archivoJson = fread($archivo, filesize($archivoNombre));
                $cuponesArchivo = json_decode($archivoJson, true);
                foreach($cuponesArchivo as $cupon)
                {
                    array_push($cupones, new Cupon($cupon["_id"],$cupon["_usuario"],$cupon["_fueUsado"],$cupon["_descuento"],$cupon["_precioFinal"]));
                }
            }
            fclose($archivo);
        }
        return $cupones;
    }


    //Guarda los cupones en el archivo Json
    public static function GuardarJson($cupones, $archivoNombre = "Cupones.json")
    {
        $archivo = fopen($archivoNombre, "w");
        if($archivo)
        {
            $cuponesJson = json_encode($cupones,JSON_PRETTY_PRINT);
            fwrite($archivo, $cuponesJson);
            
        }

        fclose($archivo);
    }

    //Busca un cupon segun el mail de la venta, devuelve el cupon en caso de encontrarlo
    public static function BuscarCuponEnVenta($venta)
    {
        $cupones = Cupon::LeerJson("Cupones.json");
        $helados = Helado::LeerJson("helados.json");
        foreach($cupones as $cupon){
            if($cupon->GetUsuario() == $venta->_email && !$cupon->GetFueUsado()){
                $cupon->setFueUsado(true);
                $cupon->BuscarHeladoEnVenta($helados, $venta);
                // Actualizar archivo

                self::GuardarJson($cupones, "Cupones.json");
                return $cupon;
            }

        }

        return null;
    }

    //Busca el helado perteneciente a la venta y actualiza el precio final del cupon
    public function BuscarHeladoEnVenta($helados, $venta){
        foreach($helados as $helado){
            if($helado->GetSabor() == $venta->_saborHelado 
            && $helado->GetTipo() == $venta->_tipoHelado){
                $this->ActualizarPrecioFinal($helado, $venta);
            }
        }
    }

    //Actualiza el precio final del cupon segun el precio del helado y el stock vendido
    private function ActualizarPrecioFinal($helado, $venta){
        $precioFinal = $helado->GetPrecio() * $venta->_stock;
        $descuento = ($precioFinal * $this->GetDescuento()) / 100;
        $this->_precioFinal = floatval($precioFinal - $descuento);
    }

    //Devuelve el cupon correspondiente al id por parametro
    public static function GetCuponPorId($id, $arrayCupones){
        foreach($arrayCupones as $cupon){
            if($cupon->GetId() == $id){
                return $cupon;
            }
        }
    }

    //Muestra el array de cupones
    public static function MostrarArrayCupones($arrayCupones){

        foreach($arrayCupones as $cupon){
            $cupon->MostrarCupon();
        }
    }

    //Muestra un cupon individual segun si fue usado o no
    private function MostrarCupon()
    {
        if($this->GetFueUsado())
        {
            echo "id Cupon: " .$this->GetId() . " - El cupon fue usado \n"; 
        }
        else{
            echo "id Cupon: " .$this->GetId() . " - El cupon aun no fue usado \n"; 
        }
        

    }

    

}






?>