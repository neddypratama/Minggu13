@extends('layout.template') 
 
@section('content') 
  <div class="card card-outline card-primary"> 
    <div class="card-header"> 
      <h3 class="card-title">{{ $page->title }}</h3> 
      <div class="card-tools"></div> 
    </div> 
    <div class="card-body"> 
      <form method="POST" action="{{ url('user') }}" class="form-horizontal" enctype="multipart/form-data"> 
        @csrf 
        <div class="form-group row"> 
          <label class="col-1 control-label col-form-label">Level</label> 
          <div class="col-11"> 
            <select class="form-control" id="level_id" name="level_id" required> 
              <option value="">- Pilih Level -</option> 
              @foreach($level as $item) 
                <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option> 
              @endforeach 
            </select> 
            @error('level_id')
              <small class="form-text text-danger">{{ $message }}</small> 
            @enderror 
          </div> 
        </div> 
        <div class="input-group mb-3">
          
      </div>
        <div class="form-group row"> 
          <label class="col-1 control-label col-form-label">Foto</label> 
          <div class="col-11"> 
            <div class="input-file-wrapper rounded">
              <div class="input-file-placeholder" id="file-placeholder">- Pilih Foto -</div>
              <input type="file" class="form-control" name="foto" id="image" value="{{ old('foto') }}">
            </div> 
            @error('foto') 
              <small class="form-text text-danger">{{ $message }}</small> 
            @enderror 
          </div> 
        </div> 
        <div class="form-group row"> 
          <label class="col-1 control-label col-form-label">Username</label> 
          <div class="col-11"> 
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="heri1" required> 
            @error('username') 
              <small class="form-text text-danger">{{ $message }}</small> 
            @enderror 
          </div> 
        </div> 
        <div class="form-group row"> 
          <label class="col-1 control-label col-form-label">Nama</label> 
          <div class="col-11"> 
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Heri Sasongko" required> 
            @error('nama') 
              <small class="form-text text-danger">{{ $message }}</small> 
            @enderror 
          </div> 
        </div> 
        <div class="form-group row"> 
          <label class="col-1 control-label col-form-label">Password</label> 
          <div class="col-11"> 
            <input type="password" class="form-control" id="password" name="password" placeholder="*****" required> 
            @error('password') 
              <small class="form-text text-danger">{{ $message }}</small> 
            @enderror 
          </div> 
        </div> 
        <div class="form-group row"> 
          <label class="col-1 control-label col-form-label"></label> 
          <div class="col-11"> 
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button> 
            <a class="btn btn-sm btn-default ml-1" href="{{ url('user') }}">Kembali</a> 
          </div> 
        </div> 
     </form> 
    </div> 
  </div> 
@endsection 
@push('css')
<style>
  /* CSS untuk menyembunyikan input file asli dan menggantinya dengan elemen placeholder */
  .input-file-wrapper {
      position: relative;
      width: 100%;
  }

  .input-file-wrapper input[type="file"] {
      opacity: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      top: 0;
      left: 0;
      cursor: pointer;
  }

  .input-file-placeholder {
      border: 1px solid #ccc;
      padding: 10px;
      display: inline-block;
      width: 100%;
      cursor: pointer;
      background-color: white;
      border-radius: 5px;
  }
</style> 
@endpush 
@push('js') 
<script>
  document.getElementById('image').addEventListener('change', function() {
      var fileName = this.files[0].name;
      document.getElementById('file-placeholder').textContent = fileName;
  });
</script>
@endpush 