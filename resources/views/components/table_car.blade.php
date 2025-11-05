<table class="table table-bordered">
    <tr>
        <th>Nama Mobil</th>
        <td>{{ $car->name }}</td>
    </tr>
    <tr>
        <th>Harga</th>
        <td>Rp {{ number_format($car->price, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th>Tahun Produksi</th>
        <td>{{ $car->manufacture_year }}</td>
    </tr>
    <tr>
        <th>Jarak Tempuh</th>
        <td>{{ number_format($car->mileage, 0, ',', '.') }} km</td>
    </tr>
    <tr>
        <th>Bahan Bakar</th>
        <td>{{ $car->fuelType?->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Kapasitas Mesin</th>
        <td>{{ $car->engine_capacity }} cc</td>
    </tr>
    <tr>
        <th>Jumlah Kursi</th>
        <td>{{ $car->seat_count }}</td>
    </tr>
    <tr>
        <th>Transmisi</th>
        <td>{{ $car->transmissionType?->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Warna</th>
        <td>{{ $car->color }}</td>
    </tr>
    <tr>
        <th>Merek Mobil</th>
        <td>{{ $car->carBrand?->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Jenis Mobil</th>
        <td>{{ $car->carType?->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Deskripsi Mobil</th>
        <td>{{ $car->description }}</td>
    </tr>
    <tr>
        <th>Status Ketersediaan</th>
        <td>{{ $car->is_available == 0 ? 'Tidak Tersedia' : 'Tersedia' }}</td>
    </tr>
</table>
