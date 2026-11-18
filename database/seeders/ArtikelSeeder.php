<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtikelSeeder extends Seeder
{
    public function run(): void
    {
        $artikels = [
            [
                'judul' => 'Pemerintah Luncurkan Program Digitalisasi Nasional 2026',
                'isi' => '<p>Pemerintah resmi meluncurkan program digitalisasi nasional yang mencakup transformasi layanan publik berbasis digital di seluruh Indonesia. Program ini bertujuan untuk mempercepat adopsi teknologi di berbagai sektor, mulai dari pendidikan, kesehatan, hingga pelayanan administrasi kependudukan.</p><p>Menteri Komunikasi dan Informatika menyatakan bahwa program ini akan menjangkau hingga 514 kabupaten/kota dengan target 100% layanan publik dapat diakses secara digital pada akhir tahun 2028. Infrastruktur internet desa juga akan diperkuat dengan pembangunan 10.000 BTS baru di daerah terpencil.</p>',
                'gambar' => 'artikel1.jpg',
                'user_id' => 1,
                'id_kategori' => 1,
                'created_at' => '2026-07-28 10:00:00',
                'updated_at' => '2026-07-28 10:00:00',
            ],
            [
                'judul' => 'Inovasi AI Terbaru: Chatbot Multibahasa untuk UMKM',
                'isi' => '<p>Sebuah startup teknologi asal Indonesia meluncurkan platform chatbot berbasis kecerdasan buatan yang mampu melayani pelanggan dalam 10 bahasa daerah sekaligus. Inovasi ini diharapkan dapat membantu pelaku UMKM meningkatkan layanan pelanggan tanpa harus merekrut tim customer service yang besar.</p><p>Platform ini menggunakan model bahasa terbaru yang telah dilatih dengan dataset bahasa Indonesia dan berbagai bahasa daerah seperti Jawa, Sunda, Batak, dan Minang. Uji coba menunjukkan peningkatan kepuasan pelanggan hingga 40% pada UMKM yang menggunakan platform ini.</p>',
                'gambar' => 'artikel2.jpg',
                'user_id' => 1,
                'id_kategori' => 2,
                'created_at' => '2026-07-27 14:30:00',
                'updated_at' => '2026-07-27 14:30:00',
            ],
            [
                'judul' => 'Timnas Indonesia Melaju ke Semi Final Piala Asia',
                'isi' => '<p>Tim Nasional Indonesia berhasil melaju ke babak semi final Piala Asia setelah mengalahkan lawan dengan skor telak 3-0. Kemenangan ini disambut euforia oleh seluruh masyarakat Indonesia yang selama ini menantikan prestasi gemilang di kancah sepak bola Asia.</p><p>Pelatih timnas memuji kerja keras para pemain dan strategi yang diterapkan berjalan sesuai rencana. Gol-gol dicetak di babak pertama melalui skema serangan balik cepat yang menjadi andalan timnas turnamen ini.</p>',
                'gambar' => 'artikel3.jpg',
                'user_id' => 1,
                'id_kategori' => 3,
                'created_at' => '2026-07-26 09:15:00',
                'updated_at' => '2026-07-26 09:15:00',
            ],
            [
                'judul' => 'Tips Hidup Sehat di Era Digital: Jaga Keseimbangan Waktu Layar',
                'isi' => '<p>Penggunaan gadget yang berlebihan dapat berdampak negatif pada kesehatan fisik dan mental. Dokter spesialis kesehatan masyarakat merekomendasikan beberapa tips untuk menjaga keseimbangan waktu layar agar tetap sehat di era digital ini.</p><p>Beberapa tips yang disarankan antara lain menerapkan aturan 20-20-20 (setiap 20 menit, lihat objek sejauh 20 kaki selama 20 detik), membatasi waktu layar maksimal 2 jam di luar pekerjaan, dan rutin melakukan peregangan setiap 1 jam sekali. Istirahat yang cukup dan olahraga teratur juga tidak kalah penting.</p>',
                'gambar' => 'artikel4.jpg',
                'user_id' => 1,
                'id_kategori' => 4,
                'created_at' => '2026-07-25 16:45:00',
                'updated_at' => '2026-07-25 16:45:00',
            ],
            [
                'judul' => 'Kurikulum Merdeka Resmi Diterapkan di Seluruh Sekolah',
                'isi' => '<p>Kementerian Pendidikan mengumumkan penerapan Kurikulum Merdeka secara penuh di seluruh jenjang pendidikan dasar dan menengah mulai tahun ajaran baru 2026/2027. Kurikulum ini memberikan fleksibilitas lebih bagi sekolah dan guru dalam menentukan metode pembelajaran yang sesuai dengan kebutuhan siswa.</p><p>Fokus utama kurikulum ini adalah pengembangan karakter dan kompetensi siswa melalui pembelajaran berbasis proyek. Guru akan berperan sebagai fasilitator, bukan lagi satu-satunya sumber belajar. Sekolah diberikan kebebasan untuk mengadaptasi kurikulum sesuai dengan kondisi dan potensi daerah masing-masing.</p>',
                'gambar' => 'artikel5.jpg',
                'user_id' => 1,
                'id_kategori' => 5,
                'created_at' => '2026-07-24 11:20:00',
                'updated_at' => '2026-07-24 11:20:00',
            ],
            [
                'judul' => 'Inflasi Terkendali: BI Pertahankan Suku Bunga Acuan',
                'isi' => '<p>Bank Indonesia memutuskan untuk mempertahankan suku bunga acuan di level 5,75% seiring dengan terkendalinya laju inflasi nasional. Keputusan ini diambil dalam Rapat Dewan Gubernur bulanan yang mengevaluasi kondisi ekonomi terkini.</p><p>Gubernur BI menyatakan bahwa inflasi inti tetap terjaga dalam kisaran sasaran 2-4%. Pertumbuhan ekonomi diproyeksikan tetap solid di kisaran 5-5,5% didukung oleh konsumsi domestik yang kuat dan investasi yang terus mengalir. Nilai tukar rupiah juga menunjukkan stabilitas yang baik terhadap dolar AS.</p>',
                'gambar' => 'artikel6.jpg',
                'user_id' => 1,
                'id_kategori' => 6,
                'created_at' => '2026-07-23 08:00:00',
                'updated_at' => '2026-07-23 08:00:00',
            ],
        ];

        foreach ($artikels as $a) {
            DB::table('artikel')->insert($a);
        }
    }
}
