<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\TransaksiDetailModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransaksiController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title'=>'Daftar transaksi',
            'list' => ['Home', 'transaksi']  
        ];

        $page = (object)[
            'title' => 'Daftar transaksi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'transaksi'; //set saat menu aktif
        $user = UserModel::all();

        return view('transaksi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu]);
    }

    // Ambil data barang dalam bentuk json untuk datatables 
    public function list(Request $request) 
    { 
        $transaksis = TransaksiModel::select('penjualan_id', 'user_id', 'penjualan_kode', 'pembeli', 'penjualan_tanggal',) 
                ->with('user'); 

                //filter
                if($request->user_id){
                    $transaksis->where('user_id', $request->user_id);
                }
 
        return DataTables::of($transaksis) 
        ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
        ->addColumn('aksi', function ($penjualan) {  // menambahkan kolom aksi 
            $btn  = '<a href="'.url('/transaksi/' . $penjualan->penjualan_id).'" class="btn btn-info btn-sm">Detail</a> ';     
            $btn  .= '<a href="'.url('/transaksi/' . $penjualan->penjualan_id).'/cetak" class="btn btn-success btn-sm">Cetak</a> ';     
            return $btn; 
        }) 
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
        ->make(true); 
    } 

    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Transaksi',
            'list' => ['Home', 'Transaksi', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah Transaksi baru'
        ];

        $user = UserModel::all(); //ambil data kategori untuk ditampilkan di form
        $barang = BarangModel::all();
        $activeMenu = 'Transaksi'; //set menu sedang aktif

        $lastPenjualan = TransaksiModel::latest('penjualan_id')->first();
        $lastId = $lastPenjualan ? $lastPenjualan->penjualan_id : 0;

        return view('Transaksi.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'barangs' => $barang,
            'lastId' => $lastId,
            'activeMenu' => $activeMenu
        ]);
    }

    //UNTUK MENGHANDLE ATAU MENYIMPAN DATA BARU 
    public function store(Request $request){
        $request->validate([
            //barang_kode harus diisi, berupa string, minimal 3 karakter dan bernilai unik di table m_barang kolom barang_kode
            'penjualan_kode' => 'required|string|min:3|unique:t_penjualan,penjualan_kode',
            'pembeli' => 'required|string|max:100',
            'penjualan_tanggal' => 'required|date',
            'member' => 'nullable',
            'jumlah.*' => 'required|integer|min:1',
            'harga.*' => 'required|numeric|min:0',
            'total.*' => 'required|numeric|min:1',
        ]);

        $transaksi = TransaksiModel::create([
            'user_id' => auth()->user()->user_id,
            'penjualan_kode'=> $request -> penjualan_kode,
            'pembeli'=> $request -> pembeli,
            'penjualan_tanggal' => $request -> penjualan_tanggal,
        ]);

        foreach ($request->barang as $index => $barang_id) {
            TransaksiDetailModel::create([
                'penjualan_id' => $transaksi->penjualan_id,
                'barang_id' => $barang_id,
                'jumlah' => $request->jumlah[$index],
                'harga' => $request->total[$index],
            ]);

            StokModel::create([
                'barang_id' => $barang_id,
                'user_id' => auth()->user()->user_id,
                'stok_tanggal' => $request -> penjualan_tanggal,
                'stok_jumlah' => -$request->jumlah[$index],
            ]);
        }

        return redirect('/transaksi')->with('success', 'Data transaksi berhasil disimpan');
    }

    //MENAMPILKAN DETAIL BARANG 
    public function show(string $id){
        $penjualan = TransaksiModel::with('user')-> find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Transaksi',
            'list' => ['Home', 'Transaksi', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail Transaksi'
        ];

        $activeMenu = 'transaksi'; // set menu yang aktif
        $detailTransaksi = TransaksiDetailModel::with('barang')->where('penjualan_id', $id)->get();
        // dd($detailTransaksi);

        return view('transaksi.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'penjualan' => $penjualan,
            'detail' => $detailTransaksi, 
            'activeMenu' => $activeMenu]);
    }

    public function edit(string $id){
        $penjualan = TransaksiModel::find($id);
        $user = UserModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit Transaksi',
            'list' => ['Home', 'Transaksi', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit Transaksi'
        ];

        $activeMenu = 'transaksi';

        return view('transaksi.edit',[
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'penjualan' => $penjualan,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id){
        $request->validate([
            //barang_kode harus diisi, berupa string, minimal 3 karakter dan bernilai unik di table m_barang kolom barang_kode
            'pembeli' => 'required|string|max:100',
            'penjualan_tanggal' => 'required|date'
        ]);

        TransaksiModel::find($id)->update([
            'penjualan_kode'=> $request -> penjualan_kode,
            'pembeli'=> $request -> pembeli,
            'penjualan_tanggal' => $request -> penjualan_tanggal,
            'user_id' => $request -> user_id,
        ]);

        return redirect('/transaksi')->with('success', 'Data berhasil diubah');
    }

    public function destroy(string $id){
        $check = TransaksiModel::find($id);
        if(!$check){
            return redirect('/transaksi')->with('error', 'Data transaksi tidak ditemukan');
        }
        try{
            TransaksiModel::destroy($id);

            return redirect('/transaksi')->with('success', 'Data transaksi berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

        return redirect('/transaksi')->with('error', 'Data transaksi gagal dihapus karena terdapat tabel lain yang terkait dengan data ini');
    }
    }

    public function cetakStruk($id)
    {
        $transaksi = TransaksiModel::with('user')->find($id); // Ambil data transaksi berdasarkan ID
        $detailTransaksi = TransaksiDetailModel::with('barang')->where('penjualan_id', $id)->get();

        if (!$transaksi && !$detailTransaksi) {
            abort(404);
        }

        $total = 0;
        foreach ($detailTransaksi as $d) {
           $total += $d->harga;
        }

        // $pdf = Pdf::loadView('transaksi.cetak', compact('transaksi', 'detailTransaksi'));

        // return $pdf->stream('struk.pdf');

        return view('transaksi.cetak', compact('transaksi', 'detailTransaksi', 'total'));
    }
}
