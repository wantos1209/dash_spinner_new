<?php

namespace App\Http\Controllers;

use App\Models\SpinnerJenisvoucher;
use App\Models\SpinnerVoucher;
use App\Models\SpinnerGeneratevoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;


class SpinnerGeneratevoucherController extends Controller
{
    // public function index(Request $request)
    // {

    //     $spinnervoucher = SpinnerGeneratevoucher::latest();
    //     $search_jenis_voucher = $request->input('search_jenis_voucher');
    //     $search_tanggal = $request->input('search_tanggal');

    //     if ($search_jenis_voucher != '') {
    //         $spinnervoucher->where('jenis_voucher', $search_jenis_voucher);
    //     }
    //     $spinnervoucher->where('bo', getDataBo2());
    //     //SET TANGGAL================================================================================
    //     $currentMonth = date('m');
    //     $currentYear = date('Y');
    //     $startDate = date('Y-m-d', strtotime($currentYear . '-' . $currentMonth . '-01'));
    //     $endDate = date('Y-m-t', strtotime($currentYear . '-' . $currentMonth . '-01'));

    //     $startDate2 = date('m/d/Y', strtotime($currentYear . '-' . $currentMonth . '-01'));
    //     $endDate2 = date('m/t/Y', strtotime($currentYear . '-' . $currentMonth . '-01'));

    //     $search_tgl_default = $startDate2 . ' - ' . $endDate2;
    //     //==========================================================================================

    //     if ($search_tanggal != '') {

    //         $dates = explode(' - ', $search_tanggal);
    //         $startDate = date_create_from_format('m/d/Y', $dates[0]);
    //         $endDate = date_create_from_format('m/d/Y', $dates[1]);
    //         $startDateFormatted = date_format($startDate, 'Y-m-d');
    //         $endDateFormatted = date_format($endDate, 'Y-m-d');

    //         $spinnervoucher->whereBetween('tgl_exp', [$startDateFormatted, $endDateFormatted]);
    //     } else {
    //         $spinnervoucher->whereBetween('tgl_exp', [$startDate, $endDate]);
    //     }



    //     $spinnervoucher = $spinnervoucher->filter(request(['search']))->paginate(10)->withQueryString();

    //     $spinnervoucher->getCollection()->each(function ($item) {
    //         $jenisVoucher = SpinnerJenisvoucher::where('index', $item->jenis_voucher)->first();
    //         if ($jenisVoucher != '') {
    //             $item->jenis_voucher = $jenisVoucher->nama;
    //         } else {
    //             $item->jenis_voucher = 'Unknown'; // Nilai default jika tidak ditemukan
    //         }
    //     });

    //     $jenis_voucher = SpinnerJenisvoucher::orderBy('saldo_point', 'ASC')->get();

    //     return view('generatevoucher.index', [
    //         'title' => 'APK - Bo',
    //         'menu' => 'bo',
    //         'data' => $spinnervoucher,
    //         'jenis_voucher' => $jenis_voucher,
    //         'search_tgl_default' => $search_tgl_default
    //     ])->with('i', ($request->input('page', 1) - 1) * 5);
    // }

    public function index()
    {

        $data = $this->getData();

        $search = request('search');

        // if ($search  != '') {
        //     $results = [];

        //     foreach ($data as $d) {
        //         if (strtoupper($d["nama"]) === strtoupper($search)) {
        //             $results[] = $d;
        //         }
        //     }
        //     $data =  $results;
        // }

        $perPage = 10;
        $page =  request()->get('page', 1);
        $slicedData = array_slice($data, ($page - 1) * $perPage, $perPage);
        $paginator = new LengthAwarePaginator(
            $slicedData,
            count($data),
            $perPage,
            $page,
            ['path' => url()->current()]
        );

        return view('jenisvoucher.index', [

            'data' => $paginator
        ]);
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
        $request['bo'] = getDataBo2();
        $validator = Validator::make($request->all(), [
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
                        'username' => '',
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

    function cekData($string)
    {
        $count = SpinnerVoucher::where('kode_voucher', $string)->count();
        return $count > 0;
    }

    public function getData($id = '')
    {
        $url = $this->getUrl();
        $options = [
            'http' => [
                'header' => 'postman-token: 54a06989-9a14-4515-afca-766a0f6f3dd9'
            ]
        ];
        $context = stream_context_create($options);
        $data = file_get_contents($url, false, $context);

        $data = json_decode($data, true);
        $data = $data['data'];

        if ($id != '') {
            foreach ($data as $index => $d) {
                if ($d['id'] == $id) {
                    $data = $data[$index];
                }
            }
        }
        return $data;
    }

    public function getUrl($id = '')
    {
        if ($id != '') {
            $url = "https://www.m3y0kl1n3.com/api/generatevoucher/" . $id;
            // dd($url);
        } else {
            $url = "https://www.m3y0kl1n3.com/api/generatevoucher";
        }
        // dd($url);

        return $url;
    }

    public function action($id, $data, $method = "POST")
    {

        $url = $this->getUrl($id);

        $data = [
            "nama" => $data['nama'],
            "index" => $data['index'],
            "saldo_point" => $data['saldo_point'],
            "bo" => $data['bo'],
        ];

        $data_string = json_encode($data);
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
            'header' => 'postman-token: 54a06989-9a14-4515-afca-766a0f6f3dd9'
        );

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_error($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return $result;
    }
}
