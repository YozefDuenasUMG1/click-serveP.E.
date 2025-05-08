<?php
require_once '../config.php'; // Asegúrate de que la ruta sea correcta

// Obtener categorías e ingredientes para los selects
$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
$ingredientes = $pdo->query("SELECT * FROM ingredientes")->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario de agregar/editar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $categoria_id = $_POST['categoria_id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen_url = $_POST['imagen_url'];
    $ingredientes_seleccionados = $_POST['ingredientes'] ?? [];
    
    if (empty($id)) {
        // Insertar nuevo producto
        $stmt = $pdo->prepare("INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$categoria_id, $nombre, $descripcion, $precio, $imagen_url]);
        $producto_id = $pdo->lastInsertId();
    } else {
        // Actualizar producto existente
        $stmt = $pdo->prepare("UPDATE productos SET categoria_id=?, nombre=?, descripcion=?, precio=?, imagen_url=? WHERE id=?");
        $stmt->execute([$categoria_id, $nombre, $descripcion, $precio, $imagen_url, $id]);
        $producto_id = $id;
        
        // Eliminar ingredientes anteriores
        $pdo->prepare("DELETE FROM producto_ingredientes WHERE producto_id = ?")->execute([$producto_id]);
    }
    
    // Insertar nuevos ingredientes
    foreach ($ingredientes_seleccionados as $ingrediente_id) {
        $pdo->prepare("INSERT INTO producto_ingredientes (producto_id, ingrediente_id) VALUES (?, ?)")
            ->execute([$producto_id, $ingrediente_id]);
    }
    
    header("Location: admin_menu.php");
    exit;
}

// Procesar eliminación de producto
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM productos WHERE id = ?")->execute([$id]);
    header("Location: admin_menu.php");
    exit;
}

// Obtener todos los productos para mostrar en la tabla
$productos = $pdo->query("
    SELECT p.*, c.nombre AS categoria_nombre 
    FROM productos p 
    JOIN categorias c ON p.categoria_id = c.id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Menú</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .ingrediente-checkbox {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #000;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            margin-right: 8px;
            cursor: pointer;
        }
        .ingrediente-checkbox:checked {
            background-color: yellow;
        }
        .ingredientes-container {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="text-center mb-4">Administración de Menú</h1>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Lista de Productos</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                    <td><?= htmlspecialchars($producto['categoria_nombre']) ?></td>
                                    <td>Q<?= number_format($producto['precio'], 2) ?></td>
                                    <td>
                                        <a href="?edit=<?= $producto['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                        <a href="?delete=<?= $producto['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-container">
                    <h3><?= isset($_GET['edit']) ? 'Editar Producto' : 'Agregar Producto' ?></h3>
                    
                    <?php
                    $producto_editar = null;
                    $ingredientes_producto = [];
                    
                    if (isset($_GET['edit'])) {
                        $id = $_GET['edit'];
                        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
                        $stmt->execute([$id]);
                        $producto_editar = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($producto_editar) {
                            $stmt = $pdo->prepare("
                                SELECT ingrediente_id 
                                FROM producto_ingredientes 
                                WHERE producto_id = ?
                            ");
                            $stmt->execute([$id]);
                            $ingredientes_producto = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        }
                    }
                    ?>
                    
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $producto_editar['id'] ?? '' ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select name="categoria_id" class="form-select" required>
                                <option value="">Seleccionar categoría</option>
                                <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria['id'] ?>" 
                                    <?= ($producto_editar['categoria_id'] ?? '') == $categoria['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($categoria['nombre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre del Producto</label>
                            <input type="text" name="nombre" class="form-control" 
                                   value="<?= htmlspecialchars($producto_editar['nombre'] ?? '') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($producto_editar['descripcion'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Precio (Q)</label>
                            <input type="number" step="0.01" name="precio" class="form-control" 
                                   value="<?= $producto_editar['precio'] ?? '' ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">URL de la Imagen</label>
                            <input type="text" name="imagen_url" class="form-control" 
                                   value="<?= htmlspecialchars($producto_editar['imagen_url'] ?? '') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ingredientes</label>
                            <div class="ingredientes-container">
                                <?php foreach ($ingredientes as $ingrediente): ?>
                                <div class="form-check">
                                    <input class="ingrediente-checkbox" type="checkbox" name="ingredientes[]" 
                                           value="<?= $ingrediente['id'] ?>" id="ing<?= $ingrediente['id'] ?>"
                                           <?= in_array($ingrediente['id'], $ingredientes_producto) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="ing<?= $ingrediente['id'] ?>">
                                        <?= htmlspecialchars($ingrediente['nombre']) ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <?= isset($_GET['edit']) ? 'Actualizar Producto' : 'Agregar Producto' ?>
                        </button>
                        
                        <?php if (isset($_GET['edit'])): ?>
                        <a href="admin_menu.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>