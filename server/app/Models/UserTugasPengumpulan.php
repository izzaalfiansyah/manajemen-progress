<?php

namespace App\Models;

use App\Rules\Base64;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTugasPengumpulan extends Model
{
    use HasFactory;

    public $table = 'user_tugas_pengumpulan';

    public $fillable = [
        'userTugasId',
        'file',
    ];

    public $appends = [
        'fileUrl',
    ];

    public function getFileUrlAttribute()
    {
        return asset('/assets/user/tugas/pengumpulan/' . $this->file);
    }

    public static function rules()
    {
        return [
            'userTugasId' => 'required|integer',
            'file' => ['required', new Base64(), function ($attr, $val, $fail) {
                if (!str_contains($val, 'application/pdf')) {
                    return $fail("The $attr field must be pdf.");
                }
            }],
        ];
    }
}
