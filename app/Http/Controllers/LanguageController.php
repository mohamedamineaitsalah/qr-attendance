<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        if (in_array($lang, ['fr', 'ar'])) {
            session()->put('locale', $lang);
        }
        return redirect()->back();
    }
}
