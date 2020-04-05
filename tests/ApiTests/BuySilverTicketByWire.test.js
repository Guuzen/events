"use strict";
const prepareDb = require('./infrastructure/prepareDb');
const postgres = require('postgres');

const Manager = require('./actors/Manager');
const Visitor = require('./actors/Visitor');

const emailTemplate = require('./matchTemplates/emailTemplate');
const responseTemplate = require('./matchTemplates/responseTemplate');
const responseSnapshotTemplate = require('./matchTemplates/responseSnapshotTemplate');

const sql = postgres('postgres://user:password@guuzen-events-pgsql:5432/guuzen-events-test');

const eventsHttpClient = require('./infrastructure/eventsHttpClient');
const mailHttpClient = require('./infrastructure/mailHttpClient');

const manager = new Manager(eventsHttpClient);
const visitor = new Visitor(eventsHttpClient, mailHttpClient);

jest.setTimeout(100 * 1000);

beforeAll(async () => {
    await prepareDb(sql);
    await mailHttpClient.delete('/api/v1/messages');
});

afterAll(async () => {
    await sql.end();
});

describe('Buy silver ticket by wire without promocode', function () {
    let eventId;
    test('manager creates event', async () => {
        const response = await manager.createEvent({
            name: '2019 foo event',
            domain: 'guuzen-events-nginx',
        });
        expect(response).toEqual(
            responseTemplate({
                id: expect.any(String)
            })
        );
        eventId = response.data.data.id;
    });

    test('manager see event in list', async () => {
        const response = await manager.getEventsInList();
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate([
                {
                    id: expect.any(String)
                }
            ])
        );
    });

    test('manager see event by id', async () => {
        const response = await manager.getEventById(eventId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate({
                id: expect.any(String)
            })
        );
    });

    let tariffId;
    test('manager creates tariff', async () => {
        const response = await manager.createTariff({
            eventId: eventId,
            tariffType: 'silver_pass',
            segments: [
                {
                    price: {amount: 200, currency: 'RUB'},
                    term: {start: '2000-01-01 12:00:00', end: '3000-01-01 12:00:00'},
                }
            ]
        });
        expect(response).toEqual(
            responseTemplate({
                id: expect.any(String)
            })
        );
        tariffId = response.data.data.id;
    });

    test('manager see tariff in list', async () => {
        const response = await manager.getTariffsList(eventId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate([
                {
                    id: expect.any(String)
                }
            ])
        );
    });

    test('manager see tariff by id', async () => {
        const response = await manager.getTariffById(tariffId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate({
                id: expect.any(String)
            })
        );
    });

    let ticketId;
    test('manager creates ticket', async () => {
        const response = await manager.createTicket({
            eventId: eventId,
            tariffId: tariffId,
            number: '10002000'
        });
        expect(response).toEqual(
            responseTemplate({
                id: expect.any(String)
            })
        );
        ticketId = response.data.data.id;
    });

    test('manager see not reserved not deilivered ticket in list', async () => {
        const response = await manager.getTicketsList(eventId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate([
                {
                    id: expect.any(String),
                    eventId: expect.any(String),
                    createdAt: expect.any(String),
                    reserved: false,
                    deliveredAt: null
                }
            ])
        );
    });

    test('manager see not reserved not delivered ticket by id', async () => {
        const response = await manager.getTicketById(ticketId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate({
                id: expect.any(String),
                eventId: expect.any(String),
                createdAt: expect.any(String),
                reserved: false,
                deliveredAt: null
            })
        );
    });

    let orderId;
    test('visitor places order', async () => {
        const response = await visitor.placeOrder({
            tariffId: tariffId,
            firstName: 'John',
            lastName: 'Doe',
            email: 'john@example.com',
            phone: '+123456789'
        });
        expect(response).toEqual(
            responseTemplate({
                id: expect.any(String)
            })
        );
        orderId = response.data.data.id;
    });

    test('manager see not paid and not delivered order in list', async () => {
        const response = await manager.getOrdersList(eventId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate([
                {
                    id: expect.any(String),
                    eventId: expect.any(String),
                    makedAt: expect.any(String),
                    productId: expect.any(String),
                    tariffId: expect.any(String),
                    userId: expect.any(String),
                    paid: false,
                    deliveredAt: null
                }
            ])
        );
    });

    test('manager see not paid and not delivered order by id', async () => {
        const response = await manager.getOrderById(orderId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate({
                id: expect.any(String),
                eventId: expect.any(String),
                makedAt: expect.any(String),
                productId: expect.any(String),
                tariffId: expect.any(String),
                userId: expect.any(String),
                paid: false,
                deliveredAt: null
            })
        );
    });

    test('manager see reserved not delivered ticket in list', async () => {
        const response = await manager.getTicketsList(eventId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate([
                {
                    id: expect.any(String),
                    eventId: expect.any(String),
                    createdAt: expect.any(String),
                    reserved: true,
                    deliveredAt: null,
                }
            ])
        );
    });

    test('manager see reserved not delivered ticket by id', async () => {
        const response = await manager.getTicketById(ticketId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate({
                id: expect.any(String),
                eventId: expect.any(String),
                createdAt: expect.any(String),
                reserved: true,
                deliveredAt: null,
            })
        );
    });

    test('manager mark order paid', async () => {
        const response = await manager.markOrderPaid({
            orderId: orderId,
        });
        expect(response).toEqual(
            responseTemplate([])
        );
    });

    test('visitor receives email with ticket', async () => {
        const response = await visitor.getReceivedEmails();
        expect(response).toMatchSnapshot({
            data: {
                items: [
                    emailTemplate(),
                ]
            },
            statusText: expect.anything(),
            headers: expect.anything(),
            config: expect.anything(),
            request: expect.anything(),
        });
    });

    test('manager see paid and delivered order in list', async () => {
        const response = await manager.getOrdersList(eventId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate([
                {
                    id: expect.any(String),
                    eventId: expect.any(String),
                    makedAt: expect.any(String),
                    productId: expect.any(String),
                    tariffId: expect.any(String),
                    userId: expect.any(String),
                    paid: true,
                    deliveredAt: expect.any(String),
                }
            ])
        );
    });

    test('manager see paid and delivered order by id', async () => {
        const response = await manager.getOrderById(orderId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate({
                id: expect.any(String),
                eventId: expect.any(String),
                makedAt: expect.any(String),
                productId: expect.any(String),
                tariffId: expect.any(String),
                userId: expect.any(String),
                paid: true,
                deliveredAt: expect.any(String),
            })
        );
    });

    test('manager see reserved and delivered ticket in list', async () => {
        const response = await manager.getTicketsList(eventId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate([
                {
                    id: expect.any(String),
                    eventId: expect.any(String),
                    createdAt: expect.any(String),
                    reserved: true,
                    deliveredAt: expect.any(String),
                }
            ])
        );
    });

    test('manager see reserved and delivered ticket by id', async () => {
        const response = await manager.getTicketById(ticketId);
        expect(response).toMatchSnapshot(
            responseSnapshotTemplate({
                id: expect.any(String),
                eventId: expect.any(String),
                createdAt: expect.any(String),
                reserved: true,
                deliveredAt: expect.any(String),
            })
        );
    });
});
