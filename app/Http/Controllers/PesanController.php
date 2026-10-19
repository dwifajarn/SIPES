<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PesanController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Message::class);

        $pesan = Message::with('user')
            ->tipe($request->string('tipe')->toString())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pesan.index', [
            'daftarPesan' => $pesan,
            'filterTipe' => $request->string('tipe')->toString(),
            'jumlahBelumDibaca' => MessageService::jumlahBelumDibaca(),
        ]);
    }

    public function tandaiDibaca(Message $pesan): RedirectResponse
    {
        Gate::authorize('update', $pesan);

        $pesan->update(['is_read' => true]);

        return back()->with('status', 'Pesan ditandai sudah dibaca.');
    }

    public function tandaiSemuaDibaca(): RedirectResponse
    {
        Gate::authorize('viewAny', Message::class);

        Message::belumDibaca()->update(['is_read' => true]);

        return back()->with('status', 'Semua pesan ditandai sudah dibaca.');
    }

    public function destroy(Message $pesan): RedirectResponse
    {
        Gate::authorize('delete', $pesan);

        $pesan->delete();

        return back()->with('status', 'Pesan berhasil dihapus.');
    }
}
