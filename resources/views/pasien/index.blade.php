@extends('layouts.app')

@section('title', 'Data Pasien')

@section('content')
<div class="container-fluid">
    <h2>Data Pasien</h2>
    
    <a href="{{ route('pasien.create') }}" class="btn btn-primary mb-3">Tambah Pasien</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>No. Telp</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pasien as $p)
            <tr>
                <td>{{ $p->PasienID }}</td>
                <td>{{ $p->Nama }}</td>
                <td>{{ $p->TanggalLahir }}</td>
                <td>{{ $p->Alamat }}</td>
                <td>{{ $p->NoTelp }}</td>
                <td>{{ $p->email }}</td>
                <td>
                    <a href="{{ route('pasien.show', $p->PasienID) }}" class="btn btn-sm btn-info">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $pasien->links() }}
</div>
<td>
    <a href="{{ route('pasien.show', $p->PasienID) }}" class="btn btn-sm btn-info">Detail</a>
    <a href="{{ route('pasien.edit', $p->PasienID) }}" class="btn btn-sm btn-warning">Edit</a>
    <form action="{{ route('pasien.destroy', $p->PasienID) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pasien ini?')">Hapus</button>
    </form>
</td>
@endsection