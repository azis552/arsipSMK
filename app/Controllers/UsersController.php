<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UsersController extends BaseController
{
    public function index()
    {
        return view('users/index');
    }

    public function login()
    {
        return view('users/login');
    }
}
