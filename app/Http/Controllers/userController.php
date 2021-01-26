<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class userController extends Controller
{
        /**
     * 註冊
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  email  $email
     * @param  password  $password
     * @return \Illuminate\Http\Response
     */
    public function Register(Request $request)
    {


        $input = request()->all();
        $rules = [
             'email' => ['required','email:rfc,dns'],
             'password' => ['required', 'min:6', 'confirmed','max:20',
            'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,20}$/', ],
            'role' => ['required'],
             'nickname' => ['required'],
             'name' => ['required'],
            //密碼規則：最少6 最多20 最少一個大寫一個小寫
        ];
        // 驗證資料
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            // 資料驗證錯誤
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all()
                ],500);
        }
        try {
            $results = User::where("email",$request["email"])->count();
            if ($results == 0) {
                $results = User::count();
                $user = User::Create([
                    "email" => $request["email"],
                    "name" => $request["name"],
                    "uuid" => "U" . date("Y") . date("m") . date("d") . date("H") . date("i") . ($results + 1),
                    "password" => Hash::make($request["password"]),
                    "role" => $request["role"],
                    "name" => $request["name"],
                    "nickname" => $request["nickname"]
                    
                ]);
                return response()->json([
                      'success' => true,
                    'message' =>  $user
                ], 200);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => "已有人註冊過囉!"
                ],500);
            }

        } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ],500);

        }

    }

     /**
     * 登入
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  email  $email
     * @param  password  $password
     * @param  device_name  $device_name
     * @return \Illuminate\Http\Response
     */
    public function Login(Request $request)
    {

        $input = $request->only('email', 'password');
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];
        // 驗證資料
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            // 資料驗證錯誤
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all()
                ],500);
        }

        if (Auth::guard()->attempt($input)) {
            $user = Auth::user();
            $token = $user->createToken($user->name . '-' . now());
            return response()->json(['token' => $token->accessToken]);
        } else {
        
                    return response()->json([
                            'success' => false,
                            'message' => "帳號或密碼錯誤"
                        ],500);
        }


      
    }

        /**
     * 取得用戶資訊
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUser()
    {
        return response()->json([
            'success' => true,
            'user' => Auth::user(),
        ], 200);
    }
}
