<?php

require_once 'Cupon.php';
require_once 'Devolucion.php';

   $arrayCupones = Cupon::LeerJSON();
   $arrayDevoluciones = Devolucion::LeerJSON();
   
   if(isset($_GET['Devoluciones'])){
       echo "\nDevoluciones Hechas";
       Devolucion::MostrarArrayDevoluciones($arrayDevoluciones);
   }

   if(isset($_GET['Cupones'])){
       echo "\nCupones Generados";
       Cupon::MostrarArrayCupones($arrayCupones);
   }

   if(isset($_GET['DevolucionCupones'])){
       echo "\nDevoluciones con Cupones";
       Devolucion::MostrarDevolucionesConCupones($arrayDevoluciones, $arrayCupones);
   }



?>