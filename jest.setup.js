const failFast = require('jasmine-fail-fast');
const jasmineEnv = jasmine.getEnv();
jasmineEnv.addReporter(failFast.init());

const {toMatchSnapshot} = require('jest-snapshot');
expect.extend({
    toMatchResponseSnapshot(response, propertiesOrHint, hint) {
        return toMatchSnapshot.call(
            this,
            {
                status: response.status,
                body: response.body,
            },
            {
                body: {
                    data: propertiesOrHint
                }
            },
            hint,
        );
    }
});
