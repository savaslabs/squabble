<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Define Comment model.
 */
class Comment extends Model
{
    protected $fillable = ['slug', 'comment', 'name', 'email', 'ip', 'token', 'savasian'];

    protected $hidden = ['token', 'email', 'ip'];

    public function getCommentAttribute($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function getSlugAttribute($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function getNameAttribute($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function getEmailAttribute($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function getIpAttribute($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
