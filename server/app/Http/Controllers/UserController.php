<?php

namespace App\Http\Controllers;

use App\Models\User as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $req)
    {
        $builder = new Model;

        if ($search = $req->q) {
            $builder = $builder->where(function ($query) use ($search) {
                return $query->where('username', 'like', "%$search%")
                    ->orWhere('nama', 'like', "%$search%")
                    ->orWhere('telepon', 'like', "%$search%");
            });
        }

        $totalCount = $builder->count();

        if ($limit = $req->_limit) {
            $page = $req->_page ?: 1;
            $builder = $builder->limit($limit)->skip(($page - 1) * $limit);
        }

        $builder = $builder->orderBy('nama');
        $data = $builder->get();

        return response()->json($data)->header('X-Total-Count', $totalCount);
    }

    public function show($id)
    {
        $item = Model::find($id);

        return response()->json($item);
    }

    public function store(Request $req)
    {
        $schema = Validator::make($req->all(), Model::rules());

        if ($schema->fails()) {
            return response()->json($schema->errors()->all(), 400);
        }

        $data = $schema->validated();
        $data['password'] = Hash::make($req->password);
        $item = Model::create($data);

        return response()->json($item);
    }

    public function update(Request $req, $id)
    {
        $schema = Validator::make($req->all(), Model::rules($id));

        if ($schema->fails()) {
            return response()->json($schema->errors()->all(), 400);
        }

        $data = $schema->validated();
        if ($req->password) {
            $data['password'] = Hash::make($req->password);
        } else {
            unset($data['password']);
        }
        $item = Model::find($id);
        if ($item) $item->update($data);

        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = Model::find($id);
        if ($item) $item->delete();

        return response()->json($item);
    }

    public function login(Request $req)
    {
        $schema = Validator::make($req->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($schema->fails()) {
            return response()->json($schema->errors()->all());
        }

        $item = Model::where('username', $req->username)->first();

        if ($item) {
            if (Hash::check($req->password, $item->password)) {
                $item->app_key = env('APP_KEY');
                return response()->json($item);
            } else {
                return response()->json(['password salah'], 400);
            }
        } else {
            return response()->json(['username tidak ditemukan'], 400);
        }
    }
}
