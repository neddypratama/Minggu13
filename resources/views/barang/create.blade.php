@extends('layout.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('barang') }}" class="form-horizontal">
            @csrf
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Kategori Barang</label>
                <div class="col-11">
                    <select class="form-control" id="kategori_id" name="kategori_id" required>
                        <option value="">- Pilih Kategori -</option>
                        @foreach($kategori as $item)
                        <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Barang Kode</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="barang_kode" name="barang_kode" value="{{ old('barang_kode') }}" placeholder="BBB" required readonly>
                    @error('barang_kode')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama Barang</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="barang_nama" name="barang_nama" value="{{ old('barang_nama') }}" placeholder="Barang" required>
                    @error('barang_nama')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Harga Beli</label>
                <div class="col-11">
                    <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}" min="0" step="1" placeholder="1000" required>
                    @error('harga_beli')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Harga Jual</label>
                <div class="col-11">
                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" min="0" step="1" placeholder="1500" required>
                    @error('harga_jual')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label"></label>
                <div class="col-11">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <a class="btn btn-sm btn-default ml-1" href="{{ url('barang') }}">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('css')
<!-- Add any additional CSS files here -->
@endpush

@push('js')
<script>
    const lastBarangId = {{ $lastId }};

    const kategoriMap = {
      @foreach($kategori as $item)
      '{{ $item->kategori_id }}': '{{ $item->kategori_kode }}',
      @endforeach
    };

    document.getElementById('kategori_id').addEventListener('change', function() {
        const selectedKategoriId = this.value;
        const barangKodeInput = document.getElementById('barang_kode');
        var isoDate = new Date().toISOString().slice(0, 10); 
        if (selectedKategoriId in kategoriMap) {
            barangKodeInput.value = kategoriMap[selectedKategoriId] + '_' + (lastBarangId+1) +    '_' + isoDate;
        } else {
            barangKodeInput.value = '';
        }
    });

    document.querySelector('form').addEventListener('submit', function(event) {
        const harga_jual = parseFloat(document.getElementById('harga_jual').value);
        const harga_beli = parseFloat(document.getElementById('harga_beli').value);

        if (harga_jual <= harga_beli) {
            alert('Harga Jual harus lebih besar daripada Harga Beli.');
            event.preventDefault(); // Prevent form submission
        }
    });
</script>
@endpush
