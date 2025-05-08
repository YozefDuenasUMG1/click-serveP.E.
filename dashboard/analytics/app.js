document.addEventListener('DOMContentLoaded', function() {
    // Inicialización
    const fechaInput = document.getElementById('selector-fecha');
    const hoy = new Date().toISOString().split('T')[0];
    fechaInput.value = hoy;
    fechaInput.max = hoy;
    
    mostrarFechaActual(hoy);
    cargarPedidos(hoy);
});

function mostrarFechaActual(fecha) {
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const fechaObj = new Date(fecha);
    const fechaFormateada = fechaObj.toLocaleDateString('es-ES', opciones);
    
    document.getElementById('fecha-actual').textContent = fechaFormateada;
    document.getElementById('fecha-consulta').textContent = `Mostrando pedidos del ${fechaFormateada}`;
}

// Función para mostrar errores
function mostrarError(mensaje) {
    const container = document.getElementById('pedidos-container');
    container.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error:</strong> ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    console.error(mensaje);
}

// Validar que los elementos existen antes de acceder a sus propiedades
function cargarPedidos(fecha) {
    const mesaInput = document.getElementById('filtro-mesa');
    const fechaInput = document.getElementById('selector-fecha');

    if (!mesaInput || !fechaInput) {
        console.error('Elementos necesarios no encontrados en el DOM');
        mostrarError('Error interno: No se encontraron los elementos de filtro.');
        return;
    }

    const mesa = mesaInput.value;
    const estado = 'todos'; // Estado fijo ya que se eliminó el filtro por estado

    // Mostrar carga
    const container = document.getElementById('pedidos-container');
    container.innerHTML = `
        <div class="text-center my-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando registros...</p>
        </div>
    `;

    // Usar URL absoluta para evitar problemas de ruta
    const url = new URL('registro_pedidos.php', window.location.href);
    url.searchParams.append('fecha', fecha);
    if (mesa) url.searchParams.append('mesa', mesa);
    url.searchParams.append('_', Date.now()); // Evitar caché

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json().catch(() => {
                throw new Error('La respuesta no es JSON válido');
            });
        })
        .then(data => {
            if (!data || typeof data !== 'object') {
                throw new Error('Respuesta inválida del servidor');
            }
            
            if (data.success) {
                mostrarPedidos(data.pedidos);
                mostrarResumen(data);
                mostrarFechaActual(data.fecha_consulta);
            } else {
                throw new Error(data.error || data.message || 'Error desconocido');
            }
        })
        .catch(error => {
            mostrarError(error.message);
            console.error('Error en cargarPedidos:', error);
        });
}

function mostrarResumen(data) {
    document.getElementById('total-pedidos').textContent = data.total_pedidos;
    document.getElementById('total-importe').textContent = data.total_importe;
}

function mostrarPedidos(pedidos) {
    const container = document.getElementById('pedidos-container');
    container.innerHTML = '';
    
    if (!pedidos || pedidos.length === 0) {
        container.innerHTML = `
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> No se encontraron registros para los filtros seleccionados
            </div>
        `;
        return;
    }
    
    pedidos.forEach(pedido => {
        const card = document.createElement('div');
        card.className = `card mb-4 estado-${pedido.estado.toLowerCase()}`;
        card.style.borderLeft = `4px solid ${getEstadoColor(pedido.estado)}`;
        
        // Procesar items
        const itemsHtml = procesarItemsPedido(pedido);
        
        // Formatear fechas
        const fechaPedido = new Date(pedido.fecha_hora_pedido);
        const fechaRegistro = new Date(pedido.fecha_hora_registro);
        
        // Asegurar que el total sea numérico antes de usar toFixed
        const total = parseFloat(pedido.total) || 0;

        card.innerHTML = `
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Mesa ${pedido.mesa}</h5>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-calendar"></i> ${fechaPedido.toLocaleDateString('es-ES')}
                            <i class="bi bi-clock ms-2"></i> ${fechaPedido.toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'})}
                        </div>
                    </div>
                    <div>
                        <span class="badge ${getBadgeClass(pedido.estado)}">
                            ${pedido.estado === 'pendiente' ? 'ENTREGADO' : pedido.estado.toUpperCase()}
                        </span>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <h6 class="text-primary">Detalles del Pedido</h6>
                    ${itemsHtml}
                </div>
                
                ${pedido.detalle ? `
                <div class="alert alert-light border mb-3">
                    <strong>Notas:</strong> ${pedido.detalle}
                </div>
                ` : ''}
                
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-clock-history"></i> Registrado: ${fechaRegistro.toLocaleString('es-ES')}
                    </small>
                    <div class="fw-bold fs-5">
                        Total: Q${total.toFixed(2)}
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(card);
    });
}

function procesarItemsPedido(pedido) {
    if (!pedido.items_json) {
        return `<div class="p-2 bg-light rounded">
            <p class="mb-0">${pedido.pedido || 'No hay detalles específicos del pedido'}</p>
        </div>`;
    }
    
    try {
        const items = JSON.parse(pedido.items_json);
        if (!Array.isArray(items)) {
            return `<div class="p-2 bg-light rounded">
                <p class="mb-0">${pedido.pedido}</p>
            </div>`;
        }
        
        return `<ul class="list-group">${items.map(item => `
            <li class="list-group-item">
                <div class="d-flex justify-content-between">
                    <span><strong>${item.nombre}</strong> x${item.cantidad}</span>
                    <span class="text-muted">Q${(item.precio * item.cantidad).toFixed(2)}</span>
                </div>
                ${item.ingredientes_removidos?.length ? `
                <div class="mt-1 small text-danger">
                    <i class="bi bi-x-circle"></i> Sin: ${item.ingredientes_removidos.join(', ')}
                </div>
                ` : ''}
            </li>
        `).join('')}</ul>`;
    } catch (e) {
        console.error('Error procesando items:', e);
        return `<div class="alert alert-danger small">
            Error al procesar los items del pedido: ${e.message}
        </div>`;
    }
}

function getEstadoColor(estado) {
    const estados = {
        pendiente: '#198754', // Cambiar a verde
        completado: '#198754',
        cancelado: '#dc3545',
        default: '#6c757d'
    };
    return estados[estado.toLowerCase()] || estados.default;
}

function getBadgeClass(estado) {
    const estados = {
        pendiente: 'bg-success', // Cambiar a verde
        completado: 'bg-success',
        cancelado: 'bg-danger',
        default: 'bg-secondary'
    };
    return estados[estado.toLowerCase()] || estados.default;
}

// Función para filtrar pedidos
function filtrarPedidos() {
    const fechaInput = document.getElementById('selector-fecha');
    const fecha = fechaInput ? fechaInput.value : new Date().toISOString().split('T')[0];
    cargarPedidos(fecha);
}

// Resto de funciones (filtrarPedidos, actualizarPedidos, mostrarError) se mantienen igual