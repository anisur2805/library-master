const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        index: './src/index.js',
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'dist'),
    },
    module: {
        rules: [
        {
            test: /.js$/,
            loader: "babel-loader",
            exclude: /node_modules/,
            options: {
            presets: [["env", "react"]],
            plugins: ["transform-class-properties"]
            }
        }
        ]
    }
};