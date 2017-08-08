module.exports = function() {
    return {
        module: {
            rules: [
                {
                    test: /\.(ico|jpg|png|gif|eot|otf|webp|svg|ttf|woff|woff2)(\?.*)?$/,
                    loader: 'file-loader',
                    options: {
                        name: 'images/[name].[ext]'
                    },
                },
            ]
        }
    }
};