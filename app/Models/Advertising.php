<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UploadStorage;

class Advertising extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
        'price',
        'description',
        'location',
        'map',
        'user_id',
        'category_id',
        'city',
        'phone'
    ];

public function category(){
    return $this->belongsTo(Category::class);
}

public function user(){
    return $this->belongsTo(User::class);
}


public function uploadStorages(){
    return $this->hasMany(UploadStorage::class);
}



}
