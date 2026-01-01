<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\ApplicantsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JobOpening::with('apply')->latest('id');
            
            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $data->where('status', $request->status);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('judul_formatted', function ($row) {
                    $colors = ['#667eea', '#11998e', '#f7971e', '#eb3349', '#00c6fb', '#a855f7'];
                    $color = $colors[$row->id % count($colors)];
                    $initials = strtoupper(substr($row->perusahaan ?? 'XX', 0, 2));
                    
                    return '
                    <div class="job-info-cell">
                        <div class="job-logo" style="background: '.$color.';">
                            '.$initials.'
                        </div>
                        <div class="job-details">
                            <h6>'.$row->judul.'</h6>
                            <small><i class="fas fa-tag mr-1"></i> '.($row->category ?? 'Umum').'</small>
                        </div>
                    </div>';
                })
                ->addColumn('lokasi_formatted', function ($row) {
                    return '<span class="location-badge"><i class="fas fa-map-marker-alt mr-1"></i> '.$row->lokasi.'</span>';
                })
                ->addColumn('status', function ($row) {
                    if ($row->status === 'open') {
                        return '<span class="status-badge open"><i class="fas fa-check-circle mr-1"></i> Buka</span>';
                    } else {
                        return '<span class="status-badge closed"><i class="fas fa-times-circle mr-1"></i> Tutup</span>';
                    }
                })
                ->addColumn('total_pelamar', function ($row) {
                    $count = $row->apply->count();
                    $class = $count > 10 ? 'high' : ($count > 5 ? 'medium' : 'low');
                    return '<span class="applicant-count '.$class.'"><i class="fas fa-users mr-1"></i> '.$count.' Pelamar</span>';
                })
                ->addColumn('created_formatted', function ($row) {
                    return '<span class="date-badge"><i class="fas fa-calendar mr-1"></i> '.$row->created_at->format('d M Y').'</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <div class="action-buttons">
                        <a href="'.route('job.show', $row->id).'" class="btn-action view" data-toggle="tooltip" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="'.route('job.edit', $row->id).'" class="btn-action edit" data-toggle="tooltip" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn-action delete" data-toggle="tooltip" title="Hapus" onclick="Delete('.$row->id.')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>';
                }) 
                ->rawColumns(['action', 'status', 'total_pelamar', 'judul_formatted', 'lokasi_formatted', 'created_formatted'])
                ->make(true);
        }

        // Statistics
        $stats = [
            'total' => JobOpening::count(),
            'open' => JobOpening::where('status', 'open')->count(),
            'closed' => JobOpening::where('status', 'closed')->count(),
            'totalApplicants' => JobApplication::count(),
        ];

        // Top jobs by applicants
        $topJobs = JobOpening::withCount('apply')
            ->orderByDesc('apply_count')
            ->take(5)
            ->get();

        return view('job.index', compact('stats', 'topJobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('job.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'judul'     => 'required',
            'perusahaan'=> 'required',
            'lokasi'    => 'required',
            'deskripsi' => 'nullable',
            'tipe'      => 'nullable',
            'tanggal_tutup' => 'nullable',
            'category' => 'required',
        ]);

        try{

            DB::beginTransaction();
                JobOpening::create($validated);

            DB::commit();

            $notification = array(
                'success'   => 'Berhasil tambah lowongan kerja '.$validated['judul'],
            );

            return redirect()->route('job.index')->with($notification);

        } catch (\Throwable $e) {
            return redirect()->route('job.index')->with(['error' => 'Tambah data gagal! ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = JobOpening::with(['apply' => function($query) {
            $query->with(['user', 'cvSubmission'])
                ->whereNotNull('score')
                ->orderByDesc('score');
        }])->find($id);

        $topCandidates = $detail->apply
            ->whereNotNull('score')
            ->where('score', '>', 0)
            ->sortByDesc('score')
            ->take(3)
            ->values();
        
        return view('job.show', compact('detail', 'topCandidates'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $detail = JobOpening::find($id);

        
        return view('job.edit', compact('detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'judul'     => 'required',
            'perusahaan'=> 'required',
            'lokasi'    => 'required',
            'deskripsi' => 'nullable',
            'tipe'      => 'nullable',
            'tanggal_tutup' => 'nullable',
            'category' => 'required',
        ]);

        try{
            $detail = JobOpening::find($id);
            DB::beginTransaction();
                $detail->update($validated);

            DB::commit();

            $notification = array(
                'success'   => 'Berhasil update lowongan kerja '.$validated['judul'],
            );

            return redirect()->route('job.index')->with($notification);

        } catch (\Throwable $e) {
            return redirect()->route('job.index')->with(['error' => 'Tambah data gagal! ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data =  JobOpening::find($id);
            if ($data) {
                $data->delete();
            }

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function close($id)
    {
        try {
            $data =  JobOpening::find($id);
            if ($data) {
                $data->update([
                    'status' => 'closed'
                ]);
            }

            $notification = array(
                'success'   => 'Berhasil menutup lowongan kerja '.$data->judul,
            );

            return redirect()->back()->with($notification);
        } catch (\Throwable $e) {
            return redirect()->back()->with(['error' => 'Data gagal! ' . $e->getMessage()]);
        }
    }

    public function scoring($id)
    {
        $kriteriaTipe = [
            1 => 'benefit', // C1 - Pendidikan (semakin tinggi semakin baik)
            2 => 'benefit', // C2 - Nilai/IPK (semakin tinggi semakin baik)
            3 => 'benefit', // C3 - Pengalaman (semakin banyak semakin baik)
        ];

        $kriteria = Kriteria::whereIn('code', ['C1', 'C2', 'C3'])->get();
        $jobApplications = JobApplication::where('jobopening_id', $id)->get();
        
        $users = [];
        
        // 1. Pengumpulan Data Penilaian
        foreach ($jobApplications as $row) {
            $cv = $row->cvSubmission;
            
            if (!$cv) {
                continue;
            }
            
            $users[] = $row->user_id;

            $kriteriaMapping = [
                1 => 'pendidikan',
                2 => 'nilai',
                3 => 'pengalaman',
            ];

            foreach ($kriteriaMapping as $kriteriaId => $cvField) {
                $existing = Penilaian::where([
                    'job_id'        => $id,
                    'alternatif_id' => $row->user_id,
                    'kriteria_id'   => $kriteriaId
                ])->first();

                if (empty($existing)) {
                    Penilaian::create([
                        'alternatif_id' => $row->user_id,
                        'kriteria_id'   => $kriteriaId,
                        'job_id'        => $id,
                        'parameter'     => $this->checkCv($cv, $cvField)
                    ]);
                }
            }
        }
        
        $alternatif = User::whereIn('id', $users)->get();
        $penilaian  = Penilaian::where('job_id', $id)->get();
        
        // 2. Hitung Max dan Min untuk setiap kriteria
        $max_min = $kriteria->mapWithKeys(function ($k) use ($penilaian) {
            $values = $penilaian
                ->where('kriteria_id', $k->id)
                ->pluck('parameter')
                ->filter(fn($v) => $v > 0);
        
            return [
                $k->id => [
                    'max' => $values->max() ?: 1,
                    'min' => $values->min() ?: 1,
                ]
            ];
        });

        // 3. Normalisasi dan Kalkulasi Skor (WSM)
        $hasil_normalisasi = $alternatif->map(function ($alt) use ($penilaian, $kriteria, $max_min, $kriteriaTipe) {
            $total_skor = 0;
            $perhitungan_detail = [];
        
            $kriteria->each(function ($k) use ($penilaian, $max_min, $alt, &$total_skor, &$perhitungan_detail, $kriteriaTipe) {
                $nilai = $penilaian
                    ->where('alternatif_id', $alt->id)
                    ->where('kriteria_id', $k->id)
                    ->first();
                
                if ($nilai && $nilai->parameter > 0) {
                    $parameter_value = $nilai->parameter;
                    $bobot = $k->bobot;
                    
                    // PERBAIKAN: Gunakan tipe dari array, bukan dari database
                    $tipe = $kriteriaTipe[$k->id] ?? 'benefit';
                    
                    $max = $max_min[$k->id]['max'];
                    $min = $max_min[$k->id]['min'];
                    
                    // Normalisasi sesuai jurnal WSM
                    if ($tipe === 'benefit') {
                        // Benefit: semakin tinggi semakin baik
                        $normalisasi = $parameter_value / $max;
                    } else {
                        // Cost: semakin rendah semakin baik
                        $normalisasi = $min / $parameter_value;
                    }

                    $weighted_value = $normalisasi * $bobot;
                    $total_skor += $weighted_value;

                    $perhitungan_detail[] = [
                        'kriteria_id'     => $k->id,
                        'kriteria_code'   => $k->code,
                        'tipe'            => $tipe,
                        'nilai_asli'      => $parameter_value,
                        'max'             => $max,
                        'min'             => $min,
                        'normalisasi'     => round($normalisasi, 4),
                        'bobot'           => $bobot,
                        'weighted_value'  => round($weighted_value, 4),
                    ];
                }
            });
        
            return [
                'id'                 => $alt->id,
                'nama'               => $alt->name,
                'total_skor'         => round($total_skor, 4),
                'detail_perhitungan' => $perhitungan_detail,
            ];
        });
        
        // 4. Ranking: Urutkan dari skor tertinggi
        $hasil_ranking = $hasil_normalisasi
            ->sortByDesc('total_skor')
            ->values();

        // 5. Update ke database
        foreach ($hasil_ranking as $index => $item) {
            JobApplication::where('jobopening_id', $id)
                ->where('user_id', $item['id'])
                ->update([
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => Carbon::now(),
                    'score'   => $item['total_skor'],
                    'ranking' => $index + 1,
                    'status'  => 'reviewed'
                ]);
        }
        
        return redirect()->back()->with([
            'success' => 'Berhasil melakukan perhitungan'
        ]);
    }

    private function checkCv($cv, $type): int
    {
        if (!$cv) {
            return 1;
        }

        return match ($type) {
            'pendidikan' => $this->scorePendidikan($cv->tipe_pendidikan),
            'nilai'      => $this->scoreNilai($cv->ipk_nilai_akhir),
            'pengalaman' => $this->scorePengalaman($cv->pengalaman),
            default      => 1,
        };
    }

    private function scorePendidikan($tipePendidikan): int
    {
        return match ((int) $tipePendidikan) {
            1 => 1, // SMA
            2 => 2, // D3
            3 => 3, // S1
            4 => 4, // S2
            default => 1,
        };
    }

    private function scoreNilai($ipk): int
    {
        $ipk = (float) $ipk;
        
        return match (true) {
            $ipk >= 3.75 => 4,
            $ipk >= 3.25 => 3,
            $ipk >= 2.75 => 2,
            default      => 1,
        };
    }

    private function scorePengalaman($tahun): int
    {
        $tahun = (int) $tahun;
        
        return match (true) {
            $tahun >= 5 => 4,
            $tahun >= 3 => 3,
            $tahun >= 1 => 2,
            default     => 1,
        };
    }

    public function exportExcel($id)
    {
        $export = new ApplicantsExport($id);
        return $export->download();
    }

    public function exportPdf($id)
    {
        $job = JobOpening::with(['apply' => function($query) {
            $query->with(['user', 'cvSubmission'])
                  ->orderBy('ranking', 'asc');
        }])->findOrFail($id);

        $data = [
            'job' => $job,
            'applicants' => $job->apply,
            'exportDate' => now()->locale('id')->translatedFormat('d F Y H:i'),
            'pendidikanLabels' => [
                1 => 'SMA/SMK',
                2 => 'D3',
                3 => 'S1',
                4 => 'S2/S3',
            ],
            'statusLabels' => [
                'submitted' => 'Pending',
                'reviewed' => 'Reviewed',
                'interview' => 'Interview',
                'accepted' => 'Diterima',
                'rejected' => 'Ditolak',
            ],
        ];

        $pdf = Pdf::loadView('exports.applicants-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'Pelamar_' . str_replace(' ', '_', $job->judul) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function checkStatus()
    {
        $job = JobOpening::where('status', 'open')
        ->get();

        foreach ($job as $item) {
            $daysLeft = now()->diffInDays($item->tanggal_tutup, false);
            if($daysLeft < 0){
                $item->update([
                    'status' => 'closed'
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
