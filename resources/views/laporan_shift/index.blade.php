@extends('template.master')

@section('content')
    <div class="container-fluid py-0 px-6 text-light">
        <h4 class="mb-3">Laporan Shift <span id="namaShift" class="text-info"></span></h4>

        {{-- Form Filter --}}
        <form id="filterForm" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control"
                        value="{{ date('Y-m-d', strtotime('-1 day')) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="shift" class="form-label">Shift</label>
                    <select id="shift" name="shift" class="form-control">
                        <option value="0">Harian</option>
                        <option value="1">Pagi</option>
                        <option value="2">Sore</option>
                        <option value="3">Malam</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="btn-group" role="group" aria-label="Aksi Laporan">
                        <button type="button" id="tampilkanBtn" class="btn btn-primary">Tampilkan</button>
                        <button type="button" id="exportExcelBtn" class="btn btn-success">Excel</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Area Laporan --}}
        <div id="laporanContainer"></div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        const shiftNames = {
            0: 'Harian',
            1: 'Pagi',
            2: 'Sore',
            3: 'Malam'
        };

        document.getElementById('tampilkanBtn').addEventListener('click', function() {
            const tanggal = document.getElementById('tanggal').value;
            const shift = document.getElementById('shift').value;
            const url = `/laporan_shift/${tanggal}/${shift}`;

            document.getElementById('namaShift').innerText = `(${shiftNames[shift] || '-'})`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('laporanContainer');
                    container.innerHTML = '';

                    if (!data || data.length === 0) {
                        container.innerHTML =
                        '<div class="alert alert-warning">Tidak ada data ditemukan.</div>';
                        return;
                    }

                    data.forEach(zona => {
                        const tableWrapper = document.createElement('div');
                        tableWrapper.className = 'mb-5';

                        const parameterMap = new Map();

                        zona.titik_pengamatans.forEach(tp => {
                            tp.hasil.forEach(h => {
                                parameterMap.set(h.parameter_id, h.simbol || h.nama);
                            });
                        });

                        const parameters = Array.from(parameterMap.entries()).map(([id, simbol]) => ({
                            id,
                            simbol
                        }));

                        let html = `
                        <h5 class="mb-2 text-white">Zona: ${zona.zona}</h5>
                        <div class="table-responsive">
                        <table class="table table-bordered table-sm text-dark bg-white">
                            <thead class="table-light">
                                <tr>
                                    <th>Titik</th>`;

                        parameters.forEach(p => {
                            html += `<th>${p.simbol}</th>`;
                        });

                        html += `</tr></thead><tbody>`;

                        zona.titik_pengamatans.forEach(tp => {
                            html += `<tr><td>${tp.nama}</td>`;

                            parameters.forEach(p => {
                                const param = tp.hasil.find(h => h.parameter_id == p
                                .id);
                                const value = param && param.nilai !== null ? Number(
                                    param.nilai).toFixed(2) : '-';
                                html += `<td>${value}</td>`;
                            });

                            html += `</tr>`;
                        });

                        html += `</tbody></table></div>`;

                        tableWrapper.innerHTML = html;
                        container.appendChild(tableWrapper);
                    });
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('laporanContainer').innerHTML =
                        '<div class="alert alert-danger">Gagal mengambil data laporan.</div>';
                });
        });

        document.getElementById('exportExcelBtn').addEventListener('click', function() {
            const container = document.getElementById('laporanContainer');
            const tanggal = document.getElementById('tanggal').value;
            const shift = document.getElementById('shift').value;
            const shiftText = shiftNames[shift] || 'Shift';

            if (!container) {
                alert('Kontainer tidak ditemukan.');
                return;
            }

            const allData = [];
            const tables = container.querySelectorAll('table');

            if (tables.length === 0) {
                alert('Tidak ada tabel yang bisa diekspor.');
                return;
            }

            allData.push([`LAPORAN SHIFT: ${shiftText.toUpperCase()} | PERIODE: ${tanggal}`]);
            allData.push([]); // baris kosong

            tables.forEach((table, tableIndex) => {
                let zonaTitle = table.closest('.table-responsive')?.previousElementSibling?.innerText || `Zona ${tableIndex + 1}`;
                zonaTitle = zonaTitle.replace(/^Zona:\s*/, '');

                allData.push([`Zona: ${zonaTitle}`]);

                const rows = table.querySelectorAll('tr');
                rows.forEach((tr) => {
                    const row = [];
                    const cells = tr.querySelectorAll('th, td');
                    cells.forEach(cell => row.push(cell.innerText));
                    allData.push(row);
                });

                allData.push([]); // pemisah antar zona
            });

            const ws = XLSX.utils.aoa_to_sheet(allData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Laporan Shift');

            const fileName = `laporan_shift-${tanggal}-${shiftText}.xlsx`;
            XLSX.writeFile(wb, fileName);
        });
    </script>
@endsection
