<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category'
    ];

    public function children()
    {
    return $this->belongsToMany(Child::class, 'child_game');
    }

}
