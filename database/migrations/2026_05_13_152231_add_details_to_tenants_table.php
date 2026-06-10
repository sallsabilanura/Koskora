<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('nama_lengkap')->nullable()->after('user_id');
            $table->string('nama_panggilan')->nullable()->after('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('nik');
            $table->string('tempat_lahir')->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('nomor_whatsapp')->nullable()->after('tanggal_lahir');
            $table->text('alamat_ktp')->nullable()->after('nomor_whatsapp');
            $table->string('rt', 5)->nullable()->after('address');
            $table->string('rw', 5)->nullable()->after('rt');
            $table->string('province')->nullable()->after('rw');
            $table->string('city')->nullable()->after('province');
            $table->string('district')->nullable()->after('city');
            $table->string('village')->nullable()->after('district');
            $table->string('foto_ktp')->nullable()->after('village');
            $table->string('foto_diri')->nullable()->after('foto_ktp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'nama_lengkap',
                'nama_panggilan',
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'nomor_whatsapp',
                'alamat_ktp',
                'rt',
                'rw',
                'province',
                'city',
                'district',
                'village',
                'foto_ktp',
                'foto_diri'
            ]);
        });
    }
};
