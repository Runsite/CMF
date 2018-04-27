<?php

namespace Runsite\CMF\Http\Controllers\Api\Ckeditor;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class ImagesController extends BaseAdminController
{
	private $fieldName = 'upload';
	private $imagesPath = 'public/images/ckeditor';
	private $maxWidth;
	private $maxHeight;

	public function __construct()
	{
		$this->imagesPath = $this->imagesPath . '/' . Carbon::now()->format('Y-m-d') . '/' . Carbon::now()->format('H');
		$this->maxWidth = config('runsite.cmf.ckeditor.image.max-width');
		$this->maxHeight = config('runsite.cmf.ckeditor.image.max-height');
	}

	private function generateFilename($extension)
	{
		return str_random(3).time().str_random(3) . '.' . $extension;
	}

	public function upload(Request $request)
	{
		if($request->hasFile($this->fieldName) and $request->file($this->fieldName)->isValid())
		{
			$path = $request->{$this->fieldName}->storeAs($this->imagesPath, $this->generateFilename(
				$request->{$this->fieldName}->extension()
			));

			$image = Image::make(storage_path('app/'.$path));

			if($image->width() > $this->maxWidth)
			{
				$image->resize($this->maxWidth, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save(storage_path('app/'.$path));
			}

			if($image->height() > $this->maxHeight)
			{
				$image->resize(null, $this->maxHeight, function ($constraint) {
					$constraint->aspectRatio();
				})->save(storage_path('app/'.$path));
			}

			$path = str_replace('public/', '', $path);

			echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$request->CKEditorFuncNum."', '/storage/".$path."', '')</script>";
		}
	}
}
