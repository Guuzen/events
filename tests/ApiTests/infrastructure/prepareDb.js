module.exports = async function (sql)
{
    await sql`
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
};
