<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePesertaRequest;
use App\Http\Requests\UpdatePesertaRequest;
use App\Models\Message;
use App\Models\Peserta;
use App\Services\MessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PesertaController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Peserta::class);

        $peserta = Peserta::query()
            ->cari($request->string('cari')->toString())
            ->status($request->string('status')->toString())
            ->urutkan($request->string('urut')->toString(), $request->string('arah')->toString())
            ->paginate(10)
            ->withQueryString();

        return view('peserta.index', [
            'daftarPeserta' => $peserta,
            'filter' => [
                'cari' => $request->string('cari')->toString(),
                'status' => $request->string('status')->toString(),
                'urut' => $request->string('urut')->toString(),
                'arah' => $request->string('arah')->toString(),
            ],
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Peserta::class);

        return view('peserta.create');
    }

    public function store(StorePesertaRequest $request): RedirectResponse
    {
        $peserta = Peserta::create($request->validated());

        MessageService::notify(
            Message::TYPE_SUCCESS,
            'Data peserta berhasil disimpan',
            "Peserta \"{$peserta->nama_lengkap}\" telah ditambahkan ke sistem.",
            code: 201,
        );

        return redirect()->route('peserta.index');
    }

    public function show(Peserta $peserta): View
    {
        Gate::authorize('view', $peserta);

        return view('peserta.show', ['peserta' => $peserta]);
    }

    public function edit(Peserta $peserta): View
    {
        Gate::authorize('update', $peserta);

        return view('peserta.edit', ['peserta' => $peserta]);
    }

    public function update(UpdatePesertaRequest $request, Peserta $peserta): RedirectResponse
    {
        $peserta->update($request->validated());

        MessageService::notify(
            Message::TYPE_SUCCESS,
            'Data peserta berhasil diperbarui',
            "Perubahan pada \"{$peserta->nama_lengkap}\" telah disimpan.",
        );

        return redirect()->route('peserta.index');
    }

    public function destroy(Peserta $peserta): RedirectResponse
    {
        Gate::authorize('delete', $peserta);

        $nama = $peserta->nama_lengkap;
        $peserta->delete();

        MessageService::notify(
            Message::TYPE_WARNING,
            'Data peserta dihapus',
            "Peserta \"{$nama}\" dipindahkan ke daftar terhapus dan masih dapat dipulihkan.",
        );

        return redirect()->route('peserta.index');
    }

    public function restore(int $id): RedirectResponse
    {
        Gate::authorize('restore', Peserta::class);

        $peserta = Peserta::onlyTrashed()->findOrFail($id);
        $peserta->restore();

        MessageService::notify(
            Message::TYPE_SUCCESS,
            'Data peserta dipulihkan',
            "Peserta \"{$peserta->nama_lengkap}\" telah dikembalikan ke daftar aktif.",
        );

        return redirect()->route('peserta.index');
    }

    public function terhapus(): View
    {
        Gate::authorize('restore', Peserta::class);

        return view('peserta.terhapus', [
            'daftarPeserta' => Peserta::onlyTrashed()->latest('deleted_at')->paginate(10),
        ]);
    }
}
