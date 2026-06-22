const fs = require('fs-extra');
const path = require('path');

/**
 * Copy WooCommerce templates from plugin to theme
 */
async function copyWooTemplates() {
    const wooPluginPath = path.resolve(__dirname, '../wp/wp-content/plugins/woocommerce/templates');
    const themeWooPath = path.resolve(__dirname, '../wp/wp-content/themes/wcs/woocommerce');

    try {
        // Check if WooCommerce plugin exists
        if (!fs.existsSync(wooPluginPath)) {
            console.log('⚠️  Plugin WooCommerce nie został znaleziony. Zainstaluj najpierw WooCommerce.');
            console.log('   Uruchom: npm run start (aby uruchomić wp-env i zainstalować WooCommerce)');
            return;
        }

        // Create theme woocommerce directory if it doesn't exist
        await fs.ensureDir(themeWooPath);

        // Copy templates
        await fs.copy(wooPluginPath, themeWooPath, {
            overwrite: false, // Don't overwrite existing customizations
            errorOnExist: false,
        });

        console.log('✅ Szablony WooCommerce skopiowane pomyślnie!');
        console.log(`   Z: ${wooPluginPath}`);
        console.log(`   Do: ${themeWooPath}`);
        console.log('\n💡 Możesz teraz dostosować szablony WooCommerce w swoim motywie.');
    } catch (error) {
        console.error('❌ Błąd podczas kopiowania szablonów WooCommerce:', error.message);
        process.exit(1);
    }
}

copyWooTemplates();
