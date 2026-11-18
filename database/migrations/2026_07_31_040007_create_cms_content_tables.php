<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsContentTables extends Migration
{
    public function up()
    {
        // Hero sliders / banners
        Schema::create('sliders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('judul');
            $table->string('subjudul')->nullable();
            $table->string('gambar')->nullable();
            $table->string('tombol_label')->nullable();
            $table->string('tombol_url')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Vision & Mission (single row)
        Schema::create('visi_misi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('visi');
            $table->text('misi')->nullable();
            $table->timestamps();
        });

        // Departments (Bidang)
        Schema::create('bidang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('singkatan');
            $table->text('deskripsi');
            $table->text('poin')->nullable();
            $table->string('ikon')->nullable();
            $table->string('gambar')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // History (single row)
        Schema::create('sejarah', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('isi');
            $table->timestamps();
        });

        // Organizational structure (single row)
        Schema::create('struktur', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('judul')->nullable();
            $table->string('gambar')->nullable();
            $table->longText('isi')->nullable();
            $table->timestamps();
        });

        // Leadership
        Schema::create('pimpinan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('jabatan');
            $table->text('bio')->nullable();
            $table->string('foto')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Employees
        Schema::create('pegawai', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('bagian')->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Announcements
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('judul');
            $table->longText('isi');
            $table->date('tanggal')->nullable();
            $table->string('status', 20)->default('published');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Research & publications
        Schema::create('penelitian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('judul');
            $table->string('jenis')->nullable();
            $table->string('tahun')->nullable();
            $table->text('ringkasan')->nullable();
            $table->string('file')->nullable();
            $table->string('url')->nullable();
            $table->string('gambar')->nullable();
            $table->string('status', 20)->default('published');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Development programs
        Schema::create('program', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('tahun')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('persen')->default(0);
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Gallery
        Schema::create('galeri', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('judul')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('gambar');
            $table->string('album')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Documents / downloads
        Schema::create('dokumen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('judul');
            $table->string('kategori')->nullable();
            $table->string('file');
            $table->string('ekstensi')->nullable();
            $table->unsignedBigInteger('ukuran')->default(0);
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Statistics
        Schema::create('statistik', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->unsignedBigInteger('nilai')->default(0);
            $table->string('ikon')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // FAQ
        Schema::create('faq', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pertanyaan');
            $table->text('jawaban');
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Partners
        Schema::create('partner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('logo')->nullable();
            $table->string('url')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Running text / ticker
        Schema::create('ticker', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('teks');
            $table->string('url')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sliders');
        Schema::dropIfExists('visi_misi');
        Schema::dropIfExists('bidang');
        Schema::dropIfExists('sejarah');
        Schema::dropIfExists('struktur');
        Schema::dropIfExists('pimpinan');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('penelitian');
        Schema::dropIfExists('program');
        Schema::dropIfExists('galeri');
        Schema::dropIfExists('dokumen');
        Schema::dropIfExists('statistik');
        Schema::dropIfExists('faq');
        Schema::dropIfExists('partner');
        Schema::dropIfExists('ticker');
    }
}
