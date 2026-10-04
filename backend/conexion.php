<?php

$conexion = mysqli_connect(
    "localhost",
    "root",
    "",
    "control_qr"
);

if(!$conexion){
    die("Error de conexión");
}

?>