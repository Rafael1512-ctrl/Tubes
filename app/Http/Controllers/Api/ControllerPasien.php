<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    public function index()
    {
        $pasien = Pasien::all();
        return response()->json($pasien);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Nama' => 'required|string|max:100',
            'TanggalLahir' => 'required|date',
            'Alamat' => 'nullable|string|max:100',
            'NoTelp' => 'nullable|string|max:20',
            'JenisKelamin' => 'nullable|in:L,P',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Generate PasienID menggunakan stored procedure atau bisa dengan cara lain
        // Karena di database sudah ada stored procedure, kita bisa panggil atau buat logika serupa

        // Untuk sementara, kita buat logika generate ID sederhana
        // Tapi sebaiknya panggil stored procedure yang sudah ada

        // Contoh generate ID: P-tahun-urut
        $tahun = date('Y');
        $prefix = 'P-' . $tahun . '-';
        $last = Pasien::where('PasienID', 'like', $prefix . '%')->orderBy('PasienID', 'desc')->first();
        if ($last) {
            $lastUrut = intval(substr($last->PasienID, -5));
            $urut = $lastUrut + 1;
        } else {
            $urut = 1;
        }
        $pasienID = $prefix . str_pad($urut, 5, '0', STR_PAD_LEFT);

        $pasien = Pasien::create(array_merge($request->all(), ['PasienID' => $pasienID]));

        return response()->json($pasien, 201);
    }

    public function show($id)
    {
        $pasien = Pasien::find($id);
        if (!$pasien) {
            return response()->json(['message' => 'Pasien not found'], 404);
        }
        return response()->json($pasien);
    }

    public function update(Request $request, $id)
    {
        $pasien = Pasien::find($id);
        if (!$pasien) {
            return response()->json(['message' => 'Pasien not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'Nama' => 'sometimes|required|string|max:100',
            'TanggalLahir' => 'sometimes|required|date',
            'Alamat' => 'nullable|string|max:100',
            'NoTelp' => 'nullable|string|max:20',
            'JenisKelamin' => 'nullable|in:L,P',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pasien->update($request->all());

        return response()->json($pasien);
    }

    public function destroy($id)
    {
        $pasien = Pasien::find($id);
        if (!$pasien) {
            return response()->json(['message' => 'Pasien not found'], 404);
        }

        $pasien->delete();

        return response()->json(['message' => 'Pasien deleted']);
    }
}