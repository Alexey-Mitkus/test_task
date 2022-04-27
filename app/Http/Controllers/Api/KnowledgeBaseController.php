<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Filters\KbPostFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Media;
use App\Models\KnowledgeBase\KbPost;
use App\Models\KnowledgeBase\KbTheme;
use App\Models\KnowledgeBase\KbFormat;
use App\Models\KnowledgeBase\KbTag;

class KnowledgeBaseController extends BaseController
{
    public function index(KbPostFilter $filter, Request $request)
    {
        $user = $request->user('api');

        if( $request->missing('state') )
        {
            $request->merge([
                'state' => 1
            ]);
        }

        if( $request->missing('orderBy') )
        {
            $request->merge([
                'orderBy' => 'newest'
            ]);
        }

        if( $request->missing('status') )
        {
            $request->merge([
                'status' => KbPost::CONST_STATUS_PUBLISHED,
            ]);
        }

        $getPosts = KbPost::filter($filter)->paginate(($request->has('limit') ? (int) $request->input('limit') : 6));

        $posts = collect([]);

        foreach($getPosts as $key => $post):
            
            $posts->push([
                'id' => $post->id,
                'user_id' => $post->user_id,
                'title' => $post->name,
                'description' => $post->description,
                'slug' => $post->slug,
                'theme' => optional($post->themes()->whereNull('parent_id')->get())->toArray(),
                'sub_theme' => optional($post->subthemes)->toArray(),
                'link' => !empty($post->link) ? $post->link->src : null,
                'file' => !empty($post->file) ? [
                    'name' => !empty($post->file) ? optional($post->file)->name . '.' . optional($post->file)->extension : $post->file->src,
                    'url' => !empty($post->file) ? $post->file_url : null,
                    'download' => !empty($post->file) ? url(route('knowledge-base.show.file-download', $post)) : null,
                ] : null,
                'image' => $post->image_url,
                'format' => optional($post->formats)->toArray(),
                'lang' => $post->lang,
                'tags' => optional($post->tags)->toArray(),
                'likes' => optional($post->likes)->count(),
                'liked' => !empty($user->id) ? optional($post->likes)->contains($user->id) : false,
                'status' => $post->status,
            ]);

        endforeach;

        $Themes = KbTheme::active()->whereNull('parent_id')->with(['childrens'])->get();
        $Formats = KbFormat::active()->whereNull('parent_id')->with(['childrens'])->get();

        $ThemesCurrent = collect([]);
        $FormatsCurrent = collect([]);

        if( $request->filled('formats') )
        {
            $FormatsCurrent = $Formats->whereIn('id', $request->input('formats'))->all();
        }

        if( $request->filled('themes') )
        {
            $ThemesCurrent = $Themes->whereIn('id', $request->input('themes'))->all();
        }

        $data = [
            'status' => optional($posts)->count() ? 'true' : 'false',
            'data' => optional($posts)->toArray(),
            'filters' => [
                'themes' => [
                    'lists' => optional($Themes)->toArray(),
                    'current' => optional($ThemesCurrent)->toArray(),
                ],
                'formats' => [
                    'lists' => optional($Formats)->toArray(),
                    'current' => optional($FormatsCurrent)->toArray()
                ]
            ],
            'allcount' => optional($getPosts)->total(),
            'currentPage' => optional($getPosts)->currentPage(),
            'lastPage' => optional($getPosts)->lastPage(),
        ];

        if( $request->filled('search') )
        {
            $data['search'] = [
                'query' => $request->input('search')
            ];
        }

        return $this->sendResponse($data);
    }

    public function searchLink(Request $request)
    {
        $validator = Validator::make($request->all(), $rules = [
            'link' => 'required'
        ]);

        if( $validator->fails() )
        {
            return $this->sendError($validator->errors()->first(), $errorMessages = [], $code = 200);
        }

        $count = KbPost::whereHas('links', function($query) use ($request){
            $query
                ->where('src', $request->input('link'))
                ->orWhere('src', urldecode($request->input('link')));
        })->count();

        if( $count )
        {
            return $this->sendError('link exists', $errorMessages = [], $code = 200);
        }

        return $this->sendResponse([], 'link not found');
    }

    public function setUnsetLikes(KbPost $post, Request $request)
    {
        $user = $request->user('api');

        if( !$user )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }

        if( $user->kbpostslikes->contains($post) )
        {
            $user->kbpostslikes()->detach($post);
        }else{
            $user->kbpostslikes()->attach($post);
        }

        $posts = collect([
            'id' => $post->id,
            'user_id' => $post->user_id,
            'title' => $post->name,
            'description' => $post->description,
            'theme' => optional($post->themes()->whereNull('parent_id')->get())->toArray(),
            'sub_theme' => optional($post->subthemes)->toArray(),
            'link' => $post->link,
            'image' => $post->image,
            'format' => optional($post->formats)->toArray(),
            'lang' => $post->lang,
            'tags' => optional($post->tags)->toArray(),
            'likes' => optional($post->likes)->count(),
            'liked' => !empty($user->id) ? optional($post->likes)->contains($user) : false
        ]);

        return $this->sendResponse($posts);
    }

    public function store(Request $request)
    {
        $user = $request->user('api');

        if( !$user )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }

        $ThemeOtherVariable = KbTheme::where('name', 'Другое')->whereNull('parent_id')->first();
        $FormatOtherVariable = KbFormat::where('name', 'Другое')->whereNull('parent_id')->first();

        $validator = Validator::make($request->all(), $rules = [
            'title' => 'required',
            'description' => 'required',
            'theme' => 'required|exists:App\Models\KnowledgeBase\KbTheme,id',
            'sub_theme' => 'string',
            // 'newTheme' => 'required_if:theme,==,' . $ThemeOtherVariable->id,
            'format' => 'required|exists:App\Models\KnowledgeBase\KbFormat,id',
            // 'newFormat' => 'required_if:format,==,' . $FormatOtherVariable->id,
            'tags' => 'string',
            'link' => 'required_without:file',
        ], [
            'title.required' => 'Поле "Название материала" обязательно для заполнения.',
            'description.required' => 'Поле "Описание материала" обязательно для заполнения.',
            'theme.required' => 'Поле "Тема материала" обязательно для заполнения.',
            'theme.exists' => 'Пожалуйста, выберите тему материала корректно',
            // 'newTheme.required_if' => 'Необходимо указать название новой темы, если существующие Вам не подходят',
            'format.required' => 'Поле "Формат материала" обязательно для заполнения.',
            'format.exists' => 'Пожалуйста, выберите формат материала корректно',
            // 'newFormat.required_if' => 'Необходимо указать название нового формата, если существующие Вам не подходят',
            'link.required' => 'Поле "Ссылка на материал" обязательно для заполнения.',
            'link.required_without' => 'Поле "Ссылка на материал" обязательно для заполнения, если Вы не загружаете файл.',
            'sub_theme.array' => 'Поле "Подкатегория" должна быть массивом',
            'tags.array' => 'Поле "Теги" должно быть массивом',
            'tags.size' => 'Максимальное количество тегов не больше 3-х',
        ]);

        if( $validator->fails() )
        {
            return $this->sendError($validator->errors()->toArray(), $errorMessages = [], $code = 200);
        }

        if( !$request->filled('file') )
        {
            $validator = Validator::make($request->all(), $rules = [
                'file' => 'file|mimes:pdf,xls,xlsx,docs,docx',
            ]);

            if( $validator->fails() )
            {
                return $this->sendError($validator->errors()->toArray(), $errorMessages = [], $code = 200);
            }
        }
        
        $postData = [
            'name' => $request->input('title'),
            'description' => preg_replace('/(<[^>]+) style=".*?"/i', '$1', strip_tags($request->input('description'), '')),
            // 'lang' => 'rus',
            'status_id' => KbPost::CONST_STATUS_MODERATION,
            'user_id' => $user->id,
            'views' => 0,
            'is_active' => 1,
            'slug' => null
        ];

        $post = KbPost::create($postData);

        if( $request->filled('theme') )
        {
            $post->themes()->attach($request->input('theme'));
        }

        if( $request->filled('newTheme') )
        {
            $newTheme = KbTheme::firstOrCreate([
                'name' => $request->input('newTheme')
            ],[
                'user_id' => $user->id,
                'is_active' => 0,
            ]);

            if( !empty($newTheme) )
            {
                $post->themes()->attach($newTheme->id);
            }
        }

        if( $request->filled('sub_theme') )
        {
            $HeadTheme = KbTheme::find($request->input('theme'));
            
            $sub_theme = $this->parseString($request->input('sub_theme'));

            foreach($sub_theme as $tkey => $subth):
                $subtheme = KbTheme::firstOrCreate([
                    'name' => $subth
                ],[
                    'user_id' => $user->id,
                    'parent_id' => $HeadTheme->id,
                    'is_active' => 0,
                ]);
                if( !empty($subtheme) )
                {
                    $post->themes()->attach($subtheme->id);
                }
            endforeach;
        }
        
        if( $request->filled('format') )
        {
            $post->formats()->attach($request->input('format'));
        }

        if( $request->filled('newFormat') )
        {
            $newFormat = KbFormat::firstOrCreate([
                'name' => $request->input('newFormat')
            ],[
                'user_id' => $user->id,
                'is_active' => 0,
            ]);

            if( !empty($newFormat) )
            {
                $post->formats()->attach($newFormat->id);
            }

        }

        if( $request->filled('tags') )
        {
            $tags = $this->parseString($request->input('tags'));
            foreach($tags as $tkey => $tg):
                $tag = KbTag::firstOrCreate([
                    'name' => $tg
                ],[
                    'user_id' => $user->id
                ]);
                if( !empty($tag) )
                {
                    $post->tags()->attach($tag->id);
                }
            endforeach;
        }

        if( $request->filled('link') )
        {
            $post->media()->create([
                'name' => $post->name,
                'type' => 'link',
                'user_id' => $user->id,
                'src' => urldecode($request->input('link'))
            ]);
        }

        if( $request->hasFile('file') && $request->file('file')->isValid() )
        {
            $UploadedFile = $request->file('file');

            do{
                $UploadedFileNewName = Str::uuid() . '.' . $UploadedFile->getClientOriginalExtension();
            }while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

            $FileData = [
                'name' => pathinfo($UploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
                'type' => 'file',
                'extension' => $UploadedFile->getClientOriginalExtension(),
                'user_id' => $user->id,
                'src' => $UploadedFileNewName,
                'mimes' => $UploadedFile->getMimeType(),
                'disk' => 'public',
                'size' => $UploadedFile->getSize(),
                'folder' => '/uploads/knowledgebase/',
            ];
            if( $UploadedFile->storePubliclyAs($FileData['folder'], $FileData['src'], $FileData['disk']) )
            {
                $post->media()->create($FileData);
            }
        }

        // \Notification::route('mail', env('MAIL_ADMINISTRATOR_EMAIL'))->notify(new \App\Notifications\KnowledgeBaseNewPostNotification($post, $user->withFields()));
        \App\Models\User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()->notify(new \App\Notifications\KnowledgeBaseNewPostNotification($post, $user->withFields()));

        return $this->sendResponse([], 'The post has been successfully submitted and is awaiting moderation');
    }

    public function DashboardStore(Request $request)
    {
        $user = $request->user('api');

        if( !$user )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }
        
        if( !$user->hasRole('admin') )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }

        $ThemeOtherVariable = KbTheme::where('name', 'Другое')->whereNull('parent_id')->first();
        $FormatOtherVariable = KbFormat::where('name', 'Другое')->whereNull('parent_id')->first();

        $validator = Validator::make($request->all(), $rules = [
            'title' => 'required',
            'description' => 'required',
            'theme' => 'required|exists:App\Models\KnowledgeBase\KbTheme,id',
            'sub_theme' => 'string',
            'newTheme' => 'required_if:theme,==,' . $ThemeOtherVariable->id,
            'format' => 'required|exists:App\Models\KnowledgeBase\KbFormat,id',
            'newFormat' => 'required_if:format,==,' . $FormatOtherVariable->id,
            'tags' => 'string',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
            // 'file' => 'file|mimes:pdf,xls,xlsx,docs,docx',
            'link' => 'required_without:file|active_url',
        ], [
            'title.required' => 'Поле "Название материала" обязательно для заполнения.',
            'description.required' => 'Поле "Описание материала" обязательно для заполнения.',
            'theme.required' => 'Поле "Тема материала" обязательно для заполнения.',
            'theme.exists' => 'Пожалуйста, выберите тему материала корректно',
            'newTheme.required_if' => 'Необходимо указать название новой темы, если существующие Вам не подходят',
            'format.required' => 'Поле "Формат материала" обязательно для заполнения.',
            'format.exists' => 'Пожалуйста, выберите формат материала корректно',
            'newFormat.required_if' => 'Необходимо указать название нового формата, если существующие Вам не подходят',
            'link.required' => 'Поле "Ссылка на материал" обязательно для заполнения.',
            'link.required_without' => 'Поле "Ссылка на материал" обязательно для заполнения, если Вы не загружаете файл.',
            'sub_theme.array' => 'Поле "Подкатегория" должна быть массивом',
            'tags.array' => 'Поле "Теги" должно быть массивом',
            'tags.size' => 'Максимальное количество тегов не больше 3-х',
        ]);

        if( $validator->fails() )
        {
            return $this->sendError($validator->errors()->toArray(), $errorMessages = [], $code = 200);
        }

        $postData = [
            'name' => $request->input('title'),
            'description' => preg_replace('/(<[^>]+) style=".*?"/i', '$1', strip_tags($request->input('description'), '')),
            'lang' => !empty($request->input('lang')) ? $request->input('lang') : 'rus',
            'status_id' => KbPost::CONST_STATUS_PUBLISHED,
            'user_id' => $user->id,
            'views' => 0,
            'is_active' => 1,
            'slug' => null
        ];

        $post = KbPost::create($postData);

        if( $request->filled('theme') )
        {
            $post->themes()->attach($request->input('theme'));
        }

        if( $request->filled('newTheme') )
        {
            $newTheme = KbTheme::firstOrCreate([
                'name' => $request->input('newTheme')
            ],[
                'user_id' => $user->id,
                'is_active' => 1,
            ]);

            if( !empty($newTheme) )
            {
                $post->themes()->attach($newTheme->id);
            }
        }

        if( $request->filled('sub_theme') )
        {
            $HeadTheme = KbTheme::find($request->input('theme'));

            $sub_theme = $this->parseString($request->input('sub_theme'));
            foreach($sub_theme as $tkey => $subth):
                $subtheme = KbTheme::firstOrCreate([
                    'name' => $subth
                ],[
                    'user_id' => $user->id,
                    'parent_id' => $HeadTheme->id
                ]);
                if( !empty($subtheme) )
                {
                    $post->themes()->attach($subtheme->id);
                }
            endforeach;
        }
        
        if( $request->filled('format') )
        {
            $post->formats()->attach($request->input('format'));
        }

        if( $request->filled('newFormat') )
        {
            $newFormat = KbFormat::firstOrCreate([
                'name' => $request->input('newFormat')
            ],[
                'user_id' => $user->id,
                'is_active' => 1,
            ]);

            if( !empty($newFormat) )
            {
                $post->formats()->attach($newFormat->id);
            }

        }

        if( $request->filled('tags') )
        {
            $tags = $this->parseString($request->input('tags'));
            foreach($tags as $tkey => $tg):
                $tag = KbTag::firstOrCreate([
                    'name' => $tg
                ],[
                    'user_id' => $user->id
                ]);
                if( !empty($tag) )
                {
                    $post->tags()->attach($tag->id);
                }
            endforeach;
        }

        if( $request->filled('link') )
        {
            $post->media()->create([
                'name' => $post->name,
                'type' => 'link',
                'user_id' => $user->id,
                'src' => urldecode($request->input('link'))
            ]);
        }

        if( $request->hasFile('file') && $request->file('file')->isValid() )
        {
            $UploadedFile = $request->file('file');

            do{
                $UploadedFileNewName = Str::uuid() . '.' . $UploadedFile->getClientOriginalExtension();
            }while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

            $FileData = [
                'name' => pathinfo($UploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
                'type' => 'file',
                'extension' => $UploadedFile->getClientOriginalExtension(),
                'user_id' => $user->id,
                'src' => $UploadedFileNewName,
                'mimes' => $UploadedFile->getMimeType(),
                'disk' => 'public',
                'size' => $UploadedFile->getSize(),
                'folder' => '/uploads/knowledgebase/',
            ];

            if( $UploadedFile->storePubliclyAs($FileData['folder'], $FileData['src'], $FileData['disk']) )
            {
                $post->media()->create($FileData);
            }
        }

        if( $request->hasFile('image') && $request->file('image')->isValid() )
        {
            $post->ImageRepositoryDelete($collection = 'poster');
            $post->ImageRepository($files = $request->file('image'), $sizesArray = config('knowledgebase.posters.sizes'), $storageDisk = 'public', $folderStorageDisk = '/uploads/knowledgebase/', $Author = $user->id);
        }

        // if( $request->hasFile('image') && $request->file('image')->isValid() )
        // {
        //     $UploadedFile = $request->file('image');

        //     do{
        //         $UploadedFileNewName = Str::uuid() . '.' . $UploadedFile->getClientOriginalExtension();
        //     }while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

        //     $FileData = [
        //         'name' => pathinfo($UploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
        //         'type' => 'image',
        //         'extension' => $UploadedFile->getClientOriginalExtension(),
        //         'user_id' => $user->id,
        //         'src' => $UploadedFileNewName,
        //         'mimes' => $UploadedFile->getMimeType(),
        //         'disk' => 'public',
        //         'size' => $UploadedFile->getSize(),
        //         'folder' => '/uploads/knowledgebase/',
        //     ];
            
        //     if( $UploadedFile->storePubliclyAs($FileData['folder'], $FileData['src'], $FileData['disk']) )
        //     {
        //         $post->media()->create($FileData);
        //     }
        // }

        $this->sendResponse([
            'id' => $post->id,
            'user_id' => $post->user_id,
            'title' => $post->name,
            'description' => $post->description,
            'slug' => $post->slug,
            'theme' => optional($post->themes()->whereNull('parent_id')->get())->toArray(),
            'sub_theme' => optional($post->subthemes)->toArray(),
            'link' => !empty($post->link) ? $post->link->src : null,
            'file' => !empty($post->file) ? [
                'name' => !empty($post->file) ? optional($post->file)->name . '.' . optional($post->file)->extension : $post->file->src,
                'url' => !empty($post->file) ? $post->file_url : null,
                'download' => !empty($post->file) ? url(route('knowledge-base.show.file-download', $post)) : null,
            ] : null,
            'image' => $post->image_url,
            'format' => optional($post->formats)->toArray(),
            'lang' => $post->lang,
            'tags' => optional($post->tags)->toArray(),
            // 'likes' => optional($post->likes)->toArray(),
            'likes' => optional($post->likes)->count(),
            'liked' => !empty($user->id) ? optional($post->likes)->contains($user->id) : false
        ], 'The post has been successfully.');
    }

    public function FileDestroy(KbPost $post, Request $request)
    {
        $user = $request->user('api');

        if( !$user )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }
        
        if( !$user->hasRole('admin') )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }

        if( !$post->files->count() )
        {
            return $this->sendError('Files not found', $errorMessages = [], $code = 200);
        }

        foreach($post->files as $key => $file):
            switch($file->storage)
            {
                case 'local':
                default:
                    if( Storage::disk($file->disk)->exists($file->folder . '/' . $file->src) )
                    {
                        Storage::disk($file->disk)->delete($file->folder . '/' . $file->src);
                    }
                    $file->delete();
                break;
            }
        endforeach;
        $this->sendResponse([], 'The post has been successfully submitted and is awaiting moderation');
    }

    public function edit(KbPost $post, Request $request)
    {
        $user = $request->user('api');

        if( !$user )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }
        
        if( !$user->hasRole('admin') )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }

        return $this->sendResponse([
            'id' => $post->id,
            'user_id' => $post->user_id,
            'title' => $post->name,
            'description' => $post->description,
            'slug' => $post->slug,
            'theme' => optional($post->themes()->whereNull('parent_id')->get())->toArray(),
            'sub_theme' => optional($post->subthemes)->toArray(),
            'link' => !empty($post->link) ? $post->link->src : null,
            'file' => !empty($post->file) ? [
                'name' => !empty($post->file) ? optional($post->file)->name . '.' . optional($post->file)->extension : $post->file->src,
                'url' => !empty($post->file) ? $post->file_url : null,
                'download' => !empty($post->file) ? url(route('knowledge-base.show.file-download', $post)) : null,
            ] : null,
            'image' => $post->image_url,
            'format' => optional($post->formats)->toArray(),
            'lang' => $post->lang,
            'tags' => optional($post->tags)->toArray(),
            'likes' => optional($post->likes)->toArray(),
            'liked' => !empty($user->id) ? optional($post->likes)->contains($user->id) : false
        ]);
    }

    public function update(KbPost $post, Request $request)
    {
        $user = $request->user('api');

        if( !$user )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }
        
        if( !$user->hasRole('admin') )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }

        $ThemeOtherVariable = KbTheme::where('name', 'Другое')->whereNull('parent_id')->first();
        $FormatOtherVariable = KbFormat::where('name', 'Другое')->whereNull('parent_id')->first();

        $validator = Validator::make($request->all(), $rules = [
            'title' => 'required',
            'description' => 'required',
            'theme' => 'required|exists:App\Models\KnowledgeBase\KbTheme,id',
            'sub_theme' => 'string',
            'newTheme' => 'required_if:theme,==,' . $ThemeOtherVariable->id,
            'format' => 'required|exists:App\Models\KnowledgeBase\KbFormat,id',
            'newFormat' => 'required_if:format,==,' . $FormatOtherVariable->id,
            'tags' => 'string',
            // 'image' => 'image|mimes:jpg,png,jpeg,gif,svg',
            'file' => 'file|mimes:pdf,xls,xlsx,docs,docx',
            'link' => 'required_without:file|active_url',
        ], [
            'title.required' => 'Поле "Название материала" обязательно для заполнения.',
            'description.required' => 'Поле "Описание материала" обязательно для заполнения.',
            'theme.required' => 'Поле "Тема материала" обязательно для заполнения.',
            'theme.exists' => 'Пожалуйста, выберите тему материала корректно',
            'newTheme.required_if' => 'Необходимо указать название новой темы, если существующие Вам не подходят',
            'format.required' => 'Поле "Формат материала" обязательно для заполнения.',
            'format.exists' => 'Пожалуйста, выберите формат материала корректно',
            'newFormat.required_if' => 'Необходимо указать название нового формата, если существующие Вам не подходят',
            'link.required' => 'Поле "Ссылка на материал" обязательно для заполнения.',
            'link.required_without' => 'Поле "Ссылка на материал" обязательно для заполнения, если Вы не загружаете файл.',
            'sub_theme.array' => 'Поле "Подкатегория" должна быть массивом',
            'tags.array' => 'Поле "Теги" должно быть массивом',
            'tags.size' => 'Максимальное количество тегов не больше 3-х',
        ]);

        if( $validator->fails() )
        {
            return $this->sendError($validator->errors()->toArray(), $errorMessages = [], $code = 200);
        }

        $postData = [
            'name' => $request->input('title'),
            'description' => preg_replace('/(<[^>]+) style=".*?"/i', '$1', strip_tags($request->input('description'), '')),
            'lang' => !empty($request->input('lang')) ? $request->input('lang') : 'rus',
            'status_id' => $request->filled('status_id') ? $request->input('status_id') : $post->status_id,
            'user_id' => $request->filled('user_id') ? $request->input('user_id') : $post->user_id,
            'is_active' => $request->filled('is_active') ? $request->input('is_active') : $post->is_active,
            'slug' => $request->filled('slug') ? $request->input('slug') : $post->slug,
        ];

        $post->update($postData);

        if( $post->themes->count() )
        {
            $post->themes()->detach();
        }

        if( $request->filled('theme') )
        {
            $post->themes()->attach($request->input('theme'));
        }

        if( $request->filled('newTheme') )
        {
            $newTheme = KbTheme::firstOrCreate([
                'name' => $request->input('newTheme')
            ],[
                'user_id' => $user->id,
                'is_active' => 1,
            ]);

            if( !empty($newTheme) )
            {
                $post->themes()->attach($newTheme->id);
            }
        }

        if( $request->filled('sub_theme') )
        {
            $HeadTheme = KbTheme::find($request->input('theme'));

            $sub_theme = $this->parseString($request->input('sub_theme'));
            foreach($sub_theme as $tkey => $subth):
                $subtheme = KbTheme::firstOrCreate([
                    'name' => $subth
                ],[
                    'user_id' => $user->id,
                    'parent_id' => $HeadTheme->id
                ]);
                if( !empty($subtheme) )
                {
                    $post->themes()->attach($subtheme->id);
                }
            endforeach;
        }
        
        if( $post->formats->count() )
        {
            $post->formats()->detach();
        }

        if( $request->filled('format') )
        {
            $post->formats()->attach($request->input('format'));
        }

        if( $request->filled('newFormat') )
        {
            $newFormat = KbFormat::firstOrCreate([
                'name' => $request->input('newFormat')
            ],[
                'user_id' => $user->id,
                'is_active' => 1,
            ]);

            if( !empty($newFormat) )
            {
                $post->formats()->attach($newFormat->id);
            }

        }

        if( $request->filled('tags') )
        {
            if( $post->tags->count() )
            {
                $post->tags()->detach();
            }

            $tags = $this->parseString($request->input('tags'));
            foreach($tags as $tkey => $tg):
                $tag = KbTag::firstOrCreate([
                    'name' => $tg
                ],[
                    'user_id' => $user->id
                ]);
                if( !empty($tag) )
                {
                    $post->tags()->attach($tag->id);
                }
            endforeach;
        }

        if( $request->filled('link') )
        {
            if( $post->links->count() )
            {
                $post->links()->delete();
            }

            $post->media()->create([
                'name' => $post->name,
                'type' => 'link',
                'user_id' => $user->id,
                'src' => urldecode($request->input('link'))
            ]);
        }

        if( $request->hasFile('file') && $request->file('file')->isValid() )
        {

            if( $post->files->count() )
            {
                foreach($post->files as $key => $file):
                    switch($file->storage)
                    {
                        case 'local':
                        default:
                            if( !empty($file->disk) && !empty($file->folder) && !empty($file->src) )
                            {
                                if( Storage::disk($file->disk)->exists($file->folder . '/' . $file->src) )
                                {
                                    Storage::disk($file->disk)->delete($file->folder . '/' . $file->src);
                                } 
                            }
                            $file->delete();
                        break;
                    }
                endforeach;
            }

            $UploadedFile = $request->file('file');

            do{
                $UploadedFileNewName = Str::uuid() . '.' . $UploadedFile->getClientOriginalExtension();
            }while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

            $FileData = [
                'name' => pathinfo($UploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
                'type' => 'file',
                'extension' => $UploadedFile->getClientOriginalExtension(),
                'user_id' => $user->id,
                'src' => $UploadedFileNewName,
                'mimes' => $UploadedFile->getMimeType(),
                'disk' => 'public',
                'size' => $UploadedFile->getSize(),
                'folder' => '/uploads/knowledgebase/',
            ];

            if( $UploadedFile->storePubliclyAs($FileData['folder'], $FileData['src'], $FileData['disk']) )
            {
                $post->media()->create($FileData);
            }
        }

        if( $request->hasFile('image') && $request->file('image')->isValid() )
        {

            if( $post->images->count() )
            {
                foreach($post->images as $key => $image):
                    switch($image->storage)
                    {
                        case 'local':
                        default:
                            if( !empty($image->disk) && !empty($image->folder) && !empty($image->src) )
                            {
                                if( Storage::disk($image->disk)->exists($image->folder . '/' . $image->src) )
                                {
                                    Storage::disk($image->disk)->delete($image->folder . '/' . $image->src);
                                } 
                            }
                            $image->delete();
                        break;
                    }
                endforeach;
            }

            $post->ImageRepositoryDelete($collection = 'poster');
            $post->ImageRepository($files = $request->file('image'), $sizesArray = config('knowledgebase.posters.sizes'), $storageDisk = 'public', $folderStorageDisk = '/uploads/knowledgebase/', $Author = $user->id);
            
            // $UploadedFile = $request->file('image');

            // do{
            //     $UploadedFileNewName = Str::uuid() . '.' . $UploadedFile->getClientOriginalExtension();
            // }while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

            // $FileData = [
            //     'name' => pathinfo($UploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
            //     'type' => 'image',
            //     'extension' => $UploadedFile->getClientOriginalExtension(),
            //     'user_id' => $user->id,
            //     'src' => $UploadedFileNewName,
            //     'mimes' => $UploadedFile->getMimeType(),
            //     'disk' => 'public',
            //     'size' => $UploadedFile->getSize(),
            //     'folder' => '/uploads/knowledgebase/',
            // ];
            
            // if( $UploadedFile->storePubliclyAs($FileData['folder'], $FileData['src'], $FileData['disk']) )
            // {
            //     $post->media()->create($FileData);
            // }
        }
        return $this->sendResponse([
            'id' => $post->id,
            'user_id' => $post->user_id,
            'title' => $post->name,
            'description' => $post->description,
            'slug' => $post->slug,
            'theme' => optional($post->themes()->whereNull('parent_id')->get())->toArray(),
            'sub_theme' => optional($post->subthemes)->toArray(),
            'link' => !empty($post->link) ? $post->link->src : null,
            'file' => !empty($post->file) ? [
                'name' => !empty($post->file) ? optional($post->file)->name . '.' . optional($post->file)->extension : $post->file->src,
                'url' => !empty($post->file) ? $post->file_url : null,
                'download' => !empty($post->file) ? url(route('knowledge-base.show.file-download', $post)) : null,
            ] : null,
            'image' => $post->image_url,
            'format' => optional($post->formats)->toArray(),
            'lang' => $post->lang,
            'tags' => optional($post->tags)->toArray(),
            // 'likes' => optional($post->likes)->toArray(),
            'likes' => optional($post->likes)->count(),
            'liked' => !empty($user->id) ? optional($post->likes)->contains($user->id) : false,
            'status' => $post->status
        ], 'The post has been successfully.');
    }

    public function approved(KbPost $post, Request $request)
    {
        $user = $request->user('api');

        if( !$user )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }
        
        if( !$user->hasRole('admin') )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }

        $ThemeOtherVariable = KbTheme::where('name', 'Другое')->whereNull('parent_id')->first();
        $FormatOtherVariable = KbFormat::where('name', 'Другое')->whereNull('parent_id')->first();

        $validator = Validator::make($request->all(), $rules = [
            'title' => 'required',
            'description' => 'required',
            'theme' => 'required|exists:App\Models\KnowledgeBase\KbTheme,id',
            'sub_theme' => 'string',
            // 'newTheme' => 'required_if:theme,==,' . $ThemeOtherVariable->id,
            'format' => 'required|exists:App\Models\KnowledgeBase\KbFormat,id',
            // 'newFormat' => 'required_if:format,==,' . $FormatOtherVariable->id,
            // 'tags' => 'array|size:3',
            'tags' => 'string',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg',
            'file' => 'file|mimes:pdf,xls,xlsx,docs,docx',
            'link' => 'required_without:file|active_url',
        ], [
            'title.required' => 'Поле "Название материала" обязательно для заполнения.',
            'description.required' => 'Поле "Описание материала" обязательно для заполнения.',
            'theme.required' => 'Поле "Тема материала" обязательно для заполнения.',
            'theme.exists' => 'Пожалуйста, выберите тему материала корректно',
            // 'newTheme.required_if' => 'Необходимо указать название новой темы, если существующие Вам не подходят',
            'format.required' => 'Поле "Формат материала" обязательно для заполнения.',
            'format.exists' => 'Пожалуйста, выберите формат материала корректно',
            // 'newFormat.required_if' => 'Необходимо указать название нового формата, если существующие Вам не подходят',
            'link.required' => 'Поле "Ссылка на материал" обязательно для заполнения.',
            'link.required_without' => 'Поле "Ссылка на материал" обязательно для заполнения, если Вы не загружаете файл.',
            'sub_theme.array' => 'Поле "Подкатегория" должна быть массивом',
            'tags.array' => 'Поле "Теги" должно быть массивом',
            'tags.size' => 'Максимальное количество тегов не больше 3-х',
        ]);

        if( $validator->fails() )
        {
            return $this->sendError($validator->errors()->toArray(), $errorMessages = [], $code = 200);
        }

        $postData = [
            'name' => $request->input('title'),
            'description' => preg_replace('/(<[^>]+) style=".*?"/i', '$1', strip_tags($request->input('description'), '')),
            'lang' => !empty($request->input('lang')) ? $request->input('lang') : 'rus',
            'status_id' => KbPost::CONST_STATUS_PUBLISHED,
            'user_id' => $request->filled('user_id') ? $request->input('user_id') : $post->user_id,
            'is_active' => $request->filled('is_active') ? $request->input('is_active') : $post->is_active,
            'slug' => $request->filled('slug') ? $request->input('slug') : $post->slug,
        ];

        $post->update($postData);

        if( $post->themes->count() )
        {
            $post->themes()->detach();
        }

        if( $request->filled('theme') )
        {
            $post->themes()->attach($request->input('theme'));
        }

        if( $request->filled('newTheme') )
        {
            $newTheme = KbTheme::firstOrCreate([
                'name' => $request->input('newTheme')
            ],[
                'user_id' => $user->id,
                'is_active' => 1,
            ]);

            if( !empty($newTheme) )
            {
                $post->themes()->attach($newTheme->id);
            }
        }

        if( $request->filled('sub_theme') )
        {
            $HeadTheme = KbTheme::find($request->input('theme'));
            
            $sub_theme = $this->parseString($request->input('sub_theme'));

            foreach($sub_theme as $tkey => $subth):
                $subtheme = KbTheme::firstOrCreate([
                    'name' => $subth
                ],[
                    'user_id' => $user->id,
                    'parent_id' => $HeadTheme->id,
                    'is_active' => 1,
                ]);
                if( !empty($subtheme) )
                {
                    $post->themes()->attach($subtheme->id);
                }
            endforeach;
        }
        
        if( $post->formats->count() )
        {
            $post->formats()->detach();
        }

        if( $request->filled('format') )
        {
            $post->formats()->attach($request->input('format'));
        }

        if( $request->filled('newFormat') )
        {
            $newFormat = KbFormat::firstOrCreate([
                'name' => $request->input('newFormat')
            ],[
                'user_id' => $user->id,
                'is_active' => 1,
            ]);

            if( !empty($newFormat) )
            {
                $post->formats()->attach($newFormat->id);
            }

        }

        if( $request->filled('tags') )
        {   
              
            if( $post->tags->count() )
            {
                $post->tags()->detach();
            }
            $tags = $this->parseString($request->input('tags'));

            foreach($tags as $tkey => $tg):
                $tag = KbTag::firstOrCreate([
                    'name' => $tg
                ],[
                    'user_id' => $user->id
                ]);
                if( !empty($tag) )
                {
                    $post->tags()->attach($tag->id);
                }
            endforeach;
        }

        if( $request->filled('link') )
        {
            if( $post->links->count() )
            {
                $post->links()->delete();
            }

            $post->media()->create([
                'name' => $post->name,
                'type' => 'link',
                'user_id' => $user->id,
                'src' => urldecode($request->input('link'))
            ]);
        }

        if( $request->hasFile('file') && $request->file('file')->isValid() )
        {

            if( $post->files->count() )
            {
                foreach($post->files as $key => $file):
                    switch($file->storage)
                    {
                        case 'local':
                        default:
                            if( !empty($file->disk) && !empty($file->folder) && !empty($file->src) )
                            {
                                if( Storage::disk($file->disk)->exists($file->folder . '/' . $file->src) )
                                {
                                    Storage::disk($file->disk)->delete($file->folder . '/' . $file->src);
                                } 
                            }
                            $file->delete();
                        break;
                    }
                endforeach;
            }

            $UploadedFile = $request->file('file');

            do{
                $UploadedFileNewName = Str::uuid() . '.' . $UploadedFile->getClientOriginalExtension();
            }while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

            $FileData = [
                'name' => pathinfo($UploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
                'type' => 'file',
                'extension' => $UploadedFile->getClientOriginalExtension(),
                'user_id' => $user->id,
                'src' => $UploadedFileNewName,
                'mimes' => $UploadedFile->getMimeType(),
                'disk' => 'public',
                'size' => $UploadedFile->getSize(),
                'folder' => '/uploads/knowledgebase/',
            ];

            if( $UploadedFile->storePubliclyAs($FileData['folder'], $FileData['src'], $FileData['disk']) )
            {
                $post->media()->create($FileData);
            }
        }

        if( $request->hasFile('image') && $request->file('image')->isValid() )
        {

            if( $post->images->count() )
            {
                foreach($post->images as $key => $image):
                    switch($image->storage)
                    {
                        case 'local':
                        default:
                            if( !empty($image->disk) && !empty($image->folder) && !empty($image->src) )
                            {
                                if( Storage::disk($image->disk)->exists($image->folder . '/' . $image->src) )
                                {
                                    Storage::disk($image->disk)->delete($image->folder . '/' . $image->src);
                                } 
                            }
                            $image->delete();
                        break;
                    }
                endforeach;

                
            }

            $post->ImageRepositoryDelete($collection = 'poster');
            $post->ImageRepository($files = $request->file('image'), $sizesArray = config('knowledgebase.posters.sizes'), $storageDisk = 'public', $folderStorageDisk = '/uploads/knowledgebase/', $Author = $user->id);
            // $UploadedFile = $request->file('image');

            // do{
            //     $UploadedFileNewName = Str::uuid() . '.' . $UploadedFile->getClientOriginalExtension();
            // }while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

            // $FileData = [
            //     'name' => pathinfo($UploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
            //     'type' => 'image',
            //     'extension' => $UploadedFile->getClientOriginalExtension(),
            //     'user_id' => $user->id,
            //     'src' => $UploadedFileNewName,
            //     'mimes' => $UploadedFile->getMimeType(),
            //     'disk' => 'public',
            //     'size' => $UploadedFile->getSize(),
            //     'folder' => '/uploads/knowledgebase/',
            // ];
            
            // if( $UploadedFile->storePubliclyAs($FileData['folder'], $FileData['src'], $FileData['disk']) )
            // {
            //     $post->media()->create($FileData);
            // }
        }

        $post->owner->notify(new \App\Notifications\KnowledgeBaseApprovedNotification($post, $user));

        return $this->sendResponse([], 'The post has been saved and published');
    }

    public function reject(KbPost $post, Request $request)
    {
        $user = $request->user('api');

        if( !$user )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }
        
        if( !$user->hasRole('admin') )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }

        $validator = Validator::make($request->all(), $rules = [
            'reject' => 'required'
        ], [
            'reject.required' => 'Необходимо указать причину отклонения поста'
        ]);

        if( $validator->fails() )
        {
            return $this->sendError($validator->errors()->toArray(), $errorMessages = [], $code = 200);
        }

        $post->owner->notify(new \App\Notifications\KnowledgeBaseRejectNotification($post, $request->input('reject'), $user));

        if( $post->media->count() )
        {
            foreach($post->media as $key => $media):
                switch($media->storage)
                {
                    case 'local':
                    default:
                        if( !empty($media->disk) && !empty($media->folder) && !empty($media->src) )
                        {
                            if( Storage::disk($media->disk)->exists($media->folder . '/' . $media->src) )
                            {
                                Storage::disk($media->disk)->delete($media->folder . '/' . $media->src);
                            } 
                        }
                        $media->delete();
                    break;
                }
            endforeach;
        }
        
        $post->ImageRepositoryDelete($collection = 'poster');

        if( $post->themes->count() )
        {
            $post->themes()->detach();
        }

        if( $post->formats->count() )
        {
            $post->formats()->detach();
        }

        if( $post->likes->count() )
        {
            $post->likes()->detach();
        }

        if( $post->tags->count() )
        {
            $post->tags()->detach();
        }

        // $post->delete();
        $post->forceDelete();

        return $this->sendResponse([], 'Post rejected and deleted');
    }

    public function destroy(KbPost $post, Request $request)
    {
        $user = $request->user('api');

        if( !$user )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }
        
        if( !$user->hasRole('admin') || $user->id !== $post->owner->id )
        {
            return $this->sendError('Authorization is required', $errorMessages = [], $code = 200);
        }
        
        if( $post->media->count() )
        {
            foreach($post->media as $key => $media):
                switch($media->storage)
                {
                    case 'local':
                    default:
                        if( !empty($media->disk) && !empty($media->folder) && !empty($media->src) )
                        {
                            if( Storage::disk($media->disk)->exists($media->folder . '/' . $media->src) )
                            {
                                Storage::disk($media->disk)->delete($media->folder . '/' . $media->src);
                            } 
                        }
                        $media->delete();
                    break;
                }
            endforeach;

            $post->ImageRepositoryDelete($collection = 'poster');
        }

        if( $post->themes->count() )
        {
            $post->themes()->detach();
        }

        if( $post->formats->count() )
        {
            $post->formats()->detach();
        }

        if( $post->likes->count() )
        {
            $post->likes()->detach();
        }

        if( $post->tags->count() )
        {
            $post->tags()->detach();
        }

        // $post->delete();
        $post->forceDelete();

        return $this->sendResponse([], 'Deletion was successful');
    }

    public function parseString(string $string) 
    {
        if(isset($string)) {
            $array = [];
            $string = explode(',', $string, 3);
            foreach($string as $value) {
                $array[] = trim($value);
            }
            return $array;
        } else {
            return [];
        }
    }
}
