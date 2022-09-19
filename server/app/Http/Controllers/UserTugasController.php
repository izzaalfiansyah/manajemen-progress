<?php

namespace App\Http\Controllers;

use App\Models\UserTugas as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserTugasController extends Controller
{
    public function index(Request $req)
    {
        $builder = new Model;

        if ($userId = $req->userId) {
            $builder = $builder->where('userId', $userId);
        }

        if ($status = $req->status) {
            $builder = $builder->where('status', $status);
        }

        if ($search = $req->q) {
            $builder = $builder->where(function ($query) use ($search) {
                return $query->where('jenis', 'like', "%$search%")
                    ->orWhere('deadline', 'like', "%$search%");
            });
        }

        $totalCount = $builder->count();

        if ($limit = $req->_limit) {
            $page = $req->_page ?: 1;
            $builder = $builder->limit($limit)->skip(($page - 1) * $limit);
        }

        $builder = $builder->orderBy('status');
        $builder = $builder->orderBy('created_at', 'desc');
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
}
