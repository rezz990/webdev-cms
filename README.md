# Webdev Reza CMS

Personal developer hub, blog, dan portfolio berbasis Laravel 13, Blade, session authentication, MySQL/MariaDB, serta Tailwind CSS v4. Konten publik berasal dari database dan dikelola melalui `/admin`.

Identitas visual menggunakan konsep **website sebagai manga volume**: dominan hitam-putih, satu aksen oranye, panel bertinta tebal, screentone, speed lines, speech bubble, dan karakter narrator original. Struktur homepage mengikuti Cover → Chapter 01: Who Am I → Chapter 02: The Projects → Chapter 03: Dev Logs → Chapter 04: Contact. Karakter dibuat sendiri dan tidak menyalin seri manga/anime berhak cipta.

## Audit dan keputusan migrasi

Repository yang diterima adalah skeleton Laravel satu commit; source Astro lama maupun riwayat/asset kontennya tidak tersedia untuk diaudit. Identitas yang dipertahankan dari brief: nama **Webdev Reza**, nada personal, palet `#17231d`, `#f1eee5`, `#f15b2a`, aksen `#d8f34a`, project Bujon Carwash, **ShADB**, Blokirjudi, dan WhatsApp `62895358302211`. Layout eksperimental dan link project palsu tidak digunakan. Project lama tidak dihapus karena memang tidak terdapat di repository.

## Requirement dan instalasi

- PHP 8.3+ (target hosting PHP 8.5) beserta `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pdo_mysql`, `session`, `tokenizer`, dan `xml`.
- Composer, MySQL/MariaDB, Node.js/npm untuk build.

```bash
cp .env.example .env
composer install
php artisan key:generate
# atur DB_* lalu:
php artisan migrate --seed
npm install
npm run build
php artisan storage:link
composer run dev
```

## Masuk ke dashboard admin

Cara yang direkomendasikan adalah membuat admin melalui command interaktif berikut (password tidak tampil dan tidak tersimpan di shell history):

```bash
php artisan admin:create
```

Setelah mengisi nama, email, dan password minimal 12 karakter, buka `http://localhost:8000/admin/login` atau `https://reza.web-id.id/admin/login`, lalu masuk memakai email dan password tersebut. Tidak ada registrasi publik. Jika sebelumnya sudah memakai seeder, admin juga dapat dibuat dengan mengisi `ADMIN_EMAIL` dan `ADMIN_PASSWORD`, menjalankan `php artisan db:seed`, lalu **menghapus dua nilai tersebut dari `.env`**.

Tulisan demo hanya dibuat pada environment `local`; settings dan tiga project awal di-upsert sehingga seeder aman dijalankan ulang.

## Fitur dan struktur

- Public: homepage, daftar/detail project, daftar/detail blog, about, kontak, sitemap, RSS, robots, serta halaman error aman.
- Admin: login tanpa registrasi, ringkasan, CRUD tulisan/project, status draft/scheduled/published/archived, soft delete, inbox kontak, media library, pengaturan profil, upload tervalidasi, dan SEO fields.
- Domain berada di `app/Models`, public/admin controller dipisah di `app/Http/Controllers`, validasi kompleks memakai Form Request, dan schema berada di `database/migrations`.

## Quality checks

```bash
php artisan test --compact
vendor/bin/pint --format agent
vendor/bin/phpstan analyse
php artisan migrate:fresh --seed --force
php artisan route:list --except-vendor
npm run build
```

## Production

Gunakan `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, cookie secure, kredensial SMTP/DB hanya di `.env`, dan document root ke `public/`. Panduan cPanel lengkap ada di [DEPLOYMENT-CPANEL.md](DEPLOYMENT-CPANEL.md).

## Alur menerbitkan konten

Pada editor admin, tombol **Terbitkan sekarang** otomatis memilih status `published`. Jika waktu publikasi dibiarkan kosong, sistem mengisi waktu saat ini dan konten langsung muncul di `/blog` atau `/projects`. Tombol **Simpan draft** menjaga konten tetap privat. Draft dapat diperiksa melalui **Preview penuh** yang hanya bisa diakses admin. Editor menyediakan toolbar Markdown, preview langsung, penghitung kata, kategori, tag/teknologi, cover, SEO, status, dan jadwal publikasi.
