const DirectoryNamedWebpackPlugin = require("directory-named-webpack-plugin");
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const path = require('path')


module.exports = {
    target: 'web',

    context: __dirname + '/www/public',

    entry: {
       '/js/app': __dirname + '/www/resources/js/app.js',
       '/css/app': __dirname + '/www/resources/sass/app.scss',

    },

    resolve: {
        plugins: [
            new DirectoryNamedWebpackPlugin()
        ]
    },

    plugins: [
		new MiniCssExtractPlugin({
		      filename: '[name].css',
		      chunkFilename: '[id].css',
		}),
   ],


    module: {
        rules: [{
	        test: /\.(sa|sc|c)ss$/,
	        use: [{
	            loader: MiniCssExtractPlugin.loader,
	            options: {
	              publicPath: '/public/css/',
	              hmr: process.env.NODE_ENV !== 'production',
	            }
	          },{
	            loader: 'css-loader',
	            options: { importLoaders: 1 }
	          },{
	            loader: 'postcss-loader',
	            options: {
	              options: {},
	            }
	          },{
                loader: 'sass-loader'
              }
            ],
	    },{
            test: /\.(eot|woff|ttf)$/,
            use: [{
                loader: 'file-loader',
                options: {
                    name: 'fonts/vendor/[name]-[md5:hash:hex:4].[ext]'
                }
            }]
        },{
            test: /\.(gif|png)$/,
            use: [{
                loader: 'file-loader',
                options: {
                    name: 'img/vendor/[name]-[md5:hash:hex:4].[ext]'
                }
            }]
        },{
            test: /\.(woff2|svg)$/,
            use: [{
                loader: 'url-loader'
            }]
        }],
    },
	output: {
	    path: __dirname + '/www/public',
	    filename: '[name].js',
	    chunkFilename: '[name].js',
	    publicPath: '/'
	},
};
