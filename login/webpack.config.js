const path = require('path');

module.exports = {
    entry: "./src/main.ts",
    output: {
        path: path.resolve(__dirname, '../webroot/webpack'),
    },
    resolve: {
        // Add '.ts' and '.tsx' as a resolvable extension.
        extensions: [".webpack.js", ".web.js", ".ts", ".tsx", ".js"]
    },
    module: {
        rules: [
            // all files with a '.ts' or '.tsx' extension will be handled by 'ts-loader'
            {test: /\.tsx?$/, use: "ts-loader"}
        ]
    }
};

