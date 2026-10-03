<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            Cache::forget('setting_'.$setting->key);
        });

        static::deleted(function ($setting) {
            Cache::forget('setting_'.$setting->key);
        });
    }

    public static function getValue($key, $default = null)
    {
        return Cache::rememberForever('setting_'.$key, function () use ($key, $default) {
            return self::where('key', $key)->value('value') ?? $default;
        });
    }
}
