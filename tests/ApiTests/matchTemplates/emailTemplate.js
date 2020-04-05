const emailTemplate = () => {
    return {
        ID: expect.any(String),
        Created: expect.any(String),
        Raw: {
            Data: expect.any(String),
        },
        Content: {
            Headers: {
                Date: expect.anything(),
                'Message-ID': expect.anything(),
                Received: expect.anything(),
            }
        }
    };
};


module.exports = emailTemplate;
