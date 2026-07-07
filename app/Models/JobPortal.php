<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class JobPortal extends Model
{
    protected $fillable = [
        'name', 'description', 'url', 'category', 'specialty', 'icon_color',
        'featured', 'sort_order', 'auth_type', 'email', 'password', 'api_key',
        'additional_data', 'last_login', 'active'
    ];

    protected $casts = [
        'additional_data' => 'array',
        'last_login' => 'datetime',
        'active' => 'boolean',
        'featured' => 'boolean',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Encrypt password when setting
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Crypt::encryptString($value);
        }
    }

    // Decrypt password when getting
    public function getPasswordAttribute($value)
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    // Same for API key
    public function setApiKeyAttribute($value)
    {
        if ($value) {
            $this->attributes['api_key'] = Crypt::encryptString($value);
        }
    }

    public function getApiKeyAttribute($value)
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }
}
