<?php
// Iniciamos la sesión
session_start();

// Importamos la configuración
require_once __DIR__ . '/config.php';

// Si el usuario ya está autenticado, lo redirigimos según su rol
if (isset($_SESSION['user_id']) && isset($_SESSION['rol'])) {
    redirectByRole($_SESSION['rol']);
    exit();
}

// Variables para el formulario
$error = '';

// Procesamos el formulario de login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificamos el token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Error de seguridad. Por favor, intente nuevamente.";
    } else {
        $usuario = trim($_POST['usuario']);
        $password = $_POST['password'];

        // Validamos que los campos no estén vacíos
        if (empty($usuario) || empty($password)) {
            $error = "Por favor complete todos los campos";
        } else {
            // Consulta preparada para evitar inyección SQL
            $sql = "SELECT id, usuario, password, rol FROM usuarios WHERE usuario = ? AND estado = 'activo'";
            $stmt = $conn->prepare($sql);

            // Verificar si la consulta SQL se preparó correctamente
            if (!$stmt) {
                $error = "Error en el sistema. Por favor contacte al administrador.";
                // Log del error (pero no exponemos detalles al usuario)
                error_log("Error en la consulta SQL: " . $conn->error);
            } else {
                $stmt->bind_param("s", $usuario);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $usuarioData = $result->fetch_assoc();
                    if (password_verify($password, $usuarioData['password'])) {
                        // Regeneramos el ID de sesión por seguridad
                        session_regenerate_id(true);
                        
                        // Guardamos los datos del usuario en la sesión
                        $_SESSION['user_id'] = $usuarioData['id'];
                        $_SESSION['usuario'] = $usuarioData['usuario'];
                        $_SESSION['rol'] = $usuarioData['rol'];
                        $_SESSION['ultimo_acceso'] = time();
                        
                        // Registramos el acceso exitoso
                        error_log("Usuario {$usuarioData['usuario']} (ID: {$usuarioData['id']}) ha iniciado sesión");
                        
                        // Redirigimos según el rol
                        redirectByRole($usuarioData['rol']);
                        exit();
                    } else {
                        // Contraseña incorrecta
                        $error = "Credenciales inválidas";
                        // Incrementar contador de intentos fallidos (implementación básica)
                        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
                    }
                } else {
                    // Usuario no encontrado o inactivo
                    $error = "Usuario no encontrado o inactivo";
                }
            }
        }
    }
}

// Generamos un nuevo token CSRF
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Función para redireccionar según el rol
function redirectByRole($rol) {
    $baseUrl = getBaseUrl();
    
    $redirect = match($rol) {
        'admin'    => $baseUrl . 'modulos/admin/dashboard.php',
        'mesero'   => $baseUrl . 'modulos/mesas/mesas.php',
        'cocinero' => $baseUrl . 'modulos/cocina/ordenes.php',
        'cajero'   => $baseUrl . 'modulos/caja/caja.php',
        default    => $baseUrl . 'login.php?error=rol_no_valido'
    };
    
    header("Location: $redirect");
    exit();
}

// Función para obtener la URL base
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);
    return $protocol . $host . rtrim($path, '/') . '/';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - RestaurantTech</title>
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
    <div class="w-full max-w-md">
        <div class="bg-dark border border-primary rounded-lg shadow-xl shadow-primary/20 p-8">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <h1 class="text-primary text-3xl font-bold font-display">RestaurantTech</h1>
                <p class="text-white mt-2 text-lg">Bienvenido al Sistema</p>
            </div>
            
            <!-- Mensaje de Error -->
            <?php if (!empty($error)): ?>
                <div class="bg-red-900/20 border border-red-500/30 text-red-400 px-4 py-3 rounded mb-6 text-center text-sm">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <!-- Formulario -->
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="loginForm">
                <!-- Token CSRF -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <!-- Usuario -->
                <div class="mb-4">
                    <div class="relative border border-gray-700 rounded-lg focus-within:border-primary transition-all">
                        <input 
                            class="w-full bg-transparent text-white px-4 py-3 rounded-lg focus:outline-none"
                            placeholder="Usuario" 
                            type="text" 
                            name="usuario" 
                            id="usuario" 
                            required
                        >
                    </div>
                </div>
                
                <!-- Contraseña -->
                <div class="mb-6">
                    <div class="relative border border-gray-700 rounded-lg focus-within:border-primary transition-all">
                        <input 
                            class="w-full bg-transparent text-white px-4 py-3 rounded-lg focus:outline-none"
                            placeholder="Contraseña" 
                            type="password" 
                            name="password" 
                            id="password" 
                            required
                        >
                    </div>
                </div>
                
                <!-- Botón de Inicio de Sesión -->
                <button 
                    type="submit" 
                    class="w-full bg-primary hover:bg-primary/90 text-dark font-bold py-3 px-4 rounded-lg transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary/50"
                >
                    Iniciar Sesión
                </button>
            </form>
            
            <!-- Decoración -->
            <div class="flex justify-center mt-8">
                <div class="w-8 h-2 bg-primary rounded-full"></div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-4 text-gray-400 text-sm">
            &copy; <?= date('Y') ?> RestaurantTech - Todos los derechos reservados
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            
            form.addEventListener('submit', function(e) {
                const usuario = document.getElementById('usuario');
                const password = document.getElementById('password');
                
                // Validación básica
                if (usuario.value.trim() === '') {
                    e.preventDefault();
                    alert('Por favor ingrese su nombre de usuario');
                    usuario.focus();
                    return false;
                }
                
                if (password.value.trim() === '') {
                    e.preventDefault();
                    alert('Por favor ingrese su contraseña');
                    password.focus();
                    return false;
                }
            });
        });
    </script>
</body>
</html>