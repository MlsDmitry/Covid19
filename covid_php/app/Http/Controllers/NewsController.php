<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Unirest\Request as URequest;

class NewsController extends Controller
{
    public function list()
    {
        $response = URequest::get("https://covid-19-news.p.rapidapi.com/v1/covid?lang=en&media=True&q=covid",
            array(
                "X-RapidAPI-Host" => "covid-19-news.p.rapidapi.com",
                "X-RapidAPI-Key" => "657d899003msh8a0874e1ec13cb7p151e6bjsned33d4376979"
            )
        );
        return response()->json($response);
    }
}
