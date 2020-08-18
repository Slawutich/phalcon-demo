const merge = require('webpack-merge').default;
const TerserPlugin = require('terser-webpack-plugin');
const common = require('./webpack.common.js');

function warningFilter(warning){
    if(warning.indexOf('node_modules') >= 0){
        return false;
    }
    return true;
}

module.exports = merge(common, {
	//devtool: 'cheap-module-source-map', // production
    devtool: 'source-map',
    mode: 'production',
    optimization: {
        minimizer: [
            new TerserPlugin({
                include: /\.js$/,
                cache: false,
                sourceMap: true,
                parallel: true,
                terserOptions: {
                    ie8: false,
                    ecma: 8,
                    mangle: true,
                    compress: true,
                    warnings: false
                },
                warningsFilter: warningFilter
            })
        ],
    }
});
