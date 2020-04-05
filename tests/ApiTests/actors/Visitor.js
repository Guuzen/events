class Visitor {
    constructor(httpClient, mailHttpClient) {
        this.httpClient = httpClient;
        this.mailHttpClient = mailHttpClient;
    }

    placeOrder(data) {
        return this.httpClient.post('/order/place', data);
    }

    getReceivedEmails() {
        return this.mailHttpClient.get('/api/v2/messages');
    }
}

module.exports = Visitor;
