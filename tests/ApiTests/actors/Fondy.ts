const axios = require('axios');
const debug = require('debug')('axios:Fondy');
const addLogger = require('../Infrastructure/logger');
const axiosHttpAdapter = require('axios/lib/adapters/http');

const httpClient = axios.create({
    baseURL: process.env.TEST_API_URL + '/fondy',
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

class Fondy {
    async markOrderPaid(orderId) {
        const response = await httpClient.post(`/${orderId}/markPaid`, {});

        expect(response.data).toEqual({data: []});
    }
}

module.exports = Fondy;
