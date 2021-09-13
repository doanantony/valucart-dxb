<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaticsController extends Controller
{
    
    public function termsofuse()
    {
        return view('termsofuse');
    }

    public function returnpolicy()
    {
        return view('returnpolicy');
    }

    public function privacypolicy()
    {
        return view('privacypolicy');
    }

    public function aboutus()
    {
        return view('about');
    }

    public function faqs()
    {
        return view('faqs');
    }

}
