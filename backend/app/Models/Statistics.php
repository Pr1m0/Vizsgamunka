<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id', 
        'game_id',
        'score',
        'play_time'
    ];

    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id'); 
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
