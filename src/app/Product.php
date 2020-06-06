<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'price',
        'description',
        'qty',
        'category'
    ];

    public static $rules = [
        'title' => 'required',
        'price' => 'required',
        'qty' => 'required|integer',
        'category' => 'required|in:shoes,shirts,bags,trousers,glasses'
    ];

    public function scopeSearch($query, $string)
    {
        if(!$string)
            return $query;
        return $query->where('category', 'Like', '%'.$string.'%')
                ->orderBy('id', 'DESC');
    }
}
