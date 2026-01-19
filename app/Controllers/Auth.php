<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Display login page
     */
    public function index()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Process login
     */
    public function login()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Get user by username
        $user = $this->userModel->getUserByUsername($username);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah');
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah');
        }

        // Check if user is active
        if (!$user['active']) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda tidak aktif. Hubungi administrator');
        }

        // Set session
        $sessionData = [
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'email'     => $user['email'],
            'nama'      => $user['nama'],
            'logged_in' => true
        ];

        $this->session->set($sessionData);

        return redirect()->to('/dashboard')->with('success', 'Selamat datang, ' . $user['nama']);
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/')->with('success', 'Anda berhasil logout');
    }

    /**
     * Display registration page
     */
    public function register()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    /**
     * Process registration
     */
    public function doRegister()
    {
        $rules = [
            'username'         => 'required|min_length[3]|max_length[100]|is_unique[usr_user.username]',
            'email'            => 'required|valid_email|is_unique[usr_user.email]',
            'nama'             => 'required|min_length[3]|max_length[200]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'         => $this->request->getPost('username'),
            'email'            => $this->request->getPost('email'),
            'nama'             => $this->request->getPost('nama'),
            'password'         => $this->request->getPost('password'),
            'nohp'             => $this->request->getPost('nohp'),
            'nomor_registrasi' => $this->request->getPost('nomor_registrasi'),
            'departemen_id'    => $this->request->getPost('departemen_id'),
            'active'           => false, // Inactive by default, need admin approval
            'created_at'       => date('Y-m-d H:i:s'),
            'created_by'       => 'self-register',
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/')->with('success', 'Registrasi berhasil! Tunggu persetujuan admin untuk mengaktifkan akun Anda.');
        }

        return redirect()->back()->withInput()->with('error', 'Registrasi gagal. Silakan coba lagi.');
    }
}
