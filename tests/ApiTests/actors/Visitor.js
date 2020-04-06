class Visitor {
    constructor(httpClient, emailClient) {
        this.httpClient = httpClient;
        this.emailClient = emailClient;
    }

    placeOrder(data) {
        return this.httpClient.post('/order/place', data);
    }

    getReceivedEmails() {
        return this.emailClient.receiveEmails();
    }

    payOrderByCard(data) {
        return this.httpClient.post('/order/payByCard', data);
    }
}

module.exports = Visitor;
