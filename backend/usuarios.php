<?php

session_start();

if(!isset($_SESSION['admin'])){

    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/conexion.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Usuarios</title>

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
    radial-gradient(circle at top left,#1a1a1a 0%,#050505 40%),
    radial-gradient(circle at bottom right,#111 0%,#000 40%);

    color:white;

    font-family:'Poppins',sans-serif;

    overflow-x:hidden;
}

/* SIDEBAR */

.sidebar{

    width:240px;
    height:100vh;

    position:fixed;

    background:
    linear-gradient(
    180deg,
    rgba(255,255,255,0.03),
    rgba(255,255,255,0.01)
    );

    backdrop-filter:blur(18px);

    border-right:1px solid rgba(255,255,255,0.05);

    padding:25px;

    display:flex;
    flex-direction:column;
}

/* LOGO */

.logo{

    width:105px;
    height:105px;

    border-radius:30px;

    background:
    linear-gradient(
    145deg,
    #050505,
    #111
    );

    border:1px solid rgba(255,255,255,0.06);

    display:flex;
    justify-content:center;
    align-items:center;

    margin:auto;

    color:white;

    font-size:32px;
    font-weight:800;

    box-shadow:
    0 0 30px rgba(0,0,0,0.6),
    inset 0 1px 0 rgba(255,255,255,0.04);

    margin-bottom:20px;
}

.empresa{

    text-align:center;

    font-size:18px;
    font-weight:700;

    margin-bottom:45px;

    color:#f1f1f1;
}

/* LINKS */

.sidebar a{

    color:white;

    text-decoration:none;

    padding:15px 18px;

    border-radius:16px;

    margin-bottom:12px;

    display:flex;
    align-items:center;
    gap:12px;

    font-size:15px;
    font-weight:500;
}

.sidebar a:hover{

    background:rgba(255,255,255,0.05);

    transform:translateX(5px);
}

.logout{

    margin-top:auto;

    background:rgba(255,255,255,0.03);
}

/* CONTENIDO */

.content{

    margin-left:260px;

    padding:30px 35px;
}

/* TITULO */

.titulo{

    font-size:55px;
    font-weight:800;

    margin-bottom:35px;

    letter-spacing:-2px;
}

/* CARD */

.card-custom{

    background:
    linear-gradient(
    145deg,
    rgba(255,255,255,0.03),
    rgba(255,255,255,0.015)
    );

    border:1px solid rgba(255,255,255,0.05);

    border-radius:35px;

    padding:35px;

    backdrop-filter:blur(20px);

    box-shadow:
    0 10px 40px rgba(0,0,0,0.45);
}

/* FORMULARIO */

.form-grid{

    display:grid;

    grid-template-columns:1fr 1fr auto;

    gap:20px;

    align-items:end;

    margin-bottom:50px;
}

.form-group{

    display:flex;
    flex-direction:column;
}

.form-group label{

    margin-bottom:12px;

    font-size:15px;
    font-weight:600;

    color:#f5f5f5;
}

/* INPUTS */

.form-control{

    height:58px;

    background:
    linear-gradient(
    145deg,
    rgba(255,255,255,0.03),
    rgba(255,255,255,0.015)
    );

    border:1px solid rgba(255,255,255,0.06);

    color:white;

    border-radius:20px;

    padding:0 20px;

    font-size:15px;
}

.form-control::placeholder{

    color:#777;
}

.form-control:focus{

    background:
    linear-gradient(
    145deg,
    rgba(255,255,255,0.04),
    rgba(255,255,255,0.02)
    );

    color:white;

    border:1px solid rgba(255,255,255,0.15);

    box-shadow:
    0 0 20px rgba(255,255,255,0.05);

    outline:none;
}

/* BOTON CREAR */

.btn-crear{

    height:58px;

    min-width:220px;

    border:none;

    border-radius:20px;

    background:
    linear-gradient(
    145deg,
    #ffffff,
    #e7e7e7
    );

    color:black;

    font-weight:700;

    padding:0 30px;

    box-shadow:
    0 10px 25px rgba(255,255,255,0.08);

    transition:0.3s ease;
}

.btn-crear:hover{

    transform:
    translateY(-2px)
    scale(1.02);

    box-shadow:
    0 15px 35px rgba(255,255,255,0.12);
}

/* SUBTITULO */

.subtitulo{

    font-size:38px;
    font-weight:700;

    margin-bottom:30px;
}

/* TABLA */

.table{

    width:100%;

    border-collapse:separate;

    border-spacing:0 16px;

    color:white;
}

/* ENCABEZADOS */

.table thead th{

    background:
    linear-gradient(
    145deg,
    rgba(255,255,255,0.04),
    rgba(255,255,255,0.015)
    );

    border:none;

    color:#9ca3af;

    text-transform:uppercase;

    letter-spacing:2px;

    font-size:12px;

    font-weight:700;

    padding:20px;

    backdrop-filter:blur(12px);
}

.table thead th:first-child{

    border-radius:18px 0 0 18px;
}

.table thead th:last-child{

    border-radius:0 18px 18px 0;
}

/* FILAS */

.table tbody tr{

    background:
    linear-gradient(
    145deg,
    #111111,
    #0a0a0a
    ) !important;

    border:1px solid rgba(255,255,255,0.05);

    box-shadow:
    0 10px 30px rgba(0,0,0,0.45);

    overflow:hidden;
}

.table tbody tr:hover{

    transform:
    translateY(-4px)
    scale(1.005);

    background:
    linear-gradient(
    145deg,
    rgba(22,22,22,0.98),
    rgba(10,10,10,0.95)
    );
}

/* CELDAS */

.table tbody td{

    padding:24px;

    border:none;

    vertical-align:middle;

    font-size:15px;

    color:#ffffff !important;

    font-weight:600;

    background:transparent;
}
.table tbody td:first-child{

    border-radius:22px 0 0 22px;
}

.table tbody td:last-child{

    border-radius:0 22px 22px 0;
}

/* BOTON ELIMINAR */

.btn-delete{

    background:
    linear-gradient(
    145deg,
    #ff2d2d,
    #ff0000
    );

    border:none;

    color:white;

    padding:12px 20px;

    border-radius:15px;

    font-weight:700;

    font-size:14px;

    box-shadow:
    0 0 20px rgba(255,0,0,0.25);
}

.btn-delete:hover{

    transform:
    translateY(-2px)
    scale(1.04);

    box-shadow:
    0 0 28px rgba(255,0,0,0.45);
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

<!-- CONTENIDO -->

<div class="content">

<div class="titulo">
Gestión de Usuarios
</div>

<div class="card-custom">

<!-- FORM -->

<form action="guardar_usuario.php" method="POST">

<div class="form-grid">

<div class="form-group">

<label>
Nombre Completo
</label>

<input
type="text"
name="nombre"
class="form-control"
placeholder="Ingrese nombre completo"
required>

</div>

<div class="form-group">

<label>
Documento
</label>

<input
type="text"
name="documento"
class="form-control"
placeholder="Ingrese documento"
required>

</div>

<div>

<button class="btn-crear">

<i class="fa-solid fa-user-plus"></i>

Crear Usuario

</button>

</div>

</div>

</form>

<!-- TABLA -->

<div class="subtitulo">
Usuarios Registrados
</div>

<table class="table">

<thead>

<tr>

<th>ID</th>
<th>Nombre</th>
<th>Documento</th>
<th>Correo</th>
<th>Contraseña</th>
<th>Acción</th>

</tr>

</thead>

<tbody>

<?php

$query = $conexion->query(
"SELECT id, nombre, documento, correo, password FROM usuarios ORDER BY id DESC");

foreach($query as $fila){

?>

<tr>

<td>
<?php echo e($fila['id']); ?>
</td>

<td>
<?php echo e($fila['nombre']); ?>
</td>

<td>
<?php echo e($fila['documento']); ?>
</td>

<td>
<?php echo e($fila['correo']); ?>
</td>

<td>
<?php echo e($fila['password']); ?>
</td>

<td>

<a
href="eliminar_usuario.php?id=<?php echo (int) $fila['id']; ?>"
onclick="return confirm('¿Eliminar este usuario?');"
class="btn btn-delete">

<i class="fa-solid fa-trash"></i>

Eliminar

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>