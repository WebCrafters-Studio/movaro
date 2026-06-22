# GitLab CI/CD - Konfiguracja Zmiennych

Aby skonfigurować deployment, dodaj następujące zmienne w GitLab:
**Settings → CI/CD → Variables**

## Zmienne dla FTP Deployment

### Staging
- `FTP_HOST` - Adres serwera FTP (np. `ftp.example.com`)
- `FTP_USERNAME` - Nazwa użytkownika FTP
- `FTP_PASSWORD` - Hasło FTP (zaznacz jako **Protected** i **Masked**)
- `FTP_REMOTE_PATH` - Ścieżka na serwerze (np. `/public_html/wp/wp-content/themes`)
- `STAGING_URL` - URL środowiska staging (np. `https://staging.example.com`)

### Production
Użyj tych samych zmiennych co dla staging lub stwórz osobne:
- `FTP_HOST_PROD`
- `FTP_USERNAME_PROD`
- `FTP_PASSWORD_PROD`
- `FTP_REMOTE_PATH_PROD`
- `PRODUCTION_URL` - URL produkcji (np. `https://example.com`)

## Zmienne dla SSH/SFTP Deployment

### Staging
- `SSH_HOST` - Adres serwera SSH (np. `ssh.example.com`)
- `SSH_USER` - Nazwa użytkownika SSH
- `SSH_PRIVATE_KEY` - Klucz prywatny SSH (zaznacz jako **Protected** i **Masked**)
  ```bash
  # Wygeneruj klucz SSH (jeśli nie masz):
  ssh-keygen -t ed25519 -C "gitlab-ci@example.com"
  
  # Skopiuj klucz publiczny na serwer:
  ssh-copy-id user@server
  
  # Skopiuj klucz prywatny do GitLab Variables:
  cat ~/.ssh/id_ed25519
  ```
- `SSH_REMOTE_PATH` - Ścieżka na serwerze (np. `/var/www/html/wp/wp-content/themes`)
- `STAGING_URL` - URL środowiska staging

### Production
Użyj tych samych zmiennych lub stwórz osobne z sufiksem `_PROD`.

## Dostępne Job'y

### Build
- `build` - Kompiluje assety i tworzy paczkę ZIP motywu
- Uruchamia się automatycznie dla branchy: `main`, `develop`, `tags`

### Deploy FTP
- `deploy:ftp:staging` - Deploy na staging przez FTP (manual)
- `deploy:ftp:production` - Deploy na produkcję przez FTP (manual)

### Deploy SSH
- `deploy:ssh:staging` - Deploy na staging przez SSH/rsync (manual)
- `deploy:ssh:production` - Deploy na produkcję przez SSH/rsync (manual)

### Deploy ZIP
- `deploy:theme:production` - Wysyła i rozpakuje ZIP motywu (tylko dla tagów, manual)

## Przykładowe użycie

1. **Commit i push do `develop`**:
   - Automatycznie buduje assety
   - Możesz ręcznie uruchomić `deploy:ftp:staging` lub `deploy:ssh:staging`

2. **Commit i push do `main`**:
   - Automatycznie buduje assety
   - Możesz ręcznie uruchomić `deploy:ftp:production` lub `deploy:ssh:production`

3. **Utworzenie taga** (np. `v1.0.0`):
   - Automatycznie buduje assety
   - Możesz ręcznie uruchomić `deploy:theme:production` do wdrożenia ZIP

## Bezpieczeństwo

- Wszystkie hasła i klucze prywatne powinny być oznaczone jako **Protected** i **Masked**
- Deployment na produkcję wymaga ręcznego zatwierdzenia (`when: manual`)
- Używaj różnych kluczy SSH dla staging i production
