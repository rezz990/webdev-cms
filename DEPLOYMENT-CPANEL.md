# Deployment aman ke cPanel

## Persiapan

Pilih PHP 8.5 dan aktifkan extension `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pdo_mysql`, `session`, `tokenizer`, dan `xml`. Buat database dan user melalui **MySQL Databases**, gunakan password acak, berikan hak hanya pada database aplikasi, dan simpan nilainya di `.env` yang tidak berada di Git.

## Rilis

1. Clone melalui Git Version Control/SSH ke direktori di luar `public_html`. Jalankan `composer install --no-dev --prefer-dist --optimize-autoloader`.
2. Build dengan `npm ci && npm run build` di server atau lokal dengan versi lockfile yang sama, lalu upload `public/build`.
3. Salin `.env.example` menjadi `.env`; isi `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://reza.web-id.id`, `DB_*`, mail domain, `QUEUE_CONNECTION=sync`, `SESSION_SECURE_COOKIE=true`, dan `FILESYSTEM_DISK=public`. Jalankan `php artisan key:generate`; jangan pernah mempublikasikan `.env` atau `APP_KEY`.
4. Arahkan document root domain ke `<repo>/public`. Karena hosting ini mendukungnya, jangan menyalin source ke `public_html`.
5. Aktifkan maintenance, backup database, lalu jalankan `php artisan migrate --force`. Isi `ADMIN_EMAIL` dan password sementara yang kuat, jalankan `php artisan db:seed --force`, lalu hapus dua nilai admin dari `.env`.
6. Jalankan `php artisan storage:link`. Bila symlink dilarang, buat symlink dari File Manager bila tersedia atau minta provider mengaktifkannya; jangan memindahkan `.env`, `vendor`, maupun source ke direktori publik. Opsi terakhir adalah disk publik khusus yang root-nya diarahkan secara eksplisit ke folder upload non-eksekutabel dan dikaji bersama provider.
7. Jalankan `php artisan config:cache && php artisan event:cache && php artisan route:cache && php artisan view:cache`.

Atur permission direktori `storage` dan `bootstrap/cache` ke 0755 atau 0775 sesuai group web server; file 0644. Jangan gunakan 0777. Pastikan PHP/script tidak dapat dieksekusi dari folder upload.

## Cron dan email

Tambahkan cron setiap menit: `cd /home/USER/path && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1`. Bila minimum cPanel lima menit, scheduled content dapat terlambat hingga lima menit. Queue default `sync`, cocok untuk shared hosting dan tidak membutuhkan daemon. Gunakan SMTP domain; pesan kontak tetap tersimpan meski integrasi email dinonaktifkan.

## Backup dan rollback

Sebelum tiap rilis, dump database serta arsipkan `storage/app/public` ke direktori backup di luar document root. Simpan beberapa generasi terenkripsi. Untuk rollback kode, checkout tag/commit rilis sebelumnya, install dependency dari lockfile, build/upload asset terkait, lalu cache ulang. Migration destruktif memerlukan restore database dari backup; `migrate:rollback` hanya digunakan setelah migration tersebut diverifikasi reversible pada staging. Setelah verifikasi, matikan maintenance dengan `php artisan up`.
