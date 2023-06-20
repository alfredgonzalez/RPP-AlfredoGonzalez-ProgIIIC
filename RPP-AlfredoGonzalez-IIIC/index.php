<?php
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        switch ($_GET["accion"]) {
            case "ConsultasVentas":
                include_once "./ConsultasVentas.php";
                break;
            case "ConsultasDevoluciones":
                include_once "./ConsultasDevoluciones.php";
                break;
            default:
                echo "Acción inválida" . PHP_EOL;
                break;
        }
        break;
    case "POST":
        switch ($_POST["require"]) {
            case "HeladeriaAlta":
                include_once "./HeladeriaAlta.php";
                break;
            case "HeladoConsultar":
                include_once "./HeladoConsultar.php";
                break;
            case "AltaVenta":
                include_once "./AltaVenta.php";
                break;
            case "DevolverHelado":
                include_once "./DevolverHelado.php";
                break;
            default:
                echo "Acción inválida" . PHP_EOL;
                break;
        }
        break;
    case "PUT":
        include_once "./ModificarVenta.php";
        break;
    case "DELETE":
        include_once "./BorrarVenta.php";
        break;

}




?>