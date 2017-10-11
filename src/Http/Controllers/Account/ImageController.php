<?php

namespace Runsite\CMF\Http\Controllers\Account;

use Auth;

class ImageController extends BaseAccountController
{
    /**
     * View account settings form
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('runsite::account.image.edit');
    }

    public function crop()
    {
        request()->validate([
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
        ]);

        $user = Auth::user();
        $user->image = $this->processImage($user->image, (int) request()->width, (int) request()->height, (int) request()->x, (int) request()->y);
        $user->save();

        return redirect()->route('admin.account.settings.edit');
    }
}
