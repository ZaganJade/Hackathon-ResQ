<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa untuk Aplikasi ResQ
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan di seluruh aplikasi ResQ untuk berbagai
    | pesan dan label yang ditampilkan kepada pengguna.
    |
    */

    // General
    'app_name' => 'ResQ',
    'tagline' => 'Sistem Mitigasi Bencana',
    'dashboard' => 'Dasbor',
    'home' => 'Beranda',
    'profile' => 'Profil',
    'settings' => 'Pengaturan',
    'logout' => 'Keluar',
    'login' => 'Masuk',
    'register' => 'Daftar',
    'save' => 'Simpan',
    'cancel' => 'Batal',
    'delete' => 'Hapus',
    'edit' => 'Ubah',
    'create' => 'Buat',
    'back' => 'Kembali',
    'search' => 'Cari',
    'filter' => 'Filter',
    'reset' => 'Atur Ulang',
    'loading' => 'Memuat...',
    'no_data' => 'Tidak ada data',
    'confirm' => 'Konfirmasi',
    'close' => 'Tutup',
    'success' => 'Berhasil',
    'error' => 'Error',
    'warning' => 'Peringatan',
    'info' => 'Informasi',

    // Navigation
    'nav' => [
        'dashboard' => 'Dasbor',
        'ai_assist' => 'AI Assist',
        'chat_history' => 'Riwayat Chat',
        'disaster_map' => 'Peta Bencana',
        'articles' => 'Artikel',
        'guides' => 'Panduan',
        'notifications' => 'Notifikasi',
        'admin' => 'Admin',
    ],

    // AI Assist
    'ai_assist' => [
        'title' => 'AI Assist',
        'subtitle' => 'Asisten Cerdas untuk Mitigasi Bencana',
        'placeholder' => 'Tanyakan tentang bencana, mitigasi, atau tips darurat...',
        'send' => 'Kirim',
        'typing' => 'AI sedang mengetik...',
        'welcome_title' => 'Selamat Datang di AI Assist',
        'welcome_message' => 'Saya adalah asisten AI yang siap membantu Anda dengan informasi tentang bencana alam, panduan mitigasi, dan tips keselamatan. Silakan ajukan pertanyaan Anda.',
        'suggestions' => 'Saran Pertanyaan:',
        'suggestion_1' => 'Apa yang harus dilakukan saat gempa bumi?',
        'suggestion_2' => 'Bagaimana cara membuat tas darurat?',
        'suggestion_3' => 'Apa artinya peringatan tsunami?',
        'suggestion_4' => 'Bagaimana cara mitigasi banjir?',
        'clear_chat' => 'Bersihkan Percakapan',
        'export_chat' => 'Ekspor Percakapan',
        'character_count' => ':count karakter',
    ],

    // Chat History
    'chat_history' => [
        'title' => 'Riwayat Chat',
        'subtitle' => 'Daftar percakapan dengan AI Assist',
        'no_history' => 'Belum ada riwayat chat',
        'no_history_desc' => 'Mulai chat dengan AI Assist untuk melihat riwayat di sini',
        'search_placeholder' => 'Cari riwayat chat...',
        'filter_by_date' => 'Filter berdasarkan tanggal',
        'today' => 'Hari Ini',
        'yesterday' => 'Kemarin',
        'this_week' => 'Minggu Ini',
        'this_month' => 'Bulan Ini',
        'older' => 'Lebih Lama',
        'total_conversations' => 'Total Percakapan',
        'total_messages' => 'Total Pesan',
        'avg_duration' => 'Durasi Rata-rata',
        'view_details' => 'Lihat Detail',
        'delete_confirm' => 'Apakah Anda yakin ingin menghapus riwayat chat ini?',
        'export' => 'Ekspor',
        'export_json' => 'Ekspor sebagai JSON',
        'export_text' => 'Ekspor sebagai Teks',
    ],

    // Disaster
    'disaster' => [
        'title' => 'Bencana',
        'map_title' => 'Peta Bencana',
        'types' => [
            'earthquake' => 'Gempa Bumi',
            'flood' => 'Banjir',
            'tsunami' => 'Tsunami',
            'landslide' => 'Tanah Longsor',
            'volcanic' => 'Erupsi Gunung Berapi',
            'fire' => 'Kebakaran',
            'drought' => 'Kekeringan',
            'other' => 'Lainnya',
        ],
        'levels' => [
            'low' => 'Rendah (Aman)',
            'moderate' => 'Sedang (Waspada)',
            'high' => 'Tinggi (Bahaya)',
            'critical' => 'Kritis (Darurat)',
        ],
        'status' => [
            'active' => 'Aktif',
            'monitoring' => 'Dipantau',
            'resolved' => 'Teratasi',
        ],
    ],

    // Articles
    'article' => [
        'title' => 'Artikel',
        'latest' => 'Artikel Terbaru',
        'read_more' => 'Baca Selengkapnya',
        'published_at' => 'Dipublikasikan :date',
        'by_author' => 'Oleh :author',
        'category' => 'Kategori',
        'tags' => 'Tag',
        'related_articles' => 'Artikel Terkait',
    ],

    // Guides
    'guide' => [
        'title' => 'Panduan',
        'categories' => 'Kategori Panduan',
        'steps' => 'Langkah-langkah',
        'materials' => 'Perlengkapan yang Diperlukan',
        'tips' => 'Tips Penting',
        'download_pdf' => 'Unduh PDF',
        'share' => 'Bagikan',
    ],

    // Notifications
    'notification' => [
        'title' => 'Notifikasi',
        'settings' => 'Pengaturan Notifikasi',
        'email_notifications' => 'Notifikasi Email',
        'whatsapp_notifications' => 'Notifikasi WhatsApp',
        'push_notifications' => 'Notifikasi Push',
        'disaster_alerts' => 'Peringatan Bencana',
        'alert_levels' => 'Level Peringatan',
        'no_notifications' => 'Tidak ada notifikasi',
        'mark_all_read' => 'Tandai Semua Dibaca',
        'mark_as_read' => 'Tandai Dibaca',
    ],

    // Profile
    'profile' => [
        'title' => 'Profil Pengguna',
        'information' => 'Informasi Profil',
        'update_info' => 'Perbarui informasi profil dan alamat email akun Anda.',
        'update_password' => 'Perbarui Kata Sandi',
        'password_desc' => 'Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.',
        'current_password' => 'Kata Sandi Saat Ini',
        'new_password' => 'Kata Sandi Baru',
        'confirm_password' => 'Konfirmasi Kata Sandi',
        'delete_account' => 'Hapus Akun',
        'delete_desc' => 'Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.',
        'delete_confirm' => 'Apakah Anda yakin ingin menghapus akun Anda?',
        'delete_warning' => 'Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun secara permanen.',
        'saved' => 'Tersimpan.',
        'save_profile' => 'Simpan Profil',
        'saved_success' => 'Profil berhasil diperbarui.',
    ],

    // Footer
    'footer' => [
        'about' => 'Tentang Kami',
        'features' => 'Fitur',
        'help' => 'Bantuan',
        'contact' => 'Hubungi Kami',
        'privacy' => 'Kebijakan Privasi',
        'terms' => 'Syarat & Ketentuan',
        'copyright' => 'Hak Cipta Dilindungi.',
    ],

    // Errors
    'error' => [
        '404_title' => 'Halaman Tidak Ditemukan',
        '404_message' => 'Maaf, halaman yang Anda cari tidak dapat ditemukan.',
        '500_title' => 'Kesalahan Server',
        '500_message' => 'Maaf, terjadi kesalahan pada server kami.',
        '503_title' => 'Pemeliharaan Sistem',
        '503_message' => 'ResQ sedang dalam pemeliharaan. Silakan kembali lagi nanti.',
        'go_home' => 'Kembali ke Beranda',
        'go_back' => 'Kembali',
    ],

];
