<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Redirect ke login jika belum login
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        
        // Redirect ke dashboard jika sudah login
        return redirect()->to('/dashboard');
    }
}
