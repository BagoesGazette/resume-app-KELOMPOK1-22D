@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Lowongan Kerja</h1>
        </div>

         <div class="section-body">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title text-right">Daftar Lowongan</h4>
                        <a href="{{ route('job.create') }}"  class="btn btn-primary">Tambah Data</a>
                </div> 
                <div class="card-body">
                    <table class="table table-hover table-striped datatable">
                        <thead style="background-color: #EDEDED;">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Perusahaan</th>
                                <th scope="col">Lokasi</th>
                                <th scope="col">Total Pelamar</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>

                    </table>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('custom-js')
    <script>
        $(document).ready(function() {
            var table = $(".datatable").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("job.index") }}',
                },
                columns: [
                    { data: "DT_RowIndex", name: "id" },
                    { data: "judul", name: "judul" },
                    { data: "perusahaan", name: "perusahaan" },
                    { data: "lokasi", name: "lokasi" },
                    { data: "total_pelamar", name: "total_pelamar" },
                    { data: "status", name: "status" },
                    { data: "action", name: "action" },
                ],
            });

            table.on("draw", function() {
                $('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();
            });
        });

      function Delete(id) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus Data!",
                cancelButtonText: "Batalkan",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // ajax delete
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        }
                    });

                    $.ajax({
                        url: "/job/" + id,
                        type: "DELETE",
                        data: { id: id },
                        success: function (response) {
                            if (response.status === "success") {
                                Swal.fire({
                                    title: "Sukses!",
                                    text: "Berhasil menghapus data!",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: "Data tidak berhasil dihapus.",
                                    icon: "error"
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            });
        }

    </script>
@endpush