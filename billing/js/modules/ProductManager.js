export class ProductManager {
    constructor() {
        this.products = [];
    }
    
    addProduct(name, price, quantity) {
        if (!name || !price || !quantity) {
            throw new Error('Todos los campos son requeridos');
        }
        
        const parsedPrice = parseFloat(price);
        const parsedQty = parseInt(quantity);
        
        if (isNaN(parsedPrice)) {
            throw new Error('El precio debe ser un número válido');
        }
        
        if (isNaN(parsedQty)) {
            throw new Error('La cantidad debe ser un número entero válido');
        }
        
        if (parsedPrice <= 0) {
            throw new Error('El precio debe ser mayor que cero');
        }
        
        if (parsedQty <= 0) {
            throw new Error('La cantidad debe ser mayor que cero');
        }
        
        const newProduct = {
            id: Date.now(), // ID único basado en timestamp
            name: name.trim(),
            price: parsedPrice,
            quantity: parsedQty,
            total: parsedPrice * parsedQty
        };
        
        this.products.push(newProduct);
        return newProduct;
    }
    
    removeProduct(id) {
        this.products = this.products.filter(product => product.id !== id);
    }
    
    getProducts() {
        return [...this.products]; // Devolver copia para evitar mutaciones externas
    }
    
    clearProducts() {
        this.products = [];
    }
    
    calculateSubtotal() {
        return this.products.reduce((sum, product) => sum + product.total, 0);
    }
    
    calculateTax(taxRate) {
        const subtotal = this.calculateSubtotal();
        return subtotal * (taxRate / 100);
    }
    
    calculateTotal(taxRate, includeTax = true) {
        const subtotal = this.calculateSubtotal();
        const tax = this.calculateTax(taxRate);
        return includeTax ? subtotal + tax : subtotal;
    }
}