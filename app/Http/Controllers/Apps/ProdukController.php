<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdukRequest;
use App\Models\Produk;
use App\Services\ProdukService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    private ProdukService $produk_service;

    public function __construct()
    {
        $this->produk_service = new ProdukService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view_admin('admin.produk.index', 'Produk', [], TRUE);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->view_admin("admin.produk.create", "Tambah Produk", []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProdukRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdukRequest $request)
    {
        $response = $this->produk_service->store($request);
        return \response_json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function show(Produk $produk)
    {
        $data = [
            "produk" => $produk
        ];

        return $this->view_admin("admin.produk.show", "Detail Produk", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function edit(Produk $produk)
    {
        $data = [
            "produk" => $produk
        ];
      
        return $this->view_admin("admin.produk.edit", "Edit Produk", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProdukRequest  $request
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function update(ProdukRequest $request, Produk $produk)
    {
        $response = $this->produk_service->update($request, $produk);
        return \response_json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();
        $response = \response_success_default("Berhasil hapus produk!", FALSE, \route("app.produk.index"));
        return \response_json($response);
    }

    public function get(Request $request)
    {
        $produks = $this->produk_service->get_list_paged($request);
        $count = $this->produk_service->get_list_count($request);

        $data = [];
        $no = $request->start;


        foreach ($produks as $produk) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $produk->product_name;
            $row[] = $produk->product_description;
            $row[] = $produk->product_price_capital;
            $row[] = $produk->product_price_sell;
            $button = "<a href='" . \route("app.produk.show", $produk->id) . "' class='btn btn-info btn-sm m-1'>Detail</a>";
            $button .= form_delete("formUser$produk->id", route("app.produk.destroy", $produk->id));
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
