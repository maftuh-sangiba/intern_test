<?php namespace App\Services\Auth;

use App\Models\SessionToken;
use App\Models\User;
use App\Services\Cores\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService extends BaseService
{
  /**
   * Create a session token
   *
   * @param int $user_id
   */
  private function create_session_token($user_id)
  {
    do {
      $token = random_string(20);
      $check_exists = SessionToken::query()->where("session_token", $token)->exists();
    } while ($check_exists);

    SessionToken::create([
      "user_id" => $user_id,
      "session_token" => $token,
      "active_time" => date("Y-m-d H:i:s"),
      "expire_time" => date("Y-m-d H:i:s", strtotime("+15minute")),
      "is_login" => 1
    ]);

    session(["session_token" => $token]);
  }

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

        $this->create_session_token($get_user->id);
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
