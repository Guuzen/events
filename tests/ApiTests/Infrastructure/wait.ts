const wait = function (milliseconds) {
    return new Promise(resolve => setTimeout(resolve, milliseconds));
}

module.exports = wait;