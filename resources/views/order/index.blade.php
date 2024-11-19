@extends('layouts.layout')

@section('content')
    <div class="container mt-3">
        <div class="d-flex justify-content-end mb-2">
            @if (Auth::user()->role == "admin")
                <a href="#" class="btn btn-success">Export Excel</a>
            @endif
            @if (Auth::user()->role == "cashier")
                <a href="{{ route('kasir.order.create') }}" class="btn btn-primary">Pembelian Baru</a>
            @endif
        </div>

        @if (Auth::user()->role == "cashier")
            <form action="" method="GET" class="d-flex justify-content-end mb-2">
                <input type="date" name="search_date" class="form-control">
                <button type="submit" class="btn btn-primary ms-2">
                    Cari
                </button>
            </form>
        @endif

        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <td><b>No</b></td>
                    <td><b>Nama Pembeli</b></td>
                    <td><b>Obat</b></td>
                    <td><b>Total Bayar</b></td>
                    <td><b>Nama Kasir</b></td>
                    <td><b>Waktu</b></td>
                    @if (Auth::user()->role == "cashier")
                        <td><b>Aksi</b></td>
                    @endif
                </tr>
            </thead>
            <tbody>
                {{-- @php
                    $no =      
                @endphp --}}
                @foreach ($orders as $index => $item)
                    <tr>
                        <td class="text-center">{{ ($orders->currentPage()-1) * ($orders->perPage()) + ($index+1) }}</td>
                        <td>{{ $item['name_costumer'] }}</td>
                        <td>
                            @foreach ($item['medicines'] as $medicine)
                                <ol>
                                    <li>
                                        {{ $medicine['name_medicine'] }} ( {{ number_format($medicine['price'], 0, ',', '.') }} ) : Rp. {{ number_format($medicine['sub_price'], 0, ',', '.') }} <small>qty {{ $medicine['qty'] }}</small>
                                    </li>
                                </ol>
                            @endforeach
                        </td>
                        <td>Rp. {{ number_format($item['total_price'], 0, ',', '.') }}</td>
                        <td>{{ $item['user']['name'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                        @if (Auth::user()->role == "cashier")
                            <td>
                                <!-- Mengakses id order dengan benar: -->
                                <a href="{{ route('kasir.order.printPdf', $item['id']) }}" class="btn btn-warning">Download Struk</a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mb-2">
        {{ $orders->links() }}
    </div>
@endsection
