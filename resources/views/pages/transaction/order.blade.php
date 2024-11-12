@extends('layouts/blank')
@section('title', 'Order Form')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="mt-5 d-sm-flex align-items-center justify-content-center mb-4 text-center">
            <h1 class="h3 mb-0 text-gray-800">Order Form</h1>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('order.store', $tx->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" name="date" class="form-control" id="date"
                                    value="{{ $tx->date }}" required readonly>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="name">Client Name</label>
                                        <input type="text" name="client_name" class="form-control" id="name"
                                            placeholder="Client Name" value="{{ $tx->client->name }}" required readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Address</label>
                                        <textarea class="form-control" name="client_address" id="exampleFormControlTextarea1" rows="1"
                                            placeholder="Address" required readonly>{{ $tx->client->address }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" name="client_phone" class="form-control" id="phone"
                                            placeholder="08xxx" value="{{ $tx->client->phone }}" required readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    @php
                                        $crewNames = $tx->crews->pluck('name')->join(', ');
                                    @endphp

                                    <div class="form-group">
                                        <label for="name">Crew Name</label>
                                        <input type="text" name="crew_name" class="form-control" id="name"
                                            placeholder="Crew Name" value="{{ $crewNames }}" required readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="checkin">Check In</label>
                                        <input type="time" name="checkin" class="form-control" id="checkin"
                                            placeholder="Check In" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="checkout">Check Out</label>
                                        <input type="time" name="checkout" class="form-control" id="checkout"
                                            placeholder="Check Out" required>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">SERVICE DESCRIPTION</th>
                                            <th scope="col">QTY</th>
                                            <th scope="col">PRICE</th>
                                            <th scope="col">TOTAL</th>
                                            <th scope="col">ACTIONS</th>

                                        </tr>
                                    </thead>
                                    <tbody id="serviceTable">
                                        {{-- <tr>
                                                        <td scope="row">1</td>
                                                        <td>Premium</td>
                                                        <td>
                                                            <input type="number" name="qty[]" class="form-control"
                                                                style="width: 100px;" id="qty1"
                                                                oninput="calculateTotal(1)">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="price[]" class="form-control"
                                                                style="width: 250px;" id="price1"
                                                                oninput="calculateTotal(1)"
                                                                onblur="formatPriceOnBlur(this)">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="total[]" class="form-control"
                                                                style="width: 250px;" id="total1" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td scope="row">2</td>
                                                        <td>Wax</td>
                                                        <td>
                                                            <input type="number" name="qty[]" class="form-control"
                                                                style="width: 100px;" id="qty2"
                                                                oninput="calculateTotal(2)">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="price[]" class="form-control"
                                                                style="width: 250px;" id="price2"
                                                                oninput="calculateTotal(2)"
                                                                onblur="formatPriceOnBlur(this)">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="total[]" class="form-control"
                                                                style="width: 250px;" id="total2" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td scope="row">3</td>
                                                        <td>Luxury</td>
                                                        <td>
                                                            <input type="number" name="qty[]" class="form-control"
                                                                style="width: 100px;" id="qty3"
                                                                oninput="calculateTotal(3)">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="price[]" class="form-control"
                                                                style="width: 250px;" id="price3"
                                                                oninput="calculateTotal(3)"
                                                                onblur="formatPriceOnBlur(this)">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="total[]" class="form-control"
                                                                style="width: 250px;" id="total3" readonly>
                                                        </td>
                                                    </tr> --}}
                                    </tbody>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Baris untuk menampilkan Total Amount -->
                            <div class="row">
                                <div class="col text-right">
                                    <strong>Total Amount: </strong>
                                    <span id="totalAmount">Rp 0</span>
                                </div>
                            </div>

                            <!-- Add Service Button -->
                            <button type="button" class="btn btn-primary my-2" onclick="addServiceRow()">Add
                                Service</button>

                            <fieldset class="form-group row">
                                <legend class="col-form-label col-sm-2 float-sm-left pt-0">Payment</legend>
                                <div class="col-sm-10">
                                    @foreach ($payments as $p)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment"
                                                id="gridRadios{{ $p->id }}" value="{{ $p->id }}" required>
                                            <label class="form-check-label" for="gridRadios1">
                                                {{ $p->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </fieldset>
                            <div>Upload Photo</div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="exampleFormControlFile1">Before Service</label>
                                        <input type="file" class="form-control-file" name="before"
                                            id="exampleFormControlFile1">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="exampleFormControlFile1">After Service</label>
                                        <input type="file" class="form-control-file" name="after"
                                            id="exampleFormControlFile1">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
@section('page-script')
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
    <script>
        // Fungsi untuk memformat angka dengan titik pemisah ribuan
        function formatNumber(value) {
            // Hapus karakter non-angka
            value = value.replace(/\D/g, '');

            // Format ulang menjadi ribuan
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Fungsi untuk memastikan hanya angka yang bisa diinput
        function isNumberKey(evt) {
            let charCode = evt.which ? evt.which : evt.keyCode;
            // Hanya izinkan angka (0-9) dan backspace
            if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
            return true;
        }

        let serviceCount = 0; // Mulai dari 3 baris awal

        function addServiceRow() {
            serviceCount++; // Tambah indeks untuk baris baru
            const tbody = document.querySelector("table tbody");

            const newRow = document.createElement("tr");
            newRow.innerHTML = `
            <td scope="row">${serviceCount}</td>
            <td><input type="text" name="service_description[]" class="form-control" placeholder="Service Description"></td>
            <td><input type="number" id="qty${serviceCount}" name="qty[]" class="form-control" style="width: 100px;" oninput="calculateTotal(${serviceCount})"></td>
            <td><input type="text" id="price${serviceCount}" name="price[]" class="form-control" style="width: 250px;" oninput="calculateTotal(${serviceCount})"></td>
            <td><input type="text" id="total${serviceCount}" name="total[]" class="form-control" style="width: 250px;" readonly></td>
            <td><button type="button" class="btn btn-danger" onclick="removeServiceRow(this)">Remove</button></td>
        `;
            tbody.appendChild(newRow);
        }

        function formatPriceOnBlur(element) {
            // Format harga dengan Rupiah saat kehilangan fokus
            element.value = formatRupiah(element.value);
        }

        function formatRupiah(value) {
            let numberString = value.replace(/[^,\d]/g, ''); // Hapus karakter selain angka
            let formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0, // Tidak ada koma desimal
                maximumFractionDigits: 0
            }).format(numberString);

            return formatted;
        }

        function calculateTotal(row) {
            let qtyElement = document.getElementById("qty" + row);
            let priceElement = document.getElementById("price" + row);
            let totalElement = document.getElementById("total" + row);

            // Ambil nilai qty dan price dan lakukan validasi
            let qty = parseInt(qtyElement.value) || 0;

            // Validasi harga
            let priceValue = priceElement ? priceElement.value.replace(/[^,\d]/g, '') :
                '0'; // Periksa apakah priceElement ada
            let price = priceValue ? parseInt(priceValue) : 0;

            let total = qty * price;

            // Format total dalam Rupiah
            totalElement.value = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(total);

            // Update total amount keseluruhan setelah perhitungan
            updateTotalAmount();
        }

        function updateTotalAmount() {
            let totalAmount = 0;

            // Ambil semua input total dan jumlahkan
            const totalElements = document.querySelectorAll('[id^="total"]');
            totalElements.forEach(function(totalElement) {
                // Pastikan totalElement.value terdefinisi dan valid sebelum diproses
                let total = totalElement.value ? parseInt(totalElement.value.replace(/[^,\d]/g, '')) : 0;
                totalAmount += total;
            });

            // Format dan tampilkan total amount
            document.getElementById("totalAmount").textContent = formatRupiah(totalAmount.toString());
        }

        function formatRupiah(value) {
            let numberString = value.replace(/[^,\d]/g, '');
            let formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0, // Tidak ada koma desimal
                maximumFractionDigits: 0
            }).format(numberString);

            return formatted;
        }

        function removeServiceRow(button) {
            const row = button.closest('tr'); // Ambil baris dari tombol
            row.remove(); // Hapus baris tersebut

            // Update total amount setelah menghapus baris
            updateTotalAmount();
        }
    </script>
@endsection
