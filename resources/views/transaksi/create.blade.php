@extends('layout.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">{{ $page->title }}</h3>
      <div class="card-tools"></div>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ url('transaksi') }}" class="form-horizontal">
        @csrf
        <div class="form-group row"> 
          <label class="col-1 control-label col-form-label">User</label> 
          <div class="col-11"> 
            <input type="text" class="form-control" id="user_id" name="user_id" value="{{auth()->user()->nama}}" readonly> 
            @error('user_id') 
              <small class="form-text text-danger">{{ $message }}</small> 
            @enderror 
          </div> 
        </div> 
        <div class="form-group row">
          <label class="col-1 control-label col-form-label">Nama Pembeli</label>
          <div class="col-11">
            <input type="text" class="form-control" id="pembeli" name="pembeli" value="{{ old('pembeli') }}" required>
            @error('pembeli')
              <small class="form-text text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>
        <div class="form-group row">
          <label class="col-1 control-label col-form-label">Kode Penjualan</label>
          <div class="col-11">
            <input type="text" class="form-control" id="penjualan_kode" name="penjualan_kode" value="{{ old('penjualan_kode') }}" required readonly>
            @error('penjualan_kode')
              <small class="form-text text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>
        <div class="form-group row">
          <label class="col-1 control-label col-form-label">Tanggal Penjualan</label>
          <div class="col-11">
            <input type="datetime-local" class="form-control" id="penjualan_tanggal" name="penjualan_tanggal" required>
            @error('penjualan_tanggal')
              <small class="form-text text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>
        <div class="form-group row">
          <label class="col-1 control-label col-form-label">Member</label>
          <div class="col-11">
              <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="member" name="member">
                  <label class="form-check-label" for="member">
                      Buyer is a member
                  </label>
              </div>
          </div>
      </div>
      
        <div class="form-group row">
          <label class="col-1 control-label col-form-label">Detail Penjualan</label>
          <div class="col-11">
            <div id="items">
              <div class="form-group row item-row">
                <div class="col-md-4">
                  <label for="barang">Barang</label>
                  <select name="barang[]" class="form-control barang-select" required>
                    <option value="">- Pilih Barang -</option>
                    @foreach($barangs as $barang)
                      <option value="{{ $barang->barang_id }}" data-harga="{{ $barang->harga_jual }}" data-nama="{{ $barang->barang_nama }}">{{ $barang->barang_nama }}</option>
                    @endforeach
                  </select>
                  @error('barang[]')
                    <small class="form-text text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-2">
                  <label for="harga">Harga</label>
                  <input type="text" name="harga[]" class="form-control harga" readonly>
                  @error('harga[]')
                    <small class="form-text text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-2">
                  <label for="jumlah">Jumlah</label>
                  <input type="number" name="jumlah[]" class="form-control jumlah" required step="1" min="0" >
                  @error('jumlah[]')
                    <small class="form-text text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-2">
                  <label for="total">Total</label>
                  <input type="text" name="total[]" class="form-control total" readonly>
                  @error('total[]')
                    <small class="form-text text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-danger mt-4 remove-item">Hapus</button>
                </div>
              </div>
            </div>
            <button type="button" id="add-item" class="btn btn-primary">Tambah Barang</button>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-1 control-label col-form-label"></label>
          <div class="col-11">
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            <a class="btn btn-sm btn-default ml-1" href="{{ url('transaksi') }}">Kembali</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('css')
@endpush


@push('js')
<script>
  const lastPenjualanId = {{ $lastId }};
  const penjualanKodeInput = document.getElementById('penjualan_kode');
  penjualanKodeInput.value = 'PJLN_' + (lastPenjualanId+1)

  $(document).ready(function() {
      // Function to populate barang select options
      function populateBarangOptions() {
          var selectedItems = [];
          $('.barang-select').each(function() {
              var selectedValue = $(this).val();
              if (selectedValue) {
                  selectedItems.push(selectedValue);
              }
          });
          
          $('.barang-select').each(function() {
              var currentValue = $(this).val();
              $(this).find('option').each(function() {
                  var optionValue = $(this).val();
                  if (optionValue && selectedItems.includes(optionValue) && optionValue !== currentValue) {
                      $(this).hide();
                  } else {
                      $(this).show();
                  }
              });
          });
      }

      // Update harga and calculate total when barang is selected
      $(document).on('change', '.barang-select', function() {
          var harga = $(this).find(':selected').data('harga');
          var isMember = $('#member').is(':checked');
          if (isMember) {
              harga *= 0.95; // Apply 5% discount for members
          }
          var itemRow = $(this).closest('.item-row');
          itemRow.find('.harga').val(harga);
          calculateTotal(itemRow);
          populateBarangOptions();
      });

      // Calculate total when jumlah is changed
      $(document).on('input', '.jumlah', function() {
          var itemRow = $(this).closest('.item-row');
          calculateTotal(itemRow);
      });

      // Add new item row
      $('#add-item').click(function() {
          var newItem = $('.item-row:first').clone();
          newItem.find('input').val('');
          newItem.find('.harga').val('');
          newItem.find('.total').val('');
          newItem.find('select').val('');
          $('#items').append(newItem);
          populateBarangOptions();
      });

      // Remove item row
      $(document).on('click', '.remove-item', function() {
          if ($('.item-row').length > 1) {
              $(this).closest('.item-row').remove();
              populateBarangOptions();
          } else {
              alert('Minimal satu barang diperlukan.');
          }
      });

      // Function to calculate total
      function calculateTotal(itemRow) {
          var harga = itemRow.find('.harga').val();
          var jumlah = itemRow.find('.jumlah').val();
          var total = harga * jumlah;
          itemRow.find('.total').val(total);
      }

      // Handle member checkbox change
      $('#member').change(function() {
          $('.barang-select').each(function() {
              var harga = $(this).find(':selected').data('harga');
              if ($('#member').is(':checked')) {
                  harga *= 0.95; // Apply 5% discount for members
              }
              $(this).closest('.item-row').find('.harga').val(harga);
              calculateTotal($(this).closest('.item-row'));
          });
      });

      // Initial population of barang options
      populateBarangOptions();
  });
</script>
@endpush
