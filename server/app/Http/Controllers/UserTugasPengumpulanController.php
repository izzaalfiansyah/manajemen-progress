<?php

namespace App\Http\Controllers;

use App\Models\UserTugasPengumpulan as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserTugasPengumpulanController extends Controller
{
    public function __construct()
    {
        @mkdir(public_path('/assets'));
        @mkdir(public_path('/assets/user'));
        @mkdir(public_path('/assets/user/tugas'));
        @mkdir(public_path('/assets/user/tugas/pengumpulan'));

        $this->filePath = public_path('/assets/user/tugas/pengumpulan/');
    }

    public function index(Request $req)
    {
        $builder = new Model;

        if ($userTugasId = $req->userTugasId) {
            $builder = $builder->where('userTugasId', $userTugasId);
        } else {
            return response()->json(['tugas tidak diketahui'], 400);
        }

        $data = $builder->get();

        return response()->json($data);
    }

    public function store(Request $req)
    {
        $schema = Validator::make($req->all(), Model::rules());

        if ($schema->fails()) {
            return response()->json($schema->errors()->all(), 400);
        }

        $data = $schema->validated();

        $fileName = $this->randomString() . '.pdf';
        $this->uploadBase64($req->file, $this->filePath . $fileName);
        $data['file'] = $fileName;

        $item = Model::create($data);

        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = Model::find($id);
        @unlink($this->filePath . $item->file);
        if ($item) $item->delete();

        return response()->json($item);
    }
}
