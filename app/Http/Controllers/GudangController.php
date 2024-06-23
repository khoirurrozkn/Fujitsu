<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsertGudangRequest;
use App\Models\Gudang;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GudangController extends Controller
{
    public function create(InsertGudangRequest $insertGudangRequest){
        $request = $insertGudangRequest->validated();

        $findGudang = Gudang::where('kode_gudang', $request['kode_gudang'])
            ->orWhere('nama_gudang', $request['nama_gudang'])
            ->exists();

        if($findGudang){
            return $this->responseError(
                Response::HTTP_CONFLICT, 
                "gudang sudah ada"
            );
        }

        $createGudang = Gudang::create($request);

        if(!$createGudang){
            return $this->responseError(
                Response::HTTP_BAD_REQUEST, 
                "gagal membuat gudang"
            );
        }

        return $this->responseDataSuccess(
            Response::HTTP_CREATED, 
            "Success create gudang", 
            $createGudang
        );
    }

    public function read(Request $request)
    {
        $query = Gudang::query();
    
        if ($request->has('kode_gudang')) {
            $query->where('kode_gudang', $request->input('kode_gudang'));
        }
    
        if ($request->has('nama_gudang')) {
            $query->orWhere('nama_gudang', $request->input('nama_gudang'));
        }

        $findGudang = $query->first();
    
        if (!$findGudang) {
            return $this->responseError(
                Response::HTTP_NOT_FOUND, 
                "gudang tidak ditemukan"
            );
        }

        return $this->responseDataSuccess(
            Response::HTTP_OK, 
            "Success read gudang", 
            $findGudang
        );
    }

    public function update(InsertGudangRequest $insertGudangRequest){
        $request = $insertGudangRequest->validated();
        $id = $request->query('id');

        $findGudang = Gudang::find($id);

        if(!$findGudang){
            return $this->responseError(
                Response::HTTP_NOT_FOUND, 
                "gudang tidak ada"
            );
        }

        $updateGudang = $findGudang->update($request);

        if(!$updateGudang){
            return $this->responseError(
                Response::HTTP_BAD_REQUEST, 
                "gagal update gudang"
            );
        }

        return $this->responseDataSuccess(
            Response::HTTP_OK, 
            "Success update gudang", 
            $findGudang
        );
    }

    public function delete(Request $request){
        $id = $request->query("id");

        $findGudang = Gudang::find($id);

        if (!$findGudang) {
            return $this->responseError(
                Response::HTTP_NOT_FOUND, 
                "gudang tidak ditemukan"
            );
        }

        $findGudang->delete();

        return $this->responseDataSuccess(
            Response::HTTP_OK,
            "Success delete gudang",
            null
        );
    }
}