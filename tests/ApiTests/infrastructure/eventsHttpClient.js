const axios = require('axios');
const axiosHttpAdapter = require('axios/lib/adapters/http');

const eventsHttpClient = axios.create({
    baseURL: 'http://guuzen-events-nginx',
    headers: {
        'Content-Type': 'application/json'
    },
    transformResponse: function (data) {
        return JSON.parse(data);
    },
    adapter: axiosHttpAdapter,
    validateStatus: status => true,
});

module.exports = eventsHttpClient;
