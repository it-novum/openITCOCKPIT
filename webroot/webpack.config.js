const path = require('path');
const TerserJSPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const webpack = require('webpack');

module.exports = [{
    entry: path.resolve(__dirname, "./app.js"),
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
    entry: path.resolve(__dirname, "./appScripts.js"),
    devtool: "source-map",
    output: {
        path: "/usr/share/openitcockpit/webroot/dist/scripts",
        filename: "[name].js"
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            angular: 'angular',
            Dropzone: 'Dropzone',
            dropzone: 'Dropzone'

        })
    ],
    /*
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
    }*/
}];

