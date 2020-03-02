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
        }
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            jquery: 'jquery',
            Dropzone: 'Dropzone',
            dropzone: 'Dropzone',
            Raphael: 'Raphael'
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
            }
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

