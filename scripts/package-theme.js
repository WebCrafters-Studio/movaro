const fs = require('fs-extra');
const archiver = require('archiver');
const path = require('path');

const themeName = 'wcs';
const sourceDir = path.join(__dirname, '../wp/wp-content/themes', themeName);
const outputDir = path.join(__dirname, '../dist');
const outputFile = path.join(outputDir, `${themeName}.zip`);

fs.ensureDirSync(outputDir);

const output = fs.createWriteStream(outputFile);
const archive = archiver('zip', {
    zlib: { level: 9 }
});

output.on('close', function () {
    console.log(archive.pointer() + ' całkowita liczba bajtów');
    console.log('Pakiet motywu utworzony w ' + outputFile);
});

archive.on('error', function (err) {
    throw err;
});

archive.pipe(output);

archive.directory(sourceDir, themeName);

archive.finalize();