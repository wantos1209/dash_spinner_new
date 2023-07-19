<?php

namespace App\Http\Controllers;

use App\Models\SpinnerJenisvoucher;
use App\Models\SpinnerVoucher;
use App\Models\SpinnerGeneratevoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class SpinnerGeneratevoucherController extends Controller
{
    public function index(Request $request)
    {
        $databo = getDataBo();
        $spinnervoucher = SpinnerGeneratevoucher::leftJoin(
            DB::raw('(SELECT genvoucherid, COUNT(id) AS totalklaim FROM spinner_voucher WHERE tgl_klaim IS NOT NULL AND COALESCE(status_transfer, 0) = 0 GROUP BY genvoucherid) as sv'),
            function ($join) {
                $join->on('spinner_generatevoucher.id', '=', 'sv.genvoucherid');
            }
        )
            ->leftJoin(
                DB::raw('(SELECT genvoucherid, COUNT(id) AS totalklaim2 FROM spinner_voucher WHERE userklaim <> "" GROUP BY genvoucherid) as sv2'),
                function ($join) {
                    $join->on('spinner_generatevoucher.id', '=', 'sv2.genvoucherid');
                }
            );

        $search_tampilvoucher = $request->input('search_tampilvoucher');

        if ($search_tampilvoucher == '1') {
            $spinnervoucher->where('sv2.totalklaim2', '!=', 0);
        } else if ($search_tampilvoucher == '2') {

            $spinnervoucher->where('spinner_generatevoucher.jumlah', '=', 'sv.totalklaim');
            // $spinnervoucher->where('sv2.totalklaim2', '=', 0);
        }
        // dd($spinnervoucher->jumlah);
        $spinnervoucher->orderBy('sv.totalklaim', 'DESC');

        $search_jenis_voucher = $request->input('search_jenis_voucher');
        $search_tanggal = $request->input('search_tanggal');
        $search_bo = $request->input('search_bo');

        if ($search_bo != '') {
            $spinnervoucher->where('bo', $search_bo);
        }
        if ($search_jenis_voucher != '') {
            $spinnervoucher->where('jenis_voucher', $search_jenis_voucher);
        }
        // Hapus duplikasi kondisi ini
        // if ($search_bo != null) {
        //     $spinnervoucher->where('bo', $search_bo);
        // }

        //SET TANGGAL (tidak ada perubahan pada bagian ini)
        $currentMonth = date('m');
        $currentYear = date('Y');
        $startDate = date('Y-m-d', strtotime($currentYear . '-' . $currentMonth . '-01'));
        $endDate = date('Y-m-t', strtotime($currentYear . '-' . $currentMonth . '-01'));

        $startDate2 = date('m/d/Y', strtotime($currentYear . '-' . $currentMonth . '-01'));
        $endDate2 = date('m/t/Y', strtotime($currentYear . '-' . $currentMonth . '-01'));

        $search_tgl_default = $startDate2 . ' - ' . $endDate2;
        //==========================================================================================

        if ($search_tanggal != '') {
            $dates = explode(' - ', $search_tanggal);
            $startDate = date_create_from_format('m/d/Y', $dates[0]);
            $endDate = date_create_from_format('m/d/Y', $dates[1]);
            $startDateFormatted = date_format($startDate, 'Y-m-d');
            $endDateFormatted = date_format($endDate, 'Y-m-d');

            $spinnervoucher->whereBetween('tgl_exp', [$startDateFormatted, $endDateFormatted]);
        } else {
            $spinnervoucher->whereBetween('tgl_exp', [$startDate, $endDate]);
        }

        // Hapus filter() karena kondisi filter sebelumnya sudah ditambahkan ke dalam query builder
        // $spinnervoucher = $spinnervoucher->filter(request(['search']))->paginate(20)->withQueryString();

        // Perubahan kode untuk pagination
        $spinnervoucher = $spinnervoucher->paginate(20)->withQueryString();

        $spinnervoucher->getCollection()->each(function ($item) {
            $jenisVoucher = SpinnerJenisvoucher::where('index', $item->jenis_voucher)->first();
            if ($jenisVoucher != '') {
                $item->jenis_voucher = $jenisVoucher->nama;
            } else {
                $item->jenis_voucher = 'Unknown'; // Nilai default jika tidak ditemukan
            }
        });

        $jenis_voucher = SpinnerJenisvoucher::orderBy('saldo_point', 'ASC')->get();

        $voucherterpakai = [];
        $voucherterpakaibb = [];
        foreach ($spinnervoucher as $index => $spv) {
            $voucherterpakai[] = SpinnerVoucher::where('userklaim', '!=', '')
                ->where('genvoucherid', $spv->id)
                ->count();

            $voucherterpakaibb[] = SpinnerVoucher::where('tgl_klaim', '!=', '')
                ->whereRaw("COALESCE(status_transfer, 0) = 0")
                ->where('genvoucherid', $spv->id)
                ->count();
        }

        return view('generatevoucher.index', [
            'title' => 'APK - Bo',
            'menu' => 'bo',
            'data' => $spinnervoucher,
            'jenis_voucher' => $jenis_voucher,
            'search_tgl_default' => $search_tgl_default,
            'databo' => $databo,
            'voucherterpakai' => $voucherterpakai,
            'voucherterpakaibb' => $voucherterpakaibb
        ])->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function create()
    {
        return view('dashboard.spinner.generatevoucher.create', [
            'title' => 'APK - Bo',
            'menu' =>  'bo'
        ]);
    }

    public function store(Request $request)
    {
        $request['userid'] = auth()->user()->username;

        $validator = Validator::make($request->all(), [
            'bo' => 'required|max:255',
            'jenis_voucher' => 'required|max:255',
            'tgl_exp' => 'required|date',
            'jumlah' => 'required|numeric'
        ]);

        // Jika validasi gagal, kirimkan respon error ke Ajax
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            $save = SpinnerGeneratevoucher::create($request->all());

            for ($i = 1; $i <= $request['jumlah']; $i++) {

                try {
                    SpinnerVoucher::create([
                        'userid' => auth()->user()->username,
                        'jenis_voucher' => $request['jenis_voucher'],
                        'kode_voucher' => $this->generateUniqueRandomString(10),
                        'balance_kredit' => 1,
                        'username' => 'voucher' . $this->generateUniqueRandomString2(5),
                        'bo' => getDataBo2(),
                        'saldo' => SpinnerJenisvoucher::where('index', $request['jenis_voucher'])->first()->saldo_point,
                        'userklaim' => '',
                        'tgl_klaim' => null,
                        'tgl_exp' => $request['tgl_exp'],
                        'genvoucherid' => $save->id
                    ]);

                    // Jika entri berhasil dibuat, tambahkan respons atau tindakan lain yang sesuai

                } catch (\Exception $e) {
                    // Tangkap exception dan tampilkan pesan error
                    $errorMessage = $e->getMessage();
                    return response()->json([
                        'message' => 'Error : ' . $errorMessage,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    public function show(SpinnerGeneratevoucher $voucher)
    {
        return view('bo.show', compact('bo'));
    }

    public function data($id)
    {
        $data = SpinnerGeneratevoucher::find($id);
        return response()->json($data);
    }

    public function update(Request $request)
    {

        $request['usertoken'] = $request['userid'] . '_' . $request['kode_voucher'];
        $request['saldo'] = SpinnerJenisvoucher::find($request['jenis_voucher'])->saldo_point;

        $id = $request->id;
        $validateData = $request->validate([
            'jenis_voucher' => 'required|max:255',
            'userid' => 'required|max:255'
        ]);

        SpinnerGeneratevoucher::where('id', $id)->update($validateData);

        return response()->json(['success' => 'Item berhasil diupdate!']);
    }

    public function destroy($id)
    {
        $data = SpinnerGeneratevoucher::findOrFail($id);
        $data->delete();

        SpinnerVoucher::where('genvoucherid', $id)->delete();

        return redirect("/spinner/generatevoucher")->with('success', 'Data voucher berhasil dihapus!');
    }

    function generateUniqueRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $maxCharIndex = strlen($characters) - 1;

        do {
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $maxCharIndex)];
            }
        } while ($this->cekData($randomString));

        return $randomString;
    }

    function generateUniqueRandomString2($length)
    {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= rand(0, 9);
        }
        return $randomString;
    }

    function cekData($string)
    {
        $count = SpinnerVoucher::where('kode_voucher', $string)->count();
        return $count > 0;
    }
}
