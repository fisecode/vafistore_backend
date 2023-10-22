<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    DB::table('users')->insert([
      'username' => 'admin',
      'name' => 'Akhmat Fikri Septiawan',
      'email' => 'admin@vafistore.com',
      'password' => Hash::make('admin'),
      'no_hp' => '089523090952',
      'level' => 'superadmin',
      'pinTrx' => 'c8837b23ff8aaa8a2dde915473ce0991',
      'reff' => 8,
      'uplineID' => 1,
      'join_date' => '2020-07-10 00:00:00',
      'last_login' => '2023-10-05 21:40:22',
      'email_verified_at' => '2023-10-11 00:38:14',
      'status' => 1,
    ]);

    DB::table('post_categories')->insert([
      [
        'name' => 'Game',
      ],
      [
        'name' => 'Premium',
      ],
      [
        'name' => 'Pulsa',
      ],
      [
        'name' => 'Tutorial',
      ],
    ]);

    DB::table('admins')->insert([
      [
        'cuid' => 1,
        'persen_sell' => 2,
        'persen_res' => 1,
        'com_ref' => 2,
        'satuan' => 0,
      ],
      [
        'cuid' => 2,
        'persen_sell' => 5,
        'persen_res' => 5,
        'com_ref' => 10,
        'satuan' => 0,
      ],
      [
        'cuid' => 3,
        'persen_sell' => 2,
        'persen_res' => 1,
        'com_ref' => 2,
        'satuan' => 0,
      ],
      [
        'cuid' => 4,
        'persen_sell' => 5,
        'persen_res' => 5,
        'com_ref' => 10,
        'satuan' => 0,
      ],
      [
        'cuid' => 5,
        'persen_sell' => 5,
        'persen_res' => 0,
        'com_ref' => 10,
        'satuan' => 0,
      ],
      [
        'cuid' => 7,
        'persen_sell' => 5,
        'persen_res' => 5,
        'com_ref' => 10,
        'satuan' => 0,
      ],
    ]);

    DB::table('balances')->insert([
      [
        'cuid' => 44,
        'userID' => 44,
        'active' => 0,
        'pending' => 0,
        'payout' => 0,
        'created_date' => Carbon::now(),
      ],
    ]);

    DB::table('banks')->insert([
      [
        'cuid' => 1,
        'image' => 'BRIVA.png',
        'akun' => 'Bank Rakyat Indonesia (BRI)',
        'pemilik' => 'Aris Ardiansyah',
        'no_rek' => '057701006551533',
        'userID' => 1,
      ],
      [
        'cuid' => 2,
        'image' => 'BCAVA.png',
        'akun' => 'Bank Central Asia (BCA)',
        'pemilik' => 'Aris Ardiansyah',
        'no_rek' => '2630562450',
        'userID' => 1,
      ],
      [
        'cuid' => 4,
        'image' => 'dana.png',
        'akun' => 'EWallet Dana',
        'pemilik' => 'Aris Ardiansyah',
        'no_rek' => '083153101800',
        'userID' => 1,
      ],
      // Tambahkan data lainnya sesuai kebutuhan
    ]);

    DB::table('main_banners')->insert([
      [
        'cuid' => 1,
        'image' => 'brand_master_20230727193648.png',
        'content' =>
          '<p style="text-align: center;">Hindari Transaksi pada jam 23:30 s/d 00:30 WIB</p><p style="text-align: center;">----------</p>',
        'status' => 'true',
      ],
      // Tambahkan data lainnya sesuai kebutuhan
    ]);

    DB::table('banners')->insert([
      [
        'cuid' => 1,
        'catID' => 1,
        'image' => 'popup_admin_20230911201419.jpeg',
        'content' => '<p style="text-align: center;">Pastikan data Anda sudah benar</p>',
        'status' => 'true',
      ],
      // Tambahkan data lainnya sesuai kebutuhan
    ]);

    DB::table('categories')->insert([
      [
        'cuid' => 1,
        'kategori' => 'Game',
        'image' => 'fas fa-gamepad',
        'sort' => 1,
        'status' => 1,
      ],
      [
        'cuid' => 2,
        'kategori' => 'Premium',
        'image' => 'fas fa-shopping-bag',
        'sort' => 5,
        'status' => 1,
      ],
      [
        'cuid' => 3,
        'kategori' => 'Pulsa',
        'image' => 'fas fa-mobile-alt',
        'sort' => 2,
        'status' => 1,
      ],
      [
        'cuid' => 4,
        'kategori' => 'Emoney',
        'image' => 'fab fa-google-wallet',
        'sort' => 3,
        'status' => 1,
      ],
      [
        'cuid' => 5,
        'kategori' => 'Social',
        'image' => 'fas fa-share-nodes',
        'sort' => 4,
        'status' => 1,
      ],
      [
        'cuid' => 6,
        'kategori' => 'Jasa Joki',
        'image' => 'fas fa-heart',
        'sort' => 6,
        'status' => 0,
      ],
      [
        'cuid' => 7,
        'kategori' => 'Pascabayar',
        'image' => 'fas fa-wallet',
        'sort' => 7,
        'status' => 1,
      ],
    ]);

    DB::table('services')->insert([
      [
        'cuid' => 1,
        'slug' => 'apexlegendsmobile',
        'layanan' => 'Apex Legends Mobile',
        'cekID' => '',
        'image' => 'apex_legends_mobile.png',
        'parent' => 1,
        'deskripsi' => '<ol style="margin-left: -25px;">
              <li>Masukan UserID dan Zone / Server ID</li>
              <li>Pilih Layanan Yang Diinginkan</li>
              <li>Pilih Metode Pembayaran</li>
              <li>Masukan No. Whatsapp Anda agar mendapapat notifikasi</li>
              <li>Klik Beli Sekarang dan Selesaikan Pembayaran</li>
              <li>Tunggu Proses 1-2 Menit (event max 2jam), Pesanan Anda akan Masuk Secara Otomatis</li>
          </ol>',
        'bantuan' => '<p>Tes</p>',
        'subtitle' => '',
        'subimage' => 'subimage.png',
        'populer' => 0,
        'sort' => 0,
        'created_date' => '2023-09-11',
        'user' => 'master',
        'status' => 1,
      ],
      [
        'cuid' => 6,
        'slug' => 'callofdutymobile',
        'layanan' => 'Call of Duty Mobile',
        'cekID' => 'call-of-duty-mobile',
        'image' => 'call_of_duty_mobile.png',
        'parent' => 1,
        'deskripsi' => '<ol style="margin-left: -25px;">
              <li>Masukan UserID dan Zone / Server ID</li>
              <li>Pilih Layanan Yang Diinginkan</li>
              <li>Pilih Metode Pembayaran</li>
              <li>Masukan No. Whatsapp Anda agar mendapapat notifikasi</li>
              <li>Klik Beli Sekarang dan Selesaikan Pembayaran</li>
              <li>Tunggu Proses 1-2 Menit (event max 2jam), Pesanan Anda akan Masuk Secara Otomatis</li>
          </ol>',
        'bantuan' => '',
        'subtitle' => '',
        'subimage' => 'subimage.png',
        'populer' => 0,
        'sort' => 0,
        'created_date' => '2023-09-11',
        'user' => 'master',
        'status' => 1,
      ],
      [
        'cuid' => 3,
        'slug' => 'au2mobile',
        'layanan' => 'AU2 MOBILE',
        'cekID' => '',
        'image' => 'au2_mobile.png',
        'parent' => 1,
        'deskripsi' => '<ol style="margin-left: -25px;">
              <li>Masukan UserID dan Zone / Server ID</li>
              <li>Pilih Layanan Yang Diinginkan</li>
              <li>Pilih Metode Pembayaran</li>
              <li>Masukan No. Whatsapp Anda agar mendapapat notifikasi</li>
              <li>Klik Beli Sekarang dan Selesaikan Pembayaran</li>
              <li>Tunggu Proses 1-2 Menit (event max 2jam), Pesanan Anda akan Masuk Secara Otomatis</li>
          </ol>',
        'bantuan' => '',
        'subtitle' => '',
        'subimage' => 'subimage.png',
        'populer' => 0,
        'sort' => 0,
        'created_date' => '2023-09-11',
        'user' => 'master',
        'status' => 1,
      ],
      [
        'cuid' => 4,
        'slug' => 'betheking',
        'layanan' => 'Be The King',
        'cekID' => '',
        'image' => 'be_the_king.png',
        'parent' => 1,
        'deskripsi' => '<ol style="margin-left: -25px;">
              <li>Masukan UserID and Zone / Server ID</li>
              <li>Pilih Layanan Yang Diinginkan</li>
              <li>Pilih Metode Pembayaran</li>
              <li>Masukan No. Whatsapp Anda agar mendapapat notifikasi</li>
              <li>Klik Beli Sekarang dan Selesaikan Pembayaran</li>
              <li>Tunggu Proses 1-2 Menit (event max 2jam), Pesanan Anda akan Masuk Secara Otomatis</li>
          </ol>',
        'bantuan' => '',
        'subtitle' => '',
        'subimage' => 'subimage.png',
        'populer' => 0,
        'sort' => 0,
        'created_date' => '2023-09-11',
        'user' => 'master',
        'status' => 1,
      ],
      [
        'cuid' => 5,
        'slug' => 'chimeraland',
        'layanan' => 'Chimeraland',
        'cekID' => '',
        'image' => 'chimeraland.png',
        'parent' => 1,
        'deskripsi' => '<ol style="margin-left: -25px;">
              <li>Masukan UserID and Zone / Server ID</li>
              <li>Pilih Layanan Yang Diinginkan</li>
              <li>Pilih Metode Pembayaran</li>
              <li>Masukan No. Whatsapp Anda agar mendapapat notifikasi</li>
              <li>Klik Beli Sekarang dan Selesaikan Pembayaran</li>
              <li>Tunggu Proses 1-2 Menit (event max 2jam), Pesanan Anda akan Masuk Secara Otomatis</li>
          </ol>',
        'bantuan' => '',
        'subtitle' => '',
        'subimage' => 'subimage.png',
        'populer' => 0,
        'sort' => 0,
        'created_date' => '2023-09-11',
        'user' => 'master',
        'status' => 1,
      ],
    ]);

    DB::table('pages')->insert([
      [
        'cuid' => 5,
        'slug' => 'kebijakan-privasi',
        'nama_page' => 'Kebijakan Privasi',
        'content' =>
          '<p><b>Kebijakan Privasi</b></p><p>Website Game Lapax Digital dimiliki oleh Game Lapax Digital, yang akan menjadi pengontrol atas data pribadi Anda.</p><p>Kami telah mengadopsi Kebijakan Privasi ini untuk menjelaskan bagaimana kami memproses informasi yang dikumpulkan oleh Game Lapax Digital, yang juga menjelaskan alasan mengapa kami perlu mengumpulkan data pribadi tertentu tentang Anda. Oleh karena itu, Anda harus membaca Kebijakan Privasi ini sebelum menggunakan website Game Lapax Digital.</p><p>Kami menjaga data pribadi Anda dan berjanji untuk menjamin kerahasiaan dan keamanannya.</p><p>Informasi pribadi yang kami kumpulkan :</p><p>Saat Anda mengunjungi Game Lapax Digital, kami secara otomatis mengumpulkan informasi tertentu mengenai perangkat Anda, termasuk informasi tentang browser web, alamat IP, zona waktu, dan sejumlah cookie yang terinstal di perangkat Anda. Selain itu, selama Anda menjelajahi Website, kami mengumpulkan informasi tentang setiap halaman web atau produk yang Anda lihat, website atau istilah pencarian apa yang mengarahkan Anda ke Website, dan cara Anda berinteraksi dengan Website. Kami menyebut informasi yang dikumpulkan secara otomatis ini sebagai \"Informasi Perangkat\". Kemudian, kami mungkin akan mengumpulkan data pribadi yang Anda berikan kepada kami (termasuk tetapi tidak terbatas pada, Nama, Nama belakang, Alamat, informasi pembayaran, dll.) selama pendaftaran untuk dapat memenuhi perjanjian.</p><p>Mengapa kami memproses data Anda?</p><p>Menjaga data pelanggan agar tetap aman adalah prioritas utama kami. Oleh karena itu, kami hanya dapat memproses sejumlah kecil data pengguna, sebanyak yang benar-benar diperlukan untuk menjalankan website. Informasi yang dikumpulkan secara otomatis hanya digunakan untuk mengidentifikasi kemungkinan kasus penyalahgunaan dan menyusun informasi statistik terkait penggunaan website. Informasi statistik ini tidak digabungkan sedemikian rupa hingga dapat mengidentifikasi pengguna tertentu dari sistem.</p><p>Anda dapat mengunjungi website tanpa memberi tahu kami identitas Anda atau mengungkapkan informasi apa pun, yang dapat digunakan oleh seseorang untuk mengidentifikasi Anda sebagai individu tertentu yang dapat dikenali. Namun, jika Anda ingin menggunakan beberapa fitur website, atau Anda ingin menerima newsletter kami atau memberikan detail lainnya dengan mengisi formulir, Anda dapat memberikan data pribadi kepada kami, seperti email, nama depan, nama belakang, kota tempat tinggal, organisasi, dan nomor telepon Anda. Anda dapat memilih untuk tidak memberikan data pribadi Anda kepada kami, tetapi Anda mungkin tidak dapat memanfaatkan beberapa fitur website. Contohnya, Anda tidak akan dapat menerima Newsletter kami atau menghubungi kami secara langsung dari website. Pengguna yang tidak yakin tentang informasi yang wajib diberikan dapat menghubungi kami melalui admin@lapaxdigital.com.</p><p>Hak-hak Anda :</p><p>Jika Anda seorang warga Eropa, Anda memiliki hak-hak berikut terkait data pribadi Anda :</p><p>Hak untuk mendapatkan penjelasan.</p><p>Hak atas akses.</p><p>Hak untuk memperbaiki.</p><p>Hak untuk menghapus.</p><p>Hak untuk membatasi pemrosesan.</p><p>Hak atas portabilitas data.</p><p>Hak untuk menolak.</p><p>Hak-hak terkait pengambilan keputusan dan pembuatan profil otomatis.</p><p>Jika Anda ingin menggunakan hak ini, silakan hubungi kami melalui informasi kontak di bawah ini.</p><p>Selain itu, jika Anda seorang warga Eropa, perlu diketahui bahwa kami akan memproses informasi Anda untuk memenuhi kontrak yang mungkin kami miliki dengan Anda (misalnya, jika Anda melakukan pemesanan melalui Website), atau untuk memenuhi kepentingan bisnis sah kami seperti yang tercantum di atas. Di samping itu, harap diperhatikan bahwa informasi Anda mungkin dapat dikirim ke luar Eropa, termasuk Kanada dan Amerika Serikat.</p><p>Link ke website lain :</p><p>Website kami mungkin berisi tautan ke website lain yang tidak dimiliki atau dikendalikan oleh kami. Perlu diketahui bahwa kami tidak bertanggung jawab atas praktik privasi website lain atau pihak ketiga. Kami menyarankan Anda untuk selalu waspada ketika meninggalkan website kami dan membaca pernyataan privasi setiap website yang mungkin mengumpulkan informasi pribadi.</p><p>Keamanan informasi :</p><p>Kami menjaga keamanan informasi yang Anda berikan pada server komputer dalam lingkungan yang terkendali, aman, dan terlindungi dari akses, penggunaan, atau pengungkapan yang tidak sah. Kami menjaga pengamanan administratif, teknis, dan fisik yang wajar untuk perlindungan terhadap akses, penggunaan, modifikasi, dan pengungkapan tidak sah atas data pribadi dalam kendali dan pengawasannya. Namun, kami tidak menjamin tidak akan ada transmisi data melalui Internet atau jaringan nirkabel.</p><p>Pengungkapan hukum :</p><p>Kami akan mengungkapkan informasi apa pun yang kami kumpulkan, gunakan, atau terima jika diwajibkan atau diizinkan oleh hukum, misalnya untuk mematuhi panggilan pengadilan atau proses hukum serupa, dan jika kami percaya dengan itikad baik bahwa pengungkapan diperlukan untuk melindungi hak kami, melindungi keselamatan Anda atau keselamatan orang lain, menyelidiki penipuan, atau menanggapi permintaan dari pemerintah.</p><p>Informasi kontak :</p><p>Jika Anda ingin menghubungi kami untuk mempelajari Kebijakan ini lebih lanjut atau menanyakan masalah apa pun yang berkaitan dengan hak perorangan dan Informasi pribadi Anda, Anda dapat mengirim email ke admin@lapaxdigital.com.</p>',
        'image' => '',
        'video' => '',
        'created_date' => '2023-09-12',
        'last_update' => '2023-09-12',
        'user' => 'admin',
      ],
      [
        'cuid' => 3,
        'slug' => 'faq',
        'nama_page' => 'FAQ',
        'content' =>
          '<p>Pertanyaan yang sering diajukan :</p><p>1. Bagaimana cara melaukan Registrasi ?</p><p>&nbsp; &nbsp; - Pilih menu Registrasi, isi semua data2 yang dibutuhkan.</p><p>2. Apakah bisa membeli produk tanpa harus login ?</p><p>&nbsp; &nbsp; - Bisa! akan tetapi jika pembelian Anda gagal otomatis dana tidak kembali. Silahkan hub admin.<br></p>',
        'image' => '',
        'video' => '',
        'created_date' => '2023-09-12',
        'last_update' => '2023-09-12',
        'user' => 'admin',
      ],
      [
        'cuid' => 2,
        'slug' => 'informasi-reseller',
        'nama_page' => 'Informasi Reseller',
        'content' =>
          '<p>Syarat Untuk melakukan Upgrade keanggotaan menjadi Reseller, Anda wajib melakukan transaksi minimal 15 Transaksi.</p><p>Lakukan transaksi sebanyak 15 Transaksi lagi untuk melakukan Upgrade keanggotan Anda menjadi Reseller.</p>',
        'image' => '',
        'video' => '',
        'created_date' => '2023-09-12',
        'last_update' => '2023-09-12',
        'user' => 'admin',
      ],
      [
        'cuid' => 1,
        'slug' => 'tentang-kami',
        'nama_page' => 'Tentang Kami',
        'content' =>
          '<p>Menyediakan Topup Game Favorit Kamu Agar Main Game Semakin Seru. Pengiriman Cepat dan berbagai macam Metode Pembayaran. Layanan 24 Jam Non-stop.<br></p>',
        'image' => '',
        'video' => '',
        'created_date' => '2023-09-12',
        'last_update' => '2023-09-12',
        'user' => 'admin',
      ],
      [
        'cuid' => 4,
        'slug' => 'ketentuan-layanan',
        'nama_page' => 'Ketentuan Layanan',
        'content' =>
          '<p>Syarat dan Ketentuan</p><p>Selamat datang di Game Lapax Digital!</p><p>Syarat dan ketentuan berikut menjelaskan peraturan dan ketentuan penggunaan Website Game Lapax Digital dengan alamat https://game.lapaxdigital.com/.</p><p>Dengan mengakses website ini, kami menganggap Anda telah menyetujui syarat dan ketentuan ini. Jangan lanjutkan penggunaan Game Lapax Digital jika Anda menolak untuk menyetujui semua syarat dan ketentuan yang tercantum di halaman ini.</p><p>Cookie :</p><p>Website ini menggunakan cookie untuk mempersonalisasi pengalaman online Anda. Dengan mengakses Game Lapax Digital, Anda menyetujui penggunaan cookie yang diperlukan.</p><p>Cookie merupakan file teks yang ditempatkan pada hard disk Anda oleh server halaman web. Cookie tidak dapat digunakan untuk menjalankan program atau mengirimkan virus ke komputer Anda. Cookie yang diberikan telah disesuaikan untuk Anda dan hanya dapat dibaca oleh web server pada domain yang mengirimkan cookie tersebut kepada Anda.</p><p>Kami dapat menggunakan cookie untuk mengumpulkan, menyimpan, dan melacak informasi untuk keperluan statistik dan pemasaran untuk mengoperasikan website kami. Ada beberapa Cookie wajib yang diperlukan untuk pengoperasian website kami. Cookie ini tidak memerlukan persetujuan Anda karena akan selalu aktif. Perlu diketahui bahwa dengan menyetujui Cookie wajib, Anda juga menyetujui Cookie pihak ketiga, yang mungkin digunakan melalui layanan yang disediakan oleh pihak ketiga jika Anda menggunakan layanan tersebut di website kami, misalnya, jendela tampilan video yang disediakan oleh pihak ketiga dan terintegrasi dengan website kami.</p><p>Lisensi :</p><p>Kecuali dinyatakan lain, Game Lapax Digital dan/atau pemberi lisensinya memiliki hak kekayaan intelektual atas semua materi di Game Lapax Digital. Semua hak kekayaan intelektual dilindungi undang-undang. Anda dapat mengaksesnya dari Game Lapax Digital untuk penggunaan pribadi Anda sendiri dengan batasan yang diatur dalam syarat dan ketentuan ini.</p><p>Anda dilarang untuk :</p><p>Menyalin atau menerbitkan ulang materi dari Game Lapax Digital</p><p>Menjual, menyewakan, atau mensublisensikan materi dari Game Lapax Digital</p><p>Memproduksi ulang, menggandakan, atau menyalin materi dari Game Lapax Digital</p><p>Mendistribusikan ulang konten dari Game Lapax Digital</p><p>Perjanjian ini akan mulai berlaku pada tanggal perjanjian ini.</p><p>Beberapa bagian website ini menawarkan kesempatan bagi pengguna untuk memposting serta bertukar pendapat dan informasi di area website tertentu. Game Lapax Digital tidak akan memfilter, mengedit, memublikasikan, atau meninjau Komentar di hadapan pengguna di website. Komentar tidak mencerminkan pandangan dan pendapat Game Lapax Digital, agennya, dan/atau afiliasinya. Komentar mencerminkan pandangan dan pendapat individu yang memposting pandangan dan pendapatnya. Selama diizinkan oleh undang-undang yang berlaku, Game Lapax Digital tidak akan bertanggung jawab atas Komentar atau kewajiban, kerugian, atau pengeluaran yang disebabkan dan/atau ditanggung sebagai akibat dari penggunaan dan/atau penempatan dan/atau penayangan Komentar di website ini.</p><p>Game Lapax Digital berhak memantau semua Komentar dan menghapus Komentar apa pun yang dianggap tidak pantas, menyinggung, atau menyebabkan pelanggaran terhadap Syarat dan Ketentuan ini.</p><p>Anda menjamin dan menyatakan bahwa :</p><p>Anda berhak memposting Komentar di website kami serta memiliki semua lisensi dan persetujuan yang diperlukan untuk melakukannya;</p><p>Komentar tidak melanggar hak kekayaan intelektual apa pun, termasuk tetapi tidak terbatas pada, hak cipta, paten, atau merek dagang pihak ketiga mana pun;</p><p>Komentar tidak mengandung materi yang bersifat memfitnah, mencemarkan nama baik, menyinggung, tidak senonoh, atau melanggar hukum, yang merupakan pelanggaran privasi.</p><p>Komentar tidak akan digunakan untuk membujuk atau mempromosikan bisnis atau kebiasaan atau memperkenalkan kegiatan komersial atau kegiatan yang melanggar hukum.</p><p>Dengan ini Anda memberikan lisensi non-eksklusif kepada Game Lapax Digital untuk menggunakan, memproduksi ulang, mengedit, dan mengizinkan orang lain untuk menggunakan, memproduksi ulang, dan mengedit Komentar Anda dalam segala bentuk, format, atau media.</p><p>Membuat hyperlink yang mengarah ke Konten kami :</p><p>Organisasi berikut dapat membuat tautan menuju Website kami tanpa persetujuan tertulis sebelumnya :</p><p>Lembaga pemerintah;</p><p>Mesin pencari;</p><p>Kantor berita;</p><p>Distributor direktori online dapat membuat tautan menuju Website kami dengan cara yang sama seperti membuat tautan ke Website bisnis terdaftar lainnya; dan</p><p>Bisnis Terakreditasi di Seluruh Sistem kecuali meminta organisasi nirlaba, pusat perbelanjaan amal, dan grup penggalangan dana amal yang mungkin tidak membuat hyperlink menuju Website kami.</p><p>Organisasi-organisasi ini dapat menautkan ke halaman beranda, ke publikasi, atau ke informasi Website kami lainnya selama tautan tersebut: (a) tidak menipu dengan cara apa pun; (b) tidak menyiratkan secara keliru adanya hubungan sponsor, rekomendasi, atau persetujuan dari pihak yang menautkan beserta produk dan/atau layanannya; serta (c) sesuai dengan konteks website pihak yang menautkan.</p><p>Kami dapat mempertimbangkan dan menyetujui permintaan penautan lain dari jenis organisasi berikut :</p><p>sumber informasi bisnis dan/atau konsumen yang sudah umum dikenal;</p><p>website komunitas dot.com;</p><p>asosiasi atau kelompok lain yang mewakili badan amal;</p><p>distributor direktori online ;</p><p>portal internet;</p><p>firma akuntansi, hukum, dan konsultan; dan</p><p>lembaga pendidikan dan asosiasi dagang.</p><p>Kami akan menyetujui permintaan penautan dari organisasi-organisasi tersebut jika kami memutuskan bahwa: (a) tautan tersebut tidak akan membuat kami terlihat merugikan kami sendiri atau bisnis terakreditasi kami; (b) organisasi tidak memiliki catatan negatif apa pun dengan kami; (c) keuntungan bagi kami dari keberadaan hyperlink mengkompensasi tidak adanya Game Lapax Digital; dan (d) tautan tersebut dalam konteks informasi sumber daya umum.</p><p>Organisasi-organisasi ini dapat menautkan ke halaman beranda kami selama tautan tersebut: (a) tidak menipu dengan cara apa pun; (b) tidak menyiratkan secara keliru adanya hubungan sponsor, rekomendasi, atau persetujuan dari pihak yang menautkan beserta produk dan/atau layanannya; dan (c) sesuai dengan konteks website pihak yang menautkan.</p><p>Jika Anda merupakan salah satu organisasi yang tercantum dalam paragraf 2 di atas dan tertarik untuk membuat tautan ke website kami, Anda harus memberitahu kami dengan mengirimkan email ke Game Lapax Digital. Harap cantumkan nama Anda, nama organisasi Anda, informasi kontak dan URL website Anda, daftar URL apa pun yang akan memuat tautan ke Website kami, serta daftar URL di website kami yang ingin Anda tautkan. Silakan tunggu tanggapan dari kami selama 2-3 minggu.</p><p>Organisasi yang telah disetujui dapat membuat hyperlink menuju Website kami seperti berikut :</p><p>Dengan menggunakan nama perusahaan kami; atau</p><p>Dengan menggunakan Uniform Resource Locator yang ditautkan; atau</p><p>Dengan menggunakan deskripsi lain dari Website kami yang ditautkan yang masuk akal dalam konteks dan format konten di website pihak yang menautkan.</p><p>Tidak ada penggunaan logo Game Lapax Digital atau karya seni lainnya yang diizinkan untuk menautkan perjanjian lisensi merek dagang.</p><p>Tanggung jawab atas Konten :</p><p>Kami tidak akan bertanggung jawab atas konten yang muncul di Website Anda. Anda setuju untuk melindungi dan membela kami terhadap semua klaim yang diajukan atas Website Anda. Tidak ada tautan yang muncul di Website mana pun yang dapat dianggap sebagai memfitnah, cabul, atau kriminal, atau yang menyalahi, atau melanggar, atau mendukung pelanggaran lain terhadap hak pihak ketiga.</p><p>Pernyataan Kepemilikan Hak :</p><p>Kami berhak meminta Anda menghapus semua tautan atau tautan tertentu yang menuju ke Website kami. Anda setuju untuk segera menghapus semua tautan ke Website kami sesuai permintaan. Kami juga berhak mengubah syarat ketentuan ini dan kebijakan penautannya kapan saja. Dengan terus menautkan ke Website kami, Anda setuju untuk terikat dan mematuhi syarat dan ketentuan penautan ini.</p><p>Penghapusan tautan dari website kami:</p><p>Jika Anda menemukan tautan di Website kami yang bersifat menyinggung karena alasan apa pun, Anda bebas menghubungi dan memberi tahu kami kapan saja. Kami akan mempertimbangkan permintaan untuk menghapus tautan, tetapi kami tidak berkewajiban untuk menanggapi Anda secara langsung.</p><p>Kami tidak memastikan bahwa informasi di website ini benar. Kami tidak menjamin kelengkapan atau keakuratannya, dan kami juga tidak berjanji untuk memastikan bahwa website tetap tersedia atau materi di website selalu diperbarui.</p><p>Penolakan :</p><p>Sejauh diizinkan oleh undang-undang yang berlaku, kami mengecualikan semua representasi, jaminan, dan ketentuan yang berkaitan dengan website kami dan penggunaan website ini. Tidak ada bagian dari penolakan ini yang akan:</p><p>membatasi atau mengecualikan tanggung jawab kami atau Anda terhadap kematian atau cedera pribadi;</p><p>membatasi atau mengecualikan tanggung jawab kami atau Anda terhadap penipuan atau pemberian keterangan yang tidak benar;</p><p>membatasi tanggung jawab kami atau Anda dengan cara apa pun yang tidak diizinkan oleh undang-undang yang berlaku; atau</p><p>mengecualikan tanggung jawab kami atau Anda yang tidak dapat dikecualikan berdasarkan undang-undang yang berlaku.</p><p>Batasan dan pengecualian tanggung jawab yang diatur dalam Bagian ini dan bagian lain dalam penolakan ini: (a) tunduk pada paragraf sebelumnya; dan (b) mengatur semua kewajiban yang timbul di bawah penolakan, termasuk kewajiban yang timbul dalam kontrak, dalam gugatan, dan untuk pelanggaran kewajiban hukum.</p><p>Selama website dan informasi serta layanan di website disediakan secara gratis, kami tidak akan bertanggung jawab atas kerugian atau kerusakan apa pun.</p>',
        'image' => '',
        'video' => '',
        'created_date' => '2023-09-12',
        'last_update' => '2023-09-12',
        'user' => 'admin',
      ],
    ]);

    DB::table('prepaids')->insert([
      [
        'cuid' => 40002,
        'slug' => '1gbnasional+4gb(01.00-09.00)1hari',
        'code' => 'TDN5',
        'title' => '1GB Nasional + 4GB (01.00 - 09.00) 1 Hari',
        'kategori' => 'paket-internet',
        'brand' => 'TRI',
        'harga_modal' => 5280,
        'harga_jual' => 5386,
        'harga_reseller' => 5333,
        'image' => 'tri.png',
        'status' => 1,
        'created_date' => '2023-09-27',
        'jenis' => 4,
        'product_type' => 3,
      ],
      [
        'cuid' => 40009,
        'slug' => 'alwayson1.5gb',
        'code' => 'TDA15',
        'title' => 'AlwaysOn 1.5 GB',
        'kategori' => 'paket-internet',
        'brand' => 'TRI',
        'harga_modal' => 15605,
        'harga_jual' => 15917,
        'harga_reseller' => 15761,
        'image' => 'tri.png',
        'status' => 1,
        'created_date' => '2023-09-27',
        'jenis' => 4,
        'product_type' => 3,
      ],
      // Tambahkan data lainnya jika diperlukan
    ]);

    DB::table('products')->insert([
      [
        'cuid' => 40033,
        'slug' => 'arenaofvalor',
        'code' => 'AOV2390-S1000',
        'title' => '2390 Vouchers',
        'kategori' => 'Arena of Valor',
        'harga_modal' => 492000,
        'harga_jual' => 501840,
        'harga_reseller' => 496920,
        'image' => 'arena_of_valor.png',
        'currency' => '',
        'type' => 'Umum',
        'status' => 1,
        'created_date' => '2023-09-27',
        'jenis' => 4,
        'product_type' => 1,
      ],
      [
        'cuid' => 40031,
        'slug' => 'arenaofvalor',
        'code' => 'AOV950-S1000',
        'title' => '950 Vouchers',
        'kategori' => 'Arena of Valor',
        'harga_modal' => 196800,
        'harga_jual' => 200736,
        'harga_reseller' => 198768,
        'image' => 'arena_of_valor.png',
        'currency' => '',
        'type' => 'Umum',
        'status' => 1,
        'created_date' => '2023-09-27',
        'jenis' => 4,
        'product_type' => 1,
      ],
      [
        'cuid' => 40032,
        'slug' => 'arenaofvalor',
        'code' => 'AOV1430-S1000',
        'title' => '1430 Vouchers',
        'kategori' => 'Arena of Valor',
        'harga_modal' => 295200,
        'harga_jual' => 301104,
        'harga_reseller' => 298152,
        'image' => 'arena_of_valor.png',
        'currency' => '',
        'type' => 'Umum',
        'status' => 1,
        'created_date' => '2023-09-27',
        'jenis' => 4,
        'product_type' => 1,
      ],
      [
        'cuid' => 40030,
        'slug' => 'arenaofvalor',
        'code' => 'AOV950-S58',
        'title' => '950 Vouchers',
        'kategori' => 'Arena of Valor',
        'harga_modal' => 181096,
        'harga_jual' => 184718,
        'harga_reseller' => 182907,
        'image' => 'arena_of_valor.png',
        'currency' => '',
        'type' => 'Umum',
        'status' => 1,
        'created_date' => '2023-09-27',
        'jenis' => 4,
        'product_type' => 1,
      ],
    ]);

    DB::table('social_products')->insert([
      [
        'cuid' => 40000,
        'slug' => '2656',
        'code' => '2656',
        'title' => 'Instagram Likes [No Drop] Real [Max 5K]',
        'kategori' => 'Instagram',
        'deskripsi' => 'Speed: 200 Likes / Hour\nNo Partial Issues\nNo Drop',
        'min_buy' => 20,
        'max_buy' => 5000,
        'harga_modal' => 6052,
        'harga_jual' => 6355,
        'harga_reseller' => 6052,
        'image' => 'instagram.png',
        'status' => 1,
        'created_date' => '2023-09-27',
        'jenis' => 4,
        'product_type' => 5,
      ],
      [
        'cuid' => 40002,
        'slug' => '2873',
        'code' => '2873',
        'title' => 'Threads Artist VERIFIED Comments | 1 Comment',
        'kategori' => 'Threads',
        'deskripsi' => 'Threads Artist VERIFIED Comments | 1 Comment',
        'min_buy' => 1,
        'max_buy' => 1,
        'harga_modal' => 14159,
        'harga_jual' => 14867,
        'harga_reseller' => 14159,
        'image' => 'threads.png',
        'status' => 1,
        'created_date' => '2023-09-27',
        'jenis' => 4,
        'product_type' => 5,
      ],
      // Tambahkan data lainnya sesuai kebutuhan
    ]);

    DB::table('seo')->insert([
      'cuid' => 1,
      'image' => 'logo_master_20230709164507.png',
      'instansi' => 'VAFI STORE',
      'keyword' =>
        'Top Up Game Murah, Joki Mobile Legend dan Layanan Booster Social Media, Instant 24 Jam, Mobile Legends, Diamond Mobile Legends, Free Fire, DM FF,  Mobile, PUBGM, Genshin Impact, CODM, Valorant, Wild Rift',
      'deskripsi' =>
        'Belanja Digital Lebih Cepat, Lebih Mudah di Vafi Store! Temukan pulsa all operator, kuota internet, dan top up game dengan harga terbaik. Transaksi aman dan mudah, hanya di Vafi Store.',
      'template' => 2,
      'warna' => 4,
      'footer' => 1,
      'upgrade' => 15,
      'urlweb' => 'https://www.vafistore.com',
      'user' => 'admin',
      'date' => '2023-01-10 20:55:37',
    ]);

    DB::table('slides')->insert([
      [
        'cuid' => 19,
        'image' => 'slide_master_20230727193547.png',
        'deskripsi' => 'Slide 1',
        'sort' => 1,
        'user' => 'master',
        'status' => 1,
      ],
      [
        'cuid' => 20,
        'image' => 'slide_master_20230727193603.jpg',
        'deskripsi' => 'Slide 2',
        'sort' => 2,
        'user' => 'master',
        'status' => 1,
      ],
      [
        'cuid' => 21,
        'image' => 'slide_master_20230727193617.png',
        'deskripsi' => 'Slide 3',
        'sort' => 3,
        'user' => 'master',
        'status' => 1,
      ],
    ]);

    DB::table('socials')->insert([
      'facebook' => 'www.facebook.com',
      'twitter' => 'www.twitter.com',
      'googleplus' => '#',
      'instagram' => 'www.instagram.com',
      'linkedin' => 'www.tiktok.com',
      'youtube' => 'www.youtube.com',
      'date' => now(),
      'user' => 'admin',
    ]);

    DB::table('api')->insert([
      [
        'cuid' => 1,
        'provider' => 'Tripay',
        'api_key' => 'UuwdDwA46ewEGOrChZz55vOZyEtAhkT4yjpwnvXc',
        'private_key' => 'qoEYp-QYH53-AQBOC-K3l20-gatWG',
        'merchant_code' => 'T25429',
        'jenis' => 0,
        'status' => 1,
      ],
      [
        'cuid' => 2,
        'provider' => 'ipaymu',
        'api_key' => '',
        'private_key' => '',
        'merchant_code' => '',
        'jenis' => 0,
        'status' => 0,
      ],
      [
        'cuid' => 3,
        'provider' => 'duitku',
        'api_key' => '',
        'private_key' => '',
        'merchant_code' => '',
        'jenis' => 0,
        'status' => 0,
      ],
      [
        'cuid' => 4,
        'provider' => 'Vip Reseller',
        'api_key' => 'DNU3ANNd3HbgfhNScmBgXwQBzarMgtJFiDSJuAak3zjsyjxkUrsq4EZKk6EQLcHz',
        'private_key' => '',
        'merchant_code' => 'W30rpgmr',
        'jenis' => 1,
        'status' => 1,
      ],
      [
        'cuid' => 5,
        'provider' => 'Digiflazz',
        'api_key' => '8bef7462-a18e-57b1-a5d0-ccf7035690b6',
        'private_key' => '',
        'merchant_code' => 'xiziziDz4lwg',
        'jenis' => 1,
        'status' => 1,
      ],
      [
        'cuid' => 6,
        'provider' => 'MedanPedia',
        'api_key' => 'f55031-9d6c69-181deb-44ee66-0cf434',
        'private_key' => '',
        'merchant_code' => '20568',
        'jenis' => 1,
        'status' => 1,
      ],
      [
        'cuid' => 7,
        'provider' => 'Cekmutasi',
        'api_key' => '#',
        'private_key' => '',
        'merchant_code' => '#',
        'jenis' => 2,
        'status' => 1,
      ],
      [
        'cuid' => 8,
        'provider' => 'Watsap',
        'api_key' => 'Vte6lCkgDK7mO6TLlqWgTqj4ueS7jJ',
        'private_key' => 'wa.srv10.wapanels.com',
        'merchant_code' => '6289523090952',
        'jenis' => 2,
        'status' => 1,
      ],
      [
        'cuid' => 9,
        'provider' => 'Apigames',
        'api_key' => 'Tes',
        'private_key' => '',
        'merchant_code' => 'Tes',
        'jenis' => 1,
        'status' => 0,
      ],
    ]);
  }
}
