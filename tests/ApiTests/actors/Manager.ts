const axios = require('axios');
const addLogger = require('../Infrastructure/logger');
const debug = require('debug')('axios:Manager');
const axiosHttpAdapter = require('axios/lib/adapters/http');
const wait = require('../Infrastructure/wait');

const httpClient = axios.create({
    baseURL: process.env.TEST_API_URL,
    headers: {
        'Content-Type': 'application/json',
    },
    transformResponse: function (data) {
        try {
            return JSON.parse(data);
        } catch (error) {
            console.log(data);
        }
    },
    adapter: axiosHttpAdapter,
    validateStatus: status => true,
});
addLogger(httpClient, debug);

class Manager {

    async wait(milliseconds) {
        await wait(milliseconds);
    }

    async createEventDomain() {
        const response = await httpClient.post('/admin/eventDomain', {
            name: '2019 foo event',
            domain: 'localhost',
        });

        expect(response.data).toEqual({
            data: expect.any(String),
        });

        return response.data.data;
    }

    async getEventsInList() {
        const response = await httpClient.get('/admin/eventDomain');

        expect(response.data).toEqual({
            data: [
                {
                    id: expect.any(String),
                    domain: 'localhost',
                    name: '2019 foo event',
                },
            ],
        });
    }

    async getEventById(eventId) {
        const response = await httpClient.get(`/admin/eventDomain/${eventId}`);
        expect(response.data).toEqual({
            data: {
                id: expect.any(String),
                domain: 'localhost',
                name: '2019 foo event',
            },
        });
    }

    async createTariff(eventId) {
        const response = await httpClient.post('/admin/tariff', {
            eventId: eventId,
            productType: 'ticket',
            segments: [
                {
                    price: {amount: '200', currency: 'RUB'},
                    term: {start: '2000-01-01 12:00:00Z', end: '3000-01-01 12:00:00Z'},
                },
            ],
        });

        expect(response.data).toEqual({
            data: expect.any(String),
        });

        return response.data.data;
    }

    async createTariffDescription(tariffId) {
        const response = await httpClient.post('/admin/tariffDescription', {
            id: tariffId,
            tariffType: 'silverPass',
        });

        expect(response.data).toEqual({data: []});
    }

    async getTariffDescriptionById(tariffId) {
        const response = await httpClient.get(`/admin/tariffDescription/${tariffId}`);

        expect(response.data).toEqual({
            data: {
                id: expect.any(String),
                tariffType: 'silverPass',
            },
        });
    }

    async getTariffsList(eventId) {
        const response = await httpClient.get(`/admin/tariff?eventId=${eventId}`);

        expect(response.data).toEqual({
            data: [
                {
                    id: expect.any(String),
                    eventId: expect.any(String),
                    priceNet: [
                        {
                            price: {
                                amount: '200',
                                currency: 'RUB',
                            },
                            term: {
                                end: '3000-01-01T12:00:00+00:00',
                                start: '2000-01-01T12:00:00+00:00',
                            },
                        },
                    ],
                    productType: 'ticket',
                },
            ],
        });
    }

    async getTariffById(tariffId) {
        const response = await httpClient.get(`/admin/tariff/${tariffId}`);

        expect(response.data).toEqual({
            data: {
                id: expect.any(String),
                eventId: expect.any(String),
                priceNet: [
                    {
                        price: {
                            amount: '200',
                            currency: 'RUB',
                        },
                        term: {
                            end: '3000-01-01T12:00:00+00:00',
                            start: '2000-01-01T12:00:00+00:00',
                        },
                    },
                ],
                productType: 'ticket',
            },
        });
    }

    async createFixedPromocode(eventId, tariffId) {
        const response = await httpClient.post('/admin/promocode/createTariff', {
            eventId: eventId,
            code: 'FOO',
            discount: {
                amount: '100',
                currency: 'RUB',
            },
            useLimit: 10,
            expireAt: '3000-01-01 12:00:00Z',
            usable: true,
            allowedTariffIds: [
                tariffId,
            ],
        });

        expect(response.data).toEqual({
            data: expect.any(String),
        });

        return response.data.data;
    }

    async getPromocodeList(eventId) {
        const response = await httpClient.get(`/admin/promocode/list?eventId=${eventId}`);

        expect(response.data).toEqual({
            data: [
                {
                    id: expect.any(String),
                    eventId: expect.any(String),
                    allowedTariffs: {
                        tariffIds: [expect.any(String)],
                        type: 'specific',
                    },
                    code: 'FOO',
                    discount: {
                        amount: {
                            amount: '100',
                            currency: 'RUB',
                        },
                        type: 'fixed',
                    },
                    expireAt: '3000-01-01T12:00:00+00:00',
                    usable: true,
                    useLimit: 10,
                    usedInOrders: [],
                },
            ],
        });
    }

    async getPromocodeListWithUsedPromocode(eventId) {
        const response = await httpClient.get(`/admin/promocode/list?eventId=${eventId}`);

        expect(response.data).toEqual({
            data: [
                {
                    id: expect.any(String),
                    eventId: expect.any(String),
                    allowedTariffs: {
                        tariffIds: [expect.any(String)],
                        type: 'specific',
                    },
                    usedInOrders: [
                        expect.any(String),
                    ],
                    code: 'FOO',
                    discount: {
                        amount: {
                            amount: '100',
                            currency: 'RUB',
                        },
                        type: 'fixed',
                    },
                    expireAt: '3000-01-01T12:00:00+00:00',
                    usable: true,
                    useLimit: 10,
                },
            ],
        });
    }

    async getOrdersList(eventId) {
        const response = await httpClient.get(`/admin/order?eventId=${eventId}`);

        expect(response.data).toEqual({
            data: [
                {
                    eventId: expect.any(String),
                    id: expect.any(String),
                    makedAt: expect.any(String),
                    tariffId: expect.any(String),
                    userId: expect.any(String),
                    promocodeId: null,
                    cancelled: false,
                    paid: false,
                    price: {
                        amount: '200',
                        currency: 'RUB',
                    },
                    total: {
                        amount: '200',
                        currency: 'RUB',
                    },
                    productType: 'ticket',
                    tariffType: 'silverPass',
                    promocode: null,
                },
            ],
        });
    }

    async getOrderById(orderId) {
        const response = await httpClient.get(`/admin/order/${orderId}`);

        expect(response.data).toEqual({
            data: {
                eventId: expect.any(String),
                id: expect.any(String),
                makedAt: expect.any(String),
                tariffId: expect.any(String),
                userId: expect.any(String),
                promocodeId: null,
                cancelled: false,
                paid: false,
                price: {
                    amount: '200',
                    currency: 'RUB',
                },
                total: {
                    amount: '200',
                    currency: 'RUB',
                },
                productType: 'ticket',
                tariffType: 'silverPass',
                promocode: null,
            },
        });
    }

    async getOrderListWithAppliedPromocode(eventId) {
        const response = await httpClient.get(`/admin/order?eventId=${eventId}`);

        expect(response.data).toEqual({
            data: [
                {
                    eventId: expect.any(String),
                    id: expect.any(String),
                    makedAt: expect.any(String),
                    tariffId: expect.any(String),
                    userId: expect.any(String),
                    promocode: {
                        id: expect.any(String),
                        discount: {
                            amount: {
                                amount: '100',
                                currency: 'RUB',
                            },
                            type: 'fixed',
                        },
                    },
                    promocodeId: expect.any(String),
                    cancelled: false,
                    paid: false,
                    price: {
                        amount: '200',
                        currency: 'RUB',
                    },
                    total: {
                        amount: '100',
                        currency: 'RUB',
                    },
                    productType: 'ticket',
                    tariffType: 'silverPass',
                },
            ],
        });
    }

    async getOrderByIdWithAppliedPromocode(orderId) {
        const response = await httpClient.get(`/admin/order/${orderId}`);

        expect(response.data).toEqual({
            data: {
                eventId: expect.any(String),
                id: expect.any(String),
                makedAt: expect.any(String),
                tariffId: expect.any(String),
                userId: expect.any(String),
                promocode: {
                    id: expect.any(String),
                    discount: {
                        amount: {
                            amount: '100',
                            currency: 'RUB',
                        },
                        type: 'fixed',
                    },
                },
                promocodeId: expect.any(String),
                cancelled: false,
                paid: false,
                price: {
                    amount: '200',
                    currency: 'RUB',
                },
                total: {
                    amount: '100',
                    currency: 'RUB',
                },
                productType: 'ticket',
                tariffType: 'silverPass',
            },
        });
    }

    async confirmPayment(eventId, orderId) {
        const response = await httpClient.post(`/admin/ticketOrder/confirmPayment`, {
            orderId: orderId,
            eventId: eventId,
        });

        expect(response.data).toEqual({data: []});
    }

    async getOrderListWithPaidOrder(eventId) {
        const response = await httpClient.get(`/admin/order?eventId=${eventId}`);

        expect(response.data).toEqual({
            data: [
                {
                    eventId: expect.any(String),
                    id: expect.any(String),
                    makedAt: expect.any(String),
                    tariffId: expect.any(String),
                    userId: expect.any(String),
                    promocode: {
                        id: expect.any(String),
                        discount: {
                            amount: {
                                amount: '100',
                                currency: 'RUB',
                            },
                            type: 'fixed',
                        },
                    },
                    promocodeId: expect.any(String),
                    cancelled: false,
                    paid: true,
                    price: {
                        amount: '200',
                        currency: 'RUB',
                    },
                    total: {
                        amount: '100',
                        currency: 'RUB',
                    },
                    productType: 'ticket',
                    tariffType: 'silverPass',
                },
            ],
        });
    }

    async getOrderByIdWithPaidOrder(orderId) {
        const response = await httpClient.get(`/admin/order/${orderId}`);

        expect(response.data).toEqual({
            data: {
                eventId: expect.any(String),
                id: expect.any(String),
                makedAt: expect.any(String),
                tariffId: expect.any(String),
                userId: expect.any(String),
                promocode: {
                    id: expect.any(String),
                    discount: {
                        amount: {
                            amount: '100',
                            currency: 'RUB',
                        },
                        type: 'fixed',
                    },
                },
                promocodeId: expect.any(String),
                cancelled: false,
                paid: true,
                price: {
                    amount: '200',
                    currency: 'RUB',
                },
                total: {
                    amount: '100',
                    currency: 'RUB',
                },
                productType: 'ticket',
                tariffType: 'silverPass',
            },
        });
    }

    async getTicketsList(eventId) {
        const response = await httpClient.get(`/admin/ticket/list?eventId=${eventId}`);

        expect(response.data).toEqual({
            data: [
                {
                    id: expect.any(String),
                    orderId: expect.any(String),
                    eventId: expect.any(String),
                    createdAt: expect.any(String),
                    number: expect.any(String),
                    userId: expect.any(String),
                },
            ],
        });

        return response.data.data[0].id;
    }

    async getTicketById(ticketId) {
        const response = await httpClient.get(`/admin/ticket/show?ticketId=${ticketId}`);

        expect(response.data).toEqual({
            data: {
                id: expect.any(String),
                orderId: expect.any(String),
                eventId: expect.any(String),
                createdAt: expect.any(String),
                number: expect.any(String),
                userId: expect.any(String),
            },
        });
    }
}

module.exports = Manager;
