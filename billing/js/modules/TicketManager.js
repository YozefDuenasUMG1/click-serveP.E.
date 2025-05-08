export class TicketManager {
    constructor() {
        this.ticketNumber = 1;
        this.ticketHistory = [];
    }
    
    generateNewTicket(products, settings, restaurantInfo, design) {
        if (!products || products.length === 0) {
            throw new Error('No hay productos para generar el ticket');
        }
        
        const now = new Date();
        const dateOptions = { year: 'numeric', month: '2-digit', day: '2-digit' };
        const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };
        
        const ticket = {
            id: Date.now(),
            number: this.ticketNumber,
            date: now.toLocaleDateString('es-MX', dateOptions),
            time: now.toLocaleTimeString('es-MX', timeOptions),
            products: [...products],
            subtotal: this.calculateSubtotal(products),
            tax: settings.includeTax ? this.calculateTax(products, settings.taxRate) : 0,
            total: this.calculateTotal(products, settings.taxRate, settings.includeTax),
            restaurantInfo: { ...restaurantInfo },
            design: { ...design },
            settings: { ...settings },
            createdAt: now
        };
        
        this.ticketHistory.push(ticket);
        this.ticketNumber++;
        
        return ticket;
    }
    
    calculateSubtotal(products) {
        return products.reduce((sum, product) => sum + product.total, 0);
    }
    
    calculateTax(products, taxRate) {
        const subtotal = this.calculateSubtotal(products);
        return subtotal * (taxRate / 100);
    }
    
    calculateTotal(products, taxRate, includeTax = true) {
        const subtotal = this.calculateSubtotal(products);
        const tax = this.calculateTax(products, taxRate);
        return includeTax ? subtotal + tax : subtotal;
    }
    
    getTicketHistory() {
        return [...this.ticketHistory]; // Devolver copia para evitar mutaciones externas
    }
    
    resetTicketNumber() {
        this.ticketNumber = 1;
    }
}