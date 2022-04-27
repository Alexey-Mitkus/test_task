<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

use Carbon\Carbon;
use GuzzleHttp\Client;

class CommunityProjectController extends BaseController
{
    protected $guzzlehttp;

    public function __construct()
    {
        $this->guzzlehttp = new Client();
    }

    public function index(Request $request)
    {
        $projects = Cache::remember('get_community_projects_lists_from_blogger_maxresults_' . Str::slug(($request->has('maxResults') ? $request->input('maxResults') : 70), '_') . '_fetchImages_' . Str::slug(( $request->has('fetchImages') ? $request->input('fetchImages') : true ), '_') . '_id_' . env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_ID'), $seconds = 60 * 60 * 60, function () use ($request) {
            try {
                $response = $this->guzzlehttp->request('GET', 'https://www.googleapis.com/blogger/v3/blogs/' . env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_ID') . '/posts', [
                    'query' => [
                        'key' => env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_API_KEY', null),
                        'fetchImages' => ( $request->has('fetchImages') ? $request->input('fetchImages') : true ),
                        'maxResults' => ( $request->has('maxResults') ? $request->input('maxResults') : 70 )
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

        if( empty($projects) )
        {
            Cache::forget('get_community_projects_lists_from_blogger_maxresults_' . Str::slug(( $request->has('maxResults') ? $request->input('maxResults') : 70 ), '_') . '_fetchImages_' . Str::slug(( $request->has('fetchImages') ? $request->input('fetchImages') : true ), '_') . '_id_' . env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_ID'));
        }
        return $this->sendResponse($projects);
    }

    public function search(Request $request)
    {
        $projects = Cache::remember('search_community_projects_lists_from_blogger_search_' . Str::slug(($request->has('search') ? $request->input('search') : ''), '_') . '_maxResults_' . Str::slug(($request->has('maxResults') ? $request->input('maxResults') : 70), '_') . '_fetchImages_' . Str::slug(( $request->has('fetchImages') ? $request->input('fetchImages') : true ), '_') . '_id_' . env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_ID'), $seconds = 60 * 60 * 5, function () use ($request) {
            try {
                $response = $this->guzzlehttp->request('GET', 'https://www.googleapis.com/blogger/v3/blogs/' . env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_ID') . '/posts/search', [
                    'query' => [
                        'key' => env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_API_KEY', null),
                        'q' => ($request->has('search') ? $request->input('search') : ''),
                        'fetchImages' => ( $request->has('fetchImages') ? $request->input('fetchImages') : true ),
                        'maxResults' => ( $request->has('maxResults') ? $request->input('maxResults') : 70 ),
                        'fetch' => true,
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

        if( empty($projects) )
        {
            Cache::forget('search_community_projects_lists_from_blogger_search_' . Str::slug(($request->has('search') ? $request->input('search') : ''), '_') . '_maxResults_' . Str::slug(($request->has('maxResults') ? $request->input('maxResults') : 70), '_') . '_fetchImages_' . Str::slug(( $request->has('fetchImages') ? $request->input('fetchImages') : true ), '_') . '_id_' . env('GOOGLE_BLOGGER_COMMUNITY_PROJECT_ID'));
        }
        return $this->sendResponse($projects);
    }
}
