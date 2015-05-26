<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Define Comment model.
 */
class Comment extends Model
{
    protected $fillable = ['slug', 'comment', 'name', 'email', 'ip'];
}
