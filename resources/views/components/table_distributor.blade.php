<table class="table table-bordered">
    <tr>
        <th>Nama Distributor</th>
        <td>{{ $distributor->name }}</td>
    </tr>
    <tr>
        <th>Nama Perusahaan</th>
        <td>{{ $distributor->company_name }}</td>
    </tr>
    <tr>
        <th>Alamat</th>
        <td>{{ $distributor->address }}</td>
    </tr>
    <tr>
        <th>Telepon</th>
        <td>{{ $distributor->phone }}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{ $distributor->email }}</td>
    </tr>
    <tr>
        <th>Kategori Produk</th>
        <td>{{ $distributor->productCategory?->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Termin Pembayaran</th>
        <td>{{ $distributor->paymentTerm?->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Metode Pengiriman</th>
        <td>{{ $distributor->deliveryMethod?->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Skala Bisnis</th>
        <td>{{ $distributor->businessScale?->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Skor Harga</th>
        <td>{{ $distributor->price_score }}</td>
    </tr>
    <tr>
        <th>Skor Kualitas</th>
        <td>{{ $distributor->quality_score }}</td>
    </tr>
    <tr>
        <th>Skor Pengiriman</th>
        <td>{{ $distributor->delivery_score }}</td>
    </tr>
    <tr>
        <th>Skor Layanan</th>
        <td>{{ $distributor->service_score }}</td>
    </tr>
    <tr>
        <th>Deskripsi Perusahaan</th>
        <td>{{ $distributor->description ?? '-' }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>{{ $distributor->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
    </tr>
</table>