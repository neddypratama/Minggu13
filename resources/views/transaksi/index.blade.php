@extends('layout.template') 
 
@section('content') 
  <div class="card card-outline card-primary"> 
      <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
        <div class="card-tools"> 
          <a class="btn btn-sm btn-primary mt-1" href="{{ url('transaksi/create') }}">Tambah</a> 
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
            <select class="form-control" id="user_id" name="user_id" required>
              <option value="">- Semua -</option>
              @foreach ($user as $item)
              <option value="{{$item->user_id}}">{{$item->nama}}</option>
              @endforeach
            </select>
            <br>
          </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table_transaksi"> 
          <thead> 
            <tr><th>ID</th><th>Nama User</th><th>Nama Pembeli</th><th>Penjualan Kode</th><th>Penjualan Tanggal</th><th>Aksi</th></tr> 
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
      var dataUser = $('#table_transaksi').DataTable({ 
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
            title: 'Tabel Transaksi',
            exportOptions: {
              columns: [0, 1, 2, 3, 4] // kolom yang akan di-include dalam ekspor
            }
          },
          {
            extend: 'pdf',
            title: 'Tabel Transaksi',
            exportOptions: {
              columns: [0, 1, 2, 3, 4] // kolom yang akan di-include dalam ekspor
            }
          },
          {
            extend: 'excel',
            title: 'Tabel Transaksi',
            exportOptions: {
              columns: [0, 1, 2, 3, 4] // kolom yang akan di-include dalam ekspor
            }
          },
          {
            extend: 'print',
            title: 'Tabel Transaksi',
            exportOptions: {
              columns: [0, 1, 2, 3, 4] // kolom yang akan di-include dalam ekspor
            }
          },
        ],
          ajax: { 
              "url": "{{ url('transaksi/list') }}", 
              "dataType": "json", 
              "type": "POST",
              "data":function(d){
                d.user_id = $('#user_id').val();
              }
          }, 
          columns: [ 
            { 
             data: "DT_RowIndex", // nomor urut dari laravel datatable addIndexColumn()            
              className: "text-center", 
              orderable: false, 
              searchable: false     
            },{ 
              data: "user.nama",                
              className: "", 
              orderable: true,    // orderable: true, jika ingin kolom ini bisa diurutkan 
              searchable: true    // searchable: true, jika ingin kolom ini bisa dicari 
            },{ 
              data: "pembeli",                
              className: "", 
              orderable: true,    // orderable: true, jika ingin kolom ini bisa diurutkan 
              searchable: true    // searchable: true, jika ingin kolom ini bisa dicari 
            },{ 
              data: "penjualan_kode",                
              className: "", 
              orderable: false,    // orderable: true, jika ingin kolom ini bisa diurutkan 
              searchable: false    // searchable: true, jika ingin kolom ini bisa dicari
            },{ 
              data: "penjualan_tanggal",                
              className: "", 
              orderable: false,    // orderable: true, jika ingin kolom ini bisa diurutkan 
              searchable: false,    // searchable: true, jika ingin kolom ini bisa dicari
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
              data: "aksi",                
              className: "", 
              orderable: false,    // orderable: true, jika ingin kolom ini bisa diurutkan 
              searchable: false    // searchable: true, jika ingin kolom ini bisa dicari 
            } 
          ] 
      }); 
      $('#user_id').on('change', function(){
        dataUser.ajax.reload();
      });
    }); 
  </script> 
@endpush 