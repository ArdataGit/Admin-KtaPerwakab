<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanExpiredPasswordResetTokens extends Command
{
    protected $signature = 'password-reset:clean';
    protected $description = 'Hapus token reset password yang sudah expired (lebih dari 60 menit)';

    public function handle()
    {
        $deleted = DB::table('password_reset_tokens')
            ->where('created_at', '<', Carbon::now()->subMinutes(60))
            ->delete();

        $this->info("Berhasil menghapus {$deleted} token yang sudah expired.");
        
        return 0;
    }
}
