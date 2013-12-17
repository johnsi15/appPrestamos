<!DOCTYPE HTML>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
  <meta property="og:title" content="LaRed.Com">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/bootstrap-responsive.css"> <!-- tener en cuenta el ancho de la pantalla actual 1200-->
  <link rel="stylesheet" href="css/estilos.css">
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="js/ajaxInicio.js"></script>
    <?php  
       //Iniciar Sesión
       session_start();//iniciamos una session con session_start es necesario para poder definir o usar las variables de session
        //Validar si se está ingresando con sesión correctamente
        if (isset($_SESSION['id_user'])){
            header('Location: menu.php');//si esta bien la session lo mandamos al menu principal...
        }else{
        }    
    ?>
   <style>
       #centrar{
          text-align: center;
          padding-left: 2%;
       }
       #fondo{
          background-color: white;
       }
       #mensaje{
          float: left;
          margin-left: 40%;
          margin-top: 7%;
       }
       #sombra{
        color: white;
        text-shadow:-3px 1px 5px #000000;
       }
       .hero-unit{
          margin-top: 7%;
          text-align: center;
          background-image: url('img/dinero-1.jpg');
       }
       form{
        margin-left: 10%;
       }
       @media all and (max-width: 480px){ 
           #form{
               margin-left: 5%;
           }
          #myCarousel.carousel img{
            max-width: 200%;
            width: 100%;
            min-height: 50%;
          }
       }
      
    </style>
    <script>
     $(document).ready(function(){
        $('.carousel').carousel({
          interval: 3000
        });

        $("#menuOpen").mouseout(function(){
            //$(".dropdown").removeClass('open');
        }).mouseover(function(){
            $(".dropdown").addClass('open');
            $("#foco").focus();
        });
    });
    </script>
</head>
<body>
  <header>
    <div class="navbar navbar-fixed-top navbar-inverse">
        <div class="navbar-inner">
          <div class="container" >
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </a>
            <a href="" class="brand">Prestamos AJ</a>
            <div class="nav-collapse collapse">
              <ul class="nav" >
                <li class="divider-vertical"></li>
                <li class="dropdown">
                  <a id="menuOpen" class="dropdown-toggle" data-toggle="dropdown">
                      <strong>Iniciar Sesión</strong>
                      <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu pull-right">
                    <div class="span4">
                       <form  action="includes/acciones.php" method="post" id="limpiar">
                          <label for="nombre" class="control-label">Nombre</label>
                          <input type="text" name="nombre" class="respon" id="foco" placeholder="Usuario" autofocus>
                          <label for="clave" class="control-label">Password</label>
                          <input type="password" name="clave" class="respon" placeholder="Contraseña">
                          <div class="control-group" id="form">
                            <div class="controls">
                               <button type="submit" name="login" class="btn btn-primary" data-loading-text="Cargando...">Iniciar Sesión</button>
                            </div>
                          </div>
                      </form>
                    </div>
                  </ul>
                </li>
              </ul>
            </div><!--nav-collapse-->
          </div><!--container-->
        </div><!--navbar-inner-->
      </div><!--navbar-->
  </header>
  <aside id="mensaje"></aside>
  <section>
    <div class="container">
      <div class="hero-unit"> 
            <h1 id="sombra">Prestamos AJ</h1> 
            <br><br><br>
      </div>
    </div>
  </section>
  <!-- contenido de la pagina-->
    <article class="container">
        <div class="span11" id="centrar">
            <div class="carousel slide" id="myCarousel">
                <!--indicadores carrusel-->
                 <ol class="carousel-indicators">
                  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                  <li data-target="#myCarousel" data-slide-to="1"></li>
                  <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>
                <!--Imganes carrusel-->
                <div class="carousel-inner">
                    <div class="active item">
                      <img src="img/dinero-1.jpg" width="900" height="525">
                    </div>
                    <div class="item">
                      <img src="img/dinero-2.jpg" width="900" height="525">
                    </div>
                    <div class="item">
                      <img src="img/dinero-3.jpg" width="900" height="525">
                    </div>
                </div>
                <!--Navegacion carrusel-->
                <a href="#myCarousel" class="left carousel-control" data-slide="prev">&lsaquo;</a>
                <a href="#myCarousel" class="right carousel-control" data-slide="next">&rsaquo;</a>
            </div>
        </div>
    </article>
    <!-- pie de pagina-->
  <footer>
      <h2 id="centrar"><img src="img/copyright.png" alt="Autor"> AJ 1.0 - 2013</h2>
      <div id="pie">
          <p>Twitter: @Jandrey15</p>
      </div>
    <br>
  </footer>
</body>
</html>