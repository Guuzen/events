const axios = require('axios');
const debug = require('debug')('axios:Visitor');
const addLogger = require('../Infrastructure/logger');
const axiosHttpAdapter = require('axios/lib/adapters/http');
const wait = require('../Infrastructure/wait');

const httpClient = axios.create({
    baseURL: process.env.TEST_API_URL,
    headers: {
        'Content-Type': 'application/json'
    },
    transformResponse: function (data) {
        return JSON.parse(data);
    },
    adapter: axiosHttpAdapter,
    validateStatus: status => true,
});

addLogger(httpClient, debug);

let endpointMock;

class Visitor {
    async wait(milliseconds) {
        await wait(milliseconds);
    }

    async applyPromocode(orderId) {
        const response = await httpClient.post('/ticketOrder/applyPromocode', {
            code: 'FOO',
            orderId: orderId,
        });

        expect(response.data).toEqual({
            data: [],
        });
    }

    async placeOrder(tariffId) {
        const response = await httpClient.post('/ticketOrder/place', {
            tariffId: tariffId,
            firstName: 'john',
            lastName: 'Doe',
            email: 'john@email.com',
            phone: '+123456789'
        });

        expect(response.data).toEqual({
            data: expect.any(String)
        });

        return response.data.data;
    }

    async awaitsEmailWithTicket(emailServer) {
        endpointMock = await emailServer.post(`${process.env.TEST_MAILER_HOST}/send_ticket_email`).thenReply(200);
    }

    async receivesEmailWithTicket() {
        const requests = await endpointMock.getSeenRequests();

        expect(requests[0].body.json).toEqual({
            subject: 'Thanks for buy ticket',
            from: {'no-reply@event.com': null},
            to: {'john@email.com': null},
        })
    }

    async payOrderByCard(orderId) {
         const response = await httpClient.post(`/ticketOrder/${orderId}/payByCard`, {});

         expect(response.data).toEqual({data: expect.any(String)});
    }
}

module.exports = Visitor;
