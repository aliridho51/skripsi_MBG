<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    protected $table = 'distribusi';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }

    public function pengembalianOmpreng()
    {
        return $this->hasOne(PengembalianOmpreng::class);
    }

    public function kritikSaran()
    {
        return $this->hasMany(KritikSaran::class);
    }
}
