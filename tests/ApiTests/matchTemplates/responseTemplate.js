function responseTemplate(bodyData, statusCode = 200) {
    return expect.objectContaining({
        status: statusCode,
        data: {
            data: bodyData
        }
    })
}

module.exports = responseTemplate;
