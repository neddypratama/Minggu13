<!-- resources/views/halamanDepan/beranda.blade.php -->

@extends('layout.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Halo, Apakabar!!!</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            Selamat datang semua, ini adalah halaman utama dari aplikasi ini
        </div>
        <div>
            {!! $countUser->container() !!}
            {!! $profitBarang->container() !!}
            <div>
                <form id="forecastForm">
                    <label for="barang">Pilih Barang:</label>
                    <select id="barang" name="barang">
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->barang_id }}">{{ $barang->barang_nama }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Tampilkan Peramalan</button>
                </form>
            </div>
            {!! $peramalanChart->container() !!}
        </div>
    </div>
    
    <script src="{{ $countUser->cdn() }}"></script>
    <script src="{{ $profitBarang->cdn() }}"></script>
    <script src="{{ $peramalanChart->cdn() }}"></script>
    {{ $countUser->script() }}
    {{ $profitBarang->script() }}
    {{ $peramalanChart->script() }}
    <script>
        document.getElementById('forecastForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const barangId = document.getElementById('barang').value;

            fetch('{{ route("forecast.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ barang_id: barangId })
            })
            .then(response => response.json())
            .then(data => {
                const chart = {!! $peramalanChart->id !!}; // Assuming peramalanChart id is available
                chart.data.labels = data.labels;
                chart.data.datasets[0].data = data.datasets[0].data;
                chart.update();
            });
        });
    </script>
@endsection
