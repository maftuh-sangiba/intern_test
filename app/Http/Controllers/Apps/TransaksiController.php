<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Http\Requests\TransaksiRequest;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view_admin('admin.transaksi.index', 'Transaksi', [], TRUE);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $produks = Produk::withoutTrashed()->get();

        $data = [ 
            'produks' => $produks 
        ];

        return $this->view_admin('admin.transaksi.create', 'Tambahkan Transaksi', $data, TRUE);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TransaksiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransaksiRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $transaksi = Transaksi::create([
                'total' => $request->total
            ]);

            foreach ($request->selectedProducts as $product) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $product['produk_id'],
                    'qty' => $product['qty'],
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Transaction created successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create transaction.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show(Transaksi $transaksi)
    {
        $detail = DetailTransaksi::where('detail_transaksis.transaksi_id', $transaksi->id)
        ->join('produks', 'detail_transaksis.produk_id', '=', 'produks.id')
        ->withoutTrashed()
        ->get();

        $data = [
            "transaksi" => $transaksi,
            "detail" => $detail
        ];

        return $this->view_admin("admin.transaksi.show", "Detail Transaksi", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaksi $transaksi)
    {
        $detail = DetailTransaksi::where('detail_transaksis.transaksi_id', $transaksi->id)
        ->join('produks', 'detail_transaksis.produk_id', '=', 'produks.id')
        ->select('detail_transaksis.id as detail_id', 'detail_transaksis.transaksi_id', 'detail_transaksis.qty', 'produks.*')
        ->withoutTrashed()
        ->get();

        $produks = Produk::withoutTrashed()->get();
        
        $data = [
            "transaksi" => $transaksi,
            "detail" => $detail,
            "produks" => $produks 
        ];
      
        return $this->view_admin("admin.transaksi.edit", "Edit Transaksi", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TransaksiRequest  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(TransaksiRequest $request, Transaksi $transaksi)
    {
        $detailData = $request->selectedProducts;

        try {
            DB::beginTransaction();
            
            $transaksi->update([
                'total' => $request->total
            ]);

            foreach ($detailData as $value) {
                dd($value['produk_id']);
                DetailTransaksi::where('')->update();
            }

            DB::commit();

            return response()->json(['message' => 'Transaction created successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create transaction.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }

    public function get(Request $request)
    {
        $transaksis = Transaksi::all();
        $count = count(Transaksi::all());

        $data = [];
        $no = $request->start;


        foreach ($transaksis as $transaksi) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = Carbon::parse($transaksi->created_at)->format('d F Y - H:i');
            $row[] = $transaksi->total;
            $button = "<a href='" . \route("app.transaksi.show", $transaksi->id) . "' class='btn btn-info btn-sm m-1'>Detail</a>";
            $button .= form_delete("formUser$transaksi->id", route("app.produk.destroy", $transaksi->id));
            $row[] = $button;
            $data[] = $row;
        }

        $output = [
            "draw" => $request->draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $data
        ];

        return \response()->json($output, 200);
    }
}
