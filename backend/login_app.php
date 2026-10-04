<?php

header('Content-Type: application/json');

include("conexion.php");

/* VALIDAR DATOS */

if(
!isset($_POST['correo']) ||
!isset($_POST['password'])
){

    echo json_encode([

        "status"=>"error",
        "mensaje"=>"Faltan datos"

    ]);

    exit();
}

/* RECIBIR DATOS */

$correo = trim($_POST['correo']);

$password = trim($_POST['password']);

/* CONSULTA */

$query = mysqli_query($conexion,

"SELECT * FROM usuarios

WHERE correo='$correo'

AND password='$password'");

/* LOGIN CORRECTO */

if(mysqli_num_rows($query)>0){

    $usuario =
    mysqli_fetch_assoc($query);

    /* GENERAR NUEVO TOKEN */

    $nuevo_token =

    md5(uniqid(rand(), true));

    /* ACTUALIZAR TOKEN */

    mysqli_query($conexion,

    "UPDATE usuarios

    SET qr_token='$nuevo_token'

    WHERE id='".$usuario['id']."'");

    /* RESPUESTA */

    echo json_encode([

        "status"=>"success",

        "nombre"=>$usuario['nombre'],

        "correo"=>$usuario['correo'],

        "token"=>$nuevo_token

    ]);

}else{

    echo json_encode([

        "status"=>"error",

        "mensaje"=>"Credenciales incorrectas"

    ]);
}
?>