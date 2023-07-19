@extends('layout.main')

@section('container')
    <div id="ajaxResponse">
        {{-- @dd(session('success')) --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <div class="col-12">
        <div class="container-fluid py-4" id="container-id">
            <div class="row my-4">
                <div class="col-md-4">
                    <button type="button" class="btn btn-icon btn-3 btn btn-outline-light bg-dark" data-bs-toggle="modal"
                        data-bs-target="#modal-jenisvoucher-tambah" id="tambah">
                        <i class="ni ni-fat-add"></i> Tambah
                    </button>
                </div>
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                    <form action="/spinner/jenisvoucher">
                        <div class="input-group">
                            <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                            <input type="text" class="form-control ps-1" placeholder="Type here..." name="search"
                                value="{{ request('search') }}">
                            <button class="btn btn-light" type="submit" id="button-addon2" hidden>Search</button>
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
                                        <th width="45%" class="text-center">
                                            <h6>Nama</h6>
                                        </th>
                                        <th width="45%" class="text-center">
                                            <h6>Index</h6>
                                        </th>
                                        <th width="45%" class="text-center">
                                            <h6>Saldo Point</h6>
                                        </th>
                                        <th class="text-center">
                                            <h6>Action</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody data-aos="fade-up" data-aos-duration="900">
                                    @foreach ($data as $d)
                                        <tr>
                                            <td class="text-center text-white"> {{ $loop->iteration }} </td>
                                            <td class="text-center text-white"> {{ $d['nama'] }} </td>
                                            <td class="text-center text-white"> {{ $d['index'] }} </td>
                                            <td class="text-center text-white"> {{ $d['saldo_point'] }} </td>
                                            <td class="text-center project-actions text-right mt-10">
                                                <form action="{{ url('spinner/jenisvoucher/delete/' . $d['id']) }}"
                                                    method="POST">
                                                    <button type="button" class="badge btn-info bg-dark edit"
                                                        data-bs-toggle="modal" data-bs-target="#modal-jenisvoucher-edit"
                                                        id="edit" value="{{ $d['id'] }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        <span class="badge">Edit</span>
                                                    </button>
                                                    <input type="hidden" id="id" name="id">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="badge btn-danger bg-dark"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal-jenisvoucher-edit{{ $d['id'] }}"
                                                        id="edit" value="{{ $d['id'] }}"
                                                        onclick="return confirm('Apakah anda yakin ingin menghapus?');">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        <span class="badge">Delete</span>
                                                    </button>

                                                </form>
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
            <div class="modal fade" id="modal-jenisvoucher-tambah" tabindex="-1" role="dialog"
                aria-labelledby="modal-jenisvoucher" aria-hidden="true">
                <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                    <div class="modal-content">
                        <form id="form-jenisvoucher">
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
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nama"
                                                class="form-control-label text-white @error('nama') is-invalid @enderror">Nama</label>
                                            <input class="form-control" type="text" id="nama" name="nama"
                                                placeholder="Masukkan Nama">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="index"
                                                class="form-control-label text-white @error('index') is-invalid @enderror">Index</label>
                                            <select name="pilihan" class="form-control" name="index" id="index">
                                                <option value=0>0</option>
                                                <option value=1>1</option>
                                                <option value=2>2</option>
                                                <option value=3>3</option>
                                                <option value=4>4</option>
                                                <option value=5>5</option>
                                                <option value=6>6</option>
                                                <option value=7>7</option>
                                                <option value=8>8</option>
                                                <option value=9>9</option>
                                                <option value=10>10</option>
                                                <option value=11>11</option>
                                                <option value=12>12</option>
                                                <option value=13>13</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="saldo_point"
                                                class="form-control-label text-white @error('saldo_point') is-invalid @enderror">Saldo
                                                Point</label>
                                            <select name="pilihan" class="form-control" name="saldo_point"
                                                id="saldo_point">
                                                <option value=0>0</option>
                                                <option value=2000>2.000</option>
                                                <option value=5000>5.000</option>
                                                <option value=10000>10.000</option>
                                                <option value=20000>20.000</option>
                                                <option value=50000>50.000</option>
                                                <option value=100000>100.000</option>
                                                <option value=200000>200.000</option>
                                                <option value=350000>350.000</option>
                                                <option value=500000>500.000</option>
                                                <option value=1000000>1.000.000</option>
                                                <option value=2000000>2.000.000</option>
                                                <option value=5000000>5.000.000</option>
                                                <option value=10000000>10.000.000</option>
                                                <option value=20000000>20.000.000</option>
                                                <option value=50000000>50.000.000</option>
                                                <option value=100000000>100.000.000</option>
                                            </select>
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

            {{-- MODAL EDIT --}}
            <div class="modal fade" id="modal-jenisvoucher-edit" tabindex="-1" role="dialog"
                aria-labelledby="modal-jenisvoucher" aria-hidden="true">
                <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                    <div class="modal-content">
                        <div class="alert alert-danger"></div>
                        <form id="form-jenisvoucher">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-modal-popup">
                                <h6 class="modal-title text-white" id="modal-title-default">Edit Jenis Voucher</h6>
                                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true" class="close-btn-popup">X</span>
                                </button>
                            </div>

                            <div class="modal-body bg-modal-popup">
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input class="form-control" type="hidden" id="id-edit" name="id-edit">
                                            <label for="nama-edit"
                                                class="form-control-label text-white @error('nama-edit') is-invalid @enderror">Nama</label>
                                            <input class="form-control" type="text" id="nama-edit" name="nama-edit"
                                                placeholder="Masukkan Nama">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="index-edit"
                                                class="form-control-label text-white @error('index-edit') is-invalid @enderror">index</label>
                                            <select name="pilihan" class="form-control" name="index-edit"
                                                id="index-edit">
                                                <option value=0>0</option>
                                                <option value=1>1</option>
                                                <option value=2>2</option>
                                                <option value=3>3</option>
                                                <option value=4>4</option>
                                                <option value=5>5</option>
                                                <option value=6>6</option>
                                                <option value=7>7</option>
                                                <option value=8>8</option>
                                                <option value=9>9</option>
                                                <option value=10>10</option>
                                                <option value=11>11</option>
                                                <option value=12>12</option>
                                                <option value=13>13</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="saldo_point-edit"
                                                class="form-control-label text-white @error('saldo_point-edit') is-invalid @enderror">Saldo
                                                Point</label>
                                            <select name="pilihan" class="form-control" name="saldo_point-edit"
                                                id="saldo_point-edit">
                                                <option value=0>0</option>
                                                <option value=2000>2.000</option>
                                                <option value=5000>5.000</option>
                                                <option value=10000>10.000</option>
                                                <option value=20000>20.000</option>
                                                <option value=50000>50.000</option>
                                                <option value=100000>100.000</option>
                                                <option value=200000>200.000</option>
                                                <option value=350000>350.000</option>
                                                <option value=500000>500.000</option>
                                                <option value=1000000>1.000.000</option>
                                                <option value=2000000>2.000.000</option>
                                                <option value=5000000>5.000.000</option>
                                                <option value=10000000>10.000.000</option>
                                                <option value=20000000>20.000.000</option>
                                                <option value=50000000>50.000.000</option>
                                                <option value=100000000>100.000.000</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer bg-modal-popup">
                                <button type="submit"
                                    class="btn bg-gradient-dark btn-outline-light my-4 edit-btn">Submit</button>
                                <button type="button" id="cancel-btn-edit"
                                    class="btn bg-gradient-dark btn-outline-light my-4"
                                    data-bs-dismiss="modal">Batal</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $("#submit-btn").click(function(e) {
                    event.preventDefault();

                    $.ajax({
                        url: "{{ url('spinner/jenisvoucher/create') }}",
                        method: "POST",
                        data: {
                            nama: $("#nama").val(),
                            index: $("#index").val(),
                            saldo_point: $("#saldo_point").val(),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(result) {

                            if (result.errors) {
                                $('.alert-danger').html('');

                                $.each(result.errors, function(key, value) {
                                    $('.alert-danger').show();
                                    $('.alert-danger').append('<li>' + value + '</li>');
                                });
                            } else {
                                $('.alert-danger').hide();
                                $.pjax.reload({
                                    container: '#container-id'
                                });
                                $("#modal-bo-tambah").modal("hide");
                            }
                            // console.log(response);
                        },
                        error: function(xhr) {
                            // tambahkan kode untuk menangani kesalahan saat mengirimkan data ke server
                            console.log(xhr.responseText);
                        }
                    });
                });


                var id;
                $(document).on('click', '.edit', function() {
                    id = $(this).val();

                    fetch(`/spinner/jenisvoucher/data/${id}`).then(
                        response => response.json()).then(data => {
                        // console.log(data);
                        document.getElementById('id-edit').value = data.id;
                        document.getElementById('nama-edit').value = data.nama;
                        document.getElementById('index-edit').value = data.index;
                        document.getElementById('saldo_point-edit').value = data.saldo_point;
                    });
                });


                $(".edit-btn").click(function(e) {

                    event.preventDefault();

                    $.ajax({
                        url: "/spinner/jenisvoucher/update/" + $("#id-edit").val(),
                        method: "PUT",
                        data: {
                            id: $("#id-edit").val(),
                            nama: $("#nama-edit").val(),
                            index: $("#index-edit").val(),
                            saldo_point: $("#saldo_point-edit").val(),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(result) {

                            if (result.errors) {
                                $('.alert-danger').html('');

                                $.each(result.errors, function(key, value) {
                                    $('.alert-danger').show();
                                    $('.alert-danger').append('<li>' + value + '</li>');
                                });
                            } else {
                                // console.log(result);
                                $('.alert-danger').hide();

                                $.pjax.reload({
                                    container: '#container-id'
                                });
                                $("#modal-jenisvoucher-edit").modal("hide");
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

        <ul class="pagination pagination-secondary justify-content-end mt-3 me-5">
            {{ $data->onEachSide(1)->links('pagination::bootstrap-4') }}
        </ul>
    @endsection
