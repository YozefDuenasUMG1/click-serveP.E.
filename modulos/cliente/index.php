<?php
session_start();
require_once '../../config.php';

// Obtener la categoría "Promociones" desde la base de datos
$categoria = $pdo->prepare("
    SELECT id, nombre, descripcion 
    FROM categorias 
    WHERE nombre = 'Promociones'
");
$categoria->execute();
$categoria = $categoria->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    die("Categoría 'Promociones' no encontrada en la base de datos");
}

// Obtener productos de la categoría con sus ingredientes
$productos = $pdo->prepare("
    SELECT p.*, GROUP_CONCAT(i.nombre SEPARATOR ', ') AS ingredientes
    FROM productos p
    LEFT JOIN producto_ingredientes pi ON p.id = pi.producto_id
    LEFT JOIN ingredientes i ON pi.ingrediente_id = i.id
    WHERE p.categoria_id = ?
    GROUP BY p.id
");
$productos->execute([$categoria['id']]);
$productos = $productos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click&Serve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Incluir el Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Modal para detalles del producto -->
    <div class="modal fade" id="productoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImagen" src="" class="img-fluid mb-3" alt="Producto">
                    <h5 id="modalNombre" class="mt-3"></h5>
                    <p id="modalDescripcion" class="text-muted"></p>
                    <p id="modalPrecio" class="fw-bold"></p>
                    <div class="text-start">
                        <h6>Ingredientes:</h6>
                        <div id="ingredientesContainer">
                            <!-- Los ingredientes se llenarán dinámicamente con JavaScript -->
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3" id="btnAgregarCarrito">Agregar al Carrito</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="container text-center" style="padding-top: 80px;">
        <h1 id="mensaje"></h1>
        <h2 class="fw-bold text-success">¿Tienes hambre?</h2>
        <h3>Elige una Opcion</h3>
        <div class="row">
            <div class="col-6 option hover-option" style="font-size: 19px;">
                <a href="Menu.php">Pedir Ahora</a>
            </div>
            <div class="col-6 option hover-option" style="font-size: 19px;">
                <a href="">Programar Pedido</a>
            </div>
        </div>
        <div class="banner-container mt-3">
            <div class="owl-carousel">
                <img src="https://img.freepik.com/fotos-premium/hamburguesa-lechuga-tomate-queso-encima_911201-2411.jpg" alt="Banner 1">
                <img src="https://img.freepik.com/fotos-premium/hamburguesa-doble-aislada-sobre-fondo-negro-hamburguesa-fresca-comida-rapida-carne-res-queso-cheddar_174541-1262.jpg" alt="Banner 2">
                <img src="https://img.freepik.com/fotos-premium/hamburguesa-doble-queso-fondo-negro-fondo-oscuro_68880-2392.jpg" alt="Banner 3">
            </div>
        </div>
        <h3 class="mt-4 text-warning">Últimos pedidos</h3>
        <div class="list-group">
            <div class="list-group-item hover-item d-flex align-items-center" onclick="agregarAlCarrito('Pizza de Birria Personal/Grande', 'El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.', 49.00)">
                <img src="https://143364461.cdn6.editmysite.com/uploads/1/4/3/3/143364461/BUDGCQITZYDRW7UQKXWLGROP.jpeg" 
                     class="img-fluid img-center" 
                     style="width: 150px; height: auto; border-radius: 10px; margin-right: 15px;"> 
                <div>
                    <h4 class="mb-1"> Pizza de Birria Personal/Grande</h4>
                    <strong>El sabor de nuestro puerto.</strong>
                    <p>Masa artesanal, birria, queso mozzarella, cilantro y cebolla.</p>
                    <p><strong>Q49.00</strong></p>
                </div>
            </div>
            <div class="list-group-item hover-item d-flex align-items-center" onclick="agregarAlCarrito('Tacos de Birria', 'Lo mejor de la Casa. Tortilla de maíz, birria, queso, cilantro y cebolla.', 19.00)">
                <img src="https://www.brandnewvegan.com/wp-content/uploads/vegan-birria-tacos-f1.jpg" 
                     class="img-fluid img-center" 
                     style="width: 150px; height: auto; border-radius: 10px; margin-right: 15px;"> 
                <div>
                    <h4 class="mb-1"> Tacos de Birria</h4>
                    <strong>Lo mejor de la Casa</strong>
                    <p>Tortilla de maíz, birria, queso, cilantro y cebolla.</p>
                    <p><strong>Q19.00</strong></p>
                </div>
            </div>
        </div>
        
        <h3 class="mt-4 text-danger">Ofertas para ti</h3>
        <div class="row">
            <div class="col-6 hover-item" onclick="mostrarDetallesProducto('2 Tocino Ranch', 'Promoción especial: Dos hamburguesas con tocino, queso, ranch y lechuga.', 75.00, 'https://img.freepik.com/fotos-premium/hamburguesa-mucho-humo-sobre-fondo-oscuro_856795-3589.jpg', 'Tocino, Queso, Ranch, Lechuga')">
                <img src="https://img.freepik.com/fotos-premium/hamburguesa-mucho-humo-sobre-fondo-oscuro_856795-3589.jpg" class="img-fluid">
                <strong>2 Tocino Ranch x 75</strong>
            </div>
            <div class="col-6 hover-item" onclick="mostrarDetallesProducto('Hamburguesa de Pollo', 'Pechuga de pollo empanizada, lechuga, tomate y mayonesa especial.', 29.00, 'https://tofuu.getjusto.com/orioneat-local/resized2/YKpAjwPmaEDuAhzpS-800-x.webp', 'Pollo, Lechuga, Tomate, Mayonesa')">
                <img src="https://tofuu.getjusto.com/orioneat-local/resized2/YKpAjwPmaEDuAhzpS-800-x.webp" class="img-fluid">
                <strong class="text-center">Hamburguesa de Pollo x 29</strong>
            </div>
        </div>
    </div>

    <!-- Scripts específicos de la página -->
    <script>
        function mostrarDetallesProducto(nombre, descripcion, precio, imagen, ingredientes) {
            productoActual = {
                nombre: nombre,
                descripcion: descripcion,
                precio: precio,
                imagen: imagen,
                ingredientes: ingredientes ? ingredientes.split(', ') : []
            };
            
            document.getElementById('modalNombre').textContent = nombre;
            document.getElementById('modalDescripcion').textContent = descripcion;
            document.getElementById('modalPrecio').textContent = `Q${precio.toFixed(2)}`;
            document.getElementById('modalImagen').src = imagen;
            
            const ingredientesContainer = document.getElementById('ingredientesContainer');
            ingredientesContainer.innerHTML = '';
            
            if (ingredientes && ingredientes.trim() !== '') {
                const ingredientesArray = ingredientes.split(', ');
                ingredientesArray.forEach(ing => {
                    const label = document.createElement('label');
                    label.innerHTML = `<input type="checkbox" class="ingredient-checkbox" checked data-ingrediente="${ing}"> ${ing}`;
                    ingredientesContainer.appendChild(label);
                    ingredientesContainer.appendChild(document.createElement('br'));
                });
            } else {
                ingredientesContainer.innerHTML = '<p>No se especificaron ingredientes</p>';
            }
            
            document.getElementById('btnAgregarCarrito').onclick = function() {
                const ingredientesRemovidos = [];
                const checkboxes = ingredientesContainer.querySelectorAll('.ingredient-checkbox');
                checkboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        ingredientesRemovidos.push(checkbox.dataset.ingrediente);
                    }
                });
                
                agregarAlCarrito(nombre, descripcion, precio, ingredientesRemovidos);
                var modal = bootstrap.Modal.getInstance(document.getElementById('productoModal'));
                modal.hide();
            };
            
            var modal = new bootstrap.Modal(document.getElementById('productoModal'));
            modal.show();
        }

        $(document).ready(function(){
            $(".owl-carousel").owlCarousel({
                loop: true,
                margin: 10,
                nav: false,
                items: 1,
                autoplay: true
            });
        });
    </script>
</body>
</html>