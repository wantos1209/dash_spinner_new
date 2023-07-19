<?php

use App\Models\ApkBo;

function getDataBo()
{
    return ApkBo::all();
}

function getDataBo2()
{
    $session_id = session('id_bo');
    $bonama = '';
    if ($session_id != '') {
        $bo = ApkBo::where('id', $session_id)->first();
        $bonama = $bo->nama;
    } else {
        $bonama = 'arwana';
    }
    return $bonama;
}


function backToDashboard()
{
    return redirect()->route('dashboard');
}

function getDataBo3()
{
    return ApkBo::orderBy('id', 'ASC')->first();
}


function isAdmin()
{
    // Ganti logika ini dengan cara Anda untuk menentukan apakah pengguna adalah admin
    // Misalnya, Anda memiliki field 'role' pada tabel 'users' dan nilai 'admin' menandakan admin.
    return auth()->check() && auth()->user()->divisi === 'admin';
}
