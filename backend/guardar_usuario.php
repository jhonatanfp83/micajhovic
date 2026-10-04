<?php

include("conexion.php");

/* DATOS */

$nombre = trim($_POST['nombre']);

$documento = trim($_POST['documento']);

/* VERIFICAR DOCUMENTO DUPLICADO */

$verificar = mysqli_query($conexion,

"SELECT * FROM usuarios
WHERE documento='$documento'");

if(mysqli_num_rows($verificar)>0){

    echo "
    <script>

    alert('Documento ya registrado');

    window.location='usuarios.php';

    </script>
    ";

    exit();
}

/* GENERAR CORREO UNICO */

$nombre_limpio =
strtolower(str_replace(' ','',$nombre));

$random =
rand(1000,9999);

$correo =

$nombre_limpio .

"_" .

substr($documento,-4) .

"_" .

$random .

"@mcjv.com";

/* GENERAR PASSWORD */

$password =

substr(str_shuffle(

"123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"

),0,8);

/* GENERAR TOKEN QR */

$qr_token =

md5(uniqid(rand(), true));

/* INSERTAR */

mysqli_query($conexion,

"INSERT INTO usuarios(

nombre,
documento,
correo,
password,
qr_token

)

VALUES(

'$nombre',
'$documento',
'$correo',
'$password',
'$qr_token'

)");

/* REDIRECCION */

echo "

<script>

alert('Usuario creado correctamente');

window.location='usuarios.php';

</script>

";

?>