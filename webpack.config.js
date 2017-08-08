const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const extractCss = require('./webpack/css.extract');
const extractImage = require('./webpack/image.extract');

const PATHS = {
    source: path.join(__dirname, './app/resources/'),
    build: path.join(__dirname, './web/assets')
};

const common = merge([
    {
        entry: PATHS.source + '/js/index.js',
        output: {
            path: PATHS.build,
            filename: 'js/[name].js'
        },
        plugins: [
            new webpack.ProvidePlugin({
                $: "jquery/dist/jquery.min.js",
                jQuery: "jquery/dist/jquery.min.js",
                "window.jQuery": "jquery/dist/jquery.min.js",
            }),
        ]
    }
]);


module.exports = merge([
    common,
    extractCss(),
    extractImage(),
]);