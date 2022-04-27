<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

use GuzzleHttp\Client;

class CommunityProjectController extends Controller
{
    protected $guzzlehttp;

    public function __construct()
    {
        SEOMeta::setTitle(env('APP_DEFAULT_SEO_TITLE'));
        $this->guzzlehttp = new Client();
    }

    public function index(Request $request)
    {
        SEOMeta::setTitle('Проекты сообщества');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');
        
        return view('projects.communities.index');
    }


    public function show($id, Request $request)
    {
        $post = Cache::remember('get_community_projects_' . $id . '_lists_from_blogger_fetchImages_' . Str::slug(( $request->has('fetchImages') ? $request->input('fetchImages') : true ), '_') . '_id_' . env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_ID'), $seconds = 60 * 60 * 24, function() use ($id, $request) {
            try {
                $response = $this->guzzlehttp->request('GET', 'https://www.googleapis.com/blogger/v3/blogs/' . env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_ID') . '/posts/' . $id, [
                    'query' => [
                        'key' => env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_API_KEY', null),
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
        
        if( empty($post) )
        {
            Cache::forget('get_community_projects_' . $id . '_lists_from_blogger_fetchImages_' . Str::slug(( $request->has('fetchImages') ? $request->input('fetchImages') : true ), '_') . '_id_' . env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_ID'));
            abort(404);
        }


        SEOMeta::setTitle($post->title);
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');

        return view('projects.communities.show', compact('post'));
    }
}
