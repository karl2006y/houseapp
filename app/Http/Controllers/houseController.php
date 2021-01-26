<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class houseController extends Controller
{
    public static function upload($input, $file_name, $save_path)
    {
        // 有上傳檔案
        if (!isset($input[$file_name])) {
            return false;
        }

        $file = $input[$file_name];
        // 檔案副檔名
        $file_extension = $file->getClientOriginalExtension();
        // 產生自訂隨機檔案名稱
        $file_name = uniqid() . '.' . $file_extension;
        // 存檔
        $file->move($save_path, $file_name);
        return $save_path . $file_name;
    }
    public static function delete($file_path)
    {
        if (File::exists($file_path)) {
            File::delete($file_path);
        }
    }
    /////////////////////

    /**
     * 類別總覽
     *
     * @return \Illuminate\Http\Response
     */
    public function index_classification()
    {
        return response()->json([
            'success' => true,
            'message' => ["成功取得類別總覽"],
            'data' => Classification::where('status', 1)->get(),
        ], 200);
    }
    /**
     * 取得所有的房屋資料
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => ["成功取得開放的房屋資料"],
            'data' => House::
                where('status', 1)->
                whereHas('classification')->
                with('classification')->
                paginate(10),
        ], 200);
    }

    /**
     * 查看單一房屋資訊
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $house_data = House::where('id', $id)->with('classification')->first();
        // dd(House::where('id',$id)->with('classification')->get());
        // $house_data['classification'] = $house_data->classification();
        if ($house_data && $house_data['status'] != 0) {
            // if(isset($house_data['classification'])) $house_data['classification'] = $house_data['classification']->classification_name;
            if (isset($house_data['medias_1_url'])) {
                $house_data['medias_1_url'] = url($house_data['medias_1_url']);
            }

            if (isset($house_data['medias_2_url'])) {
                $house_data['medias_2_url'] = url($house_data['medias_2_url']);
            }

            if (isset($house_data['medias_3_url'])) {
                $house_data['medias_3_url'] = url($house_data['medias_3_url']);
            }

            if (isset($house_data['medias_4_url'])) {
                $house_data['medias_4_url'] = url($house_data['medias_4_url']);
            }

            if (isset($house_data['medias_5_url'])) {
                $house_data['medias_5_url'] = url($house_data['medias_5_url']);
            }

            if (isset($house_data['medias_6_url'])) {
                $house_data['medias_6_url'] = url($house_data['medias_6_url']);
            }

            if (isset($house_data['medias_7_url'])) {
                $house_data['medias_7_url'] = url($house_data['medias_7_url']);
            }

            if (isset($house_data['medias_8_url'])) {
                $house_data['medias_8_url'] = url($house_data['medias_8_url']);
            }

            return response()->json([
                'success' => true,
                'message' => ["成功取得" . $house_data['name'] . "的資料"],
                'data' => $house_data,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => ["無資料或正在審核"],
            ], 500);
        }
    }

    /**
     * 類別下的房屋
     *
     * @return \Illuminate\Http\Response
     */
    public function index_by_classification($id)
    {
        $datas = House::where('status', 1)->where('classification_id', $id);
        if ($datas->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => ["成功取得資料"],
                'data' =>
                $datas->
                    whereHas('classification')->
                    with('classification')->
                    paginate(10),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => ["無資料"],
            ], 500);
        }
    }

    //________________admin___________________
    /**
     * 類別下的房屋 admin
     *
     * @return \Illuminate\Http\Response
     */
    public function index_by_classification_admin($id)
    {
        $datas = House::where('classification_id', $id);
        if ($datas->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => ["成功取得資料"],
                'data' =>
                $datas->
                    whereHas('classification')->
                    with('classification')->
                    paginate(10),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => ["無資料"],
            ], 500);
        }
    }
    /**
     * 類別總覽 admin
     *
     * @return \Illuminate\Http\Response
     */
    public function index_classification_admin()
    {
        return response()->json([
            'success' => true,
            'message' => ["成功取得類別總覽"],
            'data' => Classification::all(),
        ], 200);
    }

    /**
     * 新增類別
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_classification(Request $request)
    {
        $input = request()->all();
        $rules = ['name' => ['required']];
        // 錯的回饋
        $messages = [
            "name.required" => "類別名稱為必填",
        ];
        // 驗證資料
        $validator = Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            // 資料驗證錯誤
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ], 500);
        }
        // dd(request()->name);
        return response()->json([
            'success' => true,
            'message' => ["成功新增類別"],
            'data' => Classification::Create(['classification_name' => request()->name]),
        ], 200);
    }
    /**
     * 更新類別
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_classification(Request $request, $id)
    {
        $input = request()->all();
        if (Classification::where('id', $id)->first()) {
            $newData = [];
            if (isset($input['name'])) {
                $newData['classification_name'] = $input['name'];
            }

            if (isset($input['status'])) {
                $newData['status'] = $input['status'];
            }

            Classification::where('id', $id)->update($newData);
            return response()->json([
                'success' => true,
                'message' => ["成功更新類別"],
                'data' => Classification::where('id', $id)->first(),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => ["無資料"],
            ], 500);
        }

    }

    /**
     * 刪除類別
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_classification($id)
    {
        if (Classification::where('id', $id)->first()) {
            Classification::where('id', $id)->delete();
            return response()->json([
                'success' => true,
                'message' => ["刪除成功"],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => ["無資料"],
            ], 500);
        }

    }
    /**
     * 取得所有的房屋資料 admin
     *
     * @return \Illuminate\Http\Response
     */
    public function index_admin()
    {
        return response()->json([
            'success' => true,
            'message' => ["成功取得所有的房屋資料"],
            'data' => House::
                whereHas('classification')->
                with('classification')->
                paginate(10),
        ], 200);

    }

    /**
     * 查看單一房屋資訊 admin
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_admin($id)
    {

        $house_data = House::where('id', $id)->with('classification')->first();
        // dd(House::where('id',$id)->with('classification')->get());
        // $house_data['classification'] = $house_data->classification();
        if ($house_data) {
            // if(isset($house_data['classification'])) $house_data['classification'] = $house_data['classification']->classification_name;
            if (isset($house_data['medias_1_url'])) {
                $house_data['medias_1_url'] = url($house_data['medias_1_url']);
            }

            if (isset($house_data['medias_2_url'])) {
                $house_data['medias_2_url'] = url($house_data['medias_2_url']);
            }

            if (isset($house_data['medias_3_url'])) {
                $house_data['medias_3_url'] = url($house_data['medias_3_url']);
            }

            if (isset($house_data['medias_4_url'])) {
                $house_data['medias_4_url'] = url($house_data['medias_4_url']);
            }

            if (isset($house_data['medias_5_url'])) {
                $house_data['medias_5_url'] = url($house_data['medias_5_url']);
            }

            if (isset($house_data['medias_6_url'])) {
                $house_data['medias_6_url'] = url($house_data['medias_6_url']);
            }

            if (isset($house_data['medias_7_url'])) {
                $house_data['medias_7_url'] = url($house_data['medias_7_url']);
            }

            if (isset($house_data['medias_8_url'])) {
                $house_data['medias_8_url'] = url($house_data['medias_8_url']);
            }

            return response()->json([
                'success' => true,
                'message' => $house_data,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "無資料",
            ], 500);
        }
    }
    /**
     * 新增房屋資料
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = request()->all();
        $rules = [
            'classification_id' => ['required'],
            'name' => ['required'],
            'medias_1_file' => ['required', 'file'],
            'medias_1_type' => ['required'],
            'price' => ['required'],
            'medias_2_file' => ['file'],
            'medias_3_file' => ['file'],
            'medias_4_file' => ['file'],
            'medias_5_file' => ['file'],
            'medias_6_file' => ['file'],
            'medias_7_file' => ['file'],
            'medias_8_file' => ['file'],

        ];
        // 錯的回饋
        $messages = [
            "classification_id.required" => "房屋類別為必填",
            "name.required" => "房屋名稱為必填",
            "medias_1_file.required" => "房屋主要媒體為必填",
            "medias_1_type.required" => "房屋主要媒體類別為必填",
            "price.required" => "房屋價格為必填",
            "medias_1_file.file" => "房屋主媒體需為圖片或影片",
            "medias_2_file.file" => "房屋媒體(2)需為圖片或影片",
            "medias_3_file.file" => "房屋媒體(3)需為圖片或影片",
            "medias_4_file.file" => "房屋媒體(4)需為圖片或影片",
            "medias_5_file.file" => "房屋媒體(5)需為圖片或影片",
            "medias_6_file.file" => "房屋媒體(6)需為圖片或影片",
            "medias_7_file.file" => "房屋媒體(7)需為圖片或影片",
            "medias_8_file.file" => "房屋媒體(8)需為圖片或影片",
        ];
        // 驗證資料
        $validator = Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            // 資料驗證錯誤
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ], 500);
        }
        $newData = [];
        if (isset($input['classification_id'])) {
            $newData['classification_id'] = $input['classification_id'];
        }

        if (isset($input['name'])) {
            $newData['name'] = $input['name'];
        }

        if (isset($input['price'])) {
            $newData['price'] = $input['price'];
        }

        if (isset($input['note'])) {
            $newData['note'] = $input['note'];
        }

        if (isset($input['description'])) {
            $newData['description'] = $input['description'];
        }

        if (isset($input['medias_1_file'])) {
            $newData['medias_1_url'] = $this->upload($input, 'medias_1_file', 'media/house/');
        }

        if (isset($input['medias_1_type'])) {
            $newData['medias_1_type'] = $input['medias_1_type'];
        }

        if (isset($input['medias_2_file'])) {
            $newData['medias_2_url'] = $this->upload($input, 'medias_2_file', 'media/house/');
        }

        if (isset($input['medias_2_type'])) {
            $newData['medias_2_type'] = $input['medias_2_type'];
        }

        if (isset($input['medias_3_file'])) {
            $newData['medias_3_url'] = $this->upload($input, 'medias_3_file', 'media/house/');
        }

        if (isset($input['medias_3_type'])) {
            $newData['medias_3_type'] = $input['medias_3_type'];
        }

        if (isset($input['medias_4_file'])) {
            $newData['medias_4_url'] = $this->upload($input, 'medias_4_file', 'media/house/');
        }

        if (isset($input['medias_4_type'])) {
            $newData['medias_4_type'] = $input['medias_4_type'];
        }

        if (isset($input['medias_5_file'])) {
            $newData['medias_5_url'] = $this->upload($input, 'medias_5_file', 'media/house/');
        }

        if (isset($input['medias_5_type'])) {
            $newData['medias_5_type'] = $input['medias_5_type'];
        }

        if (isset($input['medias_6_file'])) {
            $newData['medias_6_url'] = $this->upload($input, 'medias_6_file', 'media/house/');
        }

        if (isset($input['medias_6_type'])) {
            $newData['medias_6_type'] = $input['medias_6_type'];
        }

        if (isset($input['medias_7_file'])) {
            $newData['medias_7_url'] = $this->upload($input, 'medias_7_file', 'media/house/');
        }

        if (isset($input['medias_7_type'])) {
            $newData['medias_7_type'] = $input['medias_7_type'];
        }

        if (isset($input['medias_8_file'])) {
            $newData['medias_8_url'] = $this->upload($input, 'medias_8_file', 'media/house/');
        }

        if (isset($input['medias_8_type'])) {
            $newData['medias_8_type'] = $input['medias_8_type'];
        }

        // dd($newData);
        return response()->json([
            'success' => true,
            'data' => House::Create($newData),
        ], 200);

    }

    /**
     * 更新房屋資訊
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = request()->all();
        $house_data = House::where('id', $id)->first();
        if ($house_data) {
            $newData = [];
            if (isset($input['classification_id'])) {
                $newData['classification_id'] = $input['classification_id'];
            }

            if (isset($input['status'])) {
                $newData['status'] = $input['status'];
            }

            if (isset($input['name'])) {
                $newData['name'] = $input['name'];
            }

            if (isset($input['price'])) {
                $newData['price'] = $input['price'];
            }

            if (isset($input['note'])) {
                $newData['note'] = $input['note'];
            }

            if (isset($input['description'])) {
                $newData['description'] = $input['description'];
            }

            if (isset($input['medias_1_type'])) {
                $newData['medias_1_type'] = $input['medias_1_type'];
            }

            if (isset($input['medias_2_type'])) {
                $newData['medias_2_type'] = $input['medias_2_type'];
            }

            if (isset($input['medias_3_type'])) {
                $newData['medias_3_type'] = $input['medias_3_type'];
            }

            if (isset($input['medias_4_type'])) {
                $newData['medias_4_type'] = $input['medias_4_type'];
            }

            if (isset($input['medias_5_type'])) {
                $newData['medias_5_type'] = $input['medias_5_type'];
            }

            if (isset($input['medias_6_type'])) {
                $newData['medias_6_type'] = $input['medias_6_type'];
            }

            if (isset($input['medias_7_type'])) {
                $newData['medias_7_type'] = $input['medias_7_type'];
            }

            if (isset($input['medias_8_type'])) {
                $newData['medias_8_type'] = $input['medias_8_type'];
            }

            if (isset($input['medias_1_file'])) {
                $this->delete($house_data['medias_1_url']);
                $newData['medias_1_url'] = $this->upload($input, 'medias_1_file', 'media/house/');
            }
            if (isset($input['medias_2_file'])) {
                $this->delete($house_data['medias_2_url']);
                $newData['medias_2_url'] = $this->upload($input, 'medias_2_file', 'media/house/');
            }
            if (isset($input['medias_3_file'])) {
                $this->delete($house_data['medias_3_url']);
                $newData['medias_3_url'] = $this->upload($input, 'medias_3_file', 'media/house/');
            }
            if (isset($input['medias_4_file'])) {
                $this->delete($house_data['medias_4_url']);
                $newData['medias_4_url'] = $this->upload($input, 'medias_4_file', 'media/house/');
            }
            if (isset($input['medias_5_file'])) {
                $this->delete($house_data['medias_5_url']);
                $newData['medias_5_url'] = $this->upload($input, 'medias_5_file', 'media/house/');
            }
            if (isset($input['medias_6_file'])) {
                $this->delete($house_data['medias_6_url']);
                $newData['medias_6_url'] = $this->upload($input, 'medias_6_file', 'media/house/');
            }
            if (isset($input['medias_7_file'])) {
                $this->delete($house_data['medias_7_url']);
                $newData['medias_7_url'] = $this->upload($input, 'medias_7_file', 'media/house/');
            }
            if (isset($input['medias_8_file'])) {
                $this->delete($house_data['medias_8_url']);
                $newData['medias_8_url'] = $this->upload($input, 'medias_8_file', 'media/house/');
            }
            House::where('id', $id)->update($newData);
            return response()->json([
                'success' => true,
                'data' => House::where('id', $id)->first(),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => "無資料",
            ], 500);
        }

    }

    /**
     * 刪除房屋資訊
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (House::where('id', $id)->first()) {
            House::where('id', $id)->delete();
            return response()->json([
                'success' => true,
                'message' => "刪除成功",
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "無資料",
            ], 500);
        }
    }

}
