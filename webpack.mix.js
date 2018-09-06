let webpack = require('webpack')
let mix = require('laravel-mix')

mix.js('resources/js/field.js', 'dist/js')
    .less('resources/less/field.less', 'dist/css')
	.copyDirectory('node_modules/font-awesome/fonts', 'dist/fonts/font-awesome')
    .webpackConfig({
        resolve: {
            symlinks: false
        },
		plugins: [
			new webpack.ProvidePlugin({
				$: "jquery",
				jQuery: "jquery"
			})
		]
    })
