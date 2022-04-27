<?php

namespace App\Libraries\Image;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\ImageManager;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Media;
use Auth;
use Hash;
use DB;

class ImageRepository{

	protected $Intervention;

    public function __construct(){
        $this->Intervention = new ImageManager([
        	'driver' => 'gd'
        ]);
    }

    public function createFromString($UploadedFile, $sizesArray = [], $toModel = null, $storageDisk = 'public', $folderStorageDisk = '/uploads/', $author = null)
    {
    	if( empty($UploadedFile) )
    	{
    		return null;
    	}

    	if( empty($sizesArray) )
    	{
    		return null;
    	}

    	if( empty($author) )
    	{
    		$author = Auth::user()->id;
    	}

    	$OriginalPictureMake = $this->Intervention->make($UploadedFile);

		do{
			$UploadedFileNewName = Str::uuid() . '.' . \File::extension($UploadedFile);
		}while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

		$data = [
			'name' => \File::name($UploadedFile),
			'extension' => \File::extension($UploadedFile),
			'mimes' => \File::mimeType($UploadedFile),
			'src' => $UploadedFileNewName,
			'parent_id' => null,
			'disk' => $storageDisk,
			'user_id' => $author,
			'type' => 'original',
			'tag' => null,
			'size' => \File::size($UploadedFile),
			'width' => ( !empty( $OriginalPictureMake->width() ) ? $OriginalPictureMake->width() : null ),
			'height' => ( !empty( $OriginalPictureMake->height() ) ? $OriginalPictureMake->height() : null ),
			'collection' => ( !empty($sizesArray[0]['collection']) ? $sizesArray[0]['collection'] : null ),
			'folder' => $folderStorageDisk
		];
    	
		if( $OriginalPictureMake->save(Storage::disk($storageDisk)->path($data['folder'] . $data['src']), ( !empty($sizesArray[0]['quality']) ? $sizesArray[0]['quality'] : 95 ), $data['extension']) )
		{
			// if( $OriginalPictureMake->encode('webp', 100)->save(Storage::disk($storageDisk)->path($data['folder'] . $data['src'] . '.webp'), ( !empty($sizesArray[0]['quality']) ? $sizesArray[0]['quality'] : 95 ), 'webp') )
			// {
			// 	// $data['webp'] = $data['src'] . '.webp';
			// }
			$data['size'] = Storage::disk($storageDisk)->size($data['folder'] . $data['src']);
			$OriginalPicture = $toModel->media()->create($data);
		}

		$thumbnails = [];
		foreach($sizesArray as $skey => $size):

	    	if( empty($size['type']) )
	    	{
	    		$size['type'] = 'fit';
	    	}

			switch($size['type'])
			{
				case 'widen':
					$PictureMake = $this->Intervention->make($UploadedFile)->widen(
						$size['width'],
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
					$PictureRetinaMake = $this->Intervention->make($UploadedFile)->widen(
						$size['width'] * 2,
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
				break;
				case 'heighten':
	               $PictureMake = $this->Intervention->make($UploadedFile)->heighten(
	               		$size['height'],
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
	               $PictureRetinaMake = $this->Intervention->make($UploadedFile)->heighten(
	               		$size['height'] * 2,
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
				break;
				default:
					$PictureMake = $this->Intervention->make($UploadedFile)->fit(
						$size['width'],
						$size['height'],
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
					$PictureRetinaMake = $this->Intervention->make($UploadedFile)->fit(
						$size['width'] * 2,
						$size['height'] * 2,
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
				break;
			}

			do{
				$UploadedFileNewName = Str::uuid() . '.' . \File::extension($UploadedFile);
			}while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

			$normal = [
				'name' => \File::name($UploadedFile),
				'extension' => \File::extension($UploadedFile),
				'mimes' => \File::mimeType($UploadedFile),
				'src' => $UploadedFileNewName,
				// 'parent_id' => $OriginalPicture->id,
				'user_id' => $author,
				'disk' => $storageDisk,
				'type' => 'thumbnail',
				'size' => \File::size($UploadedFile),
				'tag' => $size['width'] . 'x' . $size['height'],
				'width' => ( !empty( $OriginalPictureMake->width() ) ? $OriginalPictureMake->width() : null ),
				'height' => ( !empty( $OriginalPictureMake->height() ) ? $OriginalPictureMake->height() : null ),
				'collection' => ( !empty($size['collection']) ? $size['collection'] : null ),
				'folder' => $folderStorageDisk
			];

			if( $PictureMake->save(Storage::disk($storageDisk)->path($normal['folder'] . $normal['src']), ( !empty($size['quality']) ? $size['quality'] : 95 ), $normal['extension']) )
			{
				// if( $PictureMake->encode('webp', 100)->save(Storage::disk($storageDisk)->path($normal['folder'] . $normal['src'] . '.webp'), ( !empty($size['quality']) ? $size['quality'] : 95 ), 'webp') )
				// {
					$normal['webp'] = pathinfo($normal['src'], PATHINFO_FILENAME) . '.webp';
				// }
				$normal['size'] = Storage::disk($storageDisk)->size($normal['folder'] . $normal['src']);
			}

			$retina = [
				'name' => \File::name($UploadedFile),
				'extension' => \File::extension($UploadedFile),
				'mimes' => \File::mimeType($UploadedFile),
				'src' => \File::name($UploadedFileNewName) . '@2x.' . \File::extension($UploadedFileNewName),
				// 'parent_id' => $Picture->id,
				'user_id' => $author,
				'disk' => $storageDisk,
				'type' => 'thumbnail',
				'size' => \File::size($UploadedFile),
				'tag' => $size['width'] . 'x' . $size['height'] . '@2x',
				'width' => ( !empty( $OriginalPictureMake->width() ) ? $OriginalPictureMake->width() : null ),
				'height' => ( !empty( $OriginalPictureMake->height() ) ? $OriginalPictureMake->height() : null ),
				'collection' => ( !empty($size['collection']) ? $size['collection'] : null ),
				'folder' => $folderStorageDisk
			];

			if( $PictureRetinaMake->save(Storage::disk($storageDisk)->path($retina['folder'] . $retina['src']), ( !empty($size['quality']) ? $size['quality'] : 95 ), $retina['extension']) )
			{
				// if( $PictureRetinaMake->encode('webp', 100)->save(Storage::disk($storageDisk)->path($retina['folder'] . $retina['src'] . '.webp'), ( !empty($size['quality']) ? $size['quality'] : 95 ), 'webp') )
				// {
					$retina['webp'] = pathinfo($retina['src'], PATHINFO_FILENAME) . '.webp';
				// }
				$retina['size'] = Storage::disk($storageDisk)->size($retina['folder'] . $retina['src']);
			}

			$normal['retina'] = $retina;
			$thumbnails[$normal['tag']] = $normal;

		endforeach;

		$PicturesJsonCollect = collect($thumbnails);
		$OriginalPicture->update(['thumbnails' => $PicturesJsonCollect->toJson()]);

		Cache::forget(Str::slug('picturesSource_' . get_class($OriginalPicture->mediatable) . '_' . $OriginalPicture->mediatable->id . ( !empty($OriginalPicture->mediatable->slug) ? '_slug_' . $OriginalPicture->mediatable->slug : ( !empty($OriginalPicture->mediatable->hash) ? '_hash_' . $OriginalPicture->mediatable->hash : '' ) ) . '_pictures_collection_' . $OriginalPicture->collection, '_'));
                
		Cache::forget(Str::slug('pictures_' . get_class($OriginalPicture->mediatable) . '_' . $OriginalPicture->mediatable->id . ( !empty($OriginalPicture->mediatable->slug) ? '_slug_' . $OriginalPicture->mediatable->slug : ( !empty($OriginalPicture->mediatable->hash) ? '_hash_' . $OriginalPicture->mediatable->hash : '' ) ) . '_pictures_collection_' . $OriginalPicture->collection, '_'));
		
		return $OriginalPicture;
    }

    public function createFromSource(UploadedFile $UploadedFile, $sizesArray = [], $toModel = null, $storageDisk = 'public', $folderStorageDisk = '/uploads/', $author = null)
    {
    	if( empty($UploadedFile) )
    	{
    		return null;
    	}

    	if( empty($sizesArray) )
    	{
    		return null;
    	}

    	if( empty($author) )
    	{
    		$author = Auth::user()->id;
    	}

    	$OriginalPictureMake = $this->Intervention->make($UploadedFile->getRealPath());

		do{
			$UploadedFileNewName = Str::uuid() . '.' . $UploadedFile->getClientOriginalExtension();
		}while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

		$data = [
			'name' => pathinfo($UploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
			'extension' => $UploadedFile->getClientOriginalExtension(),
			'mimes' => $UploadedFile->getMimeType(),
			'src' => $UploadedFileNewName,
			'parent_id' => null,
			'user_id' => $author,
			'disk' => $storageDisk,
			'type' => 'original',
			'tag' => null,
			'size' => $UploadedFile->getSize(),
			'width' => ( !empty( $OriginalPictureMake->width() ) ? $OriginalPictureMake->width() : null ),
			'height' => ( !empty( $OriginalPictureMake->height() ) ? $OriginalPictureMake->height() : null ),
			'collection' => ( !empty($sizesArray[0]['collection']) ? $sizesArray[0]['collection'] : null ),
			'folder' => $folderStorageDisk
		];
    	
		if( $OriginalPictureMake->save(Storage::disk($storageDisk)->path($data['folder'] . $data['src']), ( !empty($sizesArray[0]['quality']) ? $sizesArray[0]['quality'] : 95 ), $data['extension']) )
		{
			// if( $OriginalPictureMake->encode('webp', 100)->save(Storage::disk($storageDisk)->path($data['folder'] . $data['src'] . '.webp'), ( !empty($sizesArray[0]['quality']) ? $sizesArray[0]['quality'] : 95 ), 'webp') )
			// {
				// $data['webp'] = $data['src'] . '.webp';
			// }
			$data['size'] = Storage::disk($storageDisk)->size($data['folder'] . $data['src']);
			$OriginalPicture = $toModel->media()->create($data);
		}

		$thumbnails = [];
		
		foreach($sizesArray as $skey => $size):

	    	if( empty($size['type']) )
	    	{
	    		$size['type'] = 'fit';
	    	}

			switch($size['type'])
			{
				case 'widen':
					$PictureMake = $this->Intervention->make($UploadedFile->getRealPath())->widen(
						$size['width'],
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
					$PictureRetinaMake = $this->Intervention->make($UploadedFile->getRealPath())->widen(
						$size['width'] * 2,
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
				break;
				case 'heighten':
	               $PictureMake = $this->Intervention->make($UploadedFile->getRealPath())->heighten(
	               		$size['height'],
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
	               $PictureRetinaMake = $this->Intervention->make($UploadedFile->getRealPath())->heighten(
	               		$size['height'] * 2,
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
				break;
				default:
					$PictureMake = $this->Intervention->make($UploadedFile->getRealPath())->fit(
						$size['width'],
						$size['height'],
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
					$PictureRetinaMake = $this->Intervention->make($UploadedFile->getRealPath())->fit(
						$size['width'] * 2,
						$size['height'] * 2,
						function ($constraint)
						{
							$constraint->upsize();
						}
					);
				break;
			}

			do{
				$UploadedFileNewName = Str::uuid() . '.' . $UploadedFile->getClientOriginalExtension();
			}while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

			$normal = [
				'name' => pathinfo($UploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
				'extension' => $UploadedFile->getClientOriginalExtension(),
				'mimes' => $UploadedFile->getMimeType(),
				'src' => $UploadedFileNewName,
				// 'parent_id' => $OriginalPicture->id,
				'user_id' => $author,
				'disk' => $storageDisk,
				'type' => 'thumbnail',
				'size' => $UploadedFile->getSize(),
				'tag' => $size['width'] . 'x' . $size['height'],
				'width' => ( !empty( $OriginalPictureMake->width() ) ? $OriginalPictureMake->width() : null ),
				'height' => ( !empty( $OriginalPictureMake->height() ) ? $OriginalPictureMake->height() : null ),
				'collection' => ( !empty($size['collection']) ? $size['collection'] : null ),
				'folder' => $folderStorageDisk
			];

			if( $PictureMake->save(Storage::disk($storageDisk)->path($normal['folder'] . $normal['src']), ( !empty($size['quality']) ? $size['quality'] : 95 ), $normal['extension']) )
			{
				// if( $PictureMake->encode('webp', 100)->save(Storage::disk($storageDisk)->path($normal['folder'] . $normal['src'] . '.webp'), ( !empty($size['quality']) ? $size['quality'] : 95 ), 'webp') )
				// {
					$normal['webp'] = pathinfo($normal['src'], PATHINFO_FILENAME) . '.webp';
				// }
				$normal['size'] = Storage::disk($storageDisk)->size($normal['folder'] . $normal['src']);
			}

			$retina = [
				'name' => pathinfo($UploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
				'extension' => $UploadedFile->getClientOriginalExtension(),
				'mimes' => $UploadedFile->getMimeType(),
				'src' => pathinfo($UploadedFileNewName, \PATHINFO_FILENAME) . '@2x.' . $UploadedFile->getClientOriginalExtension(),
				// 'parent_id' => $Picture->id,
				'user_id' => $author,
				'disk' => $storageDisk,
				'type' => 'thumbnail',
				'size' => $UploadedFile->getSize(),
				'tag' => $size['width'] . 'x' . $size['height'] . '@2x',
				'width' => ( !empty( $OriginalPictureMake->width() ) ? $OriginalPictureMake->width() : null ),
				'height' => ( !empty( $OriginalPictureMake->height() ) ? $OriginalPictureMake->height() : null ),
				'collection' => ( !empty($size['collection']) ? $size['collection'] : null ),
				'folder' => $folderStorageDisk
			];

			if( $PictureRetinaMake->save(Storage::disk($storageDisk)->path($retina['folder'] . $retina['src']), ( !empty($size['quality']) ? $size['quality'] : 95 ), $retina['extension']) )
			{
				// if( $PictureRetinaMake->encode('webp', 100)->save(Storage::disk($storageDisk)->path($retina['folder'] . $retina['src'] . '.webp'), ( !empty($size['quality']) ? $size['quality'] : 95 ), 'webp') )
				// {
					$retina['webp'] = pathinfo($retina['src'], PATHINFO_FILENAME) . '.webp';
				// }
				$retina['size'] = Storage::disk($storageDisk)->size($retina['folder'] . $retina['src']);
			}

			$normal['retina'] = $retina;
			$thumbnails[$normal['tag']] = $normal;

		endforeach;
		$PicturesJsonCollect = collect($thumbnails);
		$OriginalPicture->update(['thumbnails' => $PicturesJsonCollect->toJson()]);

		Cache::forget(Str::slug('picturesSource_' . get_class($OriginalPicture->mediatable) . '_' . $OriginalPicture->mediatable->id . ( !empty($OriginalPicture->mediatable->slug) ? '_slug_' . $OriginalPicture->mediatable->slug : ( !empty($OriginalPicture->mediatable->hash) ? '_hash_' . $OriginalPicture->mediatable->hash : '' ) ) . '_pictures_collection_' . $OriginalPicture->collection, '_'));
                
		Cache::forget(Str::slug('pictures_' . get_class($OriginalPicture->mediatable) . '_' . $OriginalPicture->mediatable->id . ( !empty($OriginalPicture->mediatable->slug) ? '_slug_' . $OriginalPicture->mediatable->slug : ( !empty($OriginalPicture->mediatable->hash) ? '_hash_' . $OriginalPicture->mediatable->hash : '' ) ) . '_pictures_collection_' . $OriginalPicture->collection, '_'));

		return $OriginalPicture;
    }

    public function make(UploadedFile $UploadedFile, $SizesArray = [], $storageDisk = 'public', $folderStorageDisk = '/uploads/', $user = null, $RetinaSize = 2)
    {
    	if( empty($UploadedFile) )
    	{
    		return null;
    	}

    	if( empty($SizesArray) )
    	{
    		return null;
    	}

    	if( empty($SizesArray['type']) )
    	{
    		$SizesArray['type'] = 'fit';
    	}

    	if( empty($SizesArray['collection']) )
    	{
    		$SizesArray['collection'] = null;
    	}

    	if( empty($user) )
    	{
    		$user = Auth::user()->id;
    	}

		switch($SizesArray['type'])
		{
			case 'widen':
				$Picture = $this->Intervention->make($UploadedFile->getRealPath())->widen(
					$SizesArray['width'],
					function ($constraint)
					{
						$constraint->upsize();
					}
				);
				$PictureRetina = $this->Intervention->make($UploadedFile->getRealPath())->widen(
					$SizesArray['width'] * $RetinaSize,
					function ($constraint)
					{
						$constraint->upsize();
					}
				);
			break;
			case 'heighten':
               $Picture = $this->Intervention->make($UploadedFile->getRealPath())->heighten(
               		$SizesArray['height'],
					function ($constraint)
					{
						$constraint->upsize();
					}
				);
               $PictureRetina = $this->Intervention->make($UploadedFile->getRealPath())->heighten(
               		$SizesArray['height'] * $RetinaSize,
					function ($constraint)
					{
						$constraint->upsize();
					}
				);
			break;
			default:
				$Picture = $this->Intervention->make($UploadedFile->getRealPath())->fit(
					$SizesArray['width'],
					$SizesArray['height'],
					function ($constraint)
					{
						$constraint->upsize();
					}
				);
				$PictureRetina = $this->Intervention->make($UploadedFile->getRealPath())->fit(
					$SizesArray['width'] * $RetinaSize,
					$SizesArray['height'] * $RetinaSize,
					function ($constraint)
					{
						$constraint->upsize();
					}
				);
			break;
		}
		
		do{
			$UploadedFileNewName = Str::uuid() . '.' . $UploadedFile->getClientOriginalExtension();
		}while( Media::where('src', $UploadedFileNewName)->first() instanceof Media );

    	return [
    		'original' => [
    			'picture' => $Picture,
    			'data' => [
					'name' => pathinfo($UploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
					'extension' => $UploadedFile->getClientOriginalExtension(),
					'src' => $UploadedFileNewName,
					'mimes' => $UploadedFile->getMimeType(),
					'disk' => $storageDisk,
					'user_id' => $user,
					'tag' => $SizesArray['width'] . 'x' . $SizesArray['height'],
					'width' => ( !empty( $Picture->width() ) ? $Picture->width() : null ),
					'height' => ( !empty( $Picture->height() ) ? $Picture->height() : null ),
					'collection' => ( !empty($SizesArray['collection']) ? $SizesArray['collection'] : null ),
					'folder' => $folderStorageDisk
    			]
    		],
    		'retina' => [
    			'picture' => $PictureRetina,
    			'data' => [
					'name' => $UploadedFileNewName,
					'extension' => $UploadedFile->getClientOriginalExtension(),
					'src' => pathinfo($UploadedFileNewName, PATHINFO_FILENAME) . '@' . $RetinaSize . 'x.' . $UploadedFile->getClientOriginalExtension(),
					'mimes' => $UploadedFile->getMimeType(),
					'disk' => $storageDisk,
					'user_id' => $user,
					'tag' => $SizesArray['width'] . 'x' . $SizesArray['height'] . '@' . $RetinaSize . 'x',
					'width' => ( !empty( $PictureRetina->width() ) ? $PictureRetina->width() : null ),
					'height' => ( !empty( $PictureRetina->height() ) ? $PictureRetina->height() : null ),
					'collection' => ( !empty($SizesArray['collection']) ? $SizesArray['collection'] : null ),
					'folder' => $folderStorageDisk
    			]
    		]
    	];
    }

}