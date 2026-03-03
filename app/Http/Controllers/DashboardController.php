<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DonationCampaign;
use App\Models\UmkmProduct;
use App\Models\Umkm;
use App\Models\Bisnis;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah anggota (role anggota dan publik)
        $jumlahAnggota = User::whereIn('role', ['anggota', 'publik'])->count();

        // Hitung total iuran yang sudah dikonfirm (payment_status = success)
        $totalIuran = \App\Models\MembershipFee::where('payment_status', 'success')->sum('amount');

        // Hitung jumlah produk UMKM
        $jumlahProduk = UmkmProduct::count();

        // Hitung jumlah bisnis (Karya dan Bisnis)
        $jumlahBisnis = Bisnis::count();

        // Ambil 10 anggota terbaru
        $anggotaTerbaru = User::whereIn('role', ['anggota', 'publik'])
            ->latest()
            ->take(10)
            ->get();

        // Ambil 10 transaksi iuran terbaru
        $transaksiTerbaru = \App\Models\MembershipFee::with(['user', 'validator'])
            ->latest()
            ->take(10)
            ->get();

        return view('pages.dashboard', compact(
            'jumlahAnggota',
            'totalIuran',
            'jumlahProduk',
            'jumlahBisnis',
            'anggotaTerbaru',
            'transaksiTerbaru'
        ));
    }
}
