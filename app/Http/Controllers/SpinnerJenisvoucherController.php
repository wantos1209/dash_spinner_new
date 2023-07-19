<?php

namespace App\Http\Controllers;

use App\Models\SpinnerJenisvoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

class SpinnerJenisvoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenisvoucher = SpinnerJenisvoucher::latest()->filter(request(['search']))->paginate(10)->withQueryString();
        return view('jenisvoucher.index', [
            'title' => 'Spinner - Jenis Voucher',
            'menu' =>  'Spinner',
            'data' => $jenisvoucher
        ])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.apk.bo.create', [
            'title' => 'Spinner - Jenis Voucher',
            'menu' =>  'Spinner'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'index' => 'required',
            'saldo_point' => 'required',
        ]);

        // Jika validasi gagal, kirimkan respon error ke Ajax
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            SpinnerJenisvoucher::create($request->all());
            session()->flash('success', 'Pesan sukses yang ingin ditampilkan');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    public function show(SpinnerJenisvoucher $bo)
    {
        return view('bo.show', compact('bo'));
    }

    public function data($id)
    {
        $data = SpinnerJenisvoucher::find($id);
        return response()->json($data);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validateData = $request->validate([
            'nama' => 'required',
            'index' => 'required',
            'saldo_point' => 'required',
        ]);

        SpinnerJenisvoucher::where('id', $id)->update($validateData);

        return response()->json(['success' => 'Item berhasil diupdate!']);
    }

    public function destroy($id)
    {
        $data = SpinnerJenisvoucher::findOrFail($id);
        $data->delete();

        return redirect("/spinner/jenisvoucher")->with('success', 'Jenis Voucher berhasil dihapus!');
    }

    public function datavoucher()
    {
        $data = SpinnerJenisvoucher::get();
        return response()->json($data);
    }
}
