"use strict";
const TestDatabase = require('./Infrastructure/TestDatabase');
const testDatabase = new TestDatabase();
const Manager = require('./actors/Manager');
const Visitor = require('./actors/Visitor');
const Fondy = require('./actors/Fondy');

const visitorEmailServer = require('mockttp').getLocal();

jest.setTimeout(10 * 1000);

describe('Buy ticket', function () {

    beforeEach(async () => {
        await testDatabase.prepare();
        await visitorEmailServer.start(8001);
    });

    afterEach(async () => {
        await visitorEmailServer.stop();
    });

    test('By wire', async () => {
        const manager = new Manager();

        const eventId = await manager.createEventDomain();
        await manager.getEventsInList();
        await manager.getEventById(eventId);

        const tariffId = await manager.createTariff(eventId);
        await manager.getTariffsList(eventId);
        await manager.getTariffById(tariffId);
        await manager.createTariffDescription(tariffId);
        await manager.getTariffDescriptionById(tariffId);

        await manager.createFixedPromocode(eventId, tariffId);
        await manager.getPromocodeList(eventId);

        const visitor = new Visitor();
        const orderId = await visitor.placeOrder(tariffId);

        await manager.getOrdersList(eventId);
        await manager.getOrderById(orderId);

        await visitor.usePromocode(orderId, tariffId);
        await visitor.awaitsEmailWithTicket(visitorEmailServer);

        await manager.wait(1300);
        await manager.getOrderListWithUsedPromocode(eventId);
        await manager.getOrderByIdWithUsedPromocode(orderId);
        await manager.getPromocodeListWithUsedPromocode(eventId);

        await manager.markOrderPaid(eventId, orderId);

        await visitor.wait(1300);
        await visitor.receivesEmailWithTicket();

        await manager.getOrderListWithPaidOrder(eventId);
        await manager.getOrderByIdWithPaidOrder(orderId);
        const ticketId = await manager.getTicketsList(eventId);
        await manager.getTicketById(ticketId);
    })

    test('By card', async () => {
        const manager = new Manager();

        const eventId = await manager.createEventDomain();
        await manager.getEventsInList();
        await manager.getEventById(eventId);

        const tariffId = await manager.createTariff(eventId);
        await manager.getTariffsList(eventId);
        await manager.getTariffById(tariffId);
        await manager.createTariffDescription(tariffId);
        await manager.getTariffDescriptionById(tariffId);

        await manager.createFixedPromocode(eventId, tariffId);
        await manager.getPromocodeList(eventId);

        const visitor = new Visitor();
        const orderId = await visitor.placeOrder(tariffId);

        await manager.getOrdersList(eventId);
        await manager.getOrderById(orderId);

        await visitor.usePromocode(orderId, tariffId);

        await manager.wait(1300);
        await manager.getOrderListWithUsedPromocode(eventId);
        await manager.getOrderByIdWithUsedPromocode(orderId);
        await manager.getPromocodeListWithUsedPromocode(eventId);

        await visitor.payOrderByCard(orderId);
        await visitor.awaitsEmailWithTicket(visitorEmailServer);

        const fondy = new Fondy();
        await fondy.markOrderPaid(orderId);

        await visitor.wait(1300);
        await visitor.receivesEmailWithTicket();

        await manager.getOrderListWithPaidOrder(eventId);
        await manager.getOrderByIdWithPaidOrder(orderId);
        const ticketId = await manager.getTicketsList(eventId);
        await manager.getTicketById(ticketId);
    });
});
