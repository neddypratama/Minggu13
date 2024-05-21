<!-- resources/views/peramalan.blade.php -->
@extends('layout.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Hasil Peramalan Penjualan</h3>
        </div>
        <div class="card-body">
            <canvas id="forecastChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('forecastChart').getContext('2d');
            var forecastChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($dates),
                    datasets: [{
                        label: 'Peramalan Penjualan',
                        data: @json($values),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    day: 'YYYY-MM-DD'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Penjualan'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
