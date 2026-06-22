const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const glob = require('fast-glob');
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = {
    ...defaultConfig,
    entry: async () => {
        // Get default entries from wp-scripts (handles block.json files)
        const defaultEntries = typeof defaultConfig.entry === 'function'
            ? await defaultConfig.entry()
            : defaultConfig.entry || {};

        const customEntries = {};

		// Scan scripts (with folders preserved)
		const scripts = glob.sync('./src/scripts/**/*.js');
		scripts.forEach((scriptPath) => {
			const relativePath = path
				.relative('./src', scriptPath)
				.replace(/\.js$/, '');

			customEntries[relativePath] = path.resolve(process.cwd(), scriptPath);
		});

		// Scan styles (with folders preserved)
		const styles = glob.sync('./src/styles/**/*.scss');
		styles.forEach((stylePath) => {
			const relativePath = path
				.relative('./src', stylePath)
				.replace(/\.scss$/, '');

			customEntries[relativePath] = path.resolve(process.cwd(), stylePath);
		});

        // Merge custom entries with default entries
        // Custom entries will override defaults if there's a conflict
        return {
            ...defaultEntries,
            ...customEntries,
        };
    },
    output: {
        ...defaultConfig.output,
        path: path.resolve(process.cwd(), 'build'),
        filename: '[name].js',
    },
    plugins: [
        ...defaultConfig.plugins.filter(
            (plugin) => plugin.constructor.name !== 'RtlCssPlugin'
        ),
        new CopyWebpackPlugin({
            patterns: [
                {
                    from: 'blocks/**/block.json',
                    to: '[path][name][ext]',
                    context: 'src',
                },
                {
                    from: 'blocks/**/render.php',
                    to: '[path][name][ext]',
                    context: 'src',
                },
            ],
        }),
    ],
};
