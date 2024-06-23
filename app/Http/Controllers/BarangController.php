<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsertBarangRequest;
use App\Models\Barang;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BarangController extends Controller
{
    public function create(InsertBarangRequest $insertBarangRequest)
    {
        $request = $insertBarangRequest->validated();
    
        $findBarang = Barang::where('kode_barang', $request['kode_barang'])
            ->orWhere('nama_barang', $request['nama_barang'])
            ->exists();
    
        if ($findBarang) {
            return $this->responseError(
                Response::HTTP_CONFLICT,
                "barang sudah ada"
            );
        }
    
        $createBarang = Barang::create($request);
    
        if (!$createBarang) {
            return $this->responseError(
                Response::HTTP_BAD_REQUEST,
                "gagal membuat barang"
            );
        }
    
        return $this->responseDataSuccess(
            Response::HTTP_CREATED,
            "Success create barang",
            $createBarang
        );
    }

    public function read(Request $request)
    {
        $perPage = 10;
        $page = $request->input('page', 1);

        $query = Barang::query();

        if ($request->has('kode_barang')) {
            $query->where('kode_barang', $request->input('kode_barang'));
        }

        if ($request->has('nama_barang')) {
            $query->orWhere('nama_barang', $request->input('nama_barang'));
        }

        if (!$request->has('kode_barang') && !$request->has('nama_barang')) {
            $barangs = Barang::paginate($perPage, ['*'], 'page', $page);

            return $this->responseDataWithPagination(
                Response::HTTP_OK,
                'Success read barang',
                $barangs->items(),
                [
                    'total' => $barangs->total(),
                    'per_page' => $barangs->perPage(),
                    'current_page' => $barangs->currentPage(),
                    'last_page' => $barangs->lastPage(),
                    'from' => $barangs->firstItem(),
                    'to' => $barangs->lastItem(),
                ]
            );
        }

        $findBarang = $query->first();

        if (!$findBarang) {
            return $this->responseError(
                Response::HTTP_NOT_FOUND,
                "Barang tidak ditemukan"
            );
        }

        return $this->responseDataSuccess(
            Response::HTTP_OK,
            "Success read barang",
            $findBarang
        );
    }

    public function monitoring(Request $request)
    {
        $perPage = 10;
        $page = $request->input('page', 1);

        $query = Barang::query();

        if ($request->has('nama_gudang')) {
            $query->whereHas('gudang', function ($q) use ($request) {
                $q->where('nama_gudang', $request->input('nama_gudang'));
            });
        }

        if ($request->has('expired_date')) {
            $query->whereDate('expired_barang', '<=', $request->input('expired_date'));
        }

        $barangs = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->responseDataWithPagination(
            Response::HTTP_OK,
            'Success read barang',
            $barangs->items(),
            [
                'total' => $barangs->total(),
                'per_page' => $barangs->perPage(),
                'current_page' => $barangs->currentPage(),
                'last_page' => $barangs->lastPage(),
                'from' => $barangs->firstItem(),
                'to' => $barangs->lastItem(),
            ]
        );
    }
    
    public function update(InsertBarangRequest $insertBarangRequest){
        $request = $insertBarangRequest->validated();
        $id = $request->query('id');

        $findBarang = Barang::find($id);

        if( !$findBarang ){
            return $this->responseError(
                Response::HTTP_NOT_FOUND, 
                "barang tidak ada"
            );
        }

        $updateBarang = $findBarang->update($request);

        if( !$findBarang ){
            return $this->responseError(
                Response::HTTP_BAD_REQUEST, 
                "gagal update barang"
            );
        }

        return $this->responseDataSuccess(
            Response::HTTP_CREATED, 
            "Success update barang", 
            $updateBarang
        );
    }

    public function delete(Request $request){
        $id = $request->query("id");

        $findBarang = Barang::find($id);

        if (!$findBarang) {
            return $this->responseError(
                Response::HTTP_NOT_FOUND, 
                "barang tidak di temukan"
            );
        }

        $findBarang->delete();

        return $this->responseDataSuccess(
            Response::HTTP_OK,
            "Success delete barang",
            null
        );
    }
}
