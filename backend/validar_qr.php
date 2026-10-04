<?php

include("conexion.php");

$token = $_POST['token'];

/* BUSCAR USUARIO */

$query = mysqli_query($conexion,

"SELECT * FROM usuarios
WHERE qr_token='$token'");

if(mysqli_num_rows($query)>0){

    $usuario = mysqli_fetch_assoc($query);

    $usuario_id = $usuario['id'];

    /* VERIFICAR ULTIMO REGISTRO */

    $ultimo = mysqli_query($conexion,

    "SELECT * FROM registros

    WHERE usuario_id='$usuario_id'

    ORDER BY id DESC

    LIMIT 1");

    if(mysqli_num_rows($ultimo)>0){

        $dato = mysqli_fetch_assoc($ultimo);

        /* SI LA ULTIMA FUE ENTRADA */

        if($dato['tipo']=="ENTRADA"){

            $tipo = "SALIDA";

        }else{

            $tipo = "ENTRADA";
        }

    }else{

        /* PRIMER REGISTRO */

        $tipo = "ENTRADA";
    }

    /* GUARDAR REGISTRO */

    mysqli_query($conexion,

    "INSERT INTO registros(

    usuario_id,
    fecha,
    hora,
    tipo

    )

    VALUES(

    '$usuario_id',
    CURDATE(),
    CURTIME(),
    '$tipo'

    )");

    /* QUEMAR QR */

    $nuevo_token = md5(uniqid(rand(), true));

    mysqli_query($conexion,

    "UPDATE usuarios

    SET qr_token='$nuevo_token'

    WHERE id='$usuario_id'");

    echo json_encode([

        "status"=>"success",

        "nombre"=>$usuario['nombre'],

        "tipo"=>$tipo

    ]);

}else{

    echo json_encode([

        "status"=>"error"
    ]);
}
?>