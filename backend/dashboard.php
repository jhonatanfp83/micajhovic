<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include("conexion.php");

/* TOTAL USUARIOS */

$queryUsuarios = mysqli_query($conexion,
"SELECT COUNT(*) as total FROM usuarios");

$totalUsuarios =
mysqli_fetch_assoc($queryUsuarios)['total'];

/* ENTRADAS HOY */

$queryEntradas = mysqli_query($conexion,

"SELECT COUNT(*) as total

FROM registros

WHERE tipo='ENTRADA'

AND fecha = CURDATE()");

$totalEntradas =
mysqli_fetch_assoc($queryEntradas)['total'];

/* SALIDAS HOY */

$querySalidas = mysqli_query($conexion,

"SELECT COUNT(*) as total

FROM registros

WHERE tipo='SALIDA'

AND fecha = CURDATE()");

$totalSalidas =
mysqli_fetch_assoc($querySalidas)['total'];

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    transition:0.3s ease;
}

body{

    background:
    radial-gradient(circle at top left,#1a1a1a 0%,#0a0a0a 40%),
    radial-gradient(circle at bottom right,#141414 0%,#000 40%);

    color:white;

    font-family:'Poppins',sans-serif;

    overflow-x:hidden;
}

/* SIDEBAR */

.sidebar{

    width:280px;
    height:100vh;

    position:fixed;

    background:rgba(255,255,255,0.03);

    backdrop-filter:blur(20px);

    border-right:1px solid rgba(255,255,255,0.08);

    padding:30px;

    display:flex;
    flex-direction:column;
}

/* LOGO */

.logo{

    width:130px;
    height:130px;

    border-radius:35px;

    background:black;

    border:2px solid rgba(255,255,255,0.1);

    display:flex;
    justify-content:center;
    align-items:center;

    margin:auto;

    color:white;

    font-size:38px;
    font-weight:800;

    letter-spacing:6px;

    margin-bottom:25px;

    box-shadow:
    0 0 40px rgba(255,255,255,0.08),
    inset 0 0 20px rgba(255,255,255,0.03);
}

.empresa{

    text-align:center;

    font-size:24px;
    font-weight:700;

    margin-bottom:50px;
}

/* LINKS */

.sidebar a{

    color:white;

    text-decoration:none;

    padding:16px 20px;

    border-radius:18px;

    margin-bottom:15px;

    display:block;

    border:1px solid transparent;
}

.sidebar a:hover{

    background:rgba(255,255,255,0.05);

    border:1px solid rgba(255,255,255,0.08);

    transform:translateX(5px);
}

.logout{

    margin-top:auto;

    background:rgba(255,255,255,0.03);
}

/* CONTENT */

.content{

    margin-left:310px;

    padding:50px;
}

.status-live{

    display:inline-block;

    padding:12px 20px;

    border-radius:50px;

    background:rgba(255,255,255,0.05);

    border:1px solid rgba(255,255,255,0.08);

    margin-bottom:30px;

    font-size:14px;
}

.titulo{

    font-size:42px;
    font-weight:800;

    margin-bottom:40px;
}

/* CARD */

.card-dashboard{

    background:rgba(255,255,255,0.03);

    border:1px solid rgba(255,255,255,0.08);

    border-radius:30px;

    padding:35px;

    backdrop-filter:blur(12px);

    min-height:240px;
}

.card-dashboard:hover{

    transform:translateY(-8px);

    box-shadow:
    0 0 25px rgba(255,255,255,0.08);
}

.card-dashboard i{

    font-size:45px;

    margin-bottom:25px;
}

.card-dashboard h5{

    color:#aaa;

    margin-bottom:20px;
}

.numero{

    font-size:55px;
    font-weight:800;
}

/* HERO SECTION */

.hero-card{

    width:100%;

    padding:45px;

    border-radius:35px;

    margin-bottom:40px;

    background:
    linear-gradient(
    135deg,
    rgba(255,255,255,0.08),
    rgba(255,255,255,0.02)
    );

    border:1px solid rgba(255,255,255,0.08);

    backdrop-filter:blur(15px);
}

.hero-card h1{

    font-size:48px;
    font-weight:800;

    margin-bottom:15px;
}

.hero-card p{

    color:#aaa;

    font-size:18px;
}

/* PARTICLES */

.particles{

    position:fixed;

    width:100%;
    height:100vh;

    top:0;
    left:0;

    background-image:
    radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);

    background-size:40px 40px;

    opacity:0.2;

    pointer-events:none;
}

</style>

</head>

<body>

<div class="sidebar">

<div>

<div class="logo">
MCJV
</div>

<div class="empresa">
MCJV 
</div>

<a href="dashboard.php">
<i class="fa-solid fa-house"></i> Incio
</a>

<a href="usuarios.php">
<i class="fa-solid fa-users"></i> Usuarios
</a>

<a href="registros.php">
<i class="fa-solid fa-clock"></i> Registros
</a>

</div>

<a href="cerrar_sesion.php" class="logout">
<i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
</a>

</div>

<div class="content">

<div class="status-live">
Sistema activo en tiempo real
</div>

<div class="titulo">
Panel Administrativo
</div>

<div class="hero-card">

<h1>
MCJV Security Platform
</h1>

<p>
Control inteligente de accesos mediante autenticación QR en tiempo real.
</p>

</div>

<div class="row g-4">

<div class="col-md-4">

<div class="card-dashboard">

<i class="fa-solid fa-users"></i>

<h5>Total Usuarios</h5>

<div class="numero">
<?php echo $totalUsuarios; ?>
</div>

</div>

</div>

<div class="col-md-4">

<div class="card-dashboard">

<i class="fa-solid fa-right-to-bracket"></i>

<h5>Entradas Hoy</h5>

<div class="numero">
<?php echo $totalEntradas; ?>
</div>

</div>

</div>

<div class="col-md-4">

<div class="card-dashboard">

<i class="fa-solid fa-right-from-bracket"></i>

<h5>Salidas Hoy</h5>

<div class="numero">
<?php echo $totalSalidas; ?>
</div>

</div>

</div>

</div>

</div>

<div class="particles"></div>

</body>
</html>