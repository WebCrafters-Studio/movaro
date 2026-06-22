# WebCrafters Studio WordPress Starter Kit

Nowoczesny starter kit do WordPressa oparty o `wp-env` i `wp-scripts`.

## Wymagania

-   Node.js & NPM
-   Docker (Desktop lub Engine)

## Szybki Start

1.  **Instalacja zależności:**
    ```bash
    npm install
    ```

2.  **Uruchomienie środowiska:**
    ```bash
    npm run start
    ```
    Uruchomi to kontenery Docker.
    -   URL Strony: http://dev.local:8888
    -   Admin: http://dev.local:8888/wp-admin (admin/password)
    -   phpMyAdmin: http://dev.local:9001
    
    **Własna domena (dev.local):**
    Aby używać `http://dev.local:8888`, dodaj wpis do `/etc/hosts`:
    ```
    127.0.0.1 dev.local
    ```

    **Nazwa kontenerów Docker:**
    Nazwa kontenerów jest automatycznie generowana na podstawie nazwy katalogu projektu. 
    Domyślnie będą to: `workspace-wordpress-1`, `workspace-mysql-1`, etc.
    
    Aby zmienić nazwę kontenerów, zmień nazwę katalogu projektu i uruchom ponownie `npm run start`.



3.  **Tryb Deweloperski:**
    ```bash
    npm run dev
    ```
    Nasłuchuje zmian w plikach motywu i pluginów.

    Możesz też uruchomić nasłuchiwanie tylko dla motywu lub pluginu:
    ```bash
    npm run dev:theme
    npm run dev:plugin
    ```

4.  **Debugowanie z Xdebug:**
    Aby uruchomić środowisko z włączonym Xdebug:
    ```bash
    npm run start -- --xdebug
    ```
    
    Xdebug będzie nasłuchiwał na porcie `9003`. Skonfiguruj swoje IDE (np. VS Code, PhpStorm) do połączenia z tym portem.
    
    **Konfiguracja VS Code (`.vscode/launch.json`):**
    ```json
    {
        "version": "0.2.0",
        "configurations": [
            {
                "name": "Listen for Xdebug",
                "type": "php",
                "request": "launch",
                "port": 9003,
                "pathMappings": {
                    "/var/www/html": "${workspaceFolder}"
                }
            }
        ]
    }
    ```
5. **VS Code**
    W pliku .vscode/settings.json znajduje się konfiguracja do wykluczania folderu build z wyszukiwania i eksploratora plików.

## Skrypty

-   `npm run build`: Buduje wersję produkcyjną assetów (wszystko).
-   `npm run build:theme`: Buduje tylko motyw.
-   `npm run build:plugin`: Buduje tylko plugin.
-   `npm run package:theme`: Tworzy plik ZIP z motywem w `dist/`.
-   `npm run package:full`: Eksportuje bazę danych i pakuje całą stronę.
-   `npm run package:uploads`: Pakuje folder uploads.
-   `npm run import:prod`: Importuje bazę danych z produkcji. Wymaga pliku `database.sql` w folderze `import/`. Pliki `wp-content` wystarczy skopiować bezpośrednio do `wp/wp-content/` (jest mapowany lokalnie).
-   `npm run woo:copy`: Kopiuje szablony WooCommerce z pluginu do motywu.

## Funkcje Motywu

### Theme Support

Motyw automatycznie dodaje wsparcie dla wielu funkcji WordPress. Znajdziesz je w pliku `wp/wp-content/themes/wcs/inc/theme-support.php`.

### ACF (Advanced Custom Fields)

Motyw jest skonfigurowany do zapisywania pól ACF jako JSON w `wp/wp-content/themes/wcs/acf-json/`.

### WooCommerce

Aby dostosować szablony WooCommerce:
1.  Uruchom `npm run woo:copy` - skopiuje szablony z pluginu do motywu
2.  Edytuj szablony w `wp/wp-content/themes/wcs/woocommerce/`

## Struktura Projektu

Projekt wykorzystuje **NPM Workspaces**. Główny `package.json` zarządza całym projektem.

-   `wp/wp-content/`: Cały katalog `wp-content` jest mapowany do kontenera jako jeden volume. Zmiany w motywach, pluginach, uploadach i innych podkatalogach (np. `mu-plugins`, `languages`) są od razu widoczne w WordPressie.
-   `import/`: Folder na pliki do importu z produkcji (`database.sql`, `prod-wp-content.zip`). Mapowany do kontenera jako `/var/www/html/import`.
-   `.wp-env.json`: Konfiguracja Dockera i mapowań folderów.
-   `package.json`: Główny plik konfiguracyjny zarządzający workspaces.
-   `wp/wp-content/themes/wcs/templates/`: Katalog szablonów jest w formacie .html, lecz mozna tworzyc motyw hybrydowo z wykorzystaniem klasycznych plikow .php, np. `single.php`, `page.php` itp.

## Tworzenie Nowych Bloków

### W Motywie

1.  Utwórz nowy katalog w `wp/wp-content/themes/wcs/src/blocks/`, np. `moj-nowy-blok`.
2.  Wewnątrz utwórz pliki: `block.json`, `index.js`, `style.scss`.
3.  Skonfigurowany `webpack.config.js` automatycznie wykryje nowy blok i zbuduje go do `wp-content/themes/wcs/build/blocks/moj-nowy-blok/`.
4.  Plik `inc/blocks.php` automatycznie zarejestruje blok w WordPressie.

**Ważne:** Dzięki konfiguracji webpacka, plik `block.json` jest kopiowany do folderu `build`. Możesz używać ścieżek relatywnych:

```json
"editorScript": "file:./index.js",
"style": "file:./style-index.css"
```

Możesz też uruchomić `npm run build:theme` aby zbudować motyw.

**Ważne:** W przypadku ACF Blocks template musi mieć nazwę render.php

### W Pluginie

Bloki tworzysz z wykorzystaniem paczki `@wordpress/create-block` w folderze `wp-content/plugins`.

```bash
npx @wordpress/create-block@latest starter-block
```
W folderze głównym projektu mozesz uruchomić `npm run build:plugin` aby zbudować blok. Stosownie skonfiguruj workspace w `package.json` aby `npm run build:plugin` budował blok w folderze `wp-content/plugins/starter-block`.

## Struktura Assetów Motywu

-   `src/blocks/`: Bloki Gutenberga lub ACF Blocks.
-   `src/styles/`: Globalne style SCSS. Każdy plik `.scss` w tym katalogu (bezpośrednio) jest kompilowany do osobnego pliku CSS w `build/styles/` i automatycznie rejestrowany.
-   `src/scripts/`: Globalne skrypty JS. Każdy plik `.js` w tym katalogu (bezpośrednio) jest kompilowany do osobnego pliku JS w `build/scripts/` i automatycznie rejestrowany.
-   Podkatalogi w `styles` i `scripts` (np. `src/styles/modules/`) nie są traktowane jako punkty wejścia, ale mogą być importowane przez główne pliki.
