<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function about()
    {
        $data = [
            'title' => 'About',
            'page_title' => 'About Us',
            'breadcrumbs' => ['Pages', 'About']
        ];

        return view('pages/about', $data);
    }
}
