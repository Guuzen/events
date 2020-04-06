class EmailClient {
    constructor(httpClient) {
        this.httpClient = httpClient;
    }

    async receiveEmails() {
        const response = await this.httpClient.get('/api/v2/messages');
        await this.httpClient.delete('/api/v1/messages');

        return response.data;
    }
}


module.exports = EmailClient;
