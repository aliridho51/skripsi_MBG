<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengembalianOmpreng extends Model
{
    protected $table = 'pengembalian_ompreng';
    protected $guarded = ['id'];

    public function distribusi()
    {
        return $this->belongsTo(Distribusi::class);
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}
