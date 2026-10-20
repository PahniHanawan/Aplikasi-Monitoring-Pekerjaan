<?php

namespace App\Http\Controllers;

use App\Models\Kantor;
use App\Models\Proyek;
use App\Models\Kontrak;
use App\Models\Realisasi;
use App\Models\Biaya;
use App\Models\DokumenProyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $kantorCount = Kantor::count();
        $proyekCount = Proyek::count();
        $kontrakCount = Kontrak::count();

        $totalNilaiKontrak = Kontrak::sum('nilai_kontrak');
        $totalPaguBiaya = Kantor::sum('pagu_biaya');

        // Hitung total realisasi dari tabel realisasis
        $totalRealisasi = Realisasi::sum('nilai_realisasi');
        $rataRataRealisasi = $totalNilaiKontrak > 0 ? ($totalRealisasi / $totalNilaiKontrak) * 100 : 0;

        // Data untuk charts
        $kantors = Kantor::withCount('proyeks')->get();

        $kantorNames = $kantors->pluck('nama_kantor')->toArray();
        $proyekPerKantor = $kantors->pluck('proyeks_count')->toArray();

        // Nilai kontrak per kantor (dalam miliar)
        $nilaiKontrakPerKantor = $kantors->map(function($kantor) {
            $totalNilai = DB::table('kontraks')
                ->join('proyeks', 'kontraks.proyek_id', '=', 'proyeks.id')
                ->where('proyeks.kantor_id', $kantor->id)
                ->sum('kontraks.nilai_kontrak');

            return round($totalNilai / 1000000000, 2);
        })->toArray();

        // Status proyek berdasarkan realisasi
        $statusData = $this->getStatusProyekData();
        $statusLabels = ['Belum Realisasi', 'Sedang Berjalan', 'Selesai'];

        // Pisahkan data counts dan percentages
        $statusCounts = $statusData['counts'];
        $statusPercentages = $statusData['percentages'];

        // Trend kontrak tahun ini
        $trendData = $this->getTrendKontrak();
        $trendLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Top 5 kantor berdasarkan pagu biaya
        $topKantors = Kantor::withCount('proyeks')
            ->orderBy('pagu_biaya', 'desc')
            ->limit(5)
            ->get()
            ->map(function($kantor) {
                $totalNilai = DB::table('kontraks')
                    ->join('proyeks', 'kontraks.proyek_id', '=', 'proyeks.id')
                    ->where('proyeks.kantor_id', $kantor->id)
                    ->sum('kontraks.nilai_kontrak');

                $totalRealisasi = DB::table('realisasis')
                    ->join('kontraks', 'realisasis.kontrak_id', '=', 'kontraks.id')
                    ->join('proyeks', 'kontraks.proyek_id', '=', 'proyeks.id')
                    ->where('proyeks.kantor_id', $kantor->id)
                    ->sum('realisasis.nilai_realisasi');

                return [
                    'nama' => $kantor->nama_kantor,
                    'pagu_biaya' => $kantor->pagu_biaya,
                    'proyek_count' => $kantor->proyeks_count,
                    'total_nilai' => $totalNilai,
                    'total_realisasi' => $totalRealisasi
                ];
            });

        // Proyek terbaru
        $recentProyeks = Proyek::with(['kantor', 'kontraks.realisasis'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($proyek) {
                $totalKontrak = $proyek->kontraks->sum('nilai_kontrak');
                $totalRealisasi = $proyek->kontraks->flatMap->realisasis->sum('nilai_realisasi');

                $progress = $totalKontrak > 0 ? ($totalRealisasi / $totalKontrak) * 100 : 0;

                return [
                    'nama' => $proyek->nama_proyek,
                    'kantor' => $proyek->kantor->nama_kantor ?? '-',
                    'tanggal' => $proyek->created_at->format('d/m/Y'),
                    'progress' => $progress,
                    'status' => $this->getStatusText($progress),
                    'status_color' => $this->getStatusColor($progress)
                ];
            });

        // Data biaya per jenis
        $biayaData = $this->getBiayaData();

        // Data untuk calendar events
        $calendarEvents = $this->getCalendarEvents();

        return view('dashboard', compact(
            'kantorCount',
            'proyekCount',
            'kontrakCount',
            'totalNilaiKontrak',
            'totalPaguBiaya',
            'totalRealisasi',
            'rataRataRealisasi',
            'kantorNames',
            'proyekPerKantor',
            'nilaiKontrakPerKantor',
            'statusCounts',
            'statusPercentages',
            'statusLabels',
            'trendData',
            'trendLabels',
            'topKantors',
            'recentProyeks',
            'biayaData',
            'calendarEvents'
        ));
    }

    private function getStatusProyekData()
    {
        // Hitung proyek berdasarkan progress realisasi
        $proyeks = Proyek::with(['kontraks.realisasis'])->get();

        $belumRealisasi = 0;
        $sedangBerjalan = 0;
        $selesai = 0;

        foreach ($proyeks as $proyek) {
            $totalKontrak = $proyek->kontraks->sum('nilai_kontrak');
            $totalRealisasi = $proyek->kontraks->flatMap->realisasis->sum('nilai_realisasi');

            $progress = $totalKontrak > 0 ? ($totalRealisasi / $totalKontrak) * 100 : 0;

            if ($progress == 0) {
                $belumRealisasi++;
            } elseif ($progress >= 100) {
                $selesai++;
            } else {
                $sedangBerjalan++;
            }
        }

        $totalProyek = $belumRealisasi + $sedangBerjalan + $selesai;

        // Hitung persentase
        $persentaseBelum = $totalProyek > 0 ? round(($belumRealisasi / $totalProyek) * 100, 1) : 0;
        $persentaseBerjalan = $totalProyek > 0 ? round(($sedangBerjalan / $totalProyek) * 100, 1) : 0;
        $persentaseSelesai = $totalProyek > 0 ? round(($selesai / $totalProyek) * 100, 1) : 0;

        return [
            'counts' => [$belumRealisasi, $sedangBerjalan, $selesai],
            'percentages' => [$persentaseBelum, $persentaseBerjalan, $persentaseSelesai]
        ];
    }

    private function getTrendKontrak()
    {
        $trend = DB::table('kontraks')
            ->select(DB::raw('MONTH(tanggal) as month, COUNT(*) as count'))
            ->whereYear('tanggal', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Inisialisasi array 12 bulan dengan nilai 0
        $monthlyData = array_fill(1, 12, 0);

        // Isi data yang ada
        foreach ($trend as $data) {
            $monthlyData[$data->month] = $data->count;
        }

        return array_values($monthlyData);
    }

    private function getBiayaData()
    {
        return [
            'pelaksanaan' => Biaya::where('jenis', 'Pelaksanaan')->sum('nominal_realisasi'),
            'pemeliharaan' => Biaya::where('jenis', 'Pemeliharaan')->sum('nominal_realisasi')
        ];
    }

    private function getCalendarEvents()
    {
        $events = [];

        // Events dari Kontrak (Tanggal SPK dan Deadline)
        $kontraks = Kontrak::with('proyek')->get();
        foreach ($kontraks as $kontrak) {
            // Event: Tanggal SPK
            if ($kontrak->tanggal) {
                $events[] = [
                    'title' => 'SPK: ' . $kontrak->proyek->nama_proyek,
                    'start' => $kontrak->tanggal,
                    'end' => $kontrak->tanggal,
                    'color' => '#3b82f6',
                    'type' => 'kontrak',
                    'icon' => 'file-earmark-text',
                    'description' => 'Nomor SPK: ' . $kontrak->nomor_spk
                ];
            }

            // Event: Deadline Kontrak (jika ada jangka waktu)
            if ($kontrak->tanggal && $kontrak->jangka_waktu) {
                $deadline = Carbon::parse($kontrak->tanggal)->addDays($kontrak->jangka_waktu);
                $events[] = [
                    'title' => 'Deadline: ' . $kontrak->proyek->nama_proyek,
                    'start' => $deadline->format('Y-m-d'),
                    'end' => $deadline->format('Y-m-d'),
                    'color' => '#ef4444',
                    'type' => 'deadline',
                    'icon' => 'calendar-check',
                    'description' => 'Jangka waktu: ' . $kontrak->jangka_waktu . ' hari'
                ];
            }
        }

        // Events dari Realisasi
        $realisasis = Realisasi::with('kontrak.proyek')->get();
        foreach ($realisasis as $realisasi) {
            $events[] = [
                'title' => 'Realisasi: ' . $realisasi->kontrak->proyek->nama_proyek,
                'start' => $realisasi->tanggal_realisasi,
                'end' => $realisasi->tanggal_realisasi,
                'color' => '#10b981',
                'type' => 'realisasi',
                'icon' => 'graph-up',
                'description' => 'Progress: ' . $realisasi->progress_persen . '% - Nilai: Rp ' . number_format($realisasi->nilai_realisasi, 0, ',', '.')
            ];
        }

        // Events dari Biaya
        $biayas = Biaya::with('kontrak.proyek')->whereNotNull('tanggal_realisasi')->get();
        foreach ($biayas as $biaya) {
            $events[] = [
                'title' => 'Biaya ' . $biaya->jenis . ': ' . $biaya->kontrak->proyek->nama_proyek,
                'start' => $biaya->tanggal_realisasi,
                'end' => $biaya->tanggal_realisasi,
                'color' => '#f59e0b',
                'type' => 'biaya',
                'icon' => 'currency-dollar',
                'description' => 'Termin: ' . ($biaya->termin ?? '-') . ' - Nominal: Rp ' . number_format($biaya->nominal_realisasi, 0, ',', '.')
            ];
        }

        // Events dari Dokumen (Tanggal Upload)
        $dokumens = DokumenProyek::with('proyek')->get();
        foreach ($dokumens as $dokumen) {
            $events[] = [
                'title' => 'Dokumen ' . strtoupper($dokumen->jenis_dokumen) . ': ' . $dokumen->proyek->nama_proyek,
                'start' => $dokumen->tanggal_upload,
                'end' => $dokumen->tanggal_upload,
                'color' => '#8b5cf6',
                'type' => 'dokumen',
                'icon' => 'file-earmark',
                'description' => 'Jenis: ' . $dokumen->jenis_dokumen
            ];
        }

        return $events;
    }

    private function getStatusColor($progress)
    {
        if ($progress == 0) {
            return 'secondary';
        } elseif ($progress >= 100) {
            return 'success';
        } else {
            return 'warning';
        }
    }

    private function getStatusText($progress)
    {
        if ($progress == 0) {
            return 'Belum Mulai';
        } elseif ($progress >= 100) {
            return 'Selesai';
        } else {
            return number_format($progress, 1) . '%';
        }
    }
}
