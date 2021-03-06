<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Http;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // $request->authenticate();

        // $request->session()->regenerate();

        // return redirect()->intended(RouteServiceProvider::HOME);

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $response = Http::withHeaders([
            'Accept' => 'application/json'
        ])->post('http://127.0.0.1:8000/v1/login',[
            'email' => $request->email,
            'password' => $request->password
        ]);

        if($response->status()  == 404){
            return back()->withErrors('These credentails do not matchour records.');
        }

        $service = $response->json();

        // $user = User::firstOrcreate([
        $user = User::updateOrcreate([
            'email' => $request->email
        ], $service['data']);

        if(!$user->accessToken()){
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->post('http://127.0.0.1:8000/oauth/token',[
                'grant_type' => 'password',
                'client_id' => '946aa1b7-cbb9-4f41-9a2f-b573896ea879',
                'client_secret' => 'prqN01SWBPRbrHmQp2vXcFS8YgK2IW86quKvFS63',
                'username' => $request->email,
                'password' => $request->password
            ]);

            $access_token = $response->json();


            $user->accessToken()->create([
                'service_id' => $service['data']['id'],
                'access_token' => $access_token['access_token'],
                'refresh_token' => $access_token['refresh_token'],
                'expires_at' => now()->addSecond($access_token['expires_in'])
            ]);
        }

        Auth::login($user, $request->remember);

        return redirect()->intended(RouteServiceProvider::HOME);
        // dd($access_token);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
