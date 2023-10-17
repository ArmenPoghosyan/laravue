<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class Multimedia extends Model
{
	const TYPE_PHOTO		= 'photo';
	const TYPE_VIDEO		= 'video';
	const TYPE_DOCUMENT		= 'document';
	const TYPE_LINK			= 'link';

	const TYPES				= [
		self::TYPE_DOCUMENT,
		self::TYPE_PHOTO,
		self::TYPE_VIDEO,
		self::TYPE_LINK
	];

	const PHOTO_SIZES		= [
		'xxl_'	=> 2000,
		'xl_'	=> 1200,
		'l_'	=> 800,
		'm_'	=> 400,
		's_'	=> 200,
	];

	protected $fillable = [
		'parentable_type',
		'parentable_id',
		'path',
		'type',
		'mime',
		'size',
		'order'
	];

	protected $hidden = [
		'parentable_type',
		'parentable_id',
	];

	public static function booted()
	{
		static::deleting(function (Multimedia $model) {
			// Delete all files related to the multimedia
			switch ($model->type) {
				case self::TYPE_DOCUMENT:
				case self::TYPE_PHOTO:
				case self::TYPE_VIDEO:
					logger('Deleting file: ' . $model->get_path());
					@unlink($model->get_path());

					if ($model->type == self::TYPE_PHOTO) {
						foreach (self::PHOTO_SIZES as $prefix => $size) {
							@unlink($model->get_path($prefix));
						}
					}
					break;
			}
		});
	}

	/** @return MorphTo  */
	public function parentable()
	{
		return $this->morphTo();
	}

	/**
	 * Get the path of the multimedia file / link
	 *
	 * @param mixed $size
	 * @return string
	 */
	public function get_path($prefix = null)
	{
		$path = $this->attributes['path'];

		if ($this->type == static::TYPE_LINK) {
			return $path;
		} else {
			return storage_path('app/public/media/' . $prefix . $this->attributes['path']);
		}
	}

	/**
	 * Handle incoming file
	 *
	 * @param UploadedFile $file
	 * @param mixed $type
	 * @return Multimedia
	 */
	public static function handle_file(UploadedFile $file, $type)
	{
		$file->storePublicly('media', 'public');

		$multimedia = new Multimedia([
			'type'	=> strtolower($type),
			'path'	=> $file->hashName(),
			'mime'	=> $file->getMimeType(),
			'size'	=> $file->getSize(),
		]);

		if ($type == Multimedia::TYPE_PHOTO) {
			$file_name		= $file->hashName();
			$original_file	= storage_path('app/public/media/' . $file_name);

			$image = Image::make($original_file);

			foreach (Multimedia::PHOTO_SIZES as $prefix => $size) {
				$image->resize($size, null, function ($constraint) {
					$constraint->aspectRatio();
				});

				$image->save(storage_path('app/public/media/' . $prefix . $file_name));
			}
		}

		return $multimedia;
	}
}
