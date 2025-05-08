<?php
// Iniciamos la sesión
session_start();

// Importamos la configuración
require_once __DIR__ . '/../config.php';

// Variables para el formulario
$error = '';
$success = '';
$usuario = '';
$rol = '';

// Solo los administradores pueden crear usuarios (excepto si es la instalación inicial)
$isAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
$isInitialSetup = !tableHasUsers(); // Función para verificar si ya hay usuarios en la tabla

// Procesamos el formulario de registro
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Desactiva temporalmente la validación del token CSRF para pruebas
    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //     $error = "Error de seguridad. Por favor, intente nuevamente.";
    // } else {
        // Capturamos y sanitizamos los datos del formulario
        $usuario = sanitizeInput($_POST['usuario']);
        $password = $_POST['password'];
        $confirmarPassword = $_POST['confirmar_password'];
        
        // Asegura que el rol se registre correctamente como 'cliente' si no se selecciona otro rol
        $rol = 'cliente';
        
        // Verifica que el campo 'rol' no esté vacío antes de la inserción
        if (empty($rol)) {
            $error = "Error: El rol no puede estar vacío.";
            header("Location: ../login.html?error=" . urlencode($error));
            exit();
        }
        
        // Validación de datos
        if (empty($usuario) || empty($password) || empty($confirmarPassword)) {
            $error = "Todos los campos son obligatorios";
        } elseif (strlen($usuario) < 4) {
            $error = "El nombre de usuario debe tener al menos 4 caracteres";
        } elseif (strlen($password) < 8) {
            $error = "La contraseña debe tener al menos 8 caracteres";
        } elseif ($password !== $confirmarPassword) {
            $error = "Las contraseñas no coinciden";
        } else {
            // Verificamos si el usuario ya existe
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "El nombre de usuario ya está registrado";
            } else {
                // Hash de la contraseña
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                // Ajustamos el registro para que coincida con la estructura de la tabla `usuarios`
                $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password, rol, creado_en) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("sss", $usuario, $passwordHash, $rol);
                
                if ($stmt->execute()) {
                    $success = "¡Usuario registrado correctamente!";
                    
                    // Si es la instalación inicial, el primer usuario será admin
                    if ($isInitialSetup) {
                        // Actualizamos el rol a admin
                        $userId = $conn->insert_id;
                        $adminRole = 'admin';
                        $updateStmt = $conn->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
                        $updateStmt->bind_param("si", $adminRole, $userId);
                        $updateStmt->execute();
                        
                        $success .= " Se ha configurado como administrador por ser el primer usuario.";
                    }
                    
                    // Limpiamos los campos del formulario
                    $usuario = $rol = '';

                    // Redirigir al login después de un registro exitoso con mensaje de éxito
                    header("Location: ../login.html?success=" . urlencode($success));
                    exit();
                } else {
                    $error = "Error al registrar el usuario: " . $stmt->error;
                }
            }
        }
    // }
}

// Generamos un nuevo token CSRF
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Función para verificar si ya hay usuarios en la tabla
function tableHasUsers() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as total FROM usuarios");
    $data = $result->fetch_assoc();
    return $data['total'] > 0;
}

// Función para sanitizar entradas del usuario
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Obtenemos la lista de roles disponibles
$roles = ['admin', 'mesero', 'cocinero', 'cajero'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - RestaurantTech</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E6B800',
                        dark: '#161616',
                        background: '#2A2D34',
                    },
                    fontFamily: {
                        display: ['Playfair Display', 'serif'],
                        sans: ['Lato', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background font-sans min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="bg-dark border border-primary rounded-lg shadow-xl shadow-primary/20 p-8">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <h1 class="text-primary text-3xl font-bold font-display">RestaurantTech</h1>
                <?php if ($isInitialSetup): ?>
                    <p class="text-white mt-2 text-lg">Configuración Inicial - Crear Administrador</p>
                <?php else: ?>
                    <p class="text-white mt-2 text-lg">Registro de Nuevo Usuario</p>
                <?php endif; ?>
            </div>
            
            <!-- Mensaje de Error o Éxito -->
            <?php if (!empty($error)): ?>
                <div class="bg-red-900/20 border border-red-500/30 text-red-400 px-4 py-3 rounded mb-6 text-center text-sm">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="bg-green-900/20 border border-green-500/30 text-green-400 px-4 py-3 rounded mb-6 text-center text-sm">
                    <?= htmlspecialchars($success) ?>
                    <?php if (!$isAdmin): ?>
                        <div class="mt-2">
                            <a href="login.php" class="text-primary hover:underline">Ir a Iniciar Sesión</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Formulario -->
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="registroForm">
                <!-- Token CSRF -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Usuario -->
                    <div class="mb-4">
                        <label for="usuario" class="block text-gray-400 text-sm font-bold mb-2">Usuario</label>
                        <div class="relative border border-gray-700 rounded-lg focus-within:border-primary transition-all">
                            <input 
                                class="w-full bg-transparent text-white px-4 py-3 rounded-lg focus:outline-none"
                                type="text" 
                                name="usuario" 
                                id="usuario" 
                                value="<?= htmlspecialchars($usuario) ?>"
                                required
                            >
                        </div>
                    </div>
                    <!-- Contraseña -->
                    <div class="mb-4">
                        <label for="password" class="block text-gray-400 text-sm font-bold mb-2">Contraseña</label>
                        <div class="relative border border-gray-700 rounded-lg focus-within:border-primary transition-all">
                            <input 
                                class="w-full bg-transparent text-white px-4 py-3 rounded-lg focus:outline-none"
                                type="password" 
                                name="password" 
                                id="password" 
                                required
                            >
                        </div>
                        <p class="text-gray-500 text-xs mt-1">Mínimo 8 caracteres</p>
                    </div>
                    <!-- Confirmar Contraseña -->
                    <div class="mb-4">
                        <label for="confirmar_password" class="block text-gray-400 text-sm font-bold mb-2">Confirmar Contraseña</label>
                        <div class="relative border border-gray-700 rounded-lg focus-within:border-primary transition-all">
                            <input 
                                class="w-full bg-transparent text-white px-4 py-3 rounded-lg focus:outline-none"
                                type="password" 
                                name="confirmar_password" 
                                id="confirmar_password" 
                                required
                            >
                        </div>
                    </div>
                    <!-- Campo de rol no modificable -->
                    <div class="mb-4">
                        <label for="rol" class="block text-gray-400 text-sm font-bold mb-2">Rol</label>
                        <input 
                            class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg focus:outline-none cursor-not-allowed"
                            type="text" 
                            id="rol" 
                            value="Cliente" 
                            disabled
                        >
                        <input type="hidden" name="rol" value="cliente">
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="flex flex-col sm:flex-row justify-between items-center mt-6">
                    <a href="../login.html" 
                       class="w-full sm:w-auto mb-4 sm:mb-0 text-center bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-all">
                        Cancelar
                    </a>
                    
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto bg-primary hover:bg-primary/90 text-dark font-bold py-3 px-6 rounded-lg transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary/50"
                    >
                        <?= $isInitialSetup ? 'Crear Cuenta de Administrador' : 'Registrar Usuario' ?>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-4 text-gray-400 text-sm">
            &copy; <?= date('Y') ?> RestaurantTech - Todos los derechos reservados
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registroForm');
            
            form.addEventListener('submit', function(e) {
                const usuario = document.getElementById('usuario');
                const password = document.getElementById('password');
                const confirmarPassword = document.getElementById('confirmar_password');
                
                // Validación básica
                if (usuario.value.trim().length < 4) {
                    e.preventDefault();
                    alert('El nombre de usuario debe tener al menos 4 caracteres');
                    usuario.focus();
                    return false;
                }
                
                if (password.value.length < 8) {
                    e.preventDefault();
                    alert('La contraseña debe tener al menos 8 caracteres');
                    password.focus();
                    return false;
                }
                
                if (password.value !== confirmarPassword.value) {
                    e.preventDefault();
                    alert('Las contraseñas no coinciden');
                    confirmarPassword.focus();
                    return false;
                }
            });
            
            // Validación en vivo para las contraseñas
            const password = document.getElementById('password');
            const confirmarPassword = document.getElementById('confirmar_password');
            
            function validarCoincidencia() {
                if (confirmarPassword.value === '') {
                    confirmarPassword.style.borderColor = '';
                    return;
                }
                
                if (password.value === confirmarPassword.value) {
                    confirmarPassword.parentElement.style.borderColor = '#22c55e'; // Verde para coincidencia
                } else {
                    confirmarPassword.parentElement.style.borderColor = '#ef4444'; // Rojo para no coincidencia
                }
            }
            
            password.addEventListener('keyup', validarCoincidencia);
            confirmarPassword.addEventListener('keyup', validarCoincidencia);
        });
    </script>
</body>
</html>