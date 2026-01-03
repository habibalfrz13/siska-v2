<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;

class PesertaController extends Controller
{
    // Menampilkan semua data peserta
    public function index()
    {
        $pesertas = Peserta::all();
        return view('dashboard.peserta.index', ['pesertas' => $pesertas]);
    }

    // Menampilkan halaman tambah peserta
    public function create()
    {
        return view('dashboard.peserta.create');
    }

    // Menyimpan data peserta baru
    public function store(Request $request)
    {
        // Validasi data yang dikirim dari form
        $request->validate([
            'user_id' => 'required',
            'kelas_id' => 'required',
            'nama_peserta' => 'required',
            'judul' => 'required',
            // Tambahkan validasi sesuai kebutuhan
        ]);

        // Simpan data peserta baru ke database
        Peserta::create([
            'user_id' => $request->user_id,
            'kelas_id' => $request->kelas_id,
            'nama_peserta' => $request->nama_peserta,
            'judul' => $request->judul,
        ]);

        // Redirect ke halaman index peserta dengan pesan sukses
        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil ditambahkan.');
    }

    // Menampilkan detail peserta
    public function show($id)
    {
        $peserta = Peserta::find($id);
        return view('dashboard.peserta.show', ['peserta' => $peserta]);
    }

    // Menampilkan halaman edit peserta
    public function edit($id)
    {
        $peserta = Peserta::find($id);
        return view('dashboard.peserta.edit', ['peserta' => $peserta]);
    }

    // Menyimpan perubahan data peserta
    public function update(Request $request, $id)
    {
        // Temukan peserta berdasarkan ID
        $peserta = Peserta::findOrFail($id);

        // Validasi data yang dikirim dari form
        $request->validate([
            'user_id' => 'required',
            'kelas_id' => 'required',
            'nama_peserta' => 'required',
            'judul' => 'required',
            // Tambahkan validasi sesuai kebutuhan
        ]);

        // Update data peserta
        $peserta->update([
            'user_id' => $request->user_id,
            'kelas_id' => $request->kelas_id,
            'nama_peserta' => $request->nama_peserta,
            'judul' => $request->judul,
        ]);

        // Redirect ke halaman index peserta dengan pesan sukses
        return redirect()->route('peserta.index')->with('success', 'Data peserta berhasil diperbarui.');
    }

    // Menghapus data peserta
    public function destroy($id)
    {
        // Temukan peserta berdasarkan ID dan hapus
        $peserta = Peserta::find($id);
        $peserta->delete();

        // Redirect ke halaman index peserta dengan pesan sukses
        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil dihapus.');
    }

    public function cetak(){
        $myclass = Peserta::all();
        return view('dashboard.peserta.cetak',[
            'myclass' => $myclass,
        ]);
    }
}
