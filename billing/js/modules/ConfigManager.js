export class ConfigManager {
    constructor() {
        this.defaultSettings = {
            taxRate: 16,
            currencySymbol: '$',
            includeTax: true,
            autoNumbering: true,
            darkMode: false
        };
        
        this.defaultDesign = {
            fontSize: '14px',
            borderStyle: 'dashed',
            headerColor: '#4a6fa5',
            textColor: '#333333',
            accentColor: '#4fc3a1'
        };
        
        this.defaultRestaurantInfo = {
            name: 'Restaurant El Buen Sabor',
            address: 'Av. Principal #123, Ciudad',
            phone: '(123) 456-7890',
            message: 'Esperamos verle pronto.'
        };
        
        this.settings = { ...this.defaultSettings };
        this.design = { ...this.defaultDesign };
        this.restaurantInfo = { ...this.defaultRestaurantInfo };
    }
    
    async loadSettings() {
        try {
            const savedSettings = localStorage.getItem('restaurantSettings');
            if (savedSettings) {
                this.settings = { ...this.defaultSettings, ...JSON.parse(savedSettings) };
            }
            return this.settings;
        } catch (error) {
            console.error('Error al cargar configuración:', error);
            return this.defaultSettings;
        }
    }
    
    async saveSettings(newSettings) {
        try {
            this.settings = { ...this.settings, ...newSettings };
            localStorage.setItem('restaurantSettings', JSON.stringify(this.settings));
            return true;
        } catch (error) {
            console.error('Error al guardar configuración:', error);
            return false;
        }
    }
    
    async loadDesign() {
        try {
            const savedDesign = localStorage.getItem('ticketDesign');
            if (savedDesign) {
                this.design = { ...this.defaultDesign, ...JSON.parse(savedDesign) };
            }
            return this.design;
        } catch (error) {
            console.error('Error al cargar diseño:', error);
            return this.defaultDesign;
        }
    }
    
    async saveDesign(newDesign) {
        try {
            this.design = { ...this.design, ...newDesign };
            localStorage.setItem('ticketDesign', JSON.stringify(this.design));
            return true;
        } catch (error) {
            console.error('Error al guardar diseño:', error);
            return false;
        }
    }
    
    async loadRestaurantInfo() {
        try {
            const savedInfo = localStorage.getItem('restaurantInfo');
            if (savedInfo) {
                this.restaurantInfo = { ...this.defaultRestaurantInfo, ...JSON.parse(savedInfo) };
            }
            return this.restaurantInfo;
        } catch (error) {
            console.error('Error al cargar información del restaurante:', error);
            return this.defaultRestaurantInfo;
        }
    }
    
    async saveRestaurantInfo(newInfo) {
        try {
            this.restaurantInfo = { ...this.restaurantInfo, ...newInfo };
            localStorage.setItem('restaurantInfo', JSON.stringify(this.restaurantInfo));
            return true;
        } catch (error) {
            console.error('Error al guardar información del restaurante:', error);
            return false;
        }
    }
    
    getCurrentSettings() {
        return { ...this.settings };
    }
    
    getCurrentDesign() {
        return { ...this.design };
    }
    
    getCurrentRestaurantInfo() {
        return { ...this.restaurantInfo };
    }
    
    resetToDefaults() {
        this.settings = { ...this.defaultSettings };
        this.design = { ...this.defaultDesign };
        this.restaurantInfo = { ...this.defaultRestaurantInfo };
        
        localStorage.removeItem('restaurantSettings');
        localStorage.removeItem('ticketDesign');
        localStorage.removeItem('restaurantInfo');
    }
}