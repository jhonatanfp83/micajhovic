<!-- ============================ ELIMINAR_USUARIO.PHP ============================ -->

<?php

include("conexion.php");

$id = $_GET['id'];

mysqli_query($conexion,
"DELETE FROM usuarios WHERE id='$id'");

header("Location: usuarios.php");

?>