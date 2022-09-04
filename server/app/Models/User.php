<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class User extends Model
{
    use HasFactory;

    public $table = 'user';

    public $fillable = [
        'username',
        'password',
        'nama',
        'role',
        'telepon',
        'alamat',
    ];

    public static function rules($id = null)
    {
        return [
            'username' => ['required', $id ? Rule::unique('user')->ignore($id) : Rule::unique('user'), 'min:5'],
            'password' => [$id ? 'nullable' : 'required', Password::min(8)],
            'nama' => 'required',
            'role' => 'required|in:1,2,3',
            'telepon' => 'required|numeric',
            'alamat' => 'nullable',
        ];
    }
}
