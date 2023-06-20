<?php

include './Helado.php';
include './Venta.php';


$ventas = Venta::LeerJson('Ventas.json');


if (isset($_GET['fecha'])  && !isset($_GET['fecha1'])
   && !isset($_GET['fecha2']) && !isset($_GET['usuario'])
   && !isset($_GET['vaso']) & !isset($_GET['sabor'] ))
{
   if($_GET['fecha'] == null || $_GET['fecha'] == '')
   {
      $fecha_actual = date("d-m-Y");
      $fecha = date("d-m-Y",strtotime($fecha_actual."- 1 days")); 
      var_dump(Venta::VentaSegunFecha($ventas, $fecha));
   }
   else
   {
      $fecha = $_GET['fecha'];
      var_dump(Venta::VentaSegunFecha($ventas, $fecha));
   }
   
}
else if (isset($_GET['usuario'])  && !isset($_GET['fecha1'])
   && !isset($_GET['fecha2']) && !isset($_GET['fecha'])
   && !isset($_GET['vaso']) & !isset($_GET['sabor'] ) )
{

   $usuario = $_GET['usuario'];
   var_dump(Venta::VentaSegunUsuario($ventas, $usuario));
}
else if (  isset($_GET['fecha1']) && isset($_GET['fecha2']) 
         && !isset($_GET['fecha']) && !isset($_GET['vaso']) 
         && !isset($_GET['usuario']) && !isset($_GET['sabor'] ))
{
   $fechaUno = $_GET['fecha1'];
   $fechaDos = $_GET['fecha2'];
   var_dump(Venta::VentaSegunDosFechas($ventas, $fechaUno, $fechaDos));
}
else if (isset($_GET['sabor']) && !isset($_GET['fecha1']) 
         && !isset($_GET['fecha2']) && !isset($_GET['fecha']) 
         && !isset($_GET['vaso']) && !isset($_GET['usuario']) )
{
   $sabor = $_GET['sabor'];

   var_dump(Venta::VentaSegunSabor($ventas, $sabor));
}
else if (isset($_GET['vaso'])  && !isset($_GET['fecha1']) 
         && !isset($_GET['fecha2']) && !isset($_GET['fecha']) 
         && !isset($_GET['sabor']) && !isset($_GET['usuario']) )
{
   $vaso = $_GET['vaso'];

   var_dump(Venta::VentaSegunVaso($ventas, $vaso));
}
else 
{
   echo "\nDatos invalidos";
}
?>