<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Classification extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['classification_name'];
     /**
     * 取得類別底下所有房子
     */
    public function houses()
    {
        return $this->hasMany('App\Models\House');
    }

}
