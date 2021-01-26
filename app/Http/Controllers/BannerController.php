<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = 'App\Models\Banner'::where('status',1)->get();
        foreach ($banners as $banner) {
            $banner->pic_url =  url($banner->pic_url);
            # code...
        }
        return response()->json([
            'success' => true,
            'message' =>  $banners
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 'pic_url',
        // 'goto_url',
        // 'status',
        $input = request()->all();
        $rules = [
            'picfile' => ['required','image'],
            'goto_url' => ['active_url'],
        ];
        // 錯的回饋
        $messages = [
            "picfile.required" => "圖片為必填",
            "picfile.image" => "圖片上傳格式錯誤",
            "goto_url.active_url" => "連結錯誤",
        ];
        //驗證是否正確
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // 資料驗證錯誤
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all()
                ],500);
        }

        // 有上傳圖片
        $photo = $input['picfile'];
        // 檔案副檔名
        $file_extension = $photo->getClientOriginalExtension();
        // 產生自訂隨機檔案名稱
        $file_name = uniqid() . '.' . $file_extension;

        // 檔案相對路徑
        $file_relative_path = 'images/banner/' . $file_name;
        // 檔案存放目錄為對外公開 public 目錄下的相對位置
        $file_path = public_path($file_relative_path);
        // 裁切圖片
        // $image = Image::make($photo)->fit(450, 300)->save($file_path);
        $image = Image::make($photo)->save($file_path);
        // 設定圖片檔案相對位置
        $newData['pic_url'] = $file_relative_path;
        if(isset($input['goto_url'])){
             $newData['goto_url']  = $input['goto_url'];
        }
        return response()->json([
                'success' => true,
                'data' => 'App\Models\Banner'::Create($newData),
            ], 200);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $banner = 'App\Models\Banner'::where('id',$id)->first();
        if($banner){
            $banner->pic_url =  url($banner->pic_url);
            return response()->json([
                'success' => true,
                'message' =>  $banner
            ], 200);
        }
   return response()->json([
                'success' => false,
                'message' =>  "無資料"
            ], 500);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'picfile' => ['image'],
            'goto_url' => ['active_url'],
        ];
          // 'pic_url',
        // 'goto_url',
        // 'status',
        $input = request()->all();
        $rules = [
            'picfile' => ['image'],
            'goto_url' => ['active_url'],
        ];
        // 錯的回饋
        $messages = [
            "picfile.image" => "圖片上傳格式錯誤",
            "goto_url.active_url" => "連結錯誤",
        ];
        //驗證是否正確
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // 資料驗證錯誤
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all()
                ],500);
        }
        $banner = 'App\Models\Banner'::where('id',$id)->first();
        if($banner){
            $newData = [];
            if (isset($input['goto_url'])) {$newData['goto_url'] = $input['goto_url'];};
            if (isset($input['status'])) {$newData['status'] = $input['status'];};
            if(isset($input['picfile'])){
                $s = $banner->pic_url;
                    if (File::exists($s)) {
                        File::delete($s);
                    }
                // 有上傳圖片
                $photo = $input['picfile'];
                // 檔案副檔名
                $file_extension = $photo->getClientOriginalExtension();
                // 產生自訂隨機檔案名稱
                $file_name = uniqid() . '.' . $file_extension;
                // 檔案相對路徑
                $file_relative_path = 'images/banner/' . $file_name;
                // 檔案存放目錄為對外公開 public 目錄下的相對位置
                $file_path = public_path($file_relative_path);
                // 裁切圖片
                // $image = Image::make($photo)->fit(450, 300)->save($file_path);
                $image = Image::make($photo)->save($file_path);
                // 設定圖片檔案相對位置
                $newData['pic_url'] = $file_relative_path;
            }
            'App\Models\Banner'::where('id', $id)->update($newData);
            return response()->json([
                        'success' => true,
                        'data' => 'App\Models\Banner'::where('id', $id)->first(),
                    ], 200);
        }else{
             return response()->json([
                    'success' => false,
                    'data' => "無資料",
                ], 500);
        }
        
         
           
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner = 'App\Models\Banner'::where('id',$id)->first();
        if($banner){
            if ($banner->pic_url) {
                $s = $banner->pic_url;
                if (File::exists($s)) {
                    File::delete($s);
                }
            }
            'App\Models\Banner'::where('id', $id)->delete();
            return response()->json([
                'success' => true,
                'message' =>  "刪除成功"
            ], 200);
        }
        return response()->json([
                'success' => false,
                'message' =>  "無資料"
            ], 500);    
    }
}
