<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('display_order', 'asc')->get();
        return view('frontend.partners.index', compact('partners')); // Vue: resources/views/frontend/partners/index.blade.php
    }
}