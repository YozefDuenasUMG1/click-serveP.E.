import { showToast } from '../../assets/toast.js';

export class UIManager {
    constructor(productManager, ticketManager, configManager) {
        this.productManager = productManager;
        this.ticketManager = ticketManager;
        this.configManager = configManager;
    }
    
    initEventListeners() {
        // Formulario de productos
        document.getElementById('product-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleAddProduct();
        });
        
        // Botones de acciones
        document.getElementById('print-ticket').addEventListener('click', () => {
            this.handlePrintTicket();
        });
        
        document.getElementById('save-ticket').addEventListener('click', async () => {
            await this.handleSaveTicket();
        });
        
        document.getElementById('new-ticket').addEventListener('click', () => {
            this.handleNewTicket();
        });
        
        // Configuración del restaurante
        document.getElementById('restaurant-info-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleUpdateRestaurantInfo();
        });
        
        // Diseño del ticket
        document.getElementById('ticket-design-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleApplyDesign();
        });
        
        // Configuración del sistema
        document.getElementById('system-settings-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSaveSettings();
        });
        
        // Tabs
        document.querySelectorAll('.tab-link').forEach(tab => {
            tab.addEventListener('click', () => {
                this.switchTab(tab);
            });
        });
        
        // Previsualización de colores
        document.querySelectorAll('input[type="color"]').forEach(picker => {
            picker.addEventListener('input', () => {
                picker.nextElementSibling.style.backgroundColor = picker.value;
            });
        });
        
        // Modo oscuro
        document.getElementById('toggle-dark-mode').addEventListener('click', () => {
            this.toggleDarkMode();
        });
    }
    
    applyLoadedSettings() {
        const settings = this.configManager.getCurrentSettings();
        
        // Aplicar configuración
        document.getElementById('tax-rate').value = settings.taxRate;
        document.getElementById('currency-symbol').value = settings.currencySymbol;
        document.getElementById('include-tax').checked = settings.includeTax;
        document.getElementById('auto-numbering').checked = settings.autoNumbering;
        document.getElementById('dark-mode').checked = settings.darkMode;
        
        // Aplicar modo oscuro si está activado
        if (settings.darkMode) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
        
        // Actualizar UI con la configuración
        this.updateTaxDisplay();
    }
    
    applyLoadedDesign() {
        const design = this.configManager.getCurrentDesign();
        
        // Aplicar diseño
        document.getElementById('font-size').value = design.fontSize;
        document.getElementById('border-style').value = design.borderStyle;
        document.getElementById('header-color').value = design.headerColor;
        document.getElementById('text-color').value = design.textColor;
        document.getElementById('accent-color').value = design.accentColor;
        
        // Actualizar previsualizaciones de color
        document.querySelectorAll('.color-preview').forEach(preview => {
            const inputId = preview.previousElementSibling.id;
            preview.style.backgroundColor = document.getElementById(inputId).value;
        });
        
        // Aplicar diseño al ticket
        this.applyDesignToTicket(design);
    }
    
    async handleAddProduct() {
        try {
            const name = document.getElementById('product-name').value;
            const price = parseFloat(document.getElementById('product-price').value);
            const qty = parseInt(document.getElementById('product-qty').value);
            
            if (!name || isNaN(price) || isNaN(qty)) {
                throw new Error('Por favor complete todos los campos correctamente');
            }
            
            const product = this.productManager.addProduct(name, price, qty);
            
            // Actualizar UI
            this.updateProductList();
            this.updateTicket();
            this.calculateTotals();
            
            // Limpiar formulario
            document.getElementById('product-name').value = '';
            document.getElementById('product-price').value = '';
            document.getElementById('product-qty').value = '1';
            document.getElementById('product-name').focus();
            
            showToast('Producto agregado correctamente', 'success');
        } catch (error) {
            showToast(error.message, 'error');
        }
    }
    
    async handlePrintTicket() {
        if (this.productManager.getProducts().length === 0) {
            showToast('Agregue al menos un producto para imprimir el ticket', 'warning');
            return;
        }
        
        window.print();
    }
    
    async handleSaveTicket() {
        if (this.productManager.getProducts().length === 0) {
            showToast('Agregue al menos un producto para guardar el ticket', 'warning');
            return;
        }

        const saveButton = document.getElementById('save-ticket');
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

        try {
            const ticketNumber = document.getElementById('ticket-number').textContent;
            const date = document.getElementById('ticket-date').textContent;
            const time = document.getElementById('ticket-time').textContent;
            const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace(/[^0-9.-]/g, ''));
            const tax = parseFloat(document.getElementById('tax').textContent.replace(/[^0-9.-]/g, ''));
            const total = parseFloat(document.getElementById('total').textContent.replace(/[^0-9.-]/g, ''));

            const factura = {
                numero_factura: ticketNumber,
                fecha: date,
                hora: time,
                subtotal: subtotal,
                iva: tax,
                total: total,
                cliente: 'Cliente General'
            };

            console.log('Enviando datos al servidor:', factura);
            
            const response = await fetch('../guardar_factura.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(factura)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Error en la respuesta del servidor');
            }

            const result = await response.json();
            
            if (result.success) {
                showToast(`Factura #${ticketNumber} guardada correctamente`, 'success');
                
                // Preguntar si desea nuevo ticket
                if (confirm('¿Desea crear un nuevo ticket?')) {
                    this.handleNewTicket();
                }
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error al guardar factura:', error);
            showToast('Error al guardar factura: ' + error.message, 'error');
        } finally {
            saveButton.disabled = false;
            saveButton.innerHTML = '<i class="fas fa-save"></i> Guardar Ticket';
        }
    }
    
    async handleNewTicket() {
        this.productManager.clearProducts();
        this.updateProductList();
        this.updateTicket();
        this.calculateTotals();
        
        const settings = this.configManager.getCurrentSettings();
        if (settings.autoNumbering) {
            document.getElementById('ticket-number').textContent = 
                String(this.ticketManager.ticketNumber).padStart(3, '0');
        }
        
        this.updateDateTime();
        showToast('Nuevo ticket creado', 'success');
    }
    
    async handleUpdateRestaurantInfo() {
        try {
            const newInfo = {
                name: document.getElementById('config-name').value.trim(),
                address: document.getElementById('config-address').value.trim(),
                phone: document.getElementById('config-phone').value.trim(),
                message: document.getElementById('config-message').value.trim()
            };
            
            if (!newInfo.name) {
                throw new Error('El nombre del restaurante no puede estar vacío');
            }
            
            await this.configManager.saveRestaurantInfo(newInfo);
            
            // Actualizar UI
            document.getElementById('restaurant-name').textContent = newInfo.name;
            document.getElementById('restaurant-address').textContent = newInfo.address;
            document.getElementById('restaurant-phone').textContent = newInfo.phone ? `Tel: ${newInfo.phone}` : '';
            document.getElementById('custom-message').textContent = newInfo.message;
            
            showToast('Información del restaurante actualizada', 'success');
        } catch (error) {
            showToast(error.message, 'error');
        }
    }
    
    async handleApplyDesign() {
        try {
            const newDesign = {
                fontSize: document.getElementById('font-size').value,
                borderStyle: document.getElementById('border-style').value,
                headerColor: document.getElementById('header-color').value,
                textColor: document.getElementById('text-color').value,
                accentColor: document.getElementById('accent-color').value
            };
            
            await this.configManager.saveDesign(newDesign);
            this.applyDesignToTicket(newDesign);
            
            showToast('Diseño aplicado correctamente', 'success');
        } catch (error) {
            showToast('Error al aplicar el diseño: ' + error.message, 'error');
        }
    }
    
    async handleSaveSettings() {
        try {
            const newSettings = {
                taxRate: parseFloat(document.getElementById('tax-rate').value),
                currencySymbol: document.getElementById('currency-symbol').value,
                includeTax: document.getElementById('include-tax').checked,
                autoNumbering: document.getElementById('auto-numbering').checked,
                darkMode: document.getElementById('dark-mode').checked
            };
            
            if (isNaN(newSettings.taxRate)) {
                throw new Error('La tasa de impuesto debe ser un número válido');
            }
            
            await this.configManager.saveSettings(newSettings);
            
            // Aplicar cambios
            this.updateTaxDisplay();
            this.updateProductList();
            this.updateTicket();
            this.calculateTotals();
            
            // Aplicar modo oscuro
            if (newSettings.darkMode) {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.removeAttribute('data-theme');
            }
            
            showToast('Configuración guardada correctamente', 'success');
        } catch (error) {
            showToast(error.message, 'error');
        }
    }
    
    updateProductList() {
        const products = this.productManager.getProducts();
        const productList = document.getElementById('product-list');
        const settings = this.configManager.getCurrentSettings();
        
        productList.innerHTML = '';
        
        products.forEach(product => {
            const row = document.createElement('tr');
            row.className = 'fade-in';
            row.innerHTML = `
                <td>${product.name}</td>
                <td>${settings.currencySymbol}${product.price.toFixed(2)}</td>
                <td>${product.quantity}</td>
                <td>${settings.currencySymbol}${product.total.toFixed(2)}</td>
                <td>
                    <button class="delete-btn" data-id="${product.id}">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </td>
            `;
            productList.appendChild(row);
        });
        
        // Agregar event listeners para los botones de eliminar
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.getAttribute('data-id'));
                this.productManager.removeProduct(id);
                this.updateProductList();
                this.updateTicket();
                this.calculateTotals();
                showToast('Producto eliminado', 'success');
            });
        });
    }
    
    updateTicket() {
        const products = this.productManager.getProducts();
        const ticketItems = document.getElementById('ticket-items');
        const settings = this.configManager.getCurrentSettings();
        
        ticketItems.innerHTML = '';
        
        products.forEach(product => {
            const row = document.createElement('tr');
            row.className = 'fade-in';
            row.innerHTML = `
                <td>${product.quantity}</td>
                <td>${product.name}</td>
                <td>${settings.currencySymbol}${product.price.toFixed(2)}</td>
                <td>${settings.currencySymbol}${product.total.toFixed(2)}</td>
            `;
            ticketItems.appendChild(row);
        });
    }
    
    calculateTotals() {
        const settings = this.configManager.getCurrentSettings();
        const subtotal = this.productManager.calculateSubtotal();
        const tax = this.productManager.calculateTax(settings.taxRate);
        const total = this.productManager.calculateTotal(settings.taxRate, settings.includeTax);
        
        document.getElementById('subtotal').textContent = 
            `${settings.currencySymbol}${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = 
            `${settings.currencySymbol}${tax.toFixed(2)}`;
        document.getElementById('total').textContent = 
            `${settings.currencySymbol}${total.toFixed(2)}`;
        
        // Mostrar u ocultar la fila de impuestos según la configuración
        const taxElement = document.getElementById('tax').parentElement;
        taxElement.style.display = settings.includeTax ? 'block' : 'none';
    }
    
    updateTaxDisplay() {
        const settings = this.configManager.getCurrentSettings();
        document.getElementById('tax-rate-display').textContent = settings.taxRate;
    }
    
    updateDateTime() {
        const now = new Date();
        const dateOptions = { year: 'numeric', month: '2-digit', day: '2-digit' };
        const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };
        
        document.getElementById('ticket-date').textContent = now.toLocaleDateString('es-MX', dateOptions);
        document.getElementById('ticket-time').textContent = now.toLocaleTimeString('es-MX', timeOptions);
    }
    
    applyDesignToTicket(design) {
        const ticket = document.getElementById('ticket');
        const ticketHeader = document.querySelector('.ticket-header');
        
        // Aplicar estilos
        ticket.style.fontSize = design.fontSize;
        ticket.style.borderStyle = design.borderStyle;
        ticket.style.color = design.textColor;
        
        // Estilo del encabezado
        ticketHeader.style.color = design.headerColor;
        
        // Aplicar color de acento a los botones
        document.documentElement.style.setProperty('--accent-color', design.accentColor);
    }
    
    switchTab(clickedTab) {
        // Desactivar todas las pestañas
        document.querySelectorAll('.tab-link').forEach(tab => {
            tab.classList.remove('active');
            tab.setAttribute('aria-selected', 'false');
        });
        
        // Ocultar todos los paneles
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('active');
            pane.hidden = true;
        });
        
        // Activar la pestaña clickeada
        clickedTab.classList.add('active');
        clickedTab.setAttribute('aria-selected', 'true');
        
        // Mostrar el panel correspondiente
        const tabId = clickedTab.getAttribute('data-tab');
        const tabPane = document.getElementById(tabId);
        tabPane.classList.add('active');
        tabPane.hidden = false;
    }
    
    toggleDarkMode() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? null : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme || '');
        
        // Actualizar configuración
        const settings = this.configManager.getCurrentSettings();
        settings.darkMode = newTheme === 'dark';
        this.configManager.saveSettings(settings);
        
        // Cambiar icono
        const icon = document.querySelector('#toggle-dark-mode i');
        icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        
        showToast(`Modo ${newTheme === 'dark' ? 'oscuro' : 'claro'} activado`, 'success');
    }
}