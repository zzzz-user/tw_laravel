<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Twitterの認証ページヘユーザーをリダイレクト
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }

    /**
     * 返却
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(User $userModel)
    {
        $user = Socialite::driver('twitter')->user();

        // 登録済みであるか
        $u = $userModel->where('tw_id', (string)$user->getId())->first();

        if (is_null($u)) {
            // icon
            $icon_file_name = $user->getId().substr($user->getAvatar(), strrpos($user->getAvatar(), '.'));

            // 新規登録
            $_id = $userModel->insertGetId([
                'name' => $user->getName(),
                'tw_id' => $user->getId(),
                'icon' => $icon_file_name,
                'last_login' => now(),
                ]);

            //画像を保存
            $img = file_get_contents($user->getAvatar());
            Storage::put('public\\icon\\'.$icon_file_name, $img);
        } else {
            // 更新
            $u->last_login = now();
            $u->save();

            $_id = $u->id;
        }

        // 認証
        Auth::loginUsingId($_id);

        return redirect('/home');
    }
}
