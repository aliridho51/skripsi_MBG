<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    // INI KUNCI UTAMANYA: Paksa Laravel pakai nama 'sekolah', bukan 'sekolahs'
    protected $table = 'sekolah';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function distribusi()
    {
        return $this->hasMany(Distribusi::class);
    }
}