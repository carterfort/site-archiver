<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Jobs\ProcessUrl;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    
    public function url(Request $request)
    {
    	$this->validate($request, [
    		'url' => 'required|url'
		]);

		$sessionID = md5(time());
		//Write this session to the DB
		//Begin the process
		//Redirect to this session's processing page

		$this->dispatch(new ProcessUrl($request->url, $sessionID));

    	return redirect("/processing/{$sessionID}");
    }

    public function processSession($sessionID)
    {
    	return view('processing', compact('sessionID'));
    }
}
