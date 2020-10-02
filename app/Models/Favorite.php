<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    use RecordActivity;

    protected $guarded = [];

    public function favorited()
    {
        return $this->morphTo();
    }
}
