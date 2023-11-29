<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * 
     * setting value by key
     * @param: String|setting-key
     * @return: Object| setting value
     */
    public function get_key($key)
    {   
        $value = self::where('key',$key)->first();
        
        return $value;
            
    }
}
