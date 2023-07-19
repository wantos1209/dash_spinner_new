@extends('layout.main')

@section('container')
    @php
        $search_jenis_klaim = request()->get('search_jenis_klaim');
        $search_status_transfer = request()->get('search_status_transfer');
        $search_tanggal = request()->get('search_tanggal');
        
        if (isset($search_jenis_klaim) || $search_jenis_klaim != '') {
            $search_jenis_klaim = $search_jenis_klaim;
        } else {
            $search_jenis_klaim = '';
        }
        
        if (isset($search_status_transfer) || $search_status_transfer != '') {
            $search_status_transfer = $search_status_transfer;
        } else {
            $search_status_transfer = '';
        }
        
        if (isset($search_tanggal) || $search_tanggal != '') {
            $search_tanggal = $search_tanggal;
        } else {
            $search_tanggal = $search_tgl_default;
        }
    @endphp

    <style>
        .views-submit-button input {
            visibility: hidden
        }

        .views-exposed-widget:focus-within+.views-submit-button input {
            visibility: visible
        }
    </style>
    <div id="ajaxResponse">
        {{-- @dd(session('success')) --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <div class="col-12">
        <div class="container-fluid py-4">
            <div class="row my-4">
                {{-- <div class="col-md-4">
                    <button type="button" class="btn btn-icon btn-3 btn btn-outline-light bg-dark" data-bs-toggle="modal"
                        data-bs-target="#modal-voucher-tambah" id="tambah">
                        <i class="ni ni-fat-add"></i> Tambah
                    </button>
                </div> --}}

                <div class="col-md-2">
                    @if ($id != '')
                        <a href="{{ route('spinner.generatevoucher') }}" class="btn btn-secondary">
                            <span style="margin-right: 5px">
                                << </span> Back</a>
                    @endif
                </div>



                <div class="col-md-10">
                    <form
                        action="{{ !empty($id) ? route('spinner.voucher', ['id' => $id]) : route('spinner.voucherindex') }}">

                        <div class="row">

                            @if ($id == '')
                                <div class="col-md-2">
                                    <button type="button" onclick="confirmDownload()"
                                        class="btn btn-success bg-gradient-success"
                                        onclick="return confirm('Apakah Anda yakin ingin mendownload?')"><i
                                            class="fas fa-file-excel" style="margin-right: 5px; font-size: 15px;"></i>
                                        Export</button>
                                </div>

                                <div class="col-md-3 ">
                                    <input type="text" class="form-control js-daterangepicker" name="search_tanggal"
                                        id="search_tanggal" value="{{ $search_tanggal }}">

                                </div>
                            @else
                                <div class="col-md-3 ">

                                </div>
                                <div class="col-md-2">
                                    <button type="button" onclick="confirmDownload()"
                                        class="btn btn-success bg-gradient-success"
                                        onclick="return confirm('Apakah Anda yakin ingin mendownload?')"><i
                                            class="fas fa-file-excel" style="margin-right: 5px; font-size: 15px;"></i>
                                        Export</button>
                                </div>
                            @endif
                            <div class="col-md-2">
                                <select class="form-select search_jenis_klaim" aria-label="Default select example"
                                    name="search_jenis_klaim" id="search_jenis_klaim">
                                    <option value='' {{ $search_jenis_klaim == '' ? 'selected' : '' }}>
                                        Pilih jenis klaim</option>
                                    <option value="sudah_klaim"
                                        {{ $search_jenis_klaim == 'sudah_klaim' ? 'selected' : '' }}>Sudah Klaim
                                    </option>
                                    <option value="belum_klaim"
                                        {{ $search_jenis_klaim == 'belum_klaim' ? 'selected' : '' }}>Belum klaim
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select search_status_transfer" aria-label="Default select example"
                                    name="search_status_transfer" id="search_status_transfer">
                                    <option value=3 {{ $search_status_transfer == 3 ? 'selected' : '' }}>
                                        Pilih status bayar</option>
                                    <option value=1 {{ $search_status_transfer == 1 ? 'selected' : '' }}>Sudah
                                        Bayar
                                    </option>
                                    <option value=2 {{ $search_status_transfer == 2 ? 'selected' : '' }}>Belum
                                        Bayar
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">

                                <div class="input-group">
                                    <span class="input-group-text text-body"><i class="fas fa-search"
                                            aria-hidden="true"></i></span>
                                    <input type="text" class="form-control ps-1" placeholder="Type here..."
                                        name="search" value="{{ request('search') }}">
                                    <button class="btn btn-light" type="submit" id="button-addon2" hidden>Search</button>
                                </div>

                            </div>


                        </div>
                    </form>

                </div>

                <div class="card">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center">
                                            <h6>No.</h6>
                                        </th>
                                        <th class="text-center">
                                            <h6>Kode Voucher</h6>
                                        </th>
                                        <th width="20%" class="text-center">
                                            <h6>User Kode</h6>
                                        </th>
                                        <th class="text-center">
                                            <h6>No Rekening</h6>
                                        </th>

                                        <th class="text-center">
                                            <h6>Tanggal Klaim</h6>
                                        </th>
                                        <th class="text-center">
                                            <h6>Status Bayar</h6>
                                        </th>
                                        <th class="text-center">
                                            <h6>Tanggal Exp</h6>
                                        </th>
                                        <th class="text-center">
                                            <h6>Log</h6>
                                        </th>

                                        {{-- <th class="text-center">
                                            <h6>Action</h6>
                                        </th> --}}
                                    </tr>
                                </thead>
                                <tbody data-aos="fade-up" data-aos-duration="900">

                                    @foreach ($data as $d)
                                        <tr>
                                            <td class="text-center text-white"> {{ $loop->iteration }} </td>
                                            <td class="text-center text-white">
                                                {{ $d->kode_voucher }}
                                                <button type="button"
                                                    onclick="copyText('{{ 'https://spinnerl21.com/spinnerl21?username=' . $d->username . '_' . $d->kode_voucher }}')"
                                                    title="Salin teks" class="badge badge-dark bg-secondary">
                                                    Salin
                                                </button>


                                            </td>
                                            <td class="text-center text-white"> {{ $d->username }} </td>
                                            <td id="td-{{ $d->id }}" class="text-center text-white"
                                                style="padding-top: 20px">
                                                <form id="form-voucher">
                                                    @csrf
                                                    @if ($d->userklaim == '')
                                                        <div class="col-md-12">
                                                            <div class="form-group d-flex align-items-center">
                                                                <input class="form-control" type="text"
                                                                    id="userid-{{ $d->id }}"
                                                                    name="userid-{{ $d->id }}"
                                                                    placeholder="Masukkan No Rekening"
                                                                    style="margin-right: 5px"
                                                                    oninput="toggleSubmitButton({{ $d->id }})"
                                                                    onkeypress="handleKeyPress(event, {{ $d->id }})">
                                                                <button type="button" id="submit-btn-{{ $d->id }}"
                                                                    class="btn bg-gradient-dark btn-outline-light my-4"
                                                                    style="display: none;"
                                                                    onclick="updateVoucher(event, {{ $d->id }});">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                                <button type="button" id="clear-btn-{{ $d->id }}"
                                                                    class="btn bg-gradient-dark btn-outline-light my-4"
                                                                    onclick="clearUserId({{ $d->id }})"
                                                                    style="display: none; margin-left: 5px"><i
                                                                        class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div id="td-{{ $d->id }}">
                                                            <div class="col-md-12">
                                                                <div
                                                                    class="form-group d-flex align-items-center text-center">
                                                                    <span
                                                                        style="margin-right: auto;">{{ $d->userklaim }}</span>
                                                                    <button type="button"
                                                                        class="btn bg-gradient-dark btn-outline-light my-4"
                                                                        onclick="editUserId({{ $d->id }}, '{{ $d->userklaim }}');"><i
                                                                            class="fas fa-edit"></i></button>
                                                                    <button type="button" style="margin-left: 10px"
                                                                        class="btn bg-gradient-dark btn-outline-light my-4"
                                                                        onclick="editUserClear({{ $d->id }}, '{{ $d->userklaim }}');"><i
                                                                            class="fas fa-trash"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif


                                                </form>
                                            </td>
                                            {{-- <td class="text-center text-white">
                                                @php echo $d->userklaim == '' ? '<i> { Belum klaim } </i>' : $d->userklaim @endphp
                                            </td> --}}
                                            <td class="text-center text-white">
                                                @php
                                                    if ($d->tgl_klaim != null) {
                                                        $tgl_klaim = strtotime($d->tgl_klaim); // Contoh timestamp dari nilai yang ada
                                                        $tgl_klaim = date('d-m-Y', $tgl_klaim);
                                                    } else {
                                                        $tgl_klaim = '<i> { Belum klaim } </i>';
                                                    }
                                                    echo $tgl_klaim;
                                                    
                                                @endphp
                                            </td>
                                            <td class="text-center text-white">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="hidden" id="id" name="id"
                                                        value="{{ $d->id }}">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="status_transfer_{{ $d->id }}" name="status_transfer"
                                                        {{ $d->status_transfer == 1 ? 'checked' : '' }}
                                                        onclick="updateStatusTransfer({{ $d->id }})"
                                                        {{ $d->tgl_klaim == null ? 'disabled' : '' }}>
                                                </div>
                                            </td>
                                            <td class="text-center text-white">
                                                @php
                                                    $tgl_exp = strtotime($d->tgl_exp); // Contoh timestamp dari nilai yang ada
                                                    $tgl_exp = date('d-m-Y', $tgl_exp);
                                                    
                                                    echo $tgl_exp;
                                                    
                                                @endphp
                                            </td>
                                            <td class="text-center text-white"> {{ $d->userid }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <ul class="pagination pagination-secondary justify-content-end mt-3 me-5">
            {{ $data->onEachSide(1)->links('pagination::bootstrap-4') }}
        </ul>

        <script>
            $(document).ready(function() {

                $('.js-daterangepicker').daterangepicker();

                $('#search_tanggal').on('change', function() {
                    $(this).closest('form').submit();
                });
            });
            $('#search_jenis_klaim').on('change', function() {
                $(this).closest('form').submit();
            });

            $('#search_status_transfer').on('change', function() {
                $(this).closest('form').submit();
            });

            function toggleSubmitButton(id) {
                var userInput = document.getElementById('userid-' + id).value;
                var submitButton = document.getElementById('submit-btn-' + id);
                var clearButton = document.getElementById('clear-btn-' + id);

                if (userInput.trim() !== '') {
                    submitButton.style.display = 'block';
                    clearButton.style.display = 'block';
                } else {
                    submitButton.style.display = 'none';
                    clearButton.style.display = 'none';
                }
            }

            function clearUserId(id, username = '') {

                if (username == '') {
                    document.getElementById('userid-' + id).value = '';
                    toggleSubmitButton(id);
                } else {
                    var tdElement = document.getElementById('td-' + id);

                    tdElement.innerHTML = `
                    <div class="col-md-12">
                        <div class="form-group d-flex align-items-center text-center">
                            <span style="margin-right: auto;">${username}</span>
                            <button type="button" class="btn bg-gradient-dark btn-outline-light my-4"
                                onclick="editUserId(${id}, '${username}');">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" style="margin-left : 10px" class="btn bg-gradient-dark btn-outline-light my-4"
                                onclick="editUserClear(${id}, '${username}');">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    `;
                }


            }

            function updateVoucher(event, id) {
                event.preventDefault();
                var token = $('meta[name="csrf-token"]').attr('content');
                var userid = $("#userid-" + id).val();

                $.ajax({
                    url: "/spinner/voucher/update/" + id,
                    method: "PUT",
                    data: {
                        id: id,
                        userklaim: userid,
                        _token: token
                    },
                    success: function(result) {
                        console.log(result);
                        clearUserId(id, userid);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Update gagal');
                    }
                });
            }

            function editUserId(id, username) {
                var tdElement = document.getElementById('td-' + id);
                tdElement.innerHTML = `
                    <div class="col-md-12">
                        <div class="form-group d-flex align-items-center">
                            <input class="form-control" type="text" id="userid-${id}" name="userid-${id}"
                                placeholder="Masukkan No Rekening" value="${username}" style="margin-right: 5px" oninput="toggleSubmitButton(${id})"
                                onkeypress="handleKeyPress(event, ${id})">
                            <button type="button" id="submit-btn-${id}" class="btn bg-gradient-dark btn-outline-light my-4"
                                onclick="updateVoucher(event, ${id});">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" id="clear-btn-${id}" class="btn bg-gradient-dark btn-outline-light my-4"
                                onclick="clearUserId(${id}, '${username}')" style="margin-left: 5px">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                document.getElementById(`userid-${id}`).focus();
            }

            function editUserClear(id, username) {
                var tdElement = document.getElementById('td-' + id);
                tdElement.innerHTML = `
                <div class="col-md-12">
                        <div class="form-group d-flex align-items-center">
                            <input class="form-control" type="text" id="userid-${id}" name="userid-${id}"
                                placeholder="Masukkan No Rekening" value="" style="margin-right: 5px" oninput="toggleSubmitButton(${id})"
                                onkeypress="handleKeyPress(event, ${id})">
                            <button type="button" id="submit-btn-${id}" class="btn bg-gradient-dark btn-outline-light my-4"
                                onclick="updateVoucher(event, ${id});">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" id="clear-btn-${id}" class="btn bg-gradient-dark btn-outline-light my-4"
                                onclick="clearUserId(${id}, '${username}')" style="margin-left: 5px">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                document.getElementById(`userid-${id}`).focus();
            }

            function handleKeyPress(event, id) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    document.getElementById(`submit-btn-${id}`).click();
                }
            }

            function updateStatusTransfer(id) {
                var isChecked = $('#status_transfer_' + id).is(':checked');
                var token = $('meta[name="csrf-token"]').attr('content');
                var url = "{{ route('spinner.update-status', ['id' => ':id']) }}";
                url = url.replace(':id', id);
                console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        status_transfer: isChecked ? 1 : 0,
                        _token: token
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(xhr) {
                        alert('Gagal Update! , Silahkan Hub. Admin');
                        console.log(xhr.responseText);
                    }
                });
            }

            function copyText(text) {
                var tempInput = document.createElement("input");
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                tempInput.setSelectionRange(0, 99999);

                try {
                    document.execCommand("copy");
                    // alert("Teks berhasil disalin: " + text);
                } catch (error) {
                    console.error("Gagal menyalin teks:", error);
                }

                document.body.removeChild(tempInput);
            }


            function confirmDownload() {
                if (confirm('Apakah Anda yakin ingin mendownload?')) {
                    exportArrayToExcel();
                }
            }
            currentUrl

            function exportArrayToExcel() {
                var currentURL =
                    window.location.href;

                var url = new URL(currentURL);
                url.pathname += '/1';
                var newURL = url.href;

                console.log(newURL);

                fetch(newURL)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);

                        // var data = <?php echo json_encode($datavoucher); ?>;
                        var worksheet = XLSX.utils.aoa_to_sheet(data);

                        // Menentukan lebar kolom
                        var columnWidths = [{
                                wch: 5
                            },
                            {
                                wch: 20
                            },
                            {
                                wch: 20
                            },
                            {
                                wch: 70
                            },
                            {
                                wch: 20
                            },
                            {
                                wch: 20
                            },
                            {
                                wch: 20
                            },
                            {
                                wch: 20
                            },
                            {
                                wch: 20
                            },

                        ];

                        // Mengatur lebar kolom pada sheet
                        worksheet["!cols"] = columnWidths;

                        var workbook = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet 1");

                        var excelBuffer = XLSX.write(workbook, {
                            bookType: "xlsx",
                            type: "array"
                        });

                        var blob = new Blob([excelBuffer], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        });

                        var downloadLink = document.createElement("a");
                        document.body.appendChild(downloadLink);
                        downloadLink.href = window.URL.createObjectURL(blob);
                        downloadLink.download = "data-{{ $nama_bo }}-{{ $jumlah }}.xlsx";
                        downloadLink.click();

                    })
                    .catch(error => {
                        // Tangani kesalahan jika terjadi
                        console.error('Terjadi kesalahan:', error);
                    });

            }
        </script>
        <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    @endsection
