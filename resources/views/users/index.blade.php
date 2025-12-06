@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Pengguna</h1>
        </div>

         <div class="section-body">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title text-right">Daftar Pengguna</h4>
                        <a href="{{ route('users.create') }}"  class="btn btn-primary">Tambah Data</a>
                </div> 
                <div class="card-body">
                    <table class="table table-hover table-striped datatable">
                        <thead style="background-color: #EDEDED;">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Email</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Role</th>
                                <th scope="col">Dibuat</th>
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
                    url: '{{ route("users.index") }}',
                },
                columns: [
                    { data: "DT_RowIndex", name: "id" },
                    { data: "email", name: "email" },
                    { data: "name", name: "name" },
                    { data: "role", name: "role" },
                    { data: "created", name: "created" },
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
                url: "/users/" + id,
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