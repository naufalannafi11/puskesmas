@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto py-8 px-4">

    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        Daftar Pasien Hari Ini
    </h2>

    @if($reservasis->count() > 0)

        <div class="bg-white shadow-md rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Nama Pasien
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Keluhan
                        </th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-600">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">

                    @foreach($reservasis as $r)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                {{ $r->pasien->name }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $r->keluhan }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('dokter.pemeriksaan.create', $r->id) }}"
                                   class="inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                    Periksa
                                </a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    @else
        <div class="bg-white p-6 rounded-xl shadow text-center">
            <p class="text-gray-500">
                Tidak ada pasien hari ini.
            </p>
        </div>
    @endif

</div>

@endsection
