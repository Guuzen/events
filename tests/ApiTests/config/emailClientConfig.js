const axiosHttpAdapter = require('axios/lib/adapters/http');

const emailClientConfig = {
    baseURL: 'http://mailhog:8025',
    headers: {
        'Content-Type': 'application/json'
    },
    adapter: axiosHttpAdapter,
    responseType: 'json',
    transformResponse: function (data) {
        if (data.length > 0) {
            return JSON.parse(data);
        }

        return data;
    },
    validateStatus: status => true,
};

module.exports = emailClientConfig;
