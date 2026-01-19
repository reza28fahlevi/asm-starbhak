<?php

namespace App\Controllers;

class Examples extends BaseController
{
    public function form()
    {
        $data = [
            'title' => 'Form Example',
            'page_title' => 'Form Elements',
            'breadcrumbs' => ['Examples', 'Form']
        ];

        return view('examples/form', $data);
    }
    
    public function table()
    {
        $data = [
            'title' => 'Table Example',
            'page_title' => 'Data Tables',
            'breadcrumbs' => ['Examples', 'Tables']
        ];

        return view('examples/table', $data);
    }
}
