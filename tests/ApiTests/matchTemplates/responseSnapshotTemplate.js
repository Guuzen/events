function responseSnapshotTemplate(bodyData) {
    if (bodyData === undefined) {
        return axiosResponseTemplate();
    }

    const template = axiosResponseTemplate();
    template.data = {
        data: bodyData
    };

    return template;
}

function axiosResponseTemplate() {
    return {
        statusText: expect.anything(),
        headers: expect.anything(),
        config: expect.anything(),
        request: expect.anything(),
    };
}

module.exports = responseSnapshotTemplate;
