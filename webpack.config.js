module.exports = 
{
    module: 
    {
        loaders: 
        [
            {
                test: /datatables\.net.*/,
                loader: 'imports?define=>false'
            }
        ]
    },
};