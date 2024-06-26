@extends('layout.template') 
 
@section('content') 
  <div class="card card-outline card-primary"> 
      <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
        <div class="card-tools"> 
          <a class="btn btn-sm btn-primary mt-1" href="{{ url('kategori/create') }}">Tambah</a> 
        </div> 
      </div> 
      <div class="card-body"> 
        @if (session('success'))
        <div class="alert alert-success">{{session('success')}}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{session('error')}}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori"> 
          <thead> 
            <tr>
              <th>ID</th>
              <th>Kode Kategori</th>
              <th>Nama Kategori</th>
              <th>Aksi</th>
            </tr> 
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
      var dataKategori = $('#table_kategori').DataTable({ 
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
            title: 'Tabel Kategori',
            exportOptions: {
              columns: [0, 1, 2,] // kolom yang akan di-include dalam ekspor
            }
          },
          {
            extend: 'pdf',
            title: 'Tabel Kategori',
            exportOptions: {
              columns: [0, 1, 2,] // kolom yang akan di-include dalam ekspor
            }
          },
          {
            extend: 'excel',
            title: 'Tabel Kategori',
            exportOptions: {
              columns: [0, 1, 2,] // kolom yang akan di-include dalam ekspor
            }
          },
          {
            extend: 'print',
            title: 'Tabel Kategori',
            exportOptions: {
              columns: [0, 2, 3, 4] // kolom yang akan di-include dalam ekspor
            }
          },
        ],
          ajax: { 
              "url": "{{ url('kategori/list') }}", 
              "dataType": "json", 
              "type": "POST",
              "data":function(d){
                d.kategori_id = $('#kategori_id').val();
              }
          }, 
          columns: [ 
            { 
             data: "DT_RowIndex", // nomor urut dari laravel datatable addIndexColumn()            
              className: "text-center", 
              orderable: false, 
              searchable: false     
            },{ 
              data: "kategori_kode",                
              className: "", 
              orderable: true,    // orderable: true, jika ingin kolom ini bisa diurutkan 
              searchable: true    // searchable: true, jika ingin kolom ini bisa dicari 
            },{ 
              data: "kategori_nama",                
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
      $('#kategori_id').on('change', function(){
        dataLevel.ajax.reload();
      });
    }); 
  </script> 
@endpush