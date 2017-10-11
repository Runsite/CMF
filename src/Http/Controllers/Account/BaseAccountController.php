<?php

namespace Runsite\CMF\Http\Controllers\Account;

use Runsite\CMF\Http\Controllers\BaseAdminController;
use Auth;
use Intervention\Image\Facades\Image;

class BaseAccountController extends BaseAdminController
{
    protected function originalPath($filename=null)
    {
        return public_path(config('runsite.cmf.account.images.path') . Auth::id() . '/' . $filename);
    }

    protected function thumbPath($filename=null)
    {
        return public_path(config('runsite.cmf.account.images.path') . Auth::id() . '/' . config('runsite.cmf.account.images.thumbs_folder_name') . '/' . $filename);
    }

    protected function imagesWidth()
    {
        return config('runsite.cmf.account.images.width');
    }

    protected function generateImageName($extension=null)
    {
        return str_random(5) . time() . str_random(10) . ($extension ? '.'.$extension : null);
    }

    protected function getExtension($filename)
    {
        $parts = explode('.', $filename);
        return end($parts);
    }

    protected function processImage($filename, $width=null, $height=null, $x=null, $y=null)
    {
        $user = Auth::user();
        $image = Image::make($this->originalPath($filename))->orientate();

        if($user->image)
        {
            if(file_exists($this->originalPath($user->image)))
            {
                unlink($this->originalPath($user->image));
            }

            if(file_exists($this->thumbPath($user->image)))
            {
                unlink($this->thumbPath($user->image));
            }
        }
        

        if($image)
        {
            if(!$width)
            {
                $width = $this->imagesWidth();
            }

            $new_filename = $this->generateImageName($this->getExtension($filename));

            if($height and $x and $y)
            {
                $image->save($this->originalPath($new_filename));
                $image->crop($width, $height, $x, $y);
                $image->fit($this->imagesWidth());
            }
            else
            {
                if(file_exists($this->originalPath($filename)))
                {
                    unlink($this->originalPath($filename));
                }
                $image->save($this->originalPath($new_filename));
                $image->fit($width, $width);

            }

            if(!is_dir($this->thumbPath()))
            {
                mkdir($this->thumbPath());
            }

            $image->save($this->thumbPath($new_filename));

            return $new_filename;
        }
    }
}
