@extends('layouts/master')
@section('title', 'Recap')


@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Recap</h1>
            @if (sizeof($tx) != 0)
            <a href="{{ route('recap.export') . '?' . http_build_query(Request::query()) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                                                        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
            @endif
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
                        <form action="{{ route('dashboard.recap') }}" method="GET">
                            <div class="row">
                                <div class="col">
                                    <input type="date" name="start" class="form-control" placeholder="Start"
                                        value="{{ request('start') }}">
                                </div>
                                <div class="col">
                                    <input type="date" name="end" class="form-control" placeholder="End"
                                        value="{{ request('end') }}">
                                </div>
                                <div class="col d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input class="form-check-input" name="done" type="checkbox"
                                            {{ request('done') == 1 ? 'checked' : '' }} value="1" id="defaultCheck1">
                                        <label class="form-check-label" for="defaultCheck1">
                                            Done?
                                        </label>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary">Seacrh</button>
                                    </div>
                                </div>
                            </div>

                            @if(sizeof($tx) != 0)
                            <div class="table-responsive mt-2">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">TX ID</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Client</th>
                                            <th scope="col">Crew</th>
                                            <th scope="col">Services</th>
                                            <th scope="col">Total Amount</th>
                                            <th scope="col">Status</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tx as $key => $r)
                                            @php
                                                // $crewNames = $tx->cr->pluck('name')->join(', ');
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $r->id }}</td>
                                                <td>{{ \Carbon\Carbon::parse($r->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                                </td>
                                                <td>{{ $r->client->name }}</td>
                                                <td>
                                                    @foreach ($r->cr as $c)
                                                        <div>{{ $c->crew->name }}</div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($r->services as $s)
                                                        <div>{{ $s->name }}</div>
                                                    @endforeach
                                                </td>
                                                <td>{{ $r->payment->total ?? '' }}</td>
                                                <td class="text-center">
                                                    @if ($r->done == 1)
                                                      {{ 'Done' }}
                                                    @else
                                                      <span title="Copy Link" style="cursor: pointer;"
                                                          onclick="copyLink('{{ url('/' . 'order/' . $r->id) }}')">
                                                          <svg
                                                              fill="#000000" xmlns="http://www.w3.org/2000/svg"
                                                              width="14px" height="14px" viewBox="0 0 52 52"
                                                              enable-background="new 0 0 52 52" xml:space="preserve">
                                                              <g>
                                                                  <path d="M17.4,11.6h17.3c0.9,0,1.6-0.7,1.6-1.6V6.8c0-2.6-2.1-4.8-4.7-4.8h-11c-2.6,0-4.7,2.2-4.7,4.8V10
    C15.8,10.9,16.5,11.6,17.4,11.6z" />
                                                                  <path
                                                                      d="M43.3,6h-1.6c-0.5,0-0.8,0.3-0.8,0.8V10c0,3.5-2.8,6.4-6.3,6.4H17.4c-3.5,0-6.3-2.9-6.3-6.4V6.8
    c0-0.5-0.3-0.8-0.8-0.8H8.7C6.1,6,4,8.2,4,10.8v34.4C4,47.8,6.1,50,8.7,50h34.6c2.6,0,4.7-2.2,4.7-4.8V10.8C48,8.2,45.9,6,43.3,6z" />
                                                              </g>
                                                          </svg>
                                                      </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{ $tx->links() }}
                            </div>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
            $('#crew').select2({
                placeholder: "Select Crew",
                allowClear: true
            });
        });

        function validatePhone() {
            const phoneInput = document.getElementById('phone').value;
            const errorMessage = document.getElementById('error-message');

            // Regex untuk validasi nomor telepon Indonesia
            const indonesiaPhoneRegex = /^(?:\+62|62|0)8[1-9][0-9]{3,12}$/;

            // Validasi input nomor telepon saat pengguna mengetik
            if (indonesiaPhoneRegex.test(phoneInput)) {
                errorMessage.style.display = 'none';
            } else {
                errorMessage.style.display = 'block';
                errorMessage.textContent = 'Nomor telepon tidak valid. Gunakan format +62 atau 08.';
            }
        }

        function removeNonNumericCharacters() {
            const phoneInput = document.getElementById('phone');
            phoneInput.value = phoneInput.value.replace(/[^\d+]/g, ''); // Hanya izinkan angka dan simbol '+'
        }

        function copyLink(link) {
            // Membuat input teks sementara untuk menyalin link
            const el = document.createElement('textarea');
            el.value = link;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert('Link copied to clipboard!');
        }
    </script>
@endsection
