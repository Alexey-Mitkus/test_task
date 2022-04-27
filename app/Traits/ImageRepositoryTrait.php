<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Libraries\Image\ImageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Media;
use Auth;
use Hash;
use DB;

trait ImageRepositoryTrait
{
    public function ImageRepository($UploadedFile, $sizesArray = [], $storageDisk = 'public', $folderStorageDisk = '/uploads/', $author = null)
    {
    	$ImageRepository = new ImageRepository;

        if( is_string($UploadedFile) )
        {
            return $ImageRepository->createFromString($UploadedFile, ( !empty($sizesArray) ? $sizesArray : ( !empty($this->pictures_array) ? $this->pictures_array : [] ) ), $this, $storageDisk, $folderStorageDisk, ( !empty($author) ? $author : Auth::user()->id ));
        }
        
        if( $UploadedFile instanceof UploadedFile )
        {
            return $ImageRepository->createFromSource($UploadedFile, ( !empty($sizesArray) ? $sizesArray : ( !empty($this->pictures_array) ? $this->pictures_array : [] ) ), $this, $storageDisk, $folderStorageDisk, ( !empty($author) ? $author : Auth::user()->id ));
        }

        abort(503, 'Only strings, FileObjects and UploadedFileObjects can be imported');
    }

    public function ImageRepositoryDestroy()
    {
        $CheckMedia = $this->media()->orderBy('ordering', 'ASC')->where('type', 'original');

        if( $CheckMedia->count() )
        {
            $CheckMedia = $CheckMedia->get();
            foreach($CheckMedia as $mkey => $media):
                if( $media->thumbnails->count() )
                {
                    foreach($media->thumbnails as $tkey => $thumb):
                        if( Storage::disk($thumb->disk)->exists($thumb->folder . $thumb->src) )
                        {
                            Storage::disk($thumb->disk)->delete($thumb->folder . $thumb->src);
                        }

                        if( Storage::disk($thumb->disk)->exists($thumb->folder . $thumb->webp) )
                        {
                            Storage::disk($thumb->disk)->delete($thumb->folder . $thumb->webp);
                        }elseif( Storage::disk($thumb->disk)->exists($thumb->folder . $thumb->src . '.webp') ){
                            Storage::disk($thumb->disk)->delete($thumb->folder . $thumb->src . '.webp');
                        }

                        if( !empty($thumb->retina) )
                        {
                            if( Storage::disk($thumb->retina->disk)->exists($thumb->retina->folder . $thumb->retina->src) )
                            {
                                Storage::disk($thumb->retina->disk)->delete($thumb->retina->folder . $thumb->retina->src);
                            }
                            if( Storage::disk($thumb->retina->disk)->exists($thumb->retina->folder . $thumb->retina->webp) )
                            {
                                Storage::disk($thumb->retina->disk)->delete($thumb->retina->folder . $thumb->retina->webp);
                            }elseif( Storage::disk($thumb->retina->disk)->exists($thumb->retina->folder . $thumb->retina->src . '.webp') ){
                                Storage::disk($thumb->retina->disk)->delete($thumb->retina->folder . $thumb->retina->src . '.webp');
                            }
                        }
                    endforeach;
                }
                if( Storage::disk($media->disk)->exists($media->folder . $media->src) )
                {
                    Storage::disk($media->disk)->delete($media->folder . $media->src);
                }
                if( Storage::disk($media->disk)->exists($media->folder . $media->webp) )
                {
                    Storage::disk($media->disk)->delete($media->folder . $media->webp);
                }elseif( Storage::disk($media->disk)->exists($media->folder . $media->src . '.webp') ){
                    Storage::disk($media->disk)->delete($media->folder . $media->src . '.webp');
                }

                Cache::forget(Str::slug('picturesSource_' . get_class($media->mediatable) . '_' . $media->mediatable->id . ( !empty($media->mediatable->slug) ? '_slug_' . $media->mediatable->slug : ( !empty($media->mediatable->hash) ? '_hash_' . $media->mediatable->hash : '' ) ) . '_pictures_collection_' . $media->collection, '_'));

                Cache::forget(Str::slug('pictures_' . get_class($media->mediatable) . '_' . $media->mediatable->id . ( !empty($media->mediatable->slug) ? '_slug_' . $media->mediatable->slug : ( !empty($media->mediatable->hash) ? '_hash_' . $media->mediatable->hash : '' ) ) . '_pictures_collection_' . $media->collection, '_'));

                $media->delete();
            endforeach;
        }
        return true;
    }

    public function ImageRepositoryDelete($collection = null)
    {
        $CheckMedia = $this->media()->orderBy('ordering', 'ASC')->where('collection', '=', $collection)->where('type', 'original');

		if( $CheckMedia->count() )
		{
            $CheckMedia = $CheckMedia->get();
		    foreach($CheckMedia as $mkey => $media):
                if( $media->thumbnails->count() )
                {
                    foreach($media->thumbnails as $tkey => $thumb):
                        if( Storage::disk($thumb->disk)->exists($thumb->folder . $thumb->src) )
                        {
                            Storage::disk($thumb->disk)->delete($thumb->folder . $thumb->src);
                        }

                        if( Storage::disk($thumb->disk)->exists($thumb->folder . $thumb->webp) )
                        {
                            Storage::disk($thumb->disk)->delete($thumb->folder . $thumb->webp);
                        }elseif( Storage::disk($thumb->disk)->exists($thumb->folder . $thumb->src . '.webp') ){
                            Storage::disk($thumb->disk)->delete($thumb->folder . $thumb->src . '.webp');
                        }

                        if( !empty($thumb->retina) )
                        {
                            if( Storage::disk($thumb->retina->disk)->exists($thumb->retina->folder . $thumb->retina->src) )
                            {
                                Storage::disk($thumb->retina->disk)->delete($thumb->retina->folder . $thumb->retina->src);
                            }
                            if( Storage::disk($thumb->retina->disk)->exists($thumb->retina->folder . $thumb->retina->webp) )
                            {
                                Storage::disk($thumb->retina->disk)->delete($thumb->retina->folder . $thumb->retina->webp);
                            }elseif( Storage::disk($thumb->retina->disk)->exists($thumb->retina->folder . $thumb->retina->src . '.webp') ){
                                Storage::disk($thumb->retina->disk)->delete($thumb->retina->folder . $thumb->retina->src . '.webp');
                            }
                        }
                    endforeach;
                }

		        if( Storage::disk($media->disk)->exists($media->folder . $media->src) )
		        {
		            Storage::disk($media->disk)->delete($media->folder . $media->src);
		        }

                if( Storage::disk($media->disk)->exists($media->folder . $media->webp) )
                {
                    Storage::disk($media->disk)->delete($media->folder . $media->webp);
                }elseif( Storage::disk($media->disk)->exists($media->folder . $media->src . '.webp') ){
                    Storage::disk($media->disk)->delete($media->folder . $media->src . '.webp');
                }

                Cache::forget(Str::slug('picturesSource_' . get_class($media->mediatable) . '_' . $media->mediatable->id . ( !empty($media->mediatable->slug) ? '_slug_' . $media->mediatable->slug : ( !empty($media->mediatable->hash) ? '_hash_' . $media->mediatable->hash : '' ) ) . '_pictures_collection_' . $media->collection, '_'));
                
                Cache::forget(Str::slug('pictures_' . get_class($media->mediatable) . '_' . $media->mediatable->id . ( !empty($media->mediatable->slug) ? '_slug_' . $media->mediatable->slug : ( !empty($media->mediatable->hash) ? '_hash_' . $media->mediatable->hash : '' ) ) . '_pictures_collection_' . $media->collection, '_'));
                
		        $media->delete();
		    endforeach;
		}
		return true;
    }

    public function pictures($Tag = null, $justCheck = false, $collection = null, $typeImg = null, $background = null, $color = null, $fontsize = null)
    {
        $media = Cache::remember(Str::slug('pictures_' . get_class($this) . '_' . $this->id . ( !empty($this->slug) ? '_slug_' . $this->slug : ( !empty($this->hash) ? '_hash_' . $this->hash : '' ) ) . '_pictures_collection_' . $collection, '_'), 3600 * 6, function() use ($collection){
            return $this->media()->orderBy('ordering', 'ASC')->where('type', 'original')->where('collection', $collection)->get();
        });

        // $media = Cache::remember(Str::slug('pictures_' . get_class($this) . '_' . $this->id . ( !empty($this->slug) ? '_slug_' . $this->slug : ( !empty($this->hash) ? '_hash_' . $this->hash : '' ) ) . '_pictures_' . $Tag . '_collection_' . $collection . '_typeImg_' . $typeImg . '_background_' . $background . '_' . $color . '_fontsize_' . $fontsize, '_'), 3600 * 6, function() use ($collection){
        //     return $this->media()->orderBy('ordering', 'ASC')->where('type', 'original')->where('collection', $collection)->get();
        // });

    	// $media = $this->media()->orderBy('ordering', 'ASC')->where('type', 'original')->where('collection', $collection);

    	if( !$media->count() )
    	{
            if( $justCheck )
            {
                return null;
            }

            if( empty($Tag) )
            {
                $lists = ( !empty($this->pictures_array) ? $this->pictures_array : [] );
                $newArray = new \ArrayObject(collect([]), 2);
                foreach($lists as $key => $size):
                    $newArray[$size['width'] . 'x' . $size['height']] = new \ArrayObject(collect([]), 2);
                    switch ($typeImg)
                    {
						case 'toSvg':
                        case 'svg':
                            $newArray[$size['width'] . 'x' . $size['height']]['src'] = \Avatar::create('Z')->setDimension($size['width'], $size['height'])->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14))->toSvg();
                            $newArray[$size['width'] . 'x' . $size['height']]['retina'] = \Avatar::create('Z')->setDimension($size['width'] * 2, $size['height'] * 2)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14) * 2)->toSvg();
                            $newArray[$size['width'] . 'x' . $size['height']]['webp'] = (object) [
                                'src' => \Avatar::create('Z')->setDimension($size['width'], $size['height'])->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14))->toSvg(),
                                'retina' => \Avatar::create('Z')->setDimension($size['width'] * 2, $size['height'] * 2)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14) * 2)->toSvg()
                            ];
                        break;
                        default:
                            $newArray[$size['width'] . 'x' . $size['height']]['src'] = \Avatar::create('Z')->setDimension($size['width'], $size['height'])->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14))->toBase64();
                            $newArray[$size['width'] . 'x' . $size['height']]['retina'] = \Avatar::create('Z')->setDimension($size['width'] * 2, $size['height'] * 2)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14) * 2)->toBase64();
                            $newArray[$size['width'] . 'x' . $size['height']]['webp'] = (object)[
                                'src' => \Avatar::create('Z')->setDimension($size['width'], $size['height'])->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14))->toBase64(),
                                'retina' => \Avatar::create('Z')->setDimension($size['width'] * 2, $size['height'] * 2)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14) * 2)->toBase64()
                            ];
                        break;
                    }
                endforeach;
            }else{
				list($width, $height) = Str::of($Tag)->explode('x');
				$newArray = new \ArrayObject(collect([]), 2);
				switch ($typeImg)
				{
                    case 'toSvg':
                    case 'svg':
                        $newArray['retina'] = \Avatar::create('Z')->setDimension($width * 2, $height * 2)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14) * 2)->toSvg();
                        $newArray['src'] = \Avatar::create('Z')->setDimension($width, $height)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14))->toSvg();
                        $newArray['webp'] = (object) [
                            'retina' => \Avatar::create('Z')->setDimension($width * 2, $height * 2)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14) * 2)->toSvg(),
                            'src' => \Avatar::create('Z')->setDimension($width, $height)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14))->toSvg()
                        ];
                    break;
                    default:
                        $newArray['retina'] = \Avatar::create('Z')->setDimension($width * 2, $height * 2)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14) * 2)->toBase64();
                        $newArray['src'] = \Avatar::create('Z')->setDimension($width, $height)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14))->toBase64();
                        $newArray['webp'] = (object)[
                            'retina' => \Avatar::create('Z')->setDimension($width * 2, $height * 2)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14) * 2)->toBase64(),
                            'src' => \Avatar::create('Z')->setDimension($width, $height)->setBackground(( !empty($background) ? $background : '#947cb0'))->setForeground(( !empty($color) ? $color : '#ffffff'))->setFontSize(( !empty($fontsize) ? $fontsize : 14))->toBase64()
                        ];
					break;
				}
            }
            return $newArray;
    	}

    	if( !empty($Tag) )
    	{
    		if( $media->count() > 1 )
    		{
    			$pictures = $media;
    			$newArray = [];
	            foreach($pictures as $key => $picture):
                    $pick = $picture->thumbnails->get($Tag);
                    $newArray[$key] = (object) [
                        'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->src),
                        'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->src),
                        'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->src),
                        'webp' => (object) [
                            'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->webp),
                            'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->webp),
                            'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->webp),
                        ]
                    ];
	            endforeach;
    		}else{
                $pictures = $media->first();
                $picture = $pictures->thumbnails->get($Tag);
	            $newArray = (object) [
					'original' => Storage::disk($pictures->disk)->url($pictures->folder . $pictures->src),
					'src' => Storage::disk($picture->disk)->url($picture->folder . $picture->src),
					'retina' => Storage::disk($picture->retina->disk)->url($picture->retina->folder . $picture->retina->src),
                    'webp' => (object) [
                        'original' => ( !empty($pictures->webp) ? ( Storage::disk($pictures->disk)->exists($pictures->folder . $pictures->webp) ? Storage::disk($pictures->disk)->url($pictures->folder . $pictures->webp) : Storage::disk($pictures->disk)->url($pictures->folder . $pictures->src) ) : Storage::disk($pictures->disk)->url($pictures->folder . $pictures->src) ),
                        'src' => ( Storage::disk($picture->disk)->exists($picture->folder . $picture->webp) ? Storage::disk($picture->disk)->url($picture->folder . $picture->webp) : Storage::disk($picture->disk)->url($picture->folder . $picture->src)) ,
                        'retina' => ( Storage::disk($picture->retina->disk)->exists($picture->retina->folder . $picture->retina->webp) ? Storage::disk($picture->retina->disk)->url($picture->retina->folder . $picture->retina->webp) : Storage::disk($picture->retina->disk)->url($picture->retina->folder . $picture->retina->src)),
                    ]
	            ];
    		}
    	}else{
    		if( $media->count() > 1 )
    		{
                $pictures = $media;
                $newArray = [];
                foreach($pictures as $key => $picture):
                    foreach ($picture->thumbnails as $tkey => $pick):
                    $newArray[$key][$pick->tag] = (object) [
                        'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->src),
                        'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->src),
                        'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->src),
                        'webp' => (object) [
                            'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->webp),
                            'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->webp),
                            'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->webp),
                        ]
                    ];
                    endforeach;
                endforeach;
    		}else{
                $picture = $media->first();
                $newArray = [];
                foreach ($picture->thumbnails as $tkey => $pick):
                $newArray[$pick->tag] = (object) [
                    'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->src),
                    'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->src),
                    'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->src),
                    'webp' => (object) [
                        'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->webp),
                        'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->webp),
                        'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->webp),
                    ]
                ];
                endforeach;
    		}
    	}
    	return $newArray;
    }

    public function picturesSource($Tag = null, $justCheck = false, $collection = null, $typeImg = null, $background = null, $color = null, $fontsize = null)
    {
        $media = Cache::remember(Str::slug('picturesSource_' . get_class($this) . '_' . $this->id . ( !empty($this->slug) ? '_slug_' . $this->slug : ( !empty($this->hash) ? '_hash_' . $this->hash : '' ) ) . '_pictures_collection_' . $collection, '_'), 3600 * 6, function() use ($collection){
            return $this->media()->orderBy('ordering', 'ASC')->where('type', 'original')->where('collection', $collection)->get();
        });

        // $media = Cache::remember(Str::slug('picturesSource_' . get_class($this) . '_' . $this->id . ( !empty($this->slug) ? '_slug_' . $this->slug : ( !empty($this->hash) ? '_hash_' . $this->hash : '' ) ) . '_pictures_' . $Tag . '_collection_' . $collection . '_typeImg_' . $typeImg . '_background_' . $background . '_' . $color . '_fontsize_' . $fontsize, '_'), 3600 * 6, function() use ($collection){
        //     return $this->media()->orderBy('ordering', 'ASC')->where('type', 'original')->where('collection', $collection)->get();
        // });

        // $media = $this->media()->orderBy('ordering', 'ASC')->where('type', 'original')->where('collection', $collection);

        if( !$media->count() )
        {
            if( $justCheck )
            {
                return null;
            }
            return new \ArrayObject(collect([]), 2);
        }

        if( !empty($Tag) )
        {
            if( $media->count() > 1 || ( !empty($collection) && $collection == 'gallery' ) )
            {
                $pictures = $media;
                $newArray = [];
                foreach($pictures as $key => $picture):
                    $pick = $picture->thumbnails->get($Tag);
                    $newArray[$key] = (object) [
                        'data' => (object)[
                            'original' => $picture,
                            'src' => $pick,
                            'retina' => $pick->retina
                        ],
                        'url' => (object)[
                            'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->src),
                            'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->src),
                            'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->src),
                            'webp' => (object) [
                                'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->webp),
                                'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->webp),
                                'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->webp),
                            ]
                        ],
                    ];
                endforeach;
            }else{
                $pictures = $media->first();
                $picture = $pictures->thumbnails->get($Tag);
                $newArray = (object) [
                    'data' => (object)[
                        'original' => $pictures,
                        'src' => $picture,
                        'retina' => $picture->retina
                    ],
                    'url' => (object) [
                        'original' => Storage::disk($pictures->disk)->url($pictures->folder . $pictures->src),
                        'src' => Storage::disk($picture->disk)->url($picture->folder . $picture->src),
                        'retina' => Storage::disk($picture->retina->disk)->url($picture->retina->folder . $picture->retina->src),
                        'webp' => (object) [
                            'original' => Storage::disk($pictures->disk)->url($pictures->folder . $pictures->webp),
                            'src' => Storage::disk($picture->disk)->url($picture->folder . $picture->webp),
                            'retina' => Storage::disk($picture->retina->disk)->url($picture->retina->folder . $picture->retina->webp)
                        ]
                    ],
                ];
            }
        }else{
            if( $media->count() > 1 || ( !empty($collection) && $collection == 'gallery' ) )
            {
                $pictures = $media->get();
                $newArray = [];
                foreach($pictures as $key => $picture):
                    foreach ($picture->thumbnails as $tkey => $pick):
                    $newArray[$key][$pick->tag] = (object) [
                        'data' => (object)[
                            'original' => $picture,
                            'src' => $pick,
                            'retina' => $pick->retina
                        ],
                        'url' => (object)[
                            'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->src),
                            'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->src),
                            'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->src),
                            'webp' => (object) [
                                'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->webp),
                                'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->webp),
                                'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->webp)
                            ]
                        ],
                    ];
                    endforeach;
                endforeach;
            }else{
                $picture = $media->first();
                $newArray = [];
                foreach ($picture->thumbnails as $tkey => $pick):
                $newArray[$pick->tag] = (object) [
                    'url' => (object)[
                        'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->src),
                        'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->src),
                        'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->src),
                        'webp' => (object) [
                            'original' => Storage::disk($picture->disk)->url($picture->folder . $picture->webp),
                            'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->webp),
                            'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->webp),
                        ]
                    ],
                    'data' => (object)[
                        'original' => $picture,
                        'src' => $pick,
                        'retina' => $pick->retina
                    ],
                ];
                endforeach;
            }
        }
        return $newArray;
    }
}