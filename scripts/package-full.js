const fs = require('fs-extra');
const archiver = require('archiver');
const path = require('path');
const { execSync } = require('child_process');

const outputDir = path.join(__dirname, '../dist');
const dbFile = path.join(outputDir, 'database.sql');
const outputFile = path.join(outputDir, 'full-package.zip');

fs.ensureDirSync(outputDir);

// Export DB
console.log('Eksportowanie bazy danych...');
try {
    // Export directly to stdout and redirect to file on host
    // This avoids permission issues with writing to mounted volumes inside the container
    console.log('Wykonywanie zrzutu bazy danych...');
    execSync(`npx wp-env run cli wp db export - --allow-root > "${dbFile}"`);

    if (fs.existsSync(dbFile) && fs.statSync(dbFile).size > 0) {
        console.log('Baza danych wyeksportowana pomyślnie.');
    } else {
        throw new Error('Plik bazy danych jest pusty lub nie został utworzony.');
    }
} catch (e) {
    console.error('Nie udało się wyeksportować bazy danych.');
    console.error(e.message);
    process.exit(1);
}

const output = fs.createWriteStream(outputFile);
const archive = archiver('zip', {
    zlib: { level: 9 }
});

output.on('close', function () {
    console.log(archive.pointer() + ' całkowita liczba bajtów');
    console.log('Pełny pakiet utworzony w ' + outputFile);
});

archive.on('error', function (err) {
    throw err;
});

archive.pipe(output);

// Add DB
archive.file(dbFile, { name: 'database.sql' });

// Copy WP files from container
const tempWpDir = path.join(outputDir, 'wp');
fs.ensureDirSync(tempWpDir);
fs.emptyDirSync(tempWpDir);

console.log('Pobieranie plików WordPress z kontenera...');
try {
    // Get container ID for the wordpress service
    // We use 'hostname' command inside the wordpress container to get its ID
    const containerId = execSync('npx wp-env run wordpress hostname', { encoding: 'utf8' }).trim();
    console.log(`Znaleziono kontener WordPress: ${containerId}`);

    console.log('Kopiowanie plików core (bez wp-content)...');
    // Use tar to copy files excluding wp-content to avoid mount issues and use local content
    execSync(`docker exec ${containerId} tar -cf - --exclude=wp-content --exclude=.git . | tar -xf - -C "${tempWpDir}"`);

    console.log('Kopiowanie lokalnego wp-content...');
    fs.copySync(path.join(__dirname, '../wp/wp-content'), path.join(tempWpDir, 'wp-content'));

    console.log('Pliki WordPress skopiowane pomyślnie.');

    // Add to zip
    archive.directory(tempWpDir, 'wp');

} catch (e) {
    console.error('Błąd podczas kopiowania plików z kontenera.');
    console.error(e.message);
    console.log('Próba użycia lokalnego folderu wp...');
    // Fallback to local folder if docker cp fails
    archive.directory(path.join(__dirname, '../wp'), 'wp');
}

// Add .git folder
console.log('Dodawanie folderu .git...');
const gitDir = path.join(__dirname, '../.git');
if (fs.existsSync(gitDir)) {
    archive.directory(gitDir, '.git');
}

archive.finalize();