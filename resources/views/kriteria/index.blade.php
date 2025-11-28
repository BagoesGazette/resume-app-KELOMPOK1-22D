@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Kriteria</h1>
        </div>

        <div class="section-body">
            <div class="card card-primary">
                <div class="card-header">
                    <h5>Daftar Kriteria</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead style="background-color: #EDEDED;">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Kode</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Bobot (%)</th>
                                <th scope="col">Desimal</th>
                                <th scope="col">Detail</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $totalBobot = 0;
                            @endphp

                            @foreach ($lists as $key => $item)
                                @php
                                    $totalBobot += $item->bobot;
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->bobot }}%</td>
                                    <td>{{ number_format($item->bobot / 100, 2) }}</td>
                                    <td>
                                        <ol>
                                            @foreach ($item->subKriteria as $row)
                                                <li>{{ $row->name }}</li>
                                            @endforeach
                                        </ol>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot style="background-color: #D4D8F9;">
                            <tr>
                                <th colspan="2"></th>
                                <th>Total</th>
                                <th>{{ $totalBobot }}%</th>
                                <th>{{ number_format($totalBobot / 100, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </section>
@endsection