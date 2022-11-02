<?php

namespace App\Http\Controllers;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Crypt;
class AuthController extends Controller
{

    use ResponseTrait;
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','refreshtoken','getUserInfo']]);
    }

    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 200);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rToken =auth()->user()->id.'/'. date('Y-m-d H:i:s');

        //Saat ini refreshToken menggunakan UserID + / +Tanggal Hit (Y-m-d H:i:s)
        //lalu di encrypt menggunakan enkripsi bawaan laravel..
        //masih sangat simple bisa dikembangkan lebih secure lagi
        $refreshToken =Crypt::encryptString($rToken);
        return response()->json([
                'accessToken' => $token,
                'refreshToken' => $refreshToken
            ]);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json([ 'response_message' => 'error', 'response_code' => 200,'error' => $error]);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'response_message' => 'User successfully registered', 'response_code' => 201]);
    }

    public function logout() {
        auth()->logout();
        return response()->json(['response_message' => 'User successfully signed out']);
    }

    public function refreshtoken (Request $request) {


        //Saat ini method refreshToken menggunakan UserID + / +Tanggal Hit (Y-m-d H:i:s)
        //lalu di encrypt menggunakan enkripsi bawaan laravel..
        //masih sangat simple bisa dikembangkan lebih secure lagi

        //Biasanya dalam mengerjakan system menggunakan JWT method RefreshToken ini saya sisipkan ditiap Request
        //tentunay dengan mengecek dlu pakah token nya sudah expired atau belum. jika token blum expired maka lanjutkan dengan token lama
        // jika saat request tokennya expired maka akan direfresh sebelum memproses selanjutnya..
        //karean bagi saya methode ini merampingkan ENDPOINT yg dibuat. disisi client (Mobile Apss/lainya) tidak perlu hit endpoint RefreshToken



        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 200);
        }

        //decrypt dan explode field token (refresToken) dan ambil idUsernya agar bisa dibuatkan tokenBaru by UserId
        $rId =explode("/",Crypt::decryptString($request->token));
        $ids =$rId[0];
        $token = auth()->tokenById($ids);

        //buat Ulang refeshTokennya berdarakan userID dan creation datenya
        $rToken =$ids.'/'. date('Y-m-d H:i:s');
        $refreshToken =Crypt::encryptString($rToken);

        return response()->json([
            'accessToken' => $token,
            'refreshToken' => $refreshToken
        ]);

    }


}
