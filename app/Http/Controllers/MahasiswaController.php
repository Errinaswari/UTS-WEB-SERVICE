<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function index()
    {
        // mengurutkan datanya berdasarkan terbaru dengan method latest() dan membatasi data yang ditampilkan sejumlah 5 data perhalaman.
        $mahasiswa = Mahasiswa::latest()->paginate(5);

        return view('mahasiswa.index', compact('mahasiswa'));
    }

    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request) {
    $this->validate($request, [
        'nama'     => 'required',
        'nim'   => 'required|numeric',
        'email'   => 'required|email',
        'no_hp'   => 'required|numeric',
        'jurusan'   => 'required',
        'foto'     => 'required|image|mimes:png,jpg,jpeg',
        'angkatan'   => 'required|numeric',
    ]);

    //upload foto
    $foto = $request->file('foto');
    $foto->storeAs('public/mahasiswa', $foto->hashName());

    $mahasiswa = Mahasiswa::create([
        'nama'     => $request->nama,
        'nim'   => $request->nim,
        'email'   => $request->email,
        'no_hp'   => $request->no_hp,
        'jurusan'   => $request->jurusan,
        'foto'     => $foto->hashName(),
        'angkatan'   => $request->angkatan,
    ]);

    if($mahasiswa){
        //redirect dengan pesan sukses
        return redirect()->route('mahasiswa.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }else{
        //redirect dengan pesan error
        return redirect()->route('mahasiswa.index')->with(['error' => 'Data Gagal Disimpan!']);
    }
}

public function edit(Mahasiswa $mahasiswa)
{
    return view('mahasiswa.edit', compact('mahasiswa'));
}

//di dalam function ini kita memiliki sebuah parameter, yaitu Product $product, yang artinya parameter tersebut adalah model PRODUCT yang diambil datanya sesuai dengan ID yang di dapatkan dari URL.

public function update(Request $request, Mahasiswa $mahasiswa)
{
    $this->validate($request, [
        'nama'     => 'required',
        'nim'   => 'required|numeric',
        'email'   => 'required|email',
        'no_hp'   => 'required|numeric',
        'jurusan'   => 'required',
        'angkatan'   => 'required|numeric',
    ]);

    //get data Mahasiswa by ID
    $product = Mahasiswa::findOrFail($mahasiswa->id);

    if($request->file('foto') == "") {

        $product->update([
        'nama'     => $request->nama,
        'nim'   => $request->nim,
        'email'   => $request->email,
        'no_hp'   => $request->no_hp,
        'jurusan'   => $request->jurusan,
        'angkatan'   => $request->angkatan,
        ]);

    } else {

        //hapus image lama
        Storage::disk('local')->delete('public/mahasiswa/'.$mahasiswa->foto);

        //upload new image
        $foto = $request->file('foto');
        $foto->storeAs('public/mahasiswa', $foto->hashName());

        $mahasiswa->update([
            'nama'     => $request->nama,
            'nim'   => $request->nim,
            'email'   => $request->email,
            'no_hp'   => $request->no_hp,
            'jurusan'   => $request->jurusan,
            'foto'     => $foto->hashName(),
            'angkatan'   => $request->angkatan,
        ]);

    }

    if($mahasiswa){
        //redirect dengan pesan sukses
        return redirect()->route('mahasiswa.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }else{
        //redirect dengan pesan error
        return redirect()->route('mahasiswa.index')->with(['error' => 'Data Gagal Diupdate!']);
    }
}

        public function destroy($id)
        {
        $mahasiswa = Mahasiswa::findOrFail($id);
        Storage::disk('local')->delete('public/mahasiswa/'.$mahasiswa->foto);
        $mahasiswa->delete();

        if($mahasiswa){
            //redirect dengan pesan sukses
            return redirect()->route('mahasiswa.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('mahasiswa.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
        }

}
