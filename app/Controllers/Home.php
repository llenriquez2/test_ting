<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        //  // Check if user is logged in
        //  if (!auth()->loggedIn()) {
        //     // If not logged in, redirect to login page
        //     return redirect()->to('/login');
        // }

        // // If logged in, redirect to student list
        // return redirect()->to('/student');

        return view('welcome_message');

    }
}
