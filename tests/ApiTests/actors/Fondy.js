class Fondy {
    constructor(httpClient) {
        this.httpClient = httpClient;
    }

    markOrderPaid(data) {
        return this.httpClient.post('/order/markPaidByFondy', data);
    }
}

module.exports = Fondy;
