@extends('layout.template') 
 
@section('content') 
  <div class="card card-outline card-primary"> 
      <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
        <div class="card-tools"></div> 
      </div> 
      <div class="card-body"> 
        @empty($penjualan) 
            <div class="alert alert-danger alert-dismissible"> 
                <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5> 
                Data yang Anda cari tidak ditemukan. 
            </div> 
        @else 
            <table class="table table-bordered table-striped table-hover table-sm"> 
                <tr> 
                    <th>ID</th> 
                    <td>{{ $penjualan->penjualan_id }}</td> 
                </tr> 
                <tr> 
                    <th>Nama User</th> 
                    <td>{{ $penjualan->user->nama }}</td> 
                </tr> 
                <tr> 
                    <th>Pembeli</th> 
                    <td>{{ $penjualan->pembeli }}</td> 
                </tr> 
                <tr> 
                    <th>Kode Penjualan</th> 
                    <td>{{ $penjualan->penjualan_kode }}</td> 
                </tr> 
                <tr> 
                    <th>Tanggal Penjualan</th> 
                    <td>{{ $penjualan->penjualan_tanggal}}</td> 
                </tr>
                <tr>
                    <th>Detail Penjualan</th>
                    <td>
                        <table class="table table-bordered table-striped table-hover table-sm">
                            <tr><th>Nama Barang</th><th>Harga Barang</th><th>Jumlah Barang</th></tr>
                            @foreach ($detail as $d)
                                <tr><td>{{ $d->barang->barang_nama}}</td><td>{{ $d->harga}}</td><td>{{ $d->jumlah}}</td></tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>Total Penjualan</th>
                    <td>
                        @php
                            $total = 0;
                            foreach ($detail as $d) {
                                $total += $d->harga;
                            }
                            echo $total;
                        @endphp
                    </td>
                </tr>
            </table> 
        @endempty 
        <a href="{{ url('transaksi') }}" class="btn btn-sm btn-default mt-2">Kembali</a> 
    </div> 
  </div> 
@endsection 
 
@push('css') 
@endpush 
 
@push('js') 
@endpush 