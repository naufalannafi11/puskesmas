@extends('layouts.app')

@section('content')

    <div class="max-w-2xl mx-auto">

        {{-- JUDUL --}}
        <h2 class="text-xl font-bold mb-6">Import Dataset Kunjungan Pasien</h2>

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
                <strong>❌ Import Gagal:</strong>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM UPLOAD --}}
        <div class="bg-white p-6 rounded shadow mb-6">
            <form id="importForm" action="{{ route('admin.dataset.import.proses') }}" method="POST"
                enctype="multipart/form-data" onsubmit="startPolling()">
                @csrf

                <input type="hidden" name="import_id" id="import_id">

                <label class="block font-semibold mb-2">Pilih File (xlsx / csv / xls):</label>
                <input type="file" name="file" class="block w-full border border-gray-300 p-2 rounded mb-4"
                    accept=".xlsx,.xls,.csv" required>

                <button type="submit" id="submitBtn"
                    class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                    Import Dataset
                </button>
            </form>

            {{-- PROGRESS BAR --}}
            <div id="progressContainer" class="mt-6 hidden">
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-blue-700" id="progressText">Menyiapkan data...</span>
                    <span class="text-sm font-medium text-blue-700" id="progressPercent">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-500"
                        style="width: 0%"></div>
                </div>
                <p class="mt-2 text-xs text-gray-500 italic">Jangan tutup halaman ini hingga proses selesai.</p>
            </div>
        </div>

        <script>
            // Generate unique ID
            const importId = 'import_' + Date.now();
            document.getElementById('import_id').value = importId;

            function startPolling() {
                // Tampilkan progress bar
                document.getElementById('progressContainer').classList.remove('hidden');
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').innerText = '⏳ Memproses...';

                const interval = setInterval(async () => {
                    try {
                        const response = await fetch(`/admin/dataset/progress/${importId}`);
                        const data = await response.json();

                        if (data.total > 0) {
                            const percent = Math.round((data.current / data.total) * 100);

                            document.getElementById('progressBar').style.width = percent + '%';
                            document.getElementById('progressPercent').innerText = percent + '%';
                            document.getElementById('progressText').innerText = `Memproses ${data.current} dari ${data.total} data...`;
                        }
                    } catch (error) {
                        console.error('Polling error:', error);
                    }
                }, 1000);
            }
        </script>

        {{-- PANDUAN FORMAT FILE --}}
        <div class="bg-yellow-50 border border-yellow-300 rounded p-5">
            <h3 class="font-bold text-yellow-800 mb-3">📋 Format File yang Harus Digunakan</h3>

            <p class="text-sm text-yellow-700 mb-3">
                Pastikan file Excel/CSV kamu memiliki <strong>baris pertama sebagai header</strong> dengan nama kolom persis
                seperti berikut:
            </p>

            {{-- Tabel contoh header --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-yellow-400 rounded">
                    <thead class="bg-yellow-200 text-yellow-900">
                        <tr>
                            <th class="border border-yellow-400 px-3 py-2">id_pasien</th>
                            <th class="border border-yellow-400 px-3 py-2">nama_pasien</th>
                            <th class="border border-yellow-400 px-3 py-2">tanggal_kunjungan</th>
                            <th class="border border-yellow-400 px-3 py-2">diagnosa</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white text-gray-700">
                        <tr>
                            <td class="border border-yellow-300 px-3 py-2">001</td>
                            <td class="border border-yellow-300 px-3 py-2">Budi Santoso</td>
                            <td class="border border-yellow-300 px-3 py-2">2026-01-10</td>
                            <td class="border border-yellow-300 px-3 py-2">ISPA</td>
                        </tr>
                        <tr class="bg-yellow-50">
                            <td class="border border-yellow-300 px-3 py-2">002</td>
                            <td class="border border-yellow-300 px-3 py-2">Siti Rahayu</td>
                            <td class="border border-yellow-300 px-3 py-2">2026-01-10</td>
                            <td class="border border-yellow-300 px-3 py-2">Hipertensi</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <ul class="mt-4 text-sm text-yellow-800 list-disc list-inside space-y-1">
                <li><code class="bg-yellow-100 px-1 rounded">id_pasien</code> → digunakan sebagai <strong>Nomor Rekam Medis
                        (no_rm)</strong>. Jika pasien sudah ada, data akan di-<em>update</em>.</li>
                <li><code class="bg-yellow-100 px-1 rounded">nama_pasien</code> → nama lengkap pasien. Akan diperbarui jika
                    berbeda.</li>
                <li><code class="bg-yellow-100 px-1 rounded">tanggal_kunjungan</code> → format: <strong>YYYY-MM-DD</strong>
                    (contoh: 2026-01-10)</li>
                <li><code class="bg-yellow-100 px-1 rounded">diagnosa</code> → diagnosis dari kunjungan tersebut.</li>
            </ul>

            <p class="mt-3 text-xs text-yellow-700">
                ⚠️ Import bersifat <strong>aman (upsert)</strong>: data yang sudah ada tidak akan dihapus. Pasien baru akan
                dibuat otomatis, pasien lama akan diperbarui.
            </p>
        </div>

    </div>

@endsection