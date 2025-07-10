<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Barcode</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="/admin_template/css/sb-admin-2.min.css" rel="stylesheet"> --}}

    <style>
        body {
            font-family: sans-serif;
            padding: 10px;
        }

        h1 {
            font-size: 18pt;
        }

        table {
            page-break-after: always;
        }

        @media print {
            @page {
                size: auto;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                padding: 4px 6px;
            }
        }
    </style>
</head>
<body>

    @for($i = 0; $i < 3; $i++)
        <table class="table table-bordered text-dark">
            {{-- <tr>
                <th><h1>ID</h1></th>
                <th><h1>{{ $data->id }}</h1></th>
            </tr> --}}
            <tr>
                <td><h1>Barcode</h1></td>
                <td>
                    {{-- Tempat barcode ditampilkan --}}
                    <svg id="barcode-{{ $i }}"></svg>
                </td>
            </tr>
            <tr>
                <td><h1>Titik Pengamatan</h1></td>
                <td><h1>{{ $data->titik_pengamatan->nama }}</h1></td>
            </tr>
            <tr>
                <td><h1>Periode</h1></td>
                <td><h1>{{ $data->periode }}</h1></td>
            </tr>
            <tr>
                <td><h1>Jam Sampling</h1></td>
                <td><h1>{{ $data->jam }}</h1></td>
            </tr>
            <tr>
                <td><h1>Jam Cetak</h1></td>
                <td><h1>{{ $data->created_at }}</h1></td>
            </tr>
        </table>
    @endfor

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const barcodeValue = "{{ $data->id }}";
            // Generate barcode untuk masing-masing cetakan
            @for($i = 0; $i < 3; $i++)
                JsBarcode("#barcode-{{ $i }}", barcodeValue, {
                    format: "CODE128",
                    width: 2,
                    height: 80,
                    displayValue: true
                });
            @endfor

            // Tunggu sedikit sebelum cetak agar barcode selesai render
            setTimeout(() => window.print(), 500);
        });
    </script>
</body>
</html>
