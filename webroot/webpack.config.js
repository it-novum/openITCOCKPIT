/*
    package.json dependencies for running webpack
 "devDependencies": {
        "css-loader": "^3.4.2",
        "exports-loader": "^0.7.0",
        "expose-loader": "^0.7.5",
        "file-loader": "^5.1.0",
        "imports-loader": "^0.8.0",
        "mini-css-extract-plugin": "^0.9.0",
        "optimize-css-assets-webpack-plugin": "^5.0.3",
        "style-loader": "^1.1.3",
        "svg-url-loader": "^4.0.0",
        "terser-webpack-plugin": "^2.3.5",
        "uglifyjs-webpack-plugin": "^2.2.0",
        "url-loader": "^3.0.0",
        "webpack": "^4.41.6",
        "webpack-cli": "^3.3.11"
    },
     "scripts": {
        "test": "echo \"Error: no test specified\" && exit 1",
        "build": "webpack"
    },

    execute with (from webroot dir):
    "npm run build"
 */




const path = require('path');
const TerserJSPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const webpack = require('webpack');

module.exports = [{
    mode: 'production',
    entry: path.resolve(__dirname, './app.js'),
    optimization: {
        minimizer: [new TerserJSPlugin({}), new OptimizeCSSAssetsPlugin({})],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css',
            chunkFilename: '[id].css',
        }),
    ],
    module: {
        rules: [
            {
                test: /\.css$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader
                    },
                    {
                        loader: 'css-loader',
                        options: {
                            sourceMap: true
                        }
                    },

                ],
            },
            {
                test: /\.(png|woff|woff2|eot|ttf)$/,
                loader: 'url-loader?limit=100000'
            },
            {
                test: /\.svg/,
                use: [
                    {
                        loader: 'svg-url-loader',
                        options: {}
                    }
                ]
            }
        ],
    },

}, {
    mode: 'production',
    entry: path.resolve(__dirname, './appScripts.js'),
    devtool: 'source-map',
    output: {
        path: '/opt/openitc/frontend/webroot/dist/scripts',
        filename: '[name].js'
    },
    resolve: {
        alias: {
            '$': path.resolve(__dirname, './node_modules/jquery'),
            'jQuery': path.resolve(__dirname, './node_modules/jquery'),
            'angular': path.resolve(__dirname, './node_modules/angular'),
            'Dropzone': path.resolve(__dirname, './node_modules/dropzone'),
            'Raphael': path.resolve(__dirname, './node_modules/raphael'),
            'vis': path.resolve(__dirname, './node_modules/vis-data/dist'),
        }
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            jquery: 'jquery',
            Dropzone: 'Dropzone',
            dropzone: 'Dropzone',
            Raphael: 'Raphael',
            vis: 'vis'
        })
    ],
    module: {
        rules: [
            {
                test: require.resolve('angular'),
                loader: 'expose-loader?angular'
            },
            {
                test: require.resolve('jquery'),
                //loader: 'expose-loader?jquery'
                use: [{
                    loader: 'expose-loader',
                    options: 'jQuery'
                }, {
                    loader: 'expose-loader',
                    options: '$'
                }]
            },
        ]
    },
    optimization: {
        minimizer: [
            new UglifyJsPlugin({
                cache: true,
                parallel: false,
                uglifyOptions: {
                    compress: false,
                    ecma: 6,
                    mangle: false
                },
                sourceMap: true
            })
        ]
    }
}];

