<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

use GuzzleHttp\Client;

class NewsController extends Controller
{
    protected $guzzlehttp;

    public function __construct()
    {
        SEOMeta::setTitle(env('APP_DEFAULT_SEO_TITLE'));
        $this->guzzlehttp = new Client();
    }

    public function index(Request $request)
    {
        SEOMeta::setTitle('Новости');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');
        
        return view('news.index');
    }

    public function show($id, Request $request)
    {

        $news = Cache::remember('get_news_' . $id . '_lists_from_blogger_fetchImages_' . Str::slug(( $request->has('fetchImages') ? $request->input('fetchImages') : true ), '_') . '_id_' . env('GOOGLE_BLOGGER_NEWS_ID'), $seconds = 60 * 60 * 24,function() use ($id, $request) {
            try {
                $response = $this->guzzlehttp->request('GET', 'https://www.googleapis.com/blogger/v3/blogs/' . env('GOOGLE_BLOGGER_NEWS_ID') . '/posts/' . $id, [
                    'query' => [
                        'key' => env('GOOGLE_BLOGGER_NEWS_API_KEY', null),
                        'fetchImages' => ( $request->has('fetchImages') ? $request->input('fetchImages') : true )
                    ]
                ]);
            } catch (\Throwable $th) {
                return [];
            }
    
            if( empty((string) $response->getBody()) )
            {
                return [];
            }
    
            return json_decode((string) $response->getBody());
        });
        
        if( empty($news) )
        {
            Cache::forget('get_news_' . $id . '_lists_from_blogger_fetchImages_' . Str::slug(( $request->has('fetchImages') ? $request->input('fetchImages') : true ), '_') . '_id_' . env('GOOGLE_BLOGGER_NEWS_ID'));
            abort(404);
        }
        
        SEOMeta::setTitle($news->title);
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');

        return view('news.show', compact('news'));
    }
}
