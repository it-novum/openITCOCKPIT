const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin")
const {AngularWebpackPlugin} = require('@ngtools/webpack');

module.exports = {


    //mode: "development",
    entry: "./src/main.ts",
    output: {
        path: path.resolve(__dirname, '../webroot/webpack'),
        filename: "main.js"
    },
    resolve: {
        // Add '.ts' and '.tsx' as a resolvable extension.
        extensions: [".webpack.js", ".web.js", ".ts", ".tsx", ".js", ".css"]
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/, // all files with a '.ts' or '.tsx' extension will be handled by 'ts-loader'
                use: 'ts-loader', // Use TypeScript loader
                //use: '@ngtools/webpack'
            },
            {
                test: /\.(css)$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader'],
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin(),
        //new AngularWebpackPlugin({
        //    tsconfig: './tsconfig.json',
        //}),
    ],
};

