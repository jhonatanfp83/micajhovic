<!-- ============================ VALIDAR.PHP ============================ -->

<?php

session_start();

include("conexion.php");

$correo = $_POST['correo'];
$password = $_POST['password'];

$query = mysqli_query($conexion,

"SELECT * FROM administradores
WHERE correo='$correo'
AND password='$password'");

if(mysqli_num_rows($query)>0){

    $_SESSION['admin'] = $correo;

    header("Location: dashboard.php");

}else{

    echo "
    
    <script>
    
    alert('Datos incorrectos');
    window.location='login.php';
    
    </script>
    
    ";

}

?>