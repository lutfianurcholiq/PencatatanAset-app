<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{   
    public function index()
    {
        if (Auth()->user()->is_active == 'aktif') {
            $aset = Sekolah::all()->count();
            $kecamatan = Sekolah::with('kecamatan')->get('kecamatan_id');
            $negeri = Sekolah::where('kategori','Negeri')->count();
            $swasta = Sekolah::where('kategori','Swasta')->count();
            $lb = Sekolah::where('kategori','Luar Biasa')->count();
            $kcmtn = Kecamatan::pluck('nama_kecamatan');
            
            $jmlh_aset = DB::table('sekolahs')
                        ->select('sekolahs.nama_sekolah', DB::raw('COUNT(asets.id) as jmlh_aset'))
                        ->join('asets','sekolahs.id','=','asets.sekolah_id')
                        ->groupBy('sekolahs.nama_sekolah')
                        ->get();

            $jmlh_sklh = DB::table('sekolahs')
                        ->select('kecamatans.nama_kecamatan', DB::raw('COUNT(sekolahs.id) as jmlh_sklh'))
                        ->join('kecamatans','kecamatans.id','=','sekolahs.kecamatan_id')
                        ->groupBy('kecamatans.nama_kecamatan')
                        ->pluck('jmlh_sklh','nama_kecamatan');
            
            // return $jmlh_sklh;
            // return $jmlh_aset;
            
            return view('admin.dashboard.index', [
                'asets' => $aset,
                'negeris' => $negeri,
                'swastas' => $swasta,
                'kcmtn' => $kcmtn,
                'kecamatan' => $kecamatan,
                'aset' => $jmlh_aset,
                'sklh' => $jmlh_sklh,
                'lb' => $lb
            ]);
        }else{
            return redirect('/login')->with('failed','Status anda tidak aktif, silahkan hubungi admin');
        }
        
    }
}
