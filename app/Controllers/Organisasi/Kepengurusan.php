<?php

namespace App\Controllers\Organisasi;

use App\Controllers\BaseController;
use App\Models\OrganisasiModel;
use App\Models\AnggotaModel;
use App\Models\KepengurusanModel;
use App\Models\PemilihanModel;
use App\Models\PemilihanCalonModel;
use App\Models\PemilihanCalonSuaraModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class Kepengurusan extends BaseController
{
    private $organisasiModel;
    private $anggotaModel;
    private $kepengurusanModel;
    private $pemilihanModel;
    private $pemilihanCalonModel;
    private $pemilihanCalonSuaraModel;
    private $pesanModel;

    public function __construct()
    {
        $this->organisasiModel = new OrganisasiModel;
        $this->anggotaModel = new AnggotaModel;
        $this->kepengurusanModel = new KepengurusanModel();
        $this->pemilihanModel = new PemilihanModel();
        $this->pemilihanCalonModel = new PemilihanCalonModel();
        $this->pemilihanCalonSuaraModel = new PemilihanCalonSuaraModel();
        $this->pesanModel = new \App\Models\PesanModel();
    }

    public function index()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Kepengurusan';

        // Get filter year from query parameter
        $selectedYear = $this->request->getGet('tahun');
        if (!$selectedYear) {
            $selectedYear = date('Y'); // Default to current year
        }

        // Get all available years from periode
        $allYears = $this->kepengurusanModel->select('periode')
            ->findAll();

        $availableYears = [];
        foreach ($allYears as $item) {
            // Extract year from periode (format: "2024/2025" or "2024-2025" or "2024")
            if (preg_match('/(\d{4})/', $item['periode'], $matches)) {
                $year = (int) $matches[1];
                if (!in_array($year, $availableYears)) {
                    $availableYears[] = $year;
                }
            }
        }
        rsort($availableYears); // Sort descending

        // Join the organisasi, anggota, and pemilihan_calons tables
        // Use GROUP BY to avoid duplicates when multiple pemilihan_calons match
        $query = $this->kepengurusanModel->select('kepengurusans.*, organisasis.name as organisasi_name, anggotas.name as anggota_name, MAX(pemilihan_calons.gambar_1) as gambar_1')
            ->join('organisasis', 'kepengurusans.organisasi_id = organisasis.id')
            ->join('anggotas', 'kepengurusans.anggota_id = anggotas.id')
            ->join('pemilihan_calons', '(pemilihan_calons.anggota_id_1 = kepengurusans.anggota_id OR pemilihan_calons.anggota_id_2 = kepengurusans.anggota_id)', 'left');

        // Filter by year
        if ($selectedYear && $selectedYear != 'all') {
            $query->where("kepengurusans.periode LIKE", "%{$selectedYear}%");
        }

        $data = $query->groupBy('kepengurusans.id')
            ->orderBy('kepengurusans.created_at', 'DESC')
            ->findAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/organisasi/kepengurusan/index', compact('title', 'data', 'availableYears', 'selectedYear', 'unreadCount'));
    }

    public function getAnggotaByOrganisasi($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return $this->response->setJSON(['error' => 'Anda tidak memiliki akses']);
        }

        $anggota = $this->anggotaModel->where('organisasi_id', $id)->findAll();
        return $this->response->setJSON($anggota);
    }

    public function create()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Create Kepengurusan';
        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();

        // Get pemilihan calons from pemilihan with status 'selesai'
        $pemilihanCalons = $this->pemilihanCalonModel->select('pemilihan_calons.*, pemilihans.organisasi_id, pemilihans.periode as pemilihan_periode, organisasis.name as organisasi_name, anggota_1.name as anggota_1_name, anggota_1.id as anggota_1_id, anggota_2.name as anggota_2_name, anggota_2.id as anggota_2_id')
            ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id', 'left')
            ->where('pemilihans.status', 'selesai')
            ->findAll();

        return view('page/organisasi/kepengurusan/create', compact('title', 'organisasis', 'pemilihanCalons'));
    }

    public function store()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = $this->request->getPost();

        // Remove pemilihan_calon_id from data as it's not in the table
        unset($data['pemilihan_calon_id']);

        // Validate required fields
        if (empty($data['organisasi_id']) || empty($data['anggota_id']) || empty($data['jabatan']) || empty($data['periode'])) {
            return redirect()->back()->withInput()->with('error', 'Semua field wajib diisi');
        }

        $this->kepengurusanModel->insert($data);

        // Preserve year filter if exists
        $tahun = $this->request->getGet('tahun');
        $redirectUrl = url_to('organisasi.kepengurusan.index');
        if ($tahun) {
            $redirectUrl .= '?tahun=' . $tahun;
        }

        return redirect()->to($redirectUrl)->with('success', 'Kepengurusan created successfully');
    }

    public function edit($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Edit Kepengurusan';
        $data = $this->kepengurusanModel->find($id);
        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();

        return view('page/organisasi/kepengurusan/edit', compact('title', 'data', 'organisasis'));
    }

    public function update($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = $this->request->getPost();
        $this->kepengurusanModel->update($id, $data);

        // Preserve year filter if exists
        $tahun = $this->request->getGet('tahun');
        $redirectUrl = url_to('organisasi.kepengurusan.index');
        if ($tahun) {
            $redirectUrl .= '?tahun=' . $tahun;
        }

        return redirect()->to($redirectUrl)->with('success', 'Kepengurusan updated successfully');
    }

    public function delete($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $this->kepengurusanModel->delete($id);

        // Preserve year filter if exists
        $tahun = $this->request->getGet('tahun');
        $redirectUrl = url_to('organisasi.kepengurusan.index');
        if ($tahun) {
            $redirectUrl .= '?tahun=' . $tahun;
        }

        return redirect()->to($redirectUrl)->with('success', 'Kepengurusan deleted successfully');
    }

    public function detailSuara($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get kepengurusan data
        $kepengurusan = $this->kepengurusanModel->select('kepengurusans.*, organisasis.name as organisasi_name, organisasis.id as organisasi_id, anggotas.name as anggota_name, anggotas.id as anggota_id')
            ->join('organisasis', 'kepengurusans.organisasi_id = organisasis.id')
            ->join('anggotas', 'kepengurusans.anggota_id = anggotas.id')
            ->where('kepengurusans.id', $id)
            ->first();

        if (!$kepengurusan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kepengurusan tidak ditemukan.');
        }

        // Get pemilihan_calon based on anggota_id from kepengurusan
        $pemilihanCalon = $this->pemilihanCalonModel->select('pemilihan_calons.*, pemilihans.id as pemilihan_id, pemilihans.periode, pemilihans.status as pemilihan_status')
            ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
            ->where('(pemilihan_calons.anggota_id_1 = ' . $kepengurusan['anggota_id'] . ' OR pemilihan_calons.anggota_id_2 = ' . $kepengurusan['anggota_id'] . ')')
            ->where('pemilihans.status', 'selesai')
            ->orderBy('pemilihan_calons.created_at', 'DESC')
            ->first();

        if (!$pemilihanCalon) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data pemilihan calon tidak ditemukan untuk kepengurusan ini.');
        }

        // Get pemilihan data
        $pemilihan = $this->pemilihanModel->find($pemilihanCalon['pemilihan_id']);

        if (!$pemilihan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pemilihan tidak ditemukan.');
        }

        // Get all candidates from the same pemilihan
        $allCandidates = $this->pemilihanCalonModel->select('pemilihan_calons.*, anggota_1.name as anggota_1_name, anggota_2.name as anggota_2_name, anggota_1.nim as anggota_1_nim, anggota_2.nim as anggota_2_nim')
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id', 'left')
            ->where('pemilihan_calons.pemilihan_id', $pemilihanCalon['pemilihan_id'])
            ->orderBy('pemilihan_calons.number', 'ASC')
            ->findAll();

        // Calculate total suara for each candidate
        $totalSuaras = [];
        $totalAllSuara = 0;

        foreach ($allCandidates as $candidate) {
            $total = $this->pemilihanCalonSuaraModel->where('pemilihan_calon_id', $candidate['id'])->where('status', '1')->countAllResults();
            $candidateName = $candidate['anggota_1_name'];
            if (!empty($candidate['anggota_2_name'])) {
                $candidateName .= ' & ' . $candidate['anggota_2_name'];
            }
            $totalSuaras[$candidateName] = $total;
            $totalAllSuara += $total;
        }

        // Calculate percentage
        $totalSuarasWithPercent = [];
        foreach ($totalSuaras as $name => $count) {
            $percent = $totalAllSuara > 0 ? ($count / $totalAllSuara) * 100 : 0;
            $totalSuarasWithPercent[$name] = [
                'count' => $count,
                'percent' => round($percent, 2)
            ];
        }

        // Get voting data (list of voters) for the selected candidate
        $votingData = $this->pemilihanCalonSuaraModel->select('pemilihan_calon_suara.*')
            ->where('pemilihan_calon_suara.pemilihan_calon_id', $pemilihanCalon['id'])
            ->where('pemilihan_calon_suara.status', '1')
            ->orderBy('pemilihan_calon_suara.created_at', 'DESC')
            ->findAll();

        // Add pemilihan_calon data to kepengurusan array
        $kepengurusan['pemilihan_calon_id'] = $pemilihanCalon['id'];
        $kepengurusan['gambar_1'] = $pemilihanCalon['gambar_1'] ?? null;
        $kepengurusan['pemilihan_id'] = $pemilihanCalon['pemilihan_id'];

        $title = 'Statistik Voting ' . $kepengurusan['organisasi_name'];

        return view('page/organisasi/kepengurusan/detail-suara', compact('title', 'kepengurusan', 'pemilihan', 'allCandidates', 'totalSuarasWithPercent', 'totalAllSuara', 'votingData'));
    }

    public function exportExcel($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get kepengurusan data
        $kepengurusan = $this->kepengurusanModel->select('kepengurusans.*, organisasis.name as organisasi_name, anggotas.name as anggota_name, anggotas.id as anggota_id')
            ->join('organisasis', 'kepengurusans.organisasi_id = organisasis.id')
            ->join('anggotas', 'kepengurusans.anggota_id = anggotas.id')
            ->where('kepengurusans.id', $id)
            ->first();

        if (!$kepengurusan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kepengurusan tidak ditemukan.');
        }

        // Get pemilihan_calon based on anggota_id from kepengurusan
        $pemilihanCalon = $this->pemilihanCalonModel->select('pemilihan_calons.id, pemilihan_calons.pemilihan_id')
            ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
            ->where('(pemilihan_calons.anggota_id_1 = ' . $kepengurusan['anggota_id'] . ' OR pemilihan_calons.anggota_id_2 = ' . $kepengurusan['anggota_id'] . ')')
            ->where('pemilihans.status', 'selesai')
            ->orderBy('pemilihan_calons.created_at', 'DESC')
            ->first();

        if (!$pemilihanCalon) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data pemilihan calon tidak ditemukan untuk kepengurusan ini.');
        }

        // --- DATA PREPARATION FOR STATS & CHART ---

        // Get all candidates
        $allCandidates = $this->pemilihanCalonModel->select('pemilihan_calons.*, anggota_1.name as anggota_1_name, anggota_2.name as anggota_2_name')
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id', 'left')
            ->where('pemilihan_calons.pemilihan_id', $pemilihanCalon['pemilihan_id'])
            ->orderBy('pemilihan_calons.number', 'ASC')
            ->findAll();

        $summaryData = [];
        $totalAllSuara = 0;

        foreach ($allCandidates as $candidate) {
            $total = $this->pemilihanCalonSuaraModel->where('pemilihan_calon_id', $candidate['id'])
                ->where('status', '1')
                ->countAllResults();

            $candidateName = $candidate['anggota_1_name'];
            if (!empty($candidate['anggota_2_name'])) {
                $candidateName .= ' & ' . $candidate['anggota_2_name'];
            }

            $summaryData[] = [
                'name' => $candidateName,
                'total' => $total
            ];

            $totalAllSuara += $total;
        }

        // Add percentages
        foreach ($summaryData as &$data) {
            $data['percent'] = $totalAllSuara > 0 ? round(($data['total'] / $totalAllSuara) * 100, 2) : 0;
        }
        unset($data);

        // Get detail voting data (current org/candidate context)
        // Only fetch voters for the SPECIFIC candidate relating to this Kepengurusan context?
        // Original code was: where('pemilihan_calon_suara.pemilihan_calon_id', $pemilihanCalon['id']) 
        // which implies we only export voters for the WINNER (or the kepengurusan owner).
        // The user request says "download file excel nya pada menu kepengurusan/detail_suara.php", 
        // which usually shows data for that specific result.
        // We will keep fetching voters only for this specific candidate ID found above.

        $votingData = $this->pemilihanCalonSuaraModel->select('pemilihan_calon_suara.*')
            ->where('pemilihan_calon_suara.pemilihan_calon_id', $pemilihanCalon['id'])
            ->where('pemilihan_calon_suara.status', '1')
            ->orderBy('pemilihan_calon_suara.created_at', 'DESC')
            ->findAll();

        // --- SPREADSHEET GENERATION ---

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Voting');

        // 1. Title
        $sheet->setCellValue('A1', 'Statistik Voting - ' . $kepengurusan['organisasi_name']);
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // 2. Summary Table
        $sheet->setCellValue('A3', 'Rangkuman Perolehan Suara');
        $sheet->getStyle('A3')->getFont()->setBold(true);

        $sheet->setCellValue('A4', 'Kandidat');
        $sheet->setCellValue('B4', 'Jumlah Suara');
        $sheet->setCellValue('C4', 'Persentase (%)');

        $row = 5;
        $startSummaryRow = $row;
        foreach ($summaryData as $item) {
            $sheet->setCellValue('A' . $row, $item['name']);
            $sheet->setCellValue('B' . $row, $item['total']);
            $sheet->setCellValue('C' . $row, $item['percent']);
            $row++;
        }
        $endSummaryRow = $row - 1;

        // Style Summary Table
        $summaryRange = 'A4:C' . $endSummaryRow;
        $sheet->getStyle('A4:C4')->getFont()->setBold(true);
        $sheet->getStyle($summaryRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // 3. Create Pie Chart
        // Labels: Kandidat names (A5:A...)
        // Values: Totals (B5:B...)
        if ($endSummaryRow >= $startSummaryRow) {
            $sheetTitle = $sheet->getTitle();
            $sheetRef = "'" . $sheetTitle . "'!";
            $dataSeriesLabels = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetRef . '$B$4', null, 1), // "Jumlah Suara"
            ];

            $xAxisTickValues = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetRef . '$A$' . $startSummaryRow . ':$A$' . $endSummaryRow, null, count($summaryData)), // Candidate Names
            ];

            $dataSeriesValues = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetRef . '$B$' . $startSummaryRow . ':$B$' . $endSummaryRow, null, count($summaryData)), // Vote Counts
            ];

            $series = new DataSeries(
                DataSeries::TYPE_PIECHART,       // plotType
                DataSeries::GROUPING_STANDARD,  // plotGrouping
                range(0, count($dataSeriesValues) - 1), // plotOrder
                $dataSeriesLabels,              // plotLabel
                $xAxisTickValues,               // plotCategory
                $dataSeriesValues               // plotValues
            );

            $plotArea = new PlotArea(null, [$series]);
            $legend = new Legend(Legend::POSITION_RIGHT, null, false);
            $title = new Title('Distribusi Suara');

            $chart = new Chart(
                'chart_suara',  // name
                $title,         // title
                $legend,        // legend
                $plotArea,      // plotArea
                true,           // plotVisibleOnly
                DataSeries::EMPTY_AS_GAP, // displayBlanksAs
                null,           // xAxisLabel
                null            // yAxisLabel
            );

            // Set position (next to summary table)
            $chart->setTopLeftPosition('E4');
            $chart->setBottomRightPosition('L15');

            $sheet->addChart($chart);
        }

        // 4. Detail Data (Unmasked)
        $detailStartRow = max($row + 3, 17); // Check if chart overlaps
        $sheet->setCellValue('A' . $detailStartRow, 'Detail Data Pemilih (Kandidat Terpilih)');
        $sheet->getStyle('A' . $detailStartRow)->getFont()->setBold(true);

        $headerRow = $detailStartRow + 1;
        $sheet->setCellValue('A' . $headerRow, 'No');
        $sheet->setCellValue('B' . $headerRow, 'Nama');
        $sheet->setCellValue('C' . $headerRow, 'NIM');
        $sheet->setCellValue('D' . $headerRow, 'Email');

        $sheet->getStyle('A' . $headerRow . ':D' . $headerRow)->getFont()->setBold(true);

        $row = $headerRow + 1;
        $no = 1;
        foreach ($votingData as $voter) {
            // UNMASKED DATA AS REQUESTED
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $voter['name']);
            $sheet->getCell('C' . $row)->setValueExplicit($voter['nim'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); // Keep NIM as string to prevent scientific notation
            $sheet->setCellValue('D' . $row, $voter['email']);
            $row++;
        }

        // Style Detail Table
        $lastRow = $row - 1;
        if ($lastRow >= $headerRow) {
            $sheet->getStyle('A' . $headerRow . ':D' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        // Auto size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output
        $filename = 'Hasil_Voting_' . str_replace(' ', '_', $kepengurusan['organisasi_name']) . '_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $writer->save('php://output');
        exit;
    }
}
