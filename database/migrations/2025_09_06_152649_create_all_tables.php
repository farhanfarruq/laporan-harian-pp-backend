<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Bidang
        Schema::create('bidangs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('icon');
            $table->string('color');
            $table->timestamps();
        });

        // Tabel Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['pengurus', 'admin_utama', 'admin_bidang']);
            $table->foreignId('bidang_id')->nullable()->constrained('bidangs')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabel Pengurus
        Schema::create('pengurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->constrained('bidangs')->onDelete('cascade');
            $table->string('nama');
            $table->string('kelas')->nullable(); // Untuk Bapak Kamar
            $table->timestamps();
        });
        
        // Tabel Jobdesk
        Schema::create('jobdesks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->constrained('bidangs')->onDelete('cascade');
            $table->text('description');
            $table->enum('type', ['malam', 'subuh'])->nullable(); // Untuk Bapak Kamar
            $table->timestamps();
        });

        // Tabel Laporan
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('Submitted by')->constrained('users')->onDelete('cascade');
            $table->foreignId('pengurus_id')->constrained('pengurus')->onDelete('cascade');
            $table->foreignId('bidang_id')->constrained('bidangs')->onDelete('cascade');
            $table->date('tanggal');
            $table->json('details'); // Menyimpan status jobdesk, alasan, solusi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
        Schema::dropIfExists('jobdesks');
        Schema::dropIfExists('pengurus');
        Schema::dropIfExists('users');
        Schema::dropIfExists('bidangs');
    }
};