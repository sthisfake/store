<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeCategoryTile extends Model
{
    protected $fillable = [
        'category_id',
        'image_path',
        'background_color',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function textColor(): string
    {
        $hex = ltrim($this->background_color, '#');

        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));

        $luminance = ((0.299 * $red)+ (0.587 * $green)+ (0.114 * $blue)) / 255;

        return $luminance > 0.6? '#0f172a': '#ffffff';
    }
}