<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
class GoogleController extends Controller
{
    public function getApi()
    {       
        //first, you have to get the token, this is the endpoint https://accounts.google.com/o/oauth2/token
        //You need the credencials
        
        //then you can get the api information 
        $token = "ya29.a0AXooCgvalHEKV7bWDYe_h82EZGlz53faEgcPX_LtvDixMxPRpf_y-w__NMkKXKWz7Y86sV5RvrkilVqnaHmMV0DTsmkrcOFRMjWsSbaI7YQ5RanTMA8FRZHCd_zkjXvz7f8XWoBKHlS-6fQCRtINI8AulS9mVSerSAaCgYKAeoSAQ8SFQHGX2MiCAZidGjWuVpKvC2xqAiV_g0169";
        $url = "https://www.googleapis.com/books/v1/mylibrary/bookshelves";
        $response = Http::withToken($token)->get($url);
        $jsonData = $response->json(); 
        dd($jsonData);       
    }
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(): RedirectResponse
    {   
        try {
            // you get the user first
            $user = Socialite::driver('google')->stateless()->user();
            $existingUser = User::where('google_id', $user->id)->first();
            if ($existingUser) {
                auth()->login($existingUser, true);
            } else {
                $newUser = new User();
                $newUser->name = $user->name;
                $newUser->email = $user->email;
                $newUser->google_id = $user->id;
                $newUser->password = bcrypt(request(Str::random()));
                $newUser->save();
                auth()->login($newUser, true);
            }
            return redirect()->intended('/dashboard');
        } catch (\Throwable $th) {
            return $th;
        }
    }   
}
