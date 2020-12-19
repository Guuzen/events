const postgres = require('postgres');

class TestDatabase {
    async prepare() {
        const connection = postgres(process.env.DB_URL);
        await connection`
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
        await connection.end();
    }
}

module.exports = TestDatabase;
