<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'sku_code', 'category_id', 'price', 'color_code', 'is_color_matching',
    ];

    protected $appends = ['attachments'];

    public function getAttachmentsAttribute(){
       return Attachment::where('attachmentresource_type', 'product')->where('attachmentresource_id', $this->id)->orderBy('created_at','desc')->get();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
}
