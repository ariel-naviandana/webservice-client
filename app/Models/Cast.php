<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    use HasFactory;
    protected $table = 'cast';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'role',
        'character_name',
        'film_id',
    ];

    public function film()
    {
        return $this->belongsTo(Film::class, 'film_id');
    }
}
