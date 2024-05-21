<!DOCTYPE html>
<html>
<head>
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header, .footer {
            text-align: center;
            margin: 20px;
        }
        .content {
            margin: 0 auto;
            width: 80%;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
    </style>
    <script>
        window.onload = function() {
            // Print the document
            window.print();

            // Event listener to redirect back to index if print dialog is closed
            window.onafterprint = function() {
                window.location.href = "{{ url('/transaksi/') }}";
            };

            // In case of print dialog being cancelled
            setTimeout(function() {
                window.onfocus = function() {
                    window.location.href = "{{ url('/transaksi/') }}";
                };
            }, 1);
        }
    </script>
</head>
<body>
    <div class="header">
        <h2>Struk Transaksi</h2>
    </div>
    <div class="content">
        <p><strong>ID Transaksi:</strong> {{ $transaksi->penjualan_id }}</p>
        <p><strong>Nama Petugas:</strong> {{ $transaksi->user->nama }}</p>
        <p><strong>Kode Penjualan:</strong> {{ $transaksi->penjualan_kode }}</p>
        <p><strong>Tanggal Penjualan:</strong> {{ $transaksi->penjualan_tanggal }}</p>
        <p><strong>Nama Pembeli:</strong> {{ $transaksi->pembeli }}</p>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detailTransaksi as $detail)
                <tr>
                    <td>{{ $detail->barang->barang_nama }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>{{ number_format($detail->barang->harga_jual, 2) }}</td>
                    <td>{{ number_format($detail->harga, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p><strong>Total Penjualan: {{ number_format($total) }}</strong></p>
    </div>
    <div class="footer">
        <p>Terima kasih telah bertransaksi dengan kami!</p>
    </div>
</body>
</html>
