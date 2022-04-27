<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\KnowledgeBase\KbPost;
use Artesaos\SEOTools\Facades\SEOMeta;

class KnowledgeBaseController extends Controller
{
    public function index(Request $request)
    {
        SEOMeta::setTitle('База знаний');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');
        
        return view('knowledge-base.index', ['auth' => Auth::check() ? 1 : 0]);
    }

    public function show(KbPost $post, Request $request)
    {
        $post->update([
            'views' => $post->views + 1
        ]);

        if( !empty($post->link) )
        {
            return redirect($post->link->src, 302);
        }

        if( !empty($post->file) )
        {
            return redirect(route('knowledge-base.show.file-download', $post), 302);
        }
    }

    public function FileDownload(KbPost $post, Request $request)
    {
        $file = $post->file;

        if( empty($file) || !Storage::disk($file->disk)->exists($file->folder . '/' . $file->src) )
        {
            abort(404);
        }

        return Storage::disk($file->disk)->download($file->folder . '/' . $file->src);
    }
}
