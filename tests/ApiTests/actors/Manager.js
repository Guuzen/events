class Manager {
    constructor(httpClient) {
        this.httpClient = httpClient;
    }

    createEvent(data) {
        return this.httpClient.post('/admin/event/create', data);
    }

    getEventsInList() {
        return this.httpClient.get('/admin/event/list');
    }

    getEventById(eventId) {
        return this.httpClient.get(`/admin/event/show?eventId=${eventId}`);
    }

    createTariff(data) {
        return this.httpClient.post('/admin/tariff/create', data);
    }

    getTariffsList(eventId) {
        return this.httpClient.get(`/admin/tariff/list?eventId=${eventId}`);
    }

    getTariffById(tariffId) {
        return this.httpClient.get(`/admin/tariff/show?tariffId=${tariffId}`);
    }

    createTicket(data) {
        return this.httpClient.post('/admin/ticket/create', data);
    }

    getTicketsList(eventId) {
        return this.httpClient.get(`/admin/ticket/list?eventId=${eventId}`)
    }

    getTicketById(ticketId) {
        return this.httpClient.get(`/admin/ticket/show?ticketId=${ticketId}`)
    }

    getOrdersList(eventId) {
        return this.httpClient.get(`/admin/order/list?eventId=${eventId}`)
    }

    getOrderById(orderId) {
        return this.httpClient.get(`/admin/order/show?orderId=${orderId}`);
    }

    markOrderPaid(data) {
        return this.httpClient.post('/admin/order/markPaid', data);
    }
}

module.exports = Manager;
