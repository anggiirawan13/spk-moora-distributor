@extends('layouts.app')

@section('title', 'Booking')

@section('content')

    <x-alert />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Daftar Booking</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" id="dataTable" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>

                            @if (Auth::user()->is_admin === 1)
                                <th>Nama</th>
                                <th>Email</th>
                            @endif

                            <th>No. HP</th>
                            <th>Mobil</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Jenis Booking</th>
                            <th>Status</th>

                            @if (Auth::user()->is_admin === 1)
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No.</th>

                            @if (Auth::user()->is_admin === 1)
                                <th>Nama</th>
                                <th>Email</th>
                            @endif

                            <th>No. HP</th>
                            <th>Mobil</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Jenis Booking</th>
                            <th>Status</th>

                            @if (Auth::user()->is_admin === 1)
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($bookings as $index => $booking)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                @if (Auth::user()->is_admin === 1)
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->user->email }}</td>
                                @endif

                                <td>{{ $booking->phone }}</td>
                                <td>{{ $booking->car->name }}</td>
                                <td>{{ $booking->date }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->time)->format('H:i') }}</td>
                                <td class="text-capitalize">{{ str_replace('_', ' ', $booking->type) }}</td>
                                <td>
                                    @if ($booking->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($booking->status === 'accepted')
                                        <span class="badge badge-success">Accepted</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                @if (Auth::user()->is_admin === 1)
                                    <td>
                                        @if ($booking->status === 'pending')
                                            <form action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="button" class="btn btn-sm btn-success btn-accept"
                                                    data-id="{{ $booking->id }}">
                                                    <i class="fas fa-check-circle"></i> Terima
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="button" class="btn btn-sm btn-danger btn-reject"
                                                    data-id="{{ $booking->id }}">
                                                    <i class="fas fa-times-circle"></i> Tolak
                                                </button>
                                            </form>
                                        @else
                                            <em>Tidak ada aksi</em>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">Belum ada data booking.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if (Auth::user()->is_admin === 1)
                    <form id="statusForm" method="POST" style="display: none;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" id="statusInput">
                    </form>
                @endif

            </div>
        </div>
    </div>

    @if (Auth::user()->is_admin === 1)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('statusForm');
                const statusInput = document.getElementById('statusInput');

                function handleAction(buttonClass, statusValue, confirmText) {
                    document.querySelectorAll(buttonClass).forEach(button => {
                        button.addEventListener('click', function() {
                            const bookingId = this.getAttribute('data-id');
                            Swal.fire({
                                title: confirmText,
                                text: "Tindakan ini tidak bisa dibatalkan.",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#28a745',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, lanjutkan',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.action = `/admin/booking/${bookingId}/status`;
                                    statusInput.value = statusValue;
                                    form.submit();
                                }
                            });
                        });
                    });
                }

                handleAction('.btn-accept', 'accepted', 'Terima booking ini?');
                handleAction('.btn-reject', 'rejected', 'Tolak booking ini?');
            });
        </script>
    @endif

@endsection
