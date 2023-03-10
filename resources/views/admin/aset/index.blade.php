@extends('layouts.main')

@section('container')

    <div class="row">
        <div class="col">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Master Data Aset</li>
        </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div> 
        @endif
        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div> 
        @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
        </div>
        <div class="card-body">
            @if (auth()->user()->role != 'admin')
                <a href="aset/create" class="btn btn-primary mb-3"><i class="nav-icon fas fa-plus"></i> Tambah</a>
                <button class="btn btn-info mb-3 float-right" onclick="print()"><i class="nav-icon fas fa-print"> </i></button>
            @else
                <button class="btn btn-info mb-3" onclick="print()"><i class="nav-icon fas fa-print"> </i></button>
            @endif
        <table id="dataTables" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Aset</th>
                    <th>Sekolah</th>
                    <th>Foto Aset</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asets as $aset)
                    <tr>
                        {{-- @dd($aset) --}}
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $aset->nama_aset }}</td>
                        <td>{{ $aset->sekolah->nama_sekolah }}</td>
                        <td>
                            <button class="" style="border: none" data-toggle="modal" data-target="#modalFoto{{ $aset->id }}">
                                <img src="{{ asset('storage/'. $aset->foto_aset) }}" width="90px" alt="Foto Aset {{ $aset->nama_aset }}">
                            </button>
                        </td>
                        <td>
                            {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDetailAset{{ $aset->id }}"><i class="nav-icon fas fa-eye"></i></button> --}}
                            <a href="/aset/{{ $aset->id }}" class="btn btn-primary ml-1"><i class="nav-icon fas fa-eye"></i></a>
                            @if (auth()->user()->role != 'admin')
                            <a href="/aset/{{ $aset->id }}/edit" class="btn btn-secondary ml-1"><i class="nav-icon fas fa-edit"></i></a>
                            <button type="button" data-toggle="modal" data-target="#hapusAset{{ $aset->id }}" class="btn btn-danger ml-1"><i class="nav-icon fas fa-trash"></i></button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>

            @foreach ($asets as $aset)
            <!-- Modal Foto -->
                <div class="modal fade" id="modalFoto{{ $aset->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Foto aset {{ $aset->nama_aset }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset('storage/'. $aset->foto_aset) }}" class="rounded-sm" alt="foto aset" width="500px" height="auto">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    </div>
                </div>
            {{-- end Modal Foto --}}

            {{-- Modal Detail Aset --}}
                <div class="modal fade" id="modalDetailAset{{ $aset->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Detail Aset {{ $aset->nama_aset }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                                <div class="modal-body">
                                    <p>Nama Aset : <b>{{ $aset->nama_aset }}</b></p>
                                    <p>Sekolah : <b>{{ $aset->sekolah->nama_sekolah }}</b></p>
                                    <p>Jenis Aset : <b class="text-uppercase">{{ $aset->jenis_aset }}</b></p>
                                    <p>Tahun Perolehan Aset : <b>{{ $aset->nama_aset }}</b></p>
                                    <p>Harga Perolehan Aset : <b>@mataUang($aset->harga_beli)</p>
                                    @if ($aset->status == 'telah disusutkan')
                                        <p>Status Aset : <span class="badge badge-success">{{ $aset->status }}</span></p>
                                    @else
                                        <p>Status Aset : <span class="badge badge-danger">{{ $aset->status }}</span></p>
                                    @endif
                                </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- End Modal Detail Aset --}}

            <!-- Modal Hapus Aset -->
                <div class="modal fade" id="hapusAset{{ $aset->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Yakin ingin menghapus aset <b>{{ $aset->nama_aset }}</b>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <form action="/aset/{{ $aset->id }}" method="POST">
                                @method('delete')
                                @csrf
                                <button type="submit" class="btn btn-danger">Yakin</button>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            {{-- end Modal --}}
            @endforeach
        </table>
    </div>
    
@endsection