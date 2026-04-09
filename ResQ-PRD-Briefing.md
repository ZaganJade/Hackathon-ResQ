Product Requirements Document resQ


Section 1: Product Overview 

Latar Belakang 
Indonesia merupakan negara yang berada di kawasan Ring of Fire (Cincin Api Pasifik), yang menjadikannya sangat rentan terhadap berbagai jenis bencana alam seperti gempa bumi, tsunami, letusan gunung berapi, banjir, dan angin topan. Frekuensi dan intensitas bencana yang tinggi ini menyebabkan risiko besar terhadap keselamatan masyarakat.
Namun, salah satu faktor utama yang memperparah dampak bencana bukan hanya faktor alam itu sendiri, melainkan kurangnya kesiapsiagaan masyarakat. Hal ini disebabkan oleh keterbatasan akses terhadap informasi yang cepat, akurat, dan terpusat, serta minimnya pemahaman tentang langkah mitigasi yang tepat sebelum, saat, dan setelah bencana terjadi.
Saat ini, informasi terkait bencana sering tersebar di berbagai platform yang tidak terintegrasi, sehingga menyulitkan masyarakat untuk mendapatkan data yang terpercaya secara real-time. Selain itu, kurangnya edukasi yang mudah dipahami—terutama bagi pelajar—menjadi tantangan dalam membangun budaya sadar bencana sejak dini.
Oleh karena itu, diperlukan sebuah solusi digital yang mampu mengintegrasikan informasi bencana secara terpusat, memberikan edukasi mitigasi yang mudah diakses, serta menghadirkan sistem notifikasi yang cepat dan responsif untuk meningkatkan kesiapsiagaan masyarakat.

Definisi
ResQ adalah platform digital terpadu yang berfokus pada edukasi dan mitigasi bencana (sebelum, saat, dan setelah). Aplikasi ini berfungsi untuk meningkatkan kesiapsiagaan dan respons masyarakat terhadap bencana melalui penyediaan informasi yang akurat, cepat, dan terpusat, dilengkapi dengan fitur notifikasi dini dan AI assist.


Tujuan
Aplikasi ResQ dikembangkan sebagai platform terpadu yang bertujuan untuk meningkatkan kesiapsiagaan dan respons masyarakat terhadap bencana melalui penyediaan informasi yang akurat, cepat, dan mudah diakses.
Adapun tujuan utama dari aplikasi ini adalah:
Menyediakan informasi bencana secara real-time dan terpusat
 Mengintegrasikan data dari berbagai sumber terpercaya agar pengguna dapat memperoleh informasi terkini dalam satu platform.
Meningkatkan kesiapsiagaan masyarakat
 Memberikan panduan mitigasi bencana yang praktis dan mudah dipahami untuk membantu pengguna mengambil tindakan yang tepat.
Mempermudah akses edukasi kebencanaan
 Menyediakan konten edukatif yang interaktif agar generasi muda lebih sadar dan siap menghadapi bencana.
Memberikan notifikasi dini terhadap potensi bencana
Menghadirkan sistem peringatan cepat berbasis API yang dapat membantu pengguna merespons lebih awal.
Memanfaatkan teknologi AI sebagai asisten informasi
 Menyediakan AI agent yang dapat menjawab pertanyaan pengguna terkait kondisi bencana, mitigasi, dan tindakan darurat.
Menyediakan visualisasi lokasi bencana berdasarkan data yang tersedia 
Menampilkan lokasi kejadian bencana melalui peta digital berdasarkan data yang diinput dan dikelola dalam sistem, serta dirancang untuk mendukung integrasi dengan sumber data eksternal di masa depan.



Section 2: User Persona



Section 3: Fitur
Fitur Utama
Dashboard user, halaman utama menampilkan ringkasan info pengguna dan navigasi cepat ke fitur mitigasi, berita, AI assist, notifikasi, serta peta bencana.
Informasi mitigasi bencana, koleksi konten edukasi step-by-step dari database lokal, dikategorikan per jenis bencana (gempa, tsunami, banjir, gunung api), dilengkapi ilustrasi visual.
Berita dan artikel, daftar berita bencana terkini dari data dummy database dengan filter kategori dan simulasi integrasi API eksternal (timestamp update)
Profil, halaman kelola data pengguna (nama, email, lokasi), pengaturan preferensi, melihat riwayat chat AI Assist, dan logout.
Fitur Inovasi
AI assist, chatbot integrasi Fireworks AI (accounts/fireworks/routers/claude-kimi) untuk jawab pertanyaan mitigasi bencana. System prompt dibatasi domain bencana Indonesia, response <3 detik, riwayat tersimpan di database.
Peta lokasi bencana, peta interaktif Google Maps menampilkan marker bencana dari data dummy (seeded database). Popup marker menunjukkan jenis bencana, lokasi, dan severity. Center map default Indonesia.
Notifikasi bencana, push notifikasi ke WhatsApp pengguna via WhatsApp Web API ketika terdeteksi bencana severity tinggi dari data dummy. Pesan berisi detail bencana, lokasi, waktu, dan link mitigasi.


Section 4: User Flow


Section 5: Functional Requirement
FR-003: Berita dan Artikel
Sistem menampilkan daftar berita dari data dummy yang disimpan di database
Setiap berita menampilkan judul, sumber portal berita, tanggal publikasi, dan ringkasan
Sistem mensimulasikan integrasi API eksternal dengan menampilkan timestamp "update terakhir"
User dapat melihat detail berita lengkap dengan gambar placeholder
Sistem menyediakan filter berdasarkan kategori bencana (dummy filter untuk demo)

FR-004: Profil Pengguna
Sistem menampilkan informasi dasar pengguna: nama, email, dan lokasi yang tersimpan di database
User dapat melihat riwayat percakapan dengan AI Assist (chat logs dari database)
User dapat mengupdate informasi profil (nama dan lokasi)
Sistem menyediakan tombol logout yang mengakhiri sesi autentikasi
Sistem menampilkan preferensi notifikasi yang telah diatur pengguna

FR-005: AI Assist (Fitur Inovasi - Fireworks API)
User dapat mengakses interface chat untuk berinteraksi dengan asisten virtual
User mengirimkan pertanyaan teks terkait mitigasi bencana via form input
Sistem mengirimkan request ke Fireworks AI API (model accounts/fireworks/routers/claude-kimi) menggunakan Laravel HTTP Client
System prompt dibatasi pada domain bencana Indonesia agar jawaban tetap relevan dan akurat
Sistem menampilkan respons dari AI dalam waktu kurang dari 3 detik dengan indikator loading
Sistem menyimpan riwayat percakapan (user message dan AI response) ke database untuk referensi kembali

FR-006: Peta Lokasi Bencana (Fitur Inovasi - Google Maps API + Dummy Data)
Sistem mengintegrasikan Google Maps JavaScript API untuk menampilkan peta interaktif
Sistem menampilkan data bencana dari database (dummy data yang telah di-seed) sebagai marker pada peta
Setiap marker menampilkan informasi popup berisi: jenis bencana, lokasi, koordinat, dan severity (dummy)
Sistem mensimulasikan real-time update dengan menampilkan timestamp "data terakhir diperbarui"
User dapat mengklik marker untuk melihat detail singkat dan navigasi ke konten mitigasi terkait
Peta diatur dengan center default pada wilayah Indonesia (koordinat: -2.548926, 118.014863)

FR-007: Notifikasi Bencana (Fitur Inovasi - WhatsApp Web API)
User dapat mengaktifkan notifikasi dengan menghubungkan nomor WhatsApp mereka (input manual nomor & chat_id simulation)
Sistem memonitor data dummy bencana dengan severity "tinggi" atau "kritis"
Ketika terdeteksi bencana dengan severity tinggi, sistem trigger pengiriman notifikasi
Sistem mengirimkan HTTP POST request ke WhatsApp Web API endpoint dengan payload berisi: jenis bencana, lokasi, waktu kejadian, dan link detail
Pesan notifikasi diformat dengan emoji dan bahasa Indonesia untuk readability
User menerima notifikasi push melalui aplikasi WhatsApp pada perangkat mobile mereka
Sistem mencatat status pengiriman notifikasi (terkirim/gagal) ke database untuk tracking.
Section 6: Database



Section 7: Constraint & Dependencies
1. Dependencies (Ketergantungan Sistem)
Data Bencana: menggunakan data yg ada pada database untuk API lembaga resmi (seperti BMKG) menjadi Opsional sampai ada Official API.
Pemetaan: Mengandalkan SDK seperti Google Maps atau Mapbox.
Notifikasi: Membutuhkan Firebase (FCM) atau APNs untuk mengirim real-time alert.
Fitur AI (Q-Assist): Bergantung pada layanan API LLM pihak ketiga.
Infrastruktur: Membutuhkan cloud server yang tangguh untuk backend dan database.
2. Constraints (Batasan & Tantangan Teknis)
Lonjakan Trafik (Traffic Spikes): Saat bencana terjadi, ribuan user akan mengakses aplikasi bersamaan. Server rawan crash jika tidak diatur dengan baik.
Biaya & Limit API: Lonjakan penggunaan saat krisis bisa membuat biaya API Peta dan AI membengkak atau terkena limit.
Privasi Data: Wajib mengenkripsi dan melindungi data sensitif seperti lokasi user agar mematuhi aturan privasi (seperti UU PDP).
