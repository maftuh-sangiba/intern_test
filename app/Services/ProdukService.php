<?php namespace App\Services;

use App\Http\Requests\ProdukRequest;
use App\Models\Produk;
use App\Models\SessionToken;
use App\Services\Cores\BaseService;
use App\Services\Cores\ErrorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProdukService extends BaseService
{
    private function generate_query_get(Request $request)
  {
    $column_search = ["produks.product_name"];
    $column_order = [NULL, "produks.product_name"];
    $order = ["produks.id" => "ASC"];

    $results = Produk::query()
      ->where(function ($query) use ($request, $column_search) {
        $i = 1;
        if (isset($request->search)) {
          foreach ($column_search as $column) {
            if ($request->search["value"]) {
              if ($i == 1) {
                $query->where($column, "LIKE", "%{$request->search["value"]}%");
              } else {
                $query->orWhere($column, "LIKE", "%{$request->search["value"]}%");
              }
            }
            $i++;
          }
        }
      });

    if (isset($request->order) && !empty($request->order)) {
      $results = $results->orderBy($column_order[$request->order["0"]["column"]], $request->order["0"]["dir"]);
    } else {
      $results = $results->orderBy(key($order), $order[key($order)]);
    }

    if (auth()->user()->role_id != 1) {
        $results->where("role_id", "!=", 1);
    }

    return $results;
  }

  public function get_list_paged(Request $request)
  {
    $results = $this->generate_query_get($request);
    if ($request->length != -1) {
      $limit = $results->offset($request->start)->limit($request->length);
      return $limit->get();
    }
  }

  public function get_list_count(Request $request)
  {
    return $this->generate_query_get($request)->count();
  }

  public function store(ProdukRequest $request)
  {
    try {
      $values = $request->validated();
      $produk = Produk::create($values);

      $response = \response_success_default("Berhasil menambahkan produk!", $produk->id, route("app.produk.show", $produk->id));
    } catch (\Exception $e) {
      ErrorService::error($e, "Gagal store produk!");
      $response = \response_errors_default();
    }

    return $response;
  }

  public function update(ProdukRequest $request, Produk $produk)
  {
    try {
      $values = $request->validated();
      $produk->update($values);

      $response = \response_success_default("Berhasil update data produk!", $produk->id, route("app.produk.show", $produk->id));
    } catch (\Exception $e) {
      ErrorService::error($e, "Gagal update produk!");
      $response = \response_errors_default();
    }

    return $response;
  }

  public function get_admin_online()
  {
    $query = SessionToken::query()
          ->select([
            "u.name", "session_tokens.active_time", "u.id",
            DB::raw("MAX(session_tokens.active_time) AS max_time")
          ])
          ->join("users AS u", "u.id", "session_tokens.user_id")
          ->where("is_login", 1)
          ->having("max_time", ">", DB::raw("(NOW() - INTERVAL 15 MINUTE)"))
          ->groupBy("session_tokens.user_id")
          ->get()
          ->map(function($item) {
            $to_time = strtotime(date("Y-m-d H:i:s"));
            $from_time = strtotime($item->max_time);
            $last_active = round(abs($to_time - $from_time) / 60);
            if ($last_active <= 0) {
              $last_active = "Active";
            } else {
              $last_active .= "m ago";
            }

            $item->last_active = $last_active;
            return $item;
          });

    return $query;
  }
}