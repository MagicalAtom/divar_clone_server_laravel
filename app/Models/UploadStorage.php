<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadStorage extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
        'advertising_id'
    ];
    public function advertise(){
        return $this->belongsTo(Advertising::class);
    }
}
