<?php

namespace App\Http\Controllers;

use App\Models\SpinnerJenisvoucher;
use App\Models\SpinnerVoucher;
use App\Models\SpinnerGeneratevoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class SpinnerVoucherController extends Controller
{

    public function index(Request $request, $id, $api = 0)
    {
        $searchJenisKlaim = $request->query('search_jenis_klaim');
        $search_status_transfer = $request->query('search_status_transfer');

        $spinnervoucher = SpinnerVoucher::where('genvoucherid', $id)
            ->when($searchJenisKlaim, function ($query, $jenisVoucher) {
                if ($jenisVoucher === 'sudah_klaim') {
                    return $query->whereRaw('COALESCE(userklaim, "") <> ""');
                } else if ($jenisVoucher === 'belum_klaim') {
                    return $query->whereRaw('COALESCE(userklaim, "") = ""');
                }
            })
            ->when($search_status_transfer, function ($query, $status_transfer) {

                if ($status_transfer == 1) {
                    return $query->whereRaw('COALESCE(status_transfer, 0) = 1');
                } else if ($status_transfer == 2) {
                    return $query->whereRaw('COALESCE(status_transfer, 0) = 0');
                } else if ($status_transfer == 3) {
                    return $query->whereRaw('COALESCE(status_transfer, 0) >= 0 AND COALESCE(status_transfer, 0) <= 1');
                }
            })
            ->latest()
            ->filter(request(['search']))
            ->paginate(10)
            ->withQueryString();

        $spinnervoucher2 = SpinnerVoucher::where('genvoucherid', $id)
            ->when($searchJenisKlaim, function ($query, $jenisVoucher) {
                if ($jenisVoucher === 'sudah_klaim') {
                    return $query->whereRaw('COALESCE(userklaim, "") <> ""');
                } else if ($jenisVoucher === 'belum_klaim') {
                    return $query->whereRaw('COALESCE(userklaim, "") = ""');
                }
            })
            ->when($search_status_transfer, function ($query, $status_transfer) {
                if ($status_transfer == '1') {
                    return $query->whereRaw('COALESCE(status_transfer, 0) = 1');
                } else if ($status_transfer == '0') {
                    return $query->whereRaw('COALESCE(status_transfer, 0) = 0');
                }
            })
            ->latest()
            ->filter(request(['search']))
            ->get();

        $spinnervoucher->getCollection()->each(function ($item) {
            $item->jenis_voucher = SpinnerJenisvoucher::where('index', $item->jenis_voucher)->first()->nama;
        });

        $spinnervoucher2->each(function ($item) {
            $item->jenis_voucher = SpinnerJenisvoucher::where('index', $item->jenis_voucher)->first()->nama;
        });

        $genvoucher = SpinnerGeneratevoucher::find($id);

        $dataArray = [];
        $dataArray[] = ["No", "Kode Voucher", "Jenis Voucher", "Link", "Nama Bo", "Rekening"];

        foreach ($spinnervoucher2 as $index => $spv) {
            $rowData = [
                $index + 1,
                $spv->jenis_voucher,
                $spv->kode_voucher,
                'https://spinnerl21.com/spinnerl21?username=' . $spv->username . '_' .  $spv->kode_voucher,
                $spv->bo,
                $spv->userklaim
            ];
            $dataArray[] = $rowData;
        }
        $jenis_voucher = SpinnerJenisvoucher::get();

        //SET TANGGAL================================================================================
        $currentMonth = date('m');
        $currentYear = date('Y');
        $startDate = date('Y-m-d', strtotime($currentYear . '-' . $currentMonth . '-01'));
        $endDate = date('Y-m-t', strtotime($currentYear . '-' . $currentMonth . '-01'));

        $startDate2 = date('m/d/Y', strtotime($currentYear . '-' . $currentMonth . '-01'));
        $endDate2 = date('m/t/Y', strtotime($currentYear . '-' . $currentMonth . '-01'));

        $search_tgl_default = $startDate2 . ' - ' . $endDate2;
        //==========================================================================================

        if ($api == 0) {

            return view('voucher.index', [
                'title' => 'APK - Bo',
                'menu' =>  'bo',
                'data' => $spinnervoucher,
                'jenis_voucher' => $jenis_voucher,
                'id' => $id,
                'datavoucher' => $dataArray,
                'nama_bo' => $genvoucher->bo,
                'jumlah' => $genvoucher->jumlah,
                'search_tgl_default' => $search_tgl_default
            ])->with('i', (request()->input('page', 1) - 1) * 5);
        } else {
            return response()->json($dataArray, Response::HTTP_OK);
        }
    }
    public function index2(Request $request, $api = 0)
    {
        $searchJenisKlaim = $request->query('search_jenis_klaim');
        $search_status_transfer = $request->query('search_status_transfer');
        $search_tanggal = $request->input('search_tanggal');
        // dd($search_tanggal != '');

        //SET TANGGAL================================================================================
        $currentMonth = date('m');
        $currentYear = date('Y');
        $startDate = date('Y-m-d', strtotime($currentYear . '-' . $currentMonth . '-01'));
        $endDate = date('Y-m-t', strtotime($currentYear . '-' . $currentMonth . '-01'));

        $startDate2 = date('m/d/Y', strtotime($currentYear . '-' . $currentMonth . '-01'));
        $endDate2 = date('m/t/Y', strtotime($currentYear . '-' . $currentMonth . '-01'));

        $search_tgl_default = $startDate2 . ' - ' . $endDate2;
        if ($search_tanggal == '') {
            $search_tanggal = $search_tgl_default;
        }
        //==========================================================================================

        $spinnervoucher = SpinnerVoucher::when($searchJenisKlaim, function ($query, $jenisVoucher) {
            if ($jenisVoucher === 'sudah_klaim') {
                return $query->whereRaw('COALESCE(userklaim, "") <> ""');
            } else if ($jenisVoucher === 'belum_klaim') {
                return $query->whereRaw('COALESCE(userklaim, "") = ""');
            }
        })
            ->when($search_status_transfer, function ($query, $status_transfer) {
                if ($status_transfer == '1') {
                    return $query->whereRaw('COALESCE(status_transfer, 0) = 1');
                } else if ($status_transfer == '0') {
                    return $query->whereRaw('COALESCE(status_transfer, 0) = 0');
                }
            })
            ->when($search_tanggal != '', function ($query) use ($search_tanggal) {
                $dates = explode(' - ', $search_tanggal);
                $startDate = date_create_from_format('m/d/Y', $dates[0]);
                $endDate = date_create_from_format('m/d/Y', $dates[1]);
                $startDateFormatted = date_format($startDate, 'Y-m-d');
                $endDateFormatted = date_format($endDate, 'Y-m-d');
                return $query->whereBetween('tgl_exp', [$startDateFormatted, $endDateFormatted]);
            }, function ($query) use (&$startDate, &$endDate) {
                return $query->whereBetween('tgl_exp', [$startDate, $endDate]);
            })

            ->latest()
            ->filter(request(['search']))
            ->paginate(10)
            ->withQueryString();

        $spinnervoucher2 = SpinnerVoucher::when($searchJenisKlaim, function ($query, $jenisVoucher) {
            if ($jenisVoucher === 'sudah_klaim') {
                return $query->whereRaw('COALESCE(userklaim, "") <> ""');
            } else if ($jenisVoucher === 'belum_klaim') {
                return $query->whereRaw('COALESCE(userklaim, "") = ""');
            }
        })
            ->when($search_status_transfer, function ($query, $status_transfer) {
                if ($status_transfer == '1') {
                    return $query->whereRaw('COALESCE(status_transfer, 0) = 1');
                } else if ($status_transfer == '0') {
                    return $query->whereRaw('COALESCE(status_transfer, 0) = 0');
                }
            })
            ->when($search_tanggal != '', function ($query) use ($search_tanggal) {
                $dates = explode(' - ', $search_tanggal);
                $startDate = date_create_from_format('m/d/Y', $dates[0]);
                $endDate = date_create_from_format('m/d/Y', $dates[1]);
                $startDateFormatted = date_format($startDate, 'Y-m-d');
                $endDateFormatted = date_format($endDate, 'Y-m-d');
                return $query->whereBetween('tgl_exp', [$startDateFormatted, $endDateFormatted]);
            }, function ($query) use (&$startDate, &$endDate) {
                return $query->whereBetween('tgl_exp', [$startDate, $endDate]);
            })

            ->latest()
            ->filter(request(['search']))
            ->get();


        $spinnervoucher->getCollection()->each(function ($item) {
            $item->jenis_voucher = SpinnerJenisvoucher::where('index', $item->jenis_voucher)->first()->nama;
        });

        $spinnervoucher2->each(function ($item) {
            $item->jenis_voucher = SpinnerJenisvoucher::where('index', $item->jenis_voucher)->first()->nama;
        });

        $dataArray = [];
        $dataArray[] = ["No", "Kode Voucher", "Jenis Voucher", "Link", "Nama Bo", "Rekening"];

        foreach ($spinnervoucher2 as $index => $spv) {
            $rowData = [
                $index + 1,
                $spv->jenis_voucher,
                $spv->kode_voucher,
                $spv->bo,
                $spv->user_klaim
            ];
            $dataArray[] = $rowData;
        }
        $jenis_voucher = SpinnerJenisvoucher::get();

        if ($api == 0) {

            return view('voucher.index', [
                'title' => 'APK - Bo',
                'menu' =>  'bo',
                'data' => $spinnervoucher,
                'jenis_voucher' => $jenis_voucher,
                'id' => '',
                'datavoucher' => $dataArray,
                'nama_bo' => getDataBo2(),
                'jumlah' => '',
                'search_tgl_default' => $search_tgl_default
            ])->with('i', (request()->input('page', 1) - 1) * 5);
        } else {
            return response()->json($dataArray, Response::HTTP_OK);
        }
    }
    public function create()
    {
        return view('dashboard.spinner.voucher.create', [
            'title' => 'APK - Bo',
            'menu' =>  'bo'
        ]);
    }

    public function store(Request $request)
    {
        $kode_voucher = $this->generateUUID();
        $request['kode_voucher'] = $kode_voucher;
        $request['balance_kredit'] = 1;
        $request['usertoken'] = $request['userid'] . '_' . $kode_voucher;
        $request['bo'] =  getDataBo2();
        $request['saldo'] = SpinnerJenisvoucher::find($request['jenis_voucher'])->saldo_point;

        $validator = Validator::make($request->all(), [
            'jenis_voucher' => 'required|max:255',
            'userid' => 'required|max:255',
            'kode_voucher' => 'unique:spinner_voucher,kode_voucher'
        ]);

        // Jika validasi gagal, kirimkan respon error ke Ajax
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            SpinnerVoucher::create($request->all());
        }

        return response()->json([
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    public function show(SpinnerVoucher $voucher)
    {
        return view('bo.show', compact('bo'));
    }

    public function data($id)
    {
        $data = SpinnerVoucher::find($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validateData = $request->validate([
            'userklaim' => 'nullable|max:255'
        ]);

        SpinnerVoucher::where('id', $id)->update($validateData);

        return response()->json(['success' => 'Item berhasil diupdate!']);
    }

    public function destroy($id)
    {
        $data = SpinnerVoucher::findOrFail($id);
        $data->delete();

        return redirect("/spinner/voucher")->with('success', 'Bo berhasil dihapus!');
    }

    function generateUUID()
    {
        $data = openssl_random_pseudo_bytes(16);
        assert(strlen($data) == 16);

        // Versi 4 menandakan UUID acak
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    function export($id = '22')
    {
        $spinnerVouchers = SpinnerVoucher::where('genvoucherid', $id)->latest()->get();
        $generate_voucher = SpinnerGeneratevoucher::where('id', $id)->first();
        $jenis_voucher = $generate_voucher->jenis_voucher;
        $jumlah = $generate_voucher->jumlah;
        $nama_voucher = SpinnerJenisvoucher::where('id', $jenis_voucher)->first()->nama;

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        // Set judul kolom
        $activeWorksheet->setCellValue('A1', 'No');
        $activeWorksheet->setCellValue('B1', 'Jenis Voucher');
        $activeWorksheet->setCellValue('C1', 'Kode Voucher');
        $activeWorksheet->setCellValue('D1', 'Username');
        $activeWorksheet->setCellValue('E1', 'Nama BO');
        $activeWorksheet->setCellValue('F1', 'Saldo');
        $activeWorksheet->setCellValue('G1', 'User Klaim');
        $activeWorksheet->setCellValue('H1', 'Tgl Klaim');
        $activeWorksheet->setCellValue('I1', 'Tgl Exp');

        // Set style untuk judul kolom
        $titleStyle = $activeWorksheet->getStyle('A1:I1');
        $titleStyle->getFont()->setBold(true)->setSize(12);
        $titleStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $titleStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');

        // Set data
        $row = 2;
        $num = 1;
        foreach ($spinnerVouchers as $voucher) {
            $activeWorksheet->setCellValue('A' . $row, $num);

            // Mengatur style untuk kolom nomor
            $activeWorksheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $activeWorksheet->setCellValue('B' . $row, SpinnerJenisvoucher::where('id', $voucher['jenis_voucher'])->pluck('nama')->first());
            $activeWorksheet->setCellValue('C' . $row, $voucher['kode_voucher']);
            $activeWorksheet->setCellValue('D' . $row, $voucher['username']);
            $activeWorksheet->setCellValue('E' . $row, $voucher['bo']);
            $activeWorksheet->setCellValue('F' . $row, $voucher['saldo']);
            $activeWorksheet->setCellValue('G' . $row, $voucher['userklaim']);
            $activeWorksheet->setCellValue('H' . $row, $voucher['tgl_klaim']);
            $activeWorksheet->setCellValue('I' . $row, $voucher['tgl_exp']);

            // Set border pada kolom terakhir (I) di setiap baris
            $activeWorksheet->getStyle('I' . $row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

            $row++;
            $num++;
        }


        // Set border pada seluruh data
        $activeWorksheet->getStyle('A1:I' . $activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Set lebar kolom
        $columnWidths = [
            'A' => 8,
            'B' => 15,
            'C' => 15,
            'D' => 30,
            'E' => 15,
            'F' => 15,
            'G' => 30,
            'H' => 30,
            'I' => 15,
        ];

        foreach ($columnWidths as $column => $width) {
            $activeWorksheet->getColumnDimension($column)->setWidth($width);
        }

        $writer = new Xlsx($spreadsheet);
        $namabo = strtoupper(getDataBo2());
        $namafile = "data-spin-" . $namabo . '-' . $nama_voucher . '-' . $jumlah . ".xlsx";
        // Redirect output to client browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $namafile . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function updateStatus(Request $request, $id)
    {
        $status_transfer = $request->input('status_transfer');

        $statusModel = SpinnerVoucher::find($id);
        if ($statusModel) {
            $statusModel->status_transfer = $status_transfer;
            try {
                $saved = $statusModel->save();

                if ($saved) {
                    return response()->json(['message' => 'Status diperbarui dan tersimpan di database'], 200);
                } else {
                    // Penyimpanan gagal
                    return response()->json(['message' => 'Gagal menyimpan perubahan ke database'], 500);
                }
            } catch (QueryException $e) {
                $errorMessage = $e->getMessage();
                return response()->json(['message' => $errorMessage], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }


        // Respon sukses (opsional)
        return response()->json(['message' => 'Status diperbarui'], 200);
    }


    public function exportexcel($id)
    {
        return Excel::download(new UsersExport($id), 'users.xlsx');
    }
}
