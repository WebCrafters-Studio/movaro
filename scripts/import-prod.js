const fs = require("fs-extra");
const path = require("path");
const { execSync, spawnSync } = require("child_process");
const unzipper = require("unzipper");

const importDir = path.join(__dirname, "../import");
const dbFile = path.join(importDir, "database.sql");
const uploadsZip = path.join(importDir, "uploads.zip");
const uploadsDest = path.join(__dirname, "../wp/wp-content/uploads");
const wpContentZip = path.join(importDir, "full-package.zip");
const wpContentDest = path.join(__dirname, "../wp/wp-content");

function getMysqlContainerId() {
  const output = execSync(
    `docker ps --filter "name=mysql" --format "{{.ID}}\\t{{.Names}}"`,
  )
    .toString()
    .trim();

  const devLine = output
    .split("\n")
    .find((line) => !line.includes("tests-mysql"));
  if (!devLine)
    throw new Error(
      "Nie znaleziono kontenera MySQL. Upewnij się, że wp-env jest uruchomione.",
    );
  return devLine.split("\t")[0].trim();
}

if (fs.existsSync(dbFile)) {
  console.log("Importowanie bazy danych...");
  try {
    const containerId = getMysqlContainerId();
    execSync("npx wp-env run cli wp db reset --yes", { stdio: "inherit" });
    const result = spawnSync(
      "docker",
      [
        "exec",
        "-i",
        containerId,
        "mariadb",
        "-u",
        "root",
        "-ppassword",
        "wordpress",
      ],
      {
        input: fs.readFileSync(dbFile),
        stdio: ["pipe", "inherit", "inherit"],
      },
    );
    if (result.status !== 0)
      throw new Error(`docker exec exit code: ${result.status}`);
    console.log("Baza danych zaimportowana.");
    execSync("npx wp-env run cli wp cache flush", { stdio: "inherit" });
    execSync("npx wp-env run cli wp rewrite flush", { stdio: "inherit" });
  } catch (e) {
    console.error("Nie udało się zaimportować bazy danych:", e.message);
    process.exit(1);
  }
} else {
  console.log("Nie znaleziono pliku bazy danych w " + dbFile);
}

if (fs.existsSync(wpContentZip)) {
  console.log("Rozpakowywanie całego wp-content...");
  fs.createReadStream(wpContentZip)
    .pipe(unzipper.Extract({ path: wpContentDest }))
    .on("close", () => console.log("wp-content zaimportowany."));
} else if (fs.existsSync(uploadsZip)) {
  console.log("Rozpakowywanie plików uploads...");
  fs.createReadStream(uploadsZip)
    .pipe(unzipper.Extract({ path: uploadsDest }))
    .on("close", () => console.log("Pliki uploads zaimportowane."));
} else {
  console.log("Nie znaleziono archiwum wp-content ani uploads w " + importDir);
}
