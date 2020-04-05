function responseSnapshotTemplate(bodyData) {
    return {
        data: {
            data: bodyData
        },
        statusText: expect.anything(),
        headers: expect.anything(),
        config: expect.anything(),
        request: expect.anything(),
    };
}

module.exports = responseSnapshotTemplate;
