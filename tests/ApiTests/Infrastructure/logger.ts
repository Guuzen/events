const chalk = require('chalk');

const addLogger = function (httpClient, debug) {
    httpClient.interceptors.request.use(function (config) {
        const separator = '============================================================';
        const method = chalk.magenta(config.method.toUpperCase());
        const url = chalk.magenta(config.url);
        const data = JSON.stringify(config.data, null, 4);

        debug(`${separator} \n${method} ${url} \n${data}\n`);

        return config;
    })
    httpClient.interceptors.response.use(function (response) {
        const data = JSON.stringify(response.data, null, 4);
        const responsePrefix = `Response:`;
        const coloredresponsePrefix = response.status === 200 ?
            chalk.green(responsePrefix) :
            chalk.red(responsePrefix);

        debug(`\n${coloredresponsePrefix} \n${data} \n\n`);

        return response;
    }, function (error) {
        // Read https://www.npmjs.com/package/axios#handling-errors for more info
        debug('Boom', error);

        throw error;
    });
}
module.exports = addLogger;