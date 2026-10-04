<?php

session_start();

if(isset($_SESSION['admin'])){

    header("Location: dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>MCJV Security</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    overflow:hidden;

    font-family:'Poppins',sans-serif;

    background:
    radial-gradient(circle at top left,#1b1b1b 0%,#090909 35%),
    radial-gradient(circle at bottom right,#111 0%,#000 45%);

    position:relative;
}

/* EFECTO FONDO */

body::before{

    content:"";

    position:absolute;

    width:100%;
    height:100%;

    background-image:
    radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);

    background-size:40px 40px;

    opacity:0.15;
}

/* CONTENEDOR */

.login-container{

    width:1180px;
    height:680px;

    display:flex;

    border-radius:45px;

    overflow:hidden;

    position:relative;

    z-index:2;

    background:
    linear-gradient(
    145deg,
    rgba(255,255,255,0.04),
    rgba(255,255,255,0.015)
    );

    border:1px solid rgba(255,255,255,0.08);

    backdrop-filter:blur(18px);

    box-shadow:
    0 30px 90px rgba(0,0,0,0.8);
}

/* PANEL IZQUIERDO */

.left-panel{

    width:50%;

    padding:70px;

    display:flex;
    flex-direction:column;
    justify-content:center;

    background:
    linear-gradient(
    145deg,
    rgba(255,255,255,0.02),
    rgba(255,255,255,0.01)
    );
}

/* TITULO */

.title{

    color:white;

    font-size:72px;

    font-weight:800;

    line-height:78px;

    margin-bottom:25px;
}

/* SUBTITULO */

.subtitle{

    color:#9ca3af;

    font-size:17px;

    line-height:32px;

    margin-bottom:45px;
}

/* INPUTS */

.form-control{

    height:68px;

    border-radius:22px;

    background:
    rgba(255,255,255,0.04);

    border:1px solid rgba(255,255,255,0.08);

    color:white;

    padding-left:22px;

    margin-bottom:22px;

    font-size:16px;
}

.form-control::placeholder{

    color:#777;
}

.form-control:focus{

    background:
    rgba(255,255,255,0.05);

    color:white;

    border:1px solid rgba(255,255,255,0.18);

    box-shadow:
    0 0 20px rgba(255,255,255,0.04);

    outline:none;
}

/* BOTON */

.btn-login{

    width:100%;

    height:70px;

    border:none;

    border-radius:22px;

    margin-top:10px;

    background:
    linear-gradient(
    135deg,
    #ffffff,
    #d8d8d8
    );

    color:black;

    font-size:19px;

    font-weight:700;

    transition:0.3s;
}

.btn-login:hover{

    transform:
    translateY(-3px)
    scale(1.01);

    box-shadow:
    0 15px 35px rgba(255,255,255,0.12);
}

/* PANEL DERECHO */

.right-panel{

    width:50%;

    position:relative;

    overflow:hidden;

    display:flex;
    justify-content:center;
    align-items:center;

    background:
    linear-gradient(
    180deg,
    #0b0b0b 0%,
    #000 100%
    );
}

/* CIRCULOS */

.circle1{

    position:absolute;

    width:500px;
    height:500px;

    border-radius:50%;

    background:
    radial-gradient(
    rgba(255,255,255,0.10),
    transparent 70%
    );

    top:-180px;
    right:-140px;
}

.circle2{

    position:absolute;

    width:400px;
    height:400px;

    border-radius:50%;

    background:
    radial-gradient(
    rgba(255,255,255,0.05),
    transparent 70%
    );

    bottom:-140px;
    left:-120px;
}

/* TEXTO DERECHO */

.visual-content{

    position:relative;

    z-index:3;

    max-width:430px;
}

.icon-box{

    width:90px;
    height:90px;

    border-radius:28px;

    display:flex;
    justify-content:center;
    align-items:center;

    margin-bottom:35px;

    background:
    rgba(255,255,255,0.03);

    border:1px solid rgba(255,255,255,0.06);

    box-shadow:
    0 0 30px rgba(255,255,255,0.03);
}

.icon-box i{

    color:white;

    font-size:38px;
}

.visual-content h1{

    color:white;

    font-size:78px;

    font-weight:800;

    line-height:85px;

    margin-bottom:25px;
}

.line{

    width:110px;
    height:6px;

    border-radius:20px;

    background:white;

    margin-bottom:35px;
}

.visual-content p{

    color:#c7c7c7;

    font-size:20px;

    line-height:42px;
}

/* RESPONSIVE */

@media(max-width:1100px){

    .login-container{

        width:95%;
        height:auto;

        flex-direction:column;
    }

    .left-panel,
    .right-panel{

        width:100%;
    }

    .right-panel{

        min-height:350px;

        padding:60px;
    }

    .visual-content h1{

        font-size:55px;
        line-height:60px;
    }
}

</style>

</head>

<body>

<div class="login-container">

<!-- PANEL IZQUIERDO -->

<div class="left-panel">

<div class="title">

Bienvenido

</div>

<div class="subtitle">

Sistema Inteligente de Control de Acceso<br>
y Monitoreo QR en Tiempo Real

</div>

<form action="validar.php" method="POST">

<input
type="email"
name="correo"
placeholder="Ingrese su correo"
class="form-control"
required>

<input
type="password"
name="password"
placeholder="Ingrese su contraseña"
class="form-control"
required>

<button type="submit" class="btn-login">

<i class="fa-solid fa-shield-halved"></i>

Ingresar al Sistema

</button>

</form>

</div>

<!-- PANEL DERECHO -->

<div class="right-panel">

<div class="circle1"></div>
<div class="circle2"></div>

<div class="visual-content">

<div class="icon-box">

<i class="fa-solid fa-shield-halved"></i>

</div>

<h1>
MCJV
</h1>

<div class="line"></div>

<p>

Plataforma avanzada de autenticación y control de acceso mediante códigos QR seguros en tiempo real.

</p>

</div>

</div>

</div>

</body>
</html>