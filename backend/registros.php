<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

/* EVITAR CACHE */

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include("conexion.php");

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Registros</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    transition:0.35s ease;
}

body{

    background:
    radial-gradient(circle at top left,#1b1b1b 0%,#0b0b0b 40%),
    radial-gradient(circle at bottom right,#121212 0%,#000 40%);

    color:white;

    font-family:'Poppins',sans-serif;

    overflow-x:hidden;
}

/* SIDEBAR */

.sidebar{

    width:290px;
    height:100vh;

    position:fixed;

    background:rgba(255,255,255,0.03);

    backdrop-filter:blur(25px);

    border-right:1px solid rgba(255,255,255,0.06);

    padding:35px;

    display:flex;
    flex-direction:column;

    z-index:10;
}

/* LOGO */

.logo{

    width:135px;
    height:135px;

    border-radius:40px;

    background:
    linear-gradient(
    145deg,
    #000,
    #111
    );

    border:1px solid rgba(255,255,255,0.08);

    display:flex;
    justify-content:center;
    align-items:center;

    margin:auto;

    color:white;

    font-size:42px;
    font-weight:800;

    letter-spacing:5px;

    margin-bottom:28px;

    box-shadow:

    0 20px 45px rgba(0,0,0,0.6),

    inset 0 1px 1px rgba(255,255,255,0.04),

    0 0 30px rgba(255,255,255,0.04);
}

.empresa{

    text-align:center;

    font-size:24px;
    font-weight:700;

    margin-bottom:60px;

    color:#f5f5f5;
}

/* LINKS */

.sidebar a{

    color:white;

    text-decoration:none;

    padding:18px 22px;

    border-radius:20px;

    margin-bottom:18px;

    display:flex;

    align-items:center;

    gap:14px;

    font-size:15px;

    font-weight:500;

    border:1px solid transparent;
}

.sidebar a:hover{

    background:rgba(255,255,255,0.05);

    border:1px solid rgba(255,255,255,0.08);

    transform:translateX(6px);
}

.logout{

    margin-top:auto;

    background:rgba(255,255,255,0.03);
}

/* CONTENT */

.content{

    margin-left:330px;

    padding:60px;

    position:relative;

    z-index:2;
}
/* HERO TITLE */

.hero-title{

    display:flex;

    align-items:center;

    gap:25px;

    margin-bottom:45px;
}

/* LINEA */

.title-line{

    width:6px;

    height:90px;

    border-radius:50px;

    background:
    linear-gradient(
    180deg,
    #ffffff,
    rgba(255,255,255,0.15)
    );

    box-shadow:
    0 0 25px rgba(255,255,255,0.2);
}

/* TITULO */

.hero-title h1{

    font-size:54px;

    font-weight:800;

    margin:0;

    letter-spacing:-2px;

    background:
    linear-gradient(
    90deg,
    #ffffff,
    #bdbdbd
    );

    -webkit-background-clip:text;

    -webkit-text-fill-color:transparent;
}

/* ICONO */

.hero-title h1 i{

    margin-right:15px;
}

/* SUBTITULO */

.hero-title p{

    color:#9ca3af;

    font-size:16px;

    margin-top:10px;

    letter-spacing:0.5px;
}

/* TITULO */

.titulo{

    font-size:52px;

    font-weight:800;

    margin-bottom:45px;

    letter-spacing:-1px;
}

/* CARD */

.card-custom{

    background:
    linear-gradient(
    145deg,
    rgba(255,255,255,0.04),
    rgba(255,255,255,0.015)
    );

    border:1px solid rgba(255,255,255,0.06);

    border-radius:45px;

    padding:45px;

    backdrop-filter:blur(24px);

    animation:fadeUp 0.9s ease;

    box-shadow:

    0 25px 70px rgba(0,0,0,0.55),

    inset 0 1px 1px rgba(255,255,255,0.03),

    0 0 40px rgba(255,255,255,0.03);
}

/* TABLA */

.custom-table{

    width:100%;

    border-collapse:separate;

    border-spacing:0 24px;

    color:white;
}

/* HEADERS */

.custom-table thead th{

    background:
    linear-gradient(
    135deg,
    #1d1d1d,
    #111
    );

    color:#ffffff;

    padding:22px;

    font-size:13px;

    font-weight:700;

    letter-spacing:2px;

    text-transform:uppercase;

    border:none;
}

.custom-table thead th:first-child{

    border-top-left-radius:18px;
    border-bottom-left-radius:18px;
}

.custom-table thead th:last-child{

    border-top-right-radius:18px;
    border-bottom-right-radius:18px;
}

/* FILAS */

.custom-table tbody tr{

    background:
    linear-gradient(
    135deg,
    #1a1a1a,
    #111111
    );

    box-shadow:

    0 10px 30px rgba(0,0,0,0.45),

    inset 0 1px 1px rgba(255,255,255,0.03);
}

/* HOVER */

.custom-table tbody tr:hover{

    transform:
    translateY(-5px)
    scale(1.01);

    box-shadow:

    0 18px 40px rgba(0,0,0,0.55),

    0 0 20px rgba(255,255,255,0.04);
}

/* CELDAS */

.custom-table tbody td{

    padding:30px;

    color:#f5f5f5 !important;

    font-size:16px;

    border:none;

    background:transparent !important;
}

/* BORDES */

.custom-table tbody td:first-child{

    border-top-left-radius:24px;
    border-bottom-left-radius:24px;
}

.custom-table tbody td:last-child{

    border-top-right-radius:24px;
    border-bottom-right-radius:24px;
}

/* BADGES */

.badge-entrada{

    background:
    linear-gradient(
    135deg,
    #22c55e,
    #16a34a
    );

    padding:10px 18px;

    border-radius:50px;

    font-size:12px;

    font-weight:700;

    color:white;

    box-shadow:
    0 0 18px rgba(34,197,94,0.45);
}

.badge-salida{

    background:
    linear-gradient(
    135deg,
    #ef4444,
    #dc2626
    );

    padding:10px 18px;

    border-radius:50px;

    font-size:12px;

    font-weight:700;

    color:white;

    box-shadow:
    0 0 18px rgba(239,68,68,0.45);
}

/* ANIMACION */

@keyframes fadeUp{

    from{

        opacity:0;
        transform:translateY(40px);
    }

    to{

        opacity:1;
        transform:translateY(0);
    }
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

    opacity:0.15;

    pointer-events:none;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<div>

<div class="logo">
MCJV
</div>

<div class="empresa">
MCJV 
</div>

<a href="dashboard.php">
<i class="fa-solid fa-house"></i>
Incio
</a>

<a href="usuarios.php">
<i class="fa-solid fa-users"></i>
Usuarios
</a>

<a href="registros.php">
<i class="fa-solid fa-clock"></i>
Registros
</a>

</div>

<a href="cerrar_sesion.php" class="logout">
<i class="fa-solid fa-right-from-bracket"></i>
Cerrar Sesión
</a>

</div>

<!-- CONTENT -->

<div class="content">

<div class="hero-title">

<div class="title-line"></div>

<div>

<h1>
<i class="fa-solid fa-shield-halved"></i>
Registros de Acceso
</h1>

<p>
Monitoreo inteligente de entradas y salidas en tiempo real
</p>

</div>

</div>

<div class="card-custom">

<table class="custom-table">

<thead>

<tr>

<th>Usuario</th>

<th>Fecha</th>

<th>Hora</th>

<th>Tipo</th>

</tr>

</thead>

<tbody>

<?php

$query = mysqli_query($conexion,

"SELECT usuarios.nombre,

registros.fecha,

registros.hora,

registros.tipo

FROM registros

INNER JOIN usuarios

ON registros.usuario_id = usuarios.id

ORDER BY registros.id DESC");

while($fila=mysqli_fetch_array($query)){

?>

<tr>

<td>

<?php echo $fila['nombre']; ?>

</td>

<td>

<?php echo $fila['fecha']; ?>

</td>

<td>

<?php echo $fila['hora']; ?>

</td>

<td>

<?php if($fila['tipo']=="ENTRADA"){ ?>

<span class="badge-entrada">
ENTRADA
</span>

<?php } else { ?>

<span class="badge-salida">
SALIDA
</span>

<?php } ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<div class="particles"></div>

</body>
</html>