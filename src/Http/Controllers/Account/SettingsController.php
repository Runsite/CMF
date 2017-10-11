<?php

namespace Runsite\CMF\Http\Controllers\Account;

use Auth;

class SettingsController extends BaseAccountController
{
    

    /**
     * View account settings form
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('runsite::account.settings.edit');
    }

    public function update()
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:rs_users,email,'.Auth::id(),
            'password' => 'sometimes|nullable|string|max:255|confirmed:password_confirmation',
            'image' => 'sometimes|nullable|file|image',
        ]);

        if($data['password'])
        {
            $data['password'] = bcrypt($data['password']);
        }
        else 
        {
            unset($data['password']);
        }

        if(request()->image)
        {
            $filename = $this->generateImageName(request()->file('image')->getClientOriginalExtension());
            request()->file('image')->move($this->originalPath(), $filename);
            $data['image'] = $this->processImage($filename);
        }

        Auth::user()->update($data);

        return redirect()->back();
    }
}
