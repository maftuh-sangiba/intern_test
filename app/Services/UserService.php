<?php namespace App\Services;

use App\Models\User;
use App\Services\Cores\BaseService;
use App\Services\Cores\ErrorService;
use App\Validations\UserValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
  /**
   * Generate query index page
   *
   * @param Request $request
   */
  private function generate_query_get(Request $request)
  {
    $column_search = ["users.name", "users.email", "r.role_name"];
    $column_order = [NULL, "users.name", "users.email", "r.role_name"];
    $order = ["users.id" => "DESC"];

    $results = User::query()
      ->join("roles AS r", "r.id", "users.role_id")
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

  /**
   * Store new user
   *
   * @param Request $request
   */
  public function store(Request $request)
  {
    try {
      $values = UserValidation::validated();
      $user = User::create($values);

      $response = \response_success_default("Berhasil menambahkan user!", $user->id, route("app.users.show", $user->id));
    } catch (\Exception $e) {
      ErrorService::error($e, "Gagal store user!");
      $response = \response_errors_default();
    }

    return $response;
  }

  /**
   * Update new user
   *
   * @param Request $request
   * @param User $user
   */
  public function update(Request $request, User $user)
  {
    try {
      $user_id = $user->id;
      $values = UserValidation::validated();
      if ($values["password"]) {
        $values["password"] = Hash::make($values["password"]);
      } else {
        unset($values["password"]);
      }

      // dd($values);
      $user->update($values);

      $response = \response_success_default("Berhasil update data user!", $user_id, route("app.users.show", $user->id));
    } catch (\Exception $e) {
      ErrorService::error($e, "Gagal update user!");
      $response = \response_errors_default();
    }

    return $response;
  }
}
