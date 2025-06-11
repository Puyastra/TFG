<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model
{
    use HasFactory;
    public $timeStamp = false;
    protected $fillable = [
        'name'
    ];
    public function historia()
    {
        return $this->belongsToMany(Historia::class);
    }
}
