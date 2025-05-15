<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    protected $table = 'film';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $keyType = 'int';

    protected $fillable = [
        'title',
        'synopsis',
        'genre',
        'director',
        'release_date',
        'poster_url',
    ];

    // public function casts()
    // {
    //     return $this->hasMany(Cast::class, 'film_id', 'id');
    // }
}
