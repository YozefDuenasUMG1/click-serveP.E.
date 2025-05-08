import { ProductManager } from './modules/ProductManager.js';
import { TicketManager } from './modules/TicketManager.js';
import { ConfigManager } from './modules/ConfigManager.js';
import { UIManager } from './modules/UIManager.js';
import { showToast } from '../assets/toast.js';

// Inicialización de módulos
const productManager = new ProductManager();
const ticketManager = new TicketManager();
const configManager = new ConfigManager();
const uiManager = new UIManager(productManager, ticketManager, configManager);

// Cargar configuración al iniciar
document.addEventListener('DOMContentLoaded', async () => {
    console.log('DOMContentLoaded activado, inicializando scripts...');
    try {
        // Cargar configuración guardada
        await configManager.loadSettings();
        await configManager.loadDesign();
        
        // Aplicar configuración cargada
        uiManager.applyLoadedSettings();
        uiManager.applyLoadedDesign();
        
        // Actualizar cada minuto
        setInterval(() => uiManager.updateDateTime(), 60000);
        
        showToast('Sistema cargado correctamente', 'success');
    } catch (error) {
        console.error('Error al inicializar la aplicación:', error);
        showToast('Error al cargar la configuración', 'error');
    }
});