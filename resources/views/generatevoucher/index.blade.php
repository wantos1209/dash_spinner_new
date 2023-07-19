@extends('layout.main')

@section('container')
    @php
        $search_bo = request()->get('search_bo');
        $search_jenis_voucher = request()->get('search_jenis_voucher');
        $search_tanggal = request()->get('search_tanggal');
        $search_tampilvoucher = request()->get('search_tampilvoucher');
        
        if (isset($search_bo) || $search_bo != '') {
            $search_bo = $search_bo;
        } else {
            $search_bo = '';
        }
        
        if (isset($search_jenis_voucher) || $search_jenis_voucher != '') {
            $search_jenis_voucher = $search_jenis_voucher;
        } else {
            $search_jenis_voucher = '';
        }
        if (isset($search_tanggal) || $search_tanggal != '') {
            $search_tanggal = $search_tanggal;
        } else {
            $search_tanggal = $search_tgl_default;
        }
        
        if (isset($search_tampilvoucher) || $search_tampilvoucher != '') {
            $search_tampilvoucher = $search_tampilvoucher;
        } else {
            $search_tampilvoucher = '';
        }
    @endphp
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
            <div class="row my-4" id="container-id">
                <div class="col-md-4">
                    {{-- @dd(isAdmin()) --}}
                    @if (isAdmin())
                        <button type="button" class="btn btn-icon btn-3 btn btn-outline-light bg-dark" data-bs-toggle="modal"
                            data-bs-target="#modal-voucher-tambah" id="tambah">
                            <i class="ni ni-fat-add"></i> Generate Voucher
                        </button>
                    @endif
                </div>
                <div class="col-md-8" style="margin-bottom: 10px">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3 ">
                                <input type="text" class="form-control js-daterangepicker" name="search_tanggal"
                                    id="search_tanggal" value="{{ $search_tanggal }}">

                            </div>
                            <div class="col-md-3">

                                <select class="form-select search_bo" aria-label="Default select example" name="search_bo"
                                    id="search_bo">
                                    <option value=''>Pilih Bo</option>
                                    @foreach ($databo as $db)
                                        <option value="{{ $db->nama }}" {{ $db->nama == $search_bo ? 'selected' : '' }}>
                                            {{ $db->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">

                                <select class="form-select search_jenis_voucher" aria-label="Default select example"
                                    name="search_jenis_voucher" id="search_jenis_voucher">
                                    <option value=''>Pilih jenis voucher</option>
                                    @foreach ($jenis_voucher as $jv)
                                        <option value="{{ $jv['index'] }}"
                                            {{ $jv['index'] == $search_jenis_voucher ? 'selected' : '' }}>
                                            {{ $jv['nama'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-md-3">

                                <select class="form-select search_tampilvoucher" aria-label="Default select example"
                                    name="search_tampilvoucher" id="search_tampilvoucher">
                                    <option value="">Tampilkan semua</option>
                                    <option value="1" {{ $search_tampilvoucher == '1' ? 'selected' : '' }}>
                                        Voucher Ready
                                    </option>
                                    <option value="2" {{ $search_tampilvoucher == '2' ? 'selected' : '' }}>
                                        Voucher Complete
                                    </option>

                                </select>
                            </div> --}}


                            {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
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
                                            <h6>Nama Bo</h6>
                                        </th>
                                        <th class="text-center">
                                            <h6>Jenis Voucher</h6>
                                        </th>
                                        <th class="text-center">
                                            <h6>Tanggal Exp</h6>
                                        </th>
                                        <th class="text-center">
                                            <h6>User Klaim</h6>
                                        </th>
                                        <th class="text-center">
                                            <h6>Jumlah</h6>
                                        </th>

                                        <th class="text-center">
                                            <h6>Action</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody data-aos="fade-up" data-aos-duration="900">

                                    @foreach ($data as $index => $d)
                                        <tr>
                                            <td class="text-center text-white"> {{ $loop->iteration }} </td>
                                            <td class="text-center text-white"> {{ $d['bo'] }} </td>
                                            <td class="text-center text-white"> {{ $d['jenis_voucher'] }} </td>
                                            <td class="text-center text-white">
                                                @php
                                                    $timestamp = strtotime($d['tgl_exp']); // Contoh timestamp dari nilai yang ada
                                                    $date = date('d-m-Y', $timestamp);
                                                    echo $date;
                                                @endphp
                                            </td>
                                            <td class="text-center text-white">
                                                {{ $voucherterpakaibb[$index] }}
                                            </td>
                                            <td class="text-center text-white">
                                                {{ $voucherterpakai[$index] . '/' . $d['jumlah'] }}
                                            </td>
                                            <td class="text-center project-actions text-right mt-10">

                                                <button
                                                    onclick="window.location.href='{{ route('spinner.voucher', ['id' => $d['id']]) }}'"
                                                    class="badge btn-danger bg-dark d-inline"><i class="fas fa-eye"></i>
                                                    <span class="badge">View</span></button>

                                                @if (isAdmin())
                                                    <form class="d-inline"
                                                        action="{{ url('/spinner/generatevoucher/delete/' . $d['id']) }}"
                                                        method="POST">

                                                        <input type="hidden" id="id" name="id">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="badge btn-danger bg-dark"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modal-voucher-edit{{ $d['id'] }}"
                                                            id="edit" value="{{ $d['id'] }}"
                                                            onclick="return confirm('Apakah anda yakin ingin menghapus?');">
                                                            <i class="fas fa-pencil-alt"></i>
                                                            <span class="badge">Delete</span>
                                                        </button>

                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL TAMBAH --}}
            <div class="modal fade" id="modal-voucher-tambah" tabindex="-1" role="dialog" aria-labelledby="modal-voucher"
                aria-hidden="true">
                <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                    <div class="modal-content">
                        <form id="form-voucher">
                            @csrf
                            <div class="modal-header bg-modal-popup">
                                <h6 class="modal-title" id="modal-title-default text-white">Tambah Jenis Voucher</h6>
                                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true" class="close-btn-popup">X</span>
                                </button>
                            </div>
                            <div class="alert alert-danger"></div>

                            <div class="modal-body bg-modal-popup">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="bo"
                                                class="form-control-label text-white @error('bo') is-invalid @enderror">
                                                Nama
                                                Bo</label>
                                            <select name="bo" class="form-control" id="bo">
                                                <option value=''>Pilih Bo</option>
                                                @foreach ($databo as $db)
                                                    <option value="{{ $db->nama }}">{{ $db->nama }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="jenis_voucher"
                                                class="form-control-label text-white @error('jenis_voucher') is-invalid @enderror">Jenis
                                                Voucher</label>
                                            <select name="jenis_voucher" class="form-control" id="jenis_voucher">
                                                <option value=''>Pilih Opsi</option>
                                                @foreach ($jenis_voucher as $jv)
                                                    <option value="{{ $jv['index'] }}">{{ $jv['nama'] }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="tgl_exp"
                                                class="form-control-label text-white @error('tgl_exp') is-invalid @enderror">
                                                Tanggal Exp
                                            </label>
                                            <input class="form-control" type="date" name="tgl_exp" id="tgl_exp"
                                                value="{{ date('Y-m-d') }}" id="tgl_exp">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="jumlah"
                                                class="form-control-label text-white @error('jumlah') is-invalid @enderror">
                                                Jumlah</label>
                                            <input class="form-control" type="number" placeholder="0" id="jumlah"
                                                name="jumlah">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer bg-modal-popup">
                                <button type="submit" id="submit-btn"
                                    class="btn bg-gradient-dark btn-outline-light my-4">Submit</button>
                                <button type="button" id="cancel-btn-tambah"
                                    class="btn bg-gradient-dark btn-outline-light my-4"
                                    data-bs-dismiss="modal">Batal</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>

        <script src="{{ asset('js/custom-script.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.js-daterangepicker').daterangepicker();

                $('#search_jenis_voucher').on('change', function() {
                    $(this).closest('form').submit();
                });

                $('#search_bo').on('change', function() {
                    $(this).closest('form').submit();
                });

                $('#search_tanggal').on('change', function() {
                    $(this).closest('form').submit();
                });

                $('#search_tampilvoucher').on('change', function() {
                    $(this).closest('form').submit();
                });

                $("#submit-btn").click(function(e) {
                    event.preventDefault();

                    $.ajax({
                        url: "{{ url('/spinner/generatevoucher/create') }}",
                        method: "POST",
                        data: {
                            bo: $("#bo").val(),
                            jenis_voucher: $("#jenis_voucher").val(),
                            tgl_exp: $("#tgl_exp").val(),
                            jumlah: $("#jumlah").val(),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(result) {

                            if (result.errors) {
                                $('.alert-danger').html('');

                                $.each(result.errors, function(key, value) {
                                    $('.alert-danger').show();
                                    $('.alert-danger').append('<li>' + value + '</li>');
                                });
                                if (response.status === 'success') {
                                    $('#ajaxResponse').html('<div class="alert alert-success">' +
                                        response.message + '</div>');
                                }
                            } else {
                                // console.log(result);
                                $('.alert-danger').hide();
                                $.pjax.reload({
                                    container: '#container-id'
                                });
                                $("#modal-voucher-tambah").modal("hide");
                            }
                            // console.log(response);
                        },
                        error: function(xhr) {
                            // tambahkan kode untuk menangani kesalahan saat mengirimkan data ke server
                            console.log(xhr.responseText);
                        }
                    });
                });

                $("#cancel-btn-tambah").click(function(e) {
                    $('#nama').val('');
                    $('.alert-danger').hide();
                });

                $("#modal-jenisvoucher-edit").on("hidden.bs.modal", function() {
                    $('#nama').val('');
                    $('.alert-danger').hide();
                });


                $("#modal-jenisvoucher-tambah").on("hidden.bs.modal", function() {
                    $('#nama').val('');
                    $('.alert-danger').hide();
                });

                $("#cancel-btn-edit").click(function(e) {
                    $('#nama').val('');
                    $('.alert-danger').hide();
                });

            });
        </script>


        {{-- <ul class="pagination pagination-secondary justify-content-end mt-3 me-5">
            {{ $data->onEachSide(1)->links('pagination::bootstrap-4') }}
        </ul> --}}
    @endsection
