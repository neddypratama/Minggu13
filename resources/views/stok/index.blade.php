@extends('layout.template') 
 
@section('content') 
  <div class="card card-outline card-primary"> 
      <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
        <div class="card-tools"> 
          <a class="btn btn-sm btn-primary mt-1" href="{{ url('stok/create') }}">Tambah</a> 
        </div> 
      </div> 
      <div class="card-body"> 
        @if (session('success'))
        <div class="alert alert-success">{{session('success')}}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{session('error')}}</div>
        @endif
        <div class="row">
          <label class="col-1 control-label col-form-label">Filter:</label>
          <div class="col-3">
            <select class="form-control" id="barang_id" name="barang_id" required>
              <option value="">- Semua -</option>
              @foreach ($barang as $item)
              <option value="{{$item->barang_id}}">{{$item->barang_nama}}</option>
              @endforeach
            </select>
            <br>
          </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table_stok"> 
          <thead> 
            <tr><th>ID</th><th>Nama Barang</th><th>Nama User</th><th>Tanggal Stok</th><th>Jumlah Stok</th><th>Aksi</th></tr> 
          </thead> 
      </table> 
    </div> 
  </div> 
@endsection 
 
@push('css') 
@endpush 
@push('js') 
  <script> 
    $(document).ready(function() { 
    var datastok = $('#table_stok').DataTable({ 
      pageLength: 25,
      processing: true,
      serverSide: true,     // serverSide: true, jika ingin menggunakan server side processing 
      dom: '<"d-flex justify-content-between align-items-center"lBf>tipr',
      language: {
        buttons: {
          colvis: 'show / hide', // label button show / hide
          colvisRestore: "Reset Kolom" // label untuk reset kolom ke default
        }
      },
      buttons: [
        { extend: 'colvis', postfixButtons: ['colvisRestore'] },
        {
          extend: 'csv',
          title: 'Tabel Stok',
          exportOptions: {
            columns: [0, 1, 2, 3, 4] // kolom yang akan di-include dalam ekspor
          }
        },
        {
          extend: 'pdf',
          title: 'Tabel Stok',
          exportOptions: {
            columns: [0, 1, 2, 3, 4] // kolom yang akan di-include dalam ekspor
          }
        },
        {
          extend: 'excel',
          title: 'Tabel Stok',
          exportOptions: {
            columns: [0, 1, 2, 3, 4] // kolom yang akan di-include dalam ekspor
          }
        },
        {
          extend: 'print',
          title: 'Tabel Stok',
          exportOptions: {
            columns: [0, 1, 2, 3, 4] // kolom yang akan di-include dalam ekspor
          }
        },
      ],
      ajax: { 
          "url": "{{ url('stok/list') }}", 
          "dataType": "json", 
          "type": "POST",
          "data": function(d) {
            d.barang_id = $('#barang_id').val();
          }
      }, 
      
      columns: [ 
        { 
         data: "DT_RowIndex", // nomor urut dari laravel datatable addIndexColumn()            
          className: "text-center", 
          orderable: false, 
          searchable: false     
        },{ 
          data: "barang.barang_nama",                
          className: "", 
          orderable: false,    // orderable: true, jika ingin kolom ini bisa diurutkan 
          searchable: false    // searchable: true, jika ingin kolom ini bisa dicari
        },{ 
          data: "user.nama",                
          className: "", 
          orderable: false,    // orderable: true, jika ingin kolom ini bisa diurutkan 
          searchable: false    // searchable: true, jika ingin kolom ini bisa dicari
        },{ 
          data: "stok_tanggal",                
          className: "", 
          orderable: true,    // orderable: true, jika ingin kolom ini bisa diurutkan 
          searchable: true,    // searchable: true, jika ingin kolom ini bisa dicari
          render: function(data, type, row) {
            if (data) {
              var date = new Date(data);
              var year = date.getFullYear().toString();
              var month = ('0' + (date.getMonth() + 1)).slice(-2);
              var day = ('0' + date.getDate()).slice(-2);
              return day + '-' + month + '-' + year;
            }
            return '';
          }, 
        },{ 
          data: "stok_jumlah",                
          className: "", 
          orderable: true,    // orderable: true, jika ingin kolom ini bisa diurutkan 
          searchable: true    // searchable: true, jika ingin kolom ini bisa dicari 
        },{ 
          data: "aksi",                
          className: "", 
          orderable: false,    // orderable: true, jika ingin kolom ini bisa diurutkan 
          searchable: false    // searchable: true, jika ingin kolom ini bisa dicari 
        } 
      ] 
  });
  
  $('#barang_id').on('change', function() {
    datastok.ajax.reload();
  });
}); 
  </script> 
@endpush 