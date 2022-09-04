<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserTugas extends Model
{
    use HasFactory;

    public $table = 'user_tugas';

    public $fillable = [
        'jenis',
        'userId',
        'deadline',
        'status'
    ];

    public $with = [
        'user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }


    public $appends = [
        'totalPengumpulan',
    ];

    public function getTotalPengumpulanAttribute()
    {
        $total = DB::table('user_tugas_pengumpulan')->where('userTugasId', $this->id)->count();
        return (int) $total;
    }

    public static function rules()
    {
        return [
            'jenis' => 'required',
            'userId' => 'required|integer',
            'deadline' => 'required',
            'status' => 'required|in:1,0',
        ];
    }
}
