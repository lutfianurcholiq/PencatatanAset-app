<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\Aset;
use App\Models\Sekolah;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.sekolah.index', [
            'title' => 'Aset Sekolah',
            'details' => 'Detail Aset',
            'confirm' => 'Konfirmasi Hapus Data',
            'sekolahs' => Sekolah::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->role == 'admin'){
            return redirect('/aset')->with('error','Tidak bisa akses halaman tersebut');
        }
        return view('admin.sekolah.create', [
            'title' => 'Tambah Data Sekolah',
            'kotas' => Kota::all(),
            'kec' => Kecamatan::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|max:255',
            'kategori' => 'required',
            'tahun' => 'required',
            'kota_id' => 'required',
            'kecamatan_id' => 'required',
            'alamat' => 'required',
            'jumlah_siswa' => 'required',
            'lokasi' => 'required',
            'foto' => 'required|image|mimes:jpg,png,png|max:3048',
            'deskripsi' => 'required'
        ]);

        $image = $request->file('foto')->store('foto-sekolah');

        Sekolah::create([
            'nama_sekolah' => $request->nama_sekolah,
            'kategori' => $request->kategori,
            'tahun' => $request->tahun,
            'kota_id' => $request->kota_id,
            'kecamatan_id' => $request->kecamatan_id,
            'alamat' => $request->alamat,
            'jumlah_siswa' => $request->jumlah_siswa,
            'lokasi' => $request->lokasi,
            'foto' => $image,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect('/sekolah')->with('success','Data Berhasil Di Tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sekolah  $sekolah
     * @return \Illuminate\Http\Response
     */
    public function show(Sekolah $sekolah)
    {
        $aset = DB::table('sekolahs')
                        ->select('asets.nama_aset','asets.status')
                        ->join('asets','sekolahs.id','=','asets.sekolah_id')
                        ->where('asets.sekolah_id',$sekolah->id)
                        ->get();

        $jmlh_aset = Aset::where('sekolah_id',$sekolah->id)->count();
        // return $aset;

        return view('admin.sekolah.show', [
            'sekolahs' => $sekolah,
            'title' => 'Detail Data Sekolah',
            'aset' => $jmlh_aset,
            'asets' => $aset
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sekolah  $sekolah
     * @return \Illuminate\Http\Response
     */
    public function edit(Sekolah $sekolah)
    {
        if(auth()->user()->role == 'admin'){
            return redirect('/aset')->with('error','Tidak bisa akses halaman tersebut');
        }
        return view('admin.sekolah.edit', [
            'sekolahs' => $sekolah,
            'title' => 'Ubah Data Sekolah',
            'kotas' => Kota::all(),
            'kec' => Kecamatan::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sekolah  $sekolah
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sekolah $sekolah)
    {
        // return $request;
        $validated = $request->validate([
            'nama_sekolah' => 'required|max:255',
            'kategori' => 'required',
            'tahun' => 'required|integer',
            'kota_id' => 'required',
            'kecamatan_id' => 'required',
            'alamat' => 'required',
            'jumlah_siswa' => 'required',
            'lokasi' => 'required',
            'foto' => 'image|mimes:jpg,png,png|max:3048',
            'deskripsi' => 'required'
        ]);

        // dd($request);

        if($request->file('foto')){
            if($request->foto_lama){
                Storage::delete($request->foto_lama);    
            }
            $validated['foto'] = $request->file('foto')->store('foto-sekolah');
        }

        Sekolah::where('id', $sekolah->id)->update($validated);

        return redirect('/sekolah')->with('success','Data Berhasil Di Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sekolah  $sekolah
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sekolah $sekolah)
    {
        if($sekolah->image){
            Storage::delete($sekolah->image);    
        }
        $sekolahs = Sekolah::find($sekolah->id); 

        $sekolahs->delete();

        return redirect('/sekolah')->with('success', 'Data Berhasil Terhapus');
    }
}
