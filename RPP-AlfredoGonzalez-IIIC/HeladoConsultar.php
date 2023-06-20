<?php
require_once 'Helado.php';

if(isset($_POST['Sabor']) && isset($_POST['Tipo'])){
    $hSabor = $_POST['Sabor'];
    $hTipo = $_POST['Tipo'];

    $heladosArray = Helado::LeerJSON();

    echo Helado::BuscarEnArray($heladosArray, $hSabor, $hTipo);
} 
?>