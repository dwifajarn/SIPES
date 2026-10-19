<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Peserta;
use App\Services\MessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $statistik = [
            'total' => Peserta::count(),
            'aktif' => Peserta::where('status', 'aktif')->count(),
            'nonaktif' => Peserta::where('status', 'nonaktif')->count(),
            'errorHariIni' => Message::where('type', Message::TYPE_ERROR)
                ->whereDate('created_at', today())
                ->count(),
        ];

        $pesanTerbaru = Message::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $pesertaTerbaru = Peserta::latest()->limit(5)->get();

        return view('dashboard', [
            'statistik' => $statistik,
            'pesanTerbaru' => $pesanTerbaru,
            'pesertaTerbaru' => $pesertaTerbaru,
            'jumlahBelumDibaca' => MessageService::jumlahBelumDibaca(),
        ]);
    }
}
