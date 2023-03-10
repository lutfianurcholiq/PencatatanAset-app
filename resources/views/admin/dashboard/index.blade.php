@extends('layouts.main')

@section('container')

      <div class="row">
        <div class="col">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active"></li>
        </ol>
        </div>
      </div>

  @if (auth()->user()->role != 'update role')
      <section class="content">
          <div class="container-fluid">
            @if (session()->has('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif
            <div class="row">
              <div class="col-lg-3 col-3">
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3>{{ $asets }}</h3>
                    <p>Jumlah Sekolah</p>
                  </div>
                  <div class="icon">
                      <i class="fas fa-school"></i>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-3">
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3>{{ $negeris }}</h3>
                    <p>Negeri</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-3">
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3>{{ $swastas }}</h3>
                    <p>Swasta</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-3">
                <div class="small-box bg-danger">
                  <div class="inner">
                    <h3>{{ $lb }}</h3>
                    <p>Luar Biasa</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <!-- BAR CHART -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Jumlah Sekolah Per Kecamatan</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
            <div class="row">
              <div class="col">
                <!-- BAR CHART -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Jumlah Aset Per Sekolah</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      {{-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button> --}}
                    </div>
                  </div>
                  <div class="card-body">
                    <table class="table table-bordered table-striped text-center" id="dataTables">
                      <thead>
                        <tr>
                          <th>Nama Sekolah</th>
                          <th>Jumlah Aset</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($aset as $st)
                          <tr>
                            <td>{{ $st->nama_sekolah }}</td>
                            <td>{{ $st->jmlh_aset }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
          </div>
      </section>
  @else
      <p>Silahkan Hubungi Admin untuk Update Role Anda</p>
  @endif

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
      var ctx = document.getElementById("barChart").getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: {!! json_encode($kcmtn) !!},
          datasets: [{
            label: 'Jumlah Sekolah Berdasarkan Kecamatan',
            data: {!! json_encode($sklh) !!},
            backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
          }]
        },
      });
      // const ctx = document.getElementById('barChart');
      // const myChart = new Chart(ctx, {
      //     type: 'bar',
      //     data: {
      //         labels: [{{ $kcmtn }}],
      //         datasets: [{
      //             label: 'test Scenario',
      //             data: [{!! json_encode($kecamatan) !!}, {!! json_encode($kecamatan) !!}],
      //             backgroundColor: [
      //               '#FA4676',
      //               '#4AF372'
      //             ],                  
      //         }]
      //     },
      //     options: {
      //       responsive: true
      //     }
      // });
</script>
@endsection