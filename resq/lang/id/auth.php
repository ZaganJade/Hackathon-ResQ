<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa untuk Autentikasi
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan selama proses autentikasi untuk berbagai
    | pesan yang perlu kita tampilkan kepada pengguna. Anda bebas untuk
    | memodifikasi baris bahasa sesuai dengan kebutuhan aplikasi Anda.
    |
    */

    'failed' => 'Kredensial ini tidak cocok dengan data kami.',
    'password' => 'Kata sandi yang dimasukkan salah.',
    'throttle' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam :seconds detik.',

    // Custom ResQ Auth Messages
    'login' => [
        'title' => 'Masuk ke Akun Anda',
        'email' => 'Alamat Email',
        'password' => 'Kata Sandi',
        'remember' => 'Ingat Saya',
        'forgot' => 'Lupa Kata Sandi?',
        'button' => 'Masuk',
        'no_account' => 'Belum punya akun?',
        'register' => 'Daftar sekarang',
    ],

    'register' => [
        'title' => 'Buat Akun Baru',
        'name' => 'Nama Lengkap',
        'email' => 'Alamat Email',
        'password' => 'Kata Sandi',
        'confirm_password' => 'Konfirmasi Kata Sandi',
        'button' => 'Daftar',
        'has_account' => 'Sudah punya akun?',
        'login' => 'Masuk di sini',
    ],

    'logout' => 'Keluar',
    'verify_email' => [
        'title' => 'Verifikasi Email Anda',
        'message' => 'Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengklik link yang kami kirimkan? Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan yang lain.',
        'resend' => 'Kirim Ulang Email Verifikasi',
        'sent' => 'Link verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.',
    ],

    'reset_password' => [
        'title' => 'Atur Ulang Kata Sandi',
        'email' => 'Alamat Email',
        'send_link' => 'Kirim Link Atur Ulang Kata Sandi',
        'new_password' => 'Kata Sandi Baru',
        'confirm_password' => 'Konfirmasi Kata Sandi',
        'button' => 'Atur Ulang Kata Sandi',
    ],

    'confirm_password' => [
        'title' => 'Konfirmasi Kata Sandi',
        'message' => 'Ini adalah area aman aplikasi. Silakan konfirmasi kata sandi Anda sebelum melanjutkan.',
        'button' => 'Konfirmasi',
    ],

];
