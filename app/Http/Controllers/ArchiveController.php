<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Jobs\ProcessUrl;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    
    public function url(Request $request) {
    	$this->validate($request, [
    		'url' => 'required|url'
		]);

		$sessionID = session()->getId();
		$scrubbedUrl = preg_replace('~(http|https)://|(\.com)~', '', $request->url);
		$redirectUrl = url("process/{$sessionID}/download/{$scrubbedUrl}");

		$this->dispatch(new ProcessUrl($request->url, $sessionID));
        
        return view('processing', compact('sessionID', 'redirectUrl'));

    }

    public function download($sessionId, $name) {
    	return response()->download(storage_path('archives/'.$sessionId.'.zip'), $name.'.zip');
    }

}
