<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'page_title' => 'Dashboard',
            'breadcrumbs' => ['Dashboard'],
            'nama_user' => session()->get('nama')
        ];

        return view('dashboard/index', $data);
    }
}
