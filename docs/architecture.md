# Arsitektur Backend

Stack: Laravel 11, Blade, SQLite (dev), PHPUnit. Aturan pengembangan ada di `rule.md`.

## 1. Lapisan

| Lapisan | Lokasi | Tanggung jawab |
|---|---|---|
| Presentation | `routes/web.php`, `app/Http/Controllers`, `app/Http/Requests`, `resources/views` | Menerima request, validasi lewat Form Request, memanggil service atau model, mengembalikan view |
| Application | `app/Services`, `app/Policies` | Logika bisnis: registrasi, favorit, lead, pencarian kampus, verifikasi kampus, pendaftaran, afiliasi, skoring tes |
| Domain | `app/Models`, `app/Enums` | Entitas, relasi, scope query, nilai tetap bertipe (enum) |
| Infrastructure | `database/migrations`, `database/seeders`, `database/factories`, `config/riasec.php`, `config/affiliate.php` | Skema, data awal, konfigurasi soal tes dan aturan afiliasi |

Aturan alur: controller tidak berisi logika bisnis. Query baca sederhana (daftar, filter, pencarian) berada di scope model. Aturan yang mengubah data berada di service.

## 2. Service

| Service | Tanggung jawab |
|---|---|
| `RegistrationService` | Membuat akun siswa (`role = student`) dari data yang sudah tervalidasi |
| `FavoriteService` | `toggle`, `isFavorited`, `listFor` untuk item apa pun (relasi polimorfik: kampus, jurusan, karier, beasiswa) |
| `LeadService` | `submit` (idempoten per pengguna, kampus, dan sumber), `updateStatus` |
| `RiasecService` | `questions`, `score` (skor per tipe dan kode 3 huruf), `recommend` (cocokkan ke `majors.riasec_codes` dengan bobot 3, 2, 1), `submit` (simpan ke `test_results`) |
| `LearningStyleService` | `questions` (20 soal Likert dari `config/learning_style.php`), `score` (skor per gaya, gaya dominan, saran belajar), `submit` |
| `MbtiService` | `questions` (40 soal A/B dari `config/mbti.php`), `score` (skor per huruf, kode 4 huruf, deskripsi tipe, kategori jurusan), `recommend` (jurusan menurut kategori), `submit` |
| `CampusSearchService` | `search` (hanya kampus terverifikasi; filter jenis, bentuk, wilayah, program, jadwal, metode, jenjang, rentang biaya; urut nama, terbaru, termurah), `indexData` (seluruh variabel view daftar kampus), `resolveRegion` (slug wilayah menjadi nama) |
| `SeoPageContentService` | Judul dan pengantar halaman SEO per jadwal, program, dan metode |
| `CampusVerificationService` | `submit` (pemilik kampus), `approve` dan `reject` (super admin); otorisasi lewat `CampusPolicy`, transisi tidak sah melempar `DomainException` |
| `ApplicationService` | `submit`: simpan pendaftaran tanpa login, buat lead, catat rujukan afiliasi, buat akun hanya bila diminta dan email belum terdaftar |
| `AffiliateService` | `register`, `findByCode`, `recordReferral` (rujukan diri sendiri diabaikan), `markPaid` (komisi dibuat bila masih dalam jangka bayar), `approveCommission`, `markCommissionPaid` |
| `FaqService` | `forPath`: FAQ khusus halaman, jatuh ke FAQ umum bila kosong |

Catatan tes potensi:

- Seri pada Gaya Belajar dimenangkan gaya yang muncul lebih dulu di config. Seri pada dimensi MBTI mengikuti `mbti.tie_breakers` (I, N, F, P).
- Tipe hasil tes disimpan di `test_results.test_type` memakai konstanta `TestResult::TYPE_RIASEC`, `TYPE_LEARNING_STYLE`, `TYPE_MBTI`.
- Hasil MBTI menyimpan `recommendations` berupa daftar `major_id`.

## 3. Enum

| Enum | Nilai |
|---|---|
| `UserRole` | `student`, `campus_admin`, `super_admin` |
| `LeadStatus` | `new`, `contacted`, `registered`, `accepted` |
| `LeadSource` | `brochure`, `favorite`, `application` |
| `BrochureType` | `program`, `tuition`, `general` |
| `DegreeLevel` | `d1`, `d3`, `s1`, `s2`, `s3` |
| `ProgramType` | `karyawan`, `reguler`, `rpl`, `akselerasi`, `shift` |
| `ClassSchedule` | `pagi`, `sore`, `malam`, `akhir-pekan`, `shift` |
| `LearningMethod` | `blended`, `tatap-muka`, `hybrid`, `full-online` |
| `VerificationStatus` | `draft`, `pending`, `verified`, `rejected` |
| `LastEducation` | `sma`, `paket-c`, `d3`, `s1`, `s2`, `s3` |
| `SourceInfo` | `website`, `social-media`, `brochure`, `friend`, `advertisement`, `other` |
| `AffiliateCategory` | `umum`, `mahasiswa`, `dosen`, `staf-kampus` |
| `ReferralStatus` | `registered`, `paid`, `rejected` |
| `CommissionStatus` | `pending`, `approved`, `paid` |

Nilai enum yang dipakai di URL memakai tanda hubung (contoh `akhir-pekan`) agar rute dapat memakai binding enum Laravel.

## 4. Skema Database

```
users            id, name, email*, phone, password, role, google_id*, campus_id -> campuses
campuses         id, name, slug*, type, form, city, province, accreditation,
                 description, established_year, website, logo, phone, email, address,
                 verification_status, verified_at, verified_by -> users
majors           id, name, slug*, category, description, courses(json),
                 career_prospects(json), riasec_codes
study_programs   id, campus_id -> campuses, major_id -> majors, degree_level, degree_title,
                 program_type, accreditation, registration_fee, first_payment,
                 monthly_installment, original_monthly_installment (harga coret),
                 schedules(json enum), methods(json enum)
                 (unik: campus, major, degree_level, program_type)
admission_periods id, campus_id -> campuses, name, opens_at, closes_at
applications     id, user_id -> users (null), campus_id, major_id, full_name, email,
                 whatsapp, last_education, region, program_type, schedule,
                 source_info, accepted_terms, status
affiliates       id, user_id -> users (unik), code*, category
affiliate_referrals id, affiliate_id, application_id* , status, paid_at
commissions      id, affiliate_referral_id*, amount, status, paid_at
faqs             id, scope_type (global|path), scope_key, question, answer, sort
careers          id, name, slug*, description, salary_min, salary_max, positions(json)
career_major     career_id -> careers, major_id -> majors    (unik berpasangan)
scholarships     id, campus_id -> campuses (null), name, slug*, description,
                 provider, start_date, end_date, registration_url
brochures        id, campus_id -> campuses, title, type, file_path
favorites        id, user_id -> users, favoritable_type, favoritable_id
                 (unik: user, type, id)
leads            id, user_id -> users (null), application_id -> applications (null, unik),
                 campus_id -> campuses, source, status
                 (unik: user, campus, source)
test_results     id, user_id -> users, test_type, result(json)
```

Tanda `*` berarti unik. Tabel `campus_major` lama diganti `study_programs`: migrasi menyalin data lama dengan nilai awal aman (S1, reguler, biaya 0) dan relasi `Campus::majors()` serta `Major::campuses()` tetap ada lewat `study_programs` (dibuat unik dengan `distinct`). Jumlah `majors_count` pada kampus sama dengan jumlah baris program studi, jadi seeder membuat satu baris per pasangan kampus dan jurusan.

Kampus tampil publik hanya bila `verification_status = verified` (scope `Campus::verified()`). Pemilik data adalah kampus (admin kampus, `users.campus_id`), pemeriksa adalah super admin.

Pendaftar tanpa akun disimpan di `applications` dengan `user_id` kosong. Keterkaitan rujukan afiliasi lewat `affiliate_referrals.application_id` (bukan kolom di `applications`) agar tidak ada referensi melingkar. Hapus kampus: brosur dan lead ikut terhapus, beasiswa dan admin kampus menjadi tanpa kampus.

## 5. Alur Data

- Daftar dan detail: request -> Form Request -> controller -> scope model -> view.
- Tes RIASEC: jawaban Likert 1-5 untuk 24 soal -> `RiasecService::score` -> `recommend` -> `submit` menyimpan hasil dan rekomendasi (`major_id`, `match`) di `test_results.result`.
- Pendaftaran: `/daftar-kuliah` (POST, throttle `applications`) -> `StoreApplicationRequest` (termasuk cek kampus menyediakan jurusan) -> `ApplicationService::submit` -> `applications`, `leads`, dan `affiliate_referrals` bila ada kode rujukan.
- Afiliasi: `?ref=KODE` pada URL apa pun -> middleware `CaptureAffiliateReferral` menyimpan kode di cookie -> `ApplicationController` meneruskan cookie ke service -> `AffiliateService::markPaid` membuat komisi bila pembayaran masuk dalam `affiliate.payment_window_days`.
- Verifikasi kampus: admin kampus `submit` -> super admin `approve` atau `reject` -> hanya `verified` tampil publik.
- Lead: aksi pengguna (unduh brosur, favorit kampus, ajukan pendaftaran) -> `LeadService::submit` -> admin kampus mengubah status lewat `updateStatus`.

## 6. Kontrak Variabel View

| View | Variabel |
|---|---|
| `careers.index` | `$careers` (paginator 12), `$query` |
| `careers.show` | `$career` (dengan `majors`), `$relatedCampuses` (maks 8, dengan `majors_count`) |
| `scholarships.index` | `$scholarships` (paginator 12, dengan `campus`), `$query` |
| `scholarships.show` | `$scholarship` (dengan `campus`) |
| `majors.show` | `$major` (dengan `careers`), `$campuses` |
| `campuses.index` | `$campuses` (paginator 9, dengan `majors_count` dan `min_monthly_installment`), `$filters`, `$query`, `$sort`, `$forms`, `$cities`, `$provinces`, `$accreditations`, `$typeOptions`, `$formLabels`, `$programTypes`, `$schedules`, `$methods`, `$degreeLevels`; pada halaman SEO ditambah `$heading` dan `$intro` |
| `campuses.show` | `$campus` (dengan `majors` dan `admissionPeriods`) |
| `applications.create` | `$campuses` (`id`, `name`, `city`, `province`), `$majors` (`id`, `name`), `$options` (`last_education`, `program_type`, `schedule`, `source_info`: daftar case enum dengan `label()`); sesi `status` setelah kirim |

### Rute baru

| Rute | Nama | Keterangan |
|---|---|---|
| `GET /jadwal-kuliah/{schedule}/{region?}` | `campuses.by-schedule` | `schedule` enum `ClassSchedule`, `region` slug provinsi atau kota, 404 bila tidak dikenal |
| `GET /program-kuliah/{programType}` | `campuses.by-program` | enum `ProgramType` |
| `GET /metode-belajar/{method}/{region?}` | `campuses.by-method` | enum `LearningMethod` |
| `GET /daftar-kuliah` | `applications.create` | Formulir pendaftaran |
| `POST /daftar-kuliah` | `applications.store` | Throttle `applications` (5 per menit per IP, konstanta di `AppServiceProvider`) |

### Kontrak fase 3 (lapisan HTTP)

Semua nama view di bawah dibuat oleh agent frontend. Variabel adalah kontrak: controller mengirim persis ini.

Variabel bersama untuk `layouts.app` (View Composer `NavigationComposer`, tanpa query di Blade): `$authUser` (`User` atau `null`), `$favoriteCount` (int, 0 bagi tamu), `$isAdmin` (bool, true untuk `super_admin` dan `campus_admin`). Flash umum: sesi `status` (pesan sukses), `$errors` (validasi).

Halaman detail (`campuses.show`, `majors.show`, `careers.show`, `scholarships.show`) menerima tambahan `$isFavorited` (bool). Tombol favorit mengirim `POST favorites.toggle` dengan field `type` (`campus`, `major`, `career`, `scholarship`) dan `id`, lalu kembali ke halaman asal (butuh login).

`campuses.show` kini menerima: `$campus` (dengan `majors`), `$brochures` (koleksi `Brochure`, `type` enum `BrochureType`), `$admissionPeriods` (koleksi `AdmissionPeriod`, method `isOpen()`), `$studyPrograms` (koleksi `StudyProgram` dengan `major`, diurut jenjang lalu cicilan), `$faqs` (koleksi `Faq`: `question`, `answer`), `$isFavorited`. Tautan unduh: `route('brochures.download', [$campus, $brochure])`.

`campuses.index` (juga halaman SEO) memuat `studyPrograms` pada setiap kampus (eager load) agar kartu bisa menampilkan badge program, jadwal, dan metode tanpa query tambahan di Blade.

`applications.create` menerima tambahan `$selectedCampusId` (int atau `null`) dari query `?campus={slug}`; slug yang tidak dikenal atau belum terverifikasi menghasilkan `null`.

`favorites.index`: koleksi `campuses` membawa `majors_count`, koleksi `majors` membawa `campuses_count` (dihitung di `FavoriteService::groupedFor`).

Semua enum (kecuali `UserRole`) punya `label()` untuk teks tampilan.

Login Google (`auth.google.redirect` pada `GET /auth/google`, `auth.google.callback` pada `GET /auth/google/callback`, tamu saja): hanya aktif bila `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` terisi di `.env` (redirect default `/auth/google/callback`, bisa diubah lewat `GOOGLE_REDIRECT_URI`). Tanpa kredensial kedua rute 404. `auth.login` dan `auth.register` menerima `$googleEnabled` (bool) untuk menampilkan atau menyembunyikan tombol Google.

Halaman SEO (`campuses.by-schedule`, `campuses.by-program`, `campuses.by-method`) memakai view `campuses.index` dan kini juga menerima `$faqs`.

| Rute | Nama | Akses | View dan variabel |
|---|---|---|---|
| `GET /login`, `POST /login` | `login` | tamu, throttle `login` (5 per menit per email dan IP) | `auth.login`; field `email`, `password`, `remember` |
| `GET /register`, `POST /register` | `register` | tamu, throttle `register` | `auth.register`; field `name`, `email`, `phone`, `password`, `password_confirmation` |
| `POST /logout` | `logout` | login | redirect ke `home` |
| `GET /forgot-password`, `POST /forgot-password` | `password.request`, `password.email` | tamu, throttle `password-reset` | `auth.forgot-password`; field `email`; sesi `status` |
| `GET /reset-password/{token}`, `POST /reset-password` | `password.reset`, `password.update` | tamu, throttle `password-reset` | `auth.reset-password` dengan `$token`, `$email` (dari query `?email=`); field `token`, `email`, `password`, `password_confirmation` |
| `GET /test` | `tests.index` | publik | `tests.index` dengan `$tests`: daftar `['key', 'title', 'description', 'badge', 'duration_label']`; kunci `riasec`, `learning-style`, `mbti` |
| `GET /riasec` | - | publik | redirect ke `/test/riasec` |
| `GET /test/{type}` | `tests.start` | publik | `tests.start` dengan `$type`, `$title`, `$description`, `$questions`, `$scaleLabels` |
| `POST /test/{type}` | `tests.submit` | publik (tamu disimpan di sesi lalu diarahkan login) | field `answers[indeks]` untuk setiap soal |
| `GET /test/resume` | `tests.resume` | login | menyelesaikan tes tamu yang tertunda, redirect ke hasil |
| `GET /test/results/{testResult}` | `tests.result` | login dan pemilik (403 bila bukan) | `tests.result` dengan `$testResult`, `$summary`, `$scores`, `$recommendedMajors` |
| `GET /test/history` | `tests.history` | login | `tests.history` dengan `$results` (paginator 10 dari `TestResult`), `$testTitles` (peta `test_type` ke judul) |
| `GET /favourites` | `favorites.index` | login | `favorites.index` dengan `$favorites`: `['campuses', 'majors', 'careers', 'scholarships']`, tiap nilai koleksi model |
| `POST /favorites` | `favorites.toggle` | login | field `type`, `id`; redirect back |
| `GET /universities/{campus}/brochures/{brochure}` | `brochures.download` | login | unduh berkas, mencatat `Lead` sumber `brochure`; 404 bila berkas belum ada atau kampus belum terverifikasi |
| `GET /affiliate` | `affiliate.index` | publik | `affiliate.index` dengan `$categories` (case `AffiliateCategory` dengan `label()`), `$commissionPerStudent`, `$paymentWindowDays`, `$affiliate` (milik pengguna atau `null`) |
| `POST /affiliate/register` | `affiliate.register` | login | field `category`; redirect ke dasbor |
| `GET /affiliate/dashboard` | `affiliate.dashboard` | login dan sudah menjadi afiliator (bila belum, redirect ke `affiliate.index`) | `affiliate.dashboard` dengan `$affiliate`, `$referralUrl`, `$stats`, `$referrals` |
| `GET /admin` | `admin.dashboard` | role `super_admin` atau `campus_admin` | `admin.dashboard` dengan `$leads` (paginator 15, dengan `user`, `campus`, `application`), `$statusOptions` (case `LeadStatus` dengan `label()`), `$campus` (kampus milik admin kampus, `null` bagi super admin) |
| `POST /admin/leads/{lead}/status` | `admin.leads.status` | admin; admin kampus hanya untuk kampusnya | field `status` |
| `GET /admin/campuses` | `admin.campuses.index` | hanya `super_admin` | `admin.campuses.index` dengan `$campuses` (paginator 15, status `pending`) |
| `POST /admin/campuses/{campus}/submit` | `admin.campuses.submit` | admin kampus pemilik | redirect back; transisi tidak sah muncul di `$errors->first('status')` |
| `POST /admin/campuses/{campus}/approve`, `.../reject` | `admin.campuses.approve`, `admin.campuses.reject` | hanya `super_admin` | redirect back |

Bentuk data:

- `$questions` (tes): daftar `['index' => int, 'text' => string, 'options' => [['value' => int atau string, 'label' => string], ...]]`. Tes Likert (`riasec`, `learning-style`) memakai opsi bernilai 1 sampai 5, tes `mbti` memakai opsi `a` dan `b`. Nama input: `answers[{index}]`. `$scaleLabels` berisi `nilai => label` untuk tes Likert, kosong untuk `mbti`.
- `$summary`: `['title', 'headline', 'description']`. `$scores`: daftar `['label', 'value', 'max']`. `$recommendedMajors`: koleksi `Major` terurut dari yang paling cocok.
- `$stats` (afiliasi): `['total', 'paid', 'pending_commission', 'paid_commission']`, nilai komisi dalam rupiah (integer). Komisi tertunda mencakup status pending dan approved.
- `$referrals`: paginator `AffiliateReferral` dengan `application` (`full_name`, `email`) dan `commission` (`amount`, `status`). `status` pada rujukan berupa enum `ReferralStatus`.

### Konfigurasi afiliasi (`config/affiliate.php`)

Nilai komisi per mahasiswa (`AFFILIATE_COMMISSION`, awal 200000), jangka bayar (`AFFILIATE_PAYMENT_WINDOW_DAYS`, awal 60 hari), kunci query `ref`, nama dan umur cookie, panjang kode. Porsi komisi final ditetapkan bersama PM, jadi tidak tertanam di kode.

## 7. Cara Menambah Modul Baru

1. Migrasi di `database/migrations` dengan prefiks tanggal.
2. Model di `app/Models` (relasi, scope, cast; enum bila ada nilai tetap).
3. Factory dan seeder; daftarkan seeder di `DatabaseSeeder`.
4. Service di `app/Services` bila ada aturan yang mengubah data.
5. Form Request di `app/Http/Requests` untuk input.
6. Controller tipis dan rute bernama (`modul.index`, `modul.show`).
7. View Blade dan test di `tests/Unit` (service) atau `tests/Feature` (HTTP).
8. Perbarui dokumen ini.

## 8. Menjalankan

```bash
php artisan migrate:fresh --seed   # reset dan isi ulang data
php artisan test                   # seluruh test (SQLite in-memory)
vendor/bin/pint                    # rapikan gaya PSR-12
php artisan serve                  # server pengembangan
```

Kata sandi akun demo seeder dibaca dari env `SEED_USER_PASSWORD`. Bila kosong, kata sandi acak dibuat dan tidak ditampilkan.
