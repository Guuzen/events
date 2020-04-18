class TestDatabase {
    constructor(connection) {
        this.connection = connection;
    }

    async prepare() {
        await this.connection`
            DO $$
            BEGIN
                execute
                (
                    select 'truncate table ' || string_agg(quote_ident(table_name), ', ')
                    from information_schema.tables
                    where table_schema = 'public'
                );
            END
            $$;
        `;
    }
}

module.exports = TestDatabase;