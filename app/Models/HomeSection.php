<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeSection extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
        'settings' => 'array',
    ];

    public function getSetting($key, $default = null)
    {
        $settings = is_array($this->settings) ? $this->settings : [];

        return $settings[$key] ?? $default;
    }
}
