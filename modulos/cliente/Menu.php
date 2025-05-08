<?php
session_start();
require_once '../../config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click&Serve - Menú</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .container-menu {
            padding-top: 80px;
            padding-bottom: 40px;
        }
        
        .titulo-menu {
            text-align: center;
            margin: 40px 0;
            color: #d32f2f;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #d32f2f;
            padding-bottom: 10px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .categoria-card {
            transition: all 0.3s ease;
            margin-bottom: 30px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background: white;
            height: 100%;
        }
        
        .categoria-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .categoria-card a {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
        
        .categoria-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        
        .categoria-body {
            padding: 20px;
        }
        
        .categoria-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #d32f2f;
        }
        
        .categoria-subtitle {
            font-weight: bold;
            color: #555;
            margin-bottom: 10px;
        }
        
        .categoria-desc {
            color: #666;
            font-size: 0.9rem;
        }
        
        .btn-regresar {
            margin: 40px 0;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .categoria-card {
                margin-bottom: 20px;
            }
            
            .titulo-menu {
                font-size: 1.8rem;
                margin: 30px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Contenido principal -->
    <div class="container container-menu">
        <h1 class="titulo-menu">Menú de la Casa</h1>
        
        <!-- Primera fila de categorías -->
        <div class="row">
            <div class="col-md-4">
                <div class="categoria-card">
                    <a href="Desayunos.php">
                        <img src="https://comedera.com/wp-content/uploads/sites/9/2022/12/Desayono-americano-shutterstock_2120331371.jpg" 
                             class="categoria-img" 
                             alt="Desayunos">
                        <div class="categoria-body">
                            <h3 class="categoria-title">Desayunos</h3>
                            <div class="categoria-subtitle">Lo mejor de la Casa</div>
                            <p class="categoria-desc">Empieza tu día con nuestros deliciosos desayunos preparados con ingredientes frescos.</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="categoria-card">
                    <a href="PlatosPrincipales.php">
                        <img src="https://mandolina.co/wp-content/uploads/2024/06/carne-asada-a-la-parrilla-1080x550-1-1200x900.jpg" 
                             class="categoria-img" 
                             alt="Platos Principales">
                        <div class="categoria-body">
                            <h3 class="categoria-title">Platos Principales</h3>
                            <div class="categoria-subtitle">El sabor de nuestro puerto</div>
                            <p class="categoria-desc">Nuestros platos estrella preparados con las mejores recetas tradicionales.</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="categoria-card">
                    <a href="Antojos.php">
                        <img src="https://foodisafourletterword.com/wp-content/uploads/2020/09/Instant_Pot_Birria_Tacos_with_Consomme_Recipe_tacoplate.jpg" 
                             class="categoria-img" 
                             alt="Antojos">
                        <div class="categoria-body">
                            <h3 class="categoria-title">Antojos</h3>
                            <div class="categoria-subtitle">Lo mejor de la Casa</div>
                            <p class="categoria-desc">Deliciosos antojitos para compartir o disfrutar solo.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Segunda fila de categorías -->
        <div class="row">
            <div class="col-md-4">
                <div class="categoria-card">
                    <a href="Entradas.php">
                        <img src="https://www.recetasnestle.com.ec/sites/default/files/srh_recipes/4e4293857c03d819e4ae51de1e86d66a.jpg" 
                             class="categoria-img" 
                             alt="Entradas">
                        <div class="categoria-body">
                            <h3 class="categoria-title">Entradas</h3>
                            <div class="categoria-subtitle">Para comenzar</div>
                            <p class="categoria-desc">Perfectas para compartir mientras esperas tu plato principal.</p>
                        </div>
                    </a>
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="categoria-card">
                    <a href="bebidas.php">
                        <img src="https://www.tuhogar.com/content/dam/cp-sites/home-care/tu-hogar/es_mx/recetas/snacks-bebidas-y-postres/aprende-a-preparar-batidos-saludables/4-ideas-para-preparar-batidos-saludables-axion.jpg" 
                             class="categoria-img" 
                             alt="Bebidas">
                        <div class="categoria-body">
                            <h3 class="categoria-title">Bebidas</h3>
                            <div class="categoria-subtitle">Refrescantes</div>
                            <p class="categoria-desc">La mejor selección de bebidas para acompañar tu comida.</p>
                        </div>
                    </a>
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="categoria-card">
                    <a href="Postres.php">
                        <img src="https://images.aws.nestle.recipes/resized/2024_10_23T08_34_55_badun_images.badun.es_pastelitos_de_chocolate_blanco_y_queso_con_fresas_1290_742.jpg" 
                             class="categoria-img" 
                             alt="Postres">
                        <div class="categoria-body">
                            <h3 class="categoria-title">Postres</h3>
                            <div class="categoria-subtitle">Dulces tentaciones</div>
                            <p class="categoria-desc">Termina tu comida con nuestros deliciosos postres caseros.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Botón de regreso -->
        <div class="btn-regresar">
            <a href="index.php" class="btn btn-danger btn-lg">Regresar al Inicio</a>
        </div>
    </div>
</body>
</html>