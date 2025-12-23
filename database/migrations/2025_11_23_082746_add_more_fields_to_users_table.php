<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('gender')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('gender');
            $table->text('address')->nullable()->after('birth_date');
            $table->string('city')->nullable()->after('address');
            $table->string('occupation')->nullable()->after('city');
            $table->date('join_date')->nullable()->after('occupation');
            $table->string('profile_photo')->nullable()->after('join_date');
            $table->string('status')->default('aktif')->after('profile_photo');
            $table->string('kta_id')->nullable()->after('status');
            $table->enum('role', ['superadmin','admin','pengurus','anggota','bendahara','publik'])
                ->default('anggota')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone','gender','birth_date','address','city',
                'occupation','join_date','profile_photo','status','role'
            ]);
        });
    }
};
