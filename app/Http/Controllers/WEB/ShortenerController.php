<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use  AshAllenDesign\ShortURL\Classes\Builder;

class ShortenerController extends Controller
{
    public function index(Request $request)
    {

        if ($request->url != null) {
            $builder = new \AshAllenDesign\ShortURL\Classes\Builder();

            $shortURLObject = $builder->destinationUrl($request->url)->make();
            $shortURL = $shortURLObject->default_short_url;
        }else $shortURL = '#';
        return view('shortener', [
            'shortURL' => $shortURL
        ]);
    }
}
