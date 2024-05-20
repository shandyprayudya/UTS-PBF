<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','price','image','category_id','expired_at','modified_by'];
    public function category() {
        return $this->belongsTo(Categories::class);
    }
}
