<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Services\ProdukService;
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
     * @param  \App\Http\Requests\StoreProdukRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProdukRequest $request)
    {
        //
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
        return $this->view_admin("admin.users.show", "Detail User", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function edit(Produk $produk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProdukRequest  $request
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProdukRequest $request, Produk $produk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produk $produk)
    {
        //
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
            $button .= form_delete("formUser$produk->id", route("app.users.destroy", $produk->id));
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
