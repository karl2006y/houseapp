<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;
    protected $fillable = [
            'classification_id',	//classification_id
            'name',//房屋物件名稱
            'medias_1_url',//媒體1位置
            'medias_1_type',//媒體1類型
            'medias_2_url',//媒體2位置
            'medias_2_type',//媒體2類型
            'medias_3_url',//媒體3位置
            'medias_3_type',//媒體3類型
            'medias_4_url',//媒體4位置
            'medias_4_type',//媒體4類型
            'medias_5_url',//媒體5位置
            'medias_5_type',//媒體5類型
            'medias_6_url',//媒體6位置
            'medias_6_type',//媒體6類型
            'medias_7_url',//媒體7位置
            'medias_7_type',//媒體7類型
            'medias_8_url',//媒體8位置
            'medias_8_type',//媒體8類型
            'price',//價格
            'note',//文字備註
            'description',//文字備註
    ];
    /**
     * 取得房子的類別
     */
    public function classification()
    {
        return $this->belongsTo('App\Models\Classification');
    }
}
