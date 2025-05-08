<?php
session_start();
require_once '../../config.php';

// Obtener la categoría "Platos Principales" desde la base de datos
$categoria = $pdo->prepare("
    SELECT id, nombre, descripcion 
    FROM categorias 
    WHERE nombre = 'Platos Principales'
");
$categoria->execute();
$categoria = $categoria->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    die("Categoría 'Platos Principales' no encontrada en la base de datos");
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
    <title>Platos Principales</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .menu-item {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .menu-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }
        .ingredient-checkbox {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #000;
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }
        .ingredient-checkbox:checked {
            background-color: yellow;
        }
        .hover-item:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
    </style>
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

    <div class="container mt-5 pt-5">
        <h2 class="text-center"><?= htmlspecialchars($categoria['nombre']) ?></h2>
        <p class="text-center"><?= htmlspecialchars($categoria['descripcion']) ?></p>
        
        <div class="list-group mt-3">
            <?php foreach ($productos as $producto): ?>
            <div class="list-group-item list-group-item-action menu-item hover-item" 
                 onclick="mostrarDetallesProducto(
                    '<?= addslashes($producto['nombre']) ?>', 
                    '<?= addslashes($producto['descripcion']) ?>', 
                    <?= $producto['precio'] ?>, 
                    '<?= addslashes($producto['imagen_url']) ?>',
                    '<?= isset($producto['ingredientes']) ? addslashes($producto['ingredientes']) : '' ?>'
                 )">
                <img src="<?= htmlspecialchars($producto['imagen_url']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                <div>
                    <strong><?= htmlspecialchars($producto['nombre']) ?></strong><br>
                    Q<?= number_format($producto['precio'], 2) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="mt-5 mb-3 text-center">
        <a href="Menu.php"><button type="button" class="btn btn-danger">Regresar</button></a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar detalles del producto en modal
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
    </script>
</body>
</html>