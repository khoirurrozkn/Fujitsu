<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertBarangRequest extends FormRequest
{
    use CustomValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_barang' => 'required|max:255',
            'nama_barang' => 'required|max:255',
            'harga_barang' => 'required|integer',
            'jumlah_barang' => 'required|integer',
            'expired_barang' => 'required|date',
            'id_gudang' => 'required|integer'
        ];
    }
}
