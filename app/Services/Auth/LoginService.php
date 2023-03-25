<?php namespace App\Services\Auth;

use App\Models\User;
use App\Services\Cores\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService extends BaseService
{
  /**
   * Store login
   * 
   * @param Request $request
   */
  public function login(Request $request)
  {
    $response = \create_response();

    $get_user = User::where(function($query) use ($request) {
      $query->where("email", $request->email)->orWhere("username", $request->email);
    })
    ->whereNotNull("username")
    ->first();

    if ($get_user) {
      if (Hash::check($request->password, $get_user->password)) {
        $remember = $request->has("remember_me");
        Auth::loginUsingId($get_user->id, $remember);

        $response->status = TRUE;
        $response->status_code = 200;
        $response->message = "Success!";
        $response->next_url = \route("app.dashboard");
      } else {
        $response->message = "Password salah!";
        $response->status_code = 403;
      }
    } else {
      $response->message = "User tidak ditemukan!";
      $response->status_code = 403;
    }

    return $response;
  }
}