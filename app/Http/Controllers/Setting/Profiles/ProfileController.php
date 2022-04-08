<?php

namespace App\Http\Controllers\Setting\Profiles;

use App\Http\Controllers\Controller;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\UploadableTrait;
use App\Repositories\Users\Requests\UserRequest;
use App\Repositories\Users\User;

class ProfileController extends Controller
{
    use UploadableTrait;

    public function edit(User $profile)
    {
        return view('setting.profiles.edit',[
            'model' => $profile
        ]);
    }

    public function update(UserRequest $request, User $profile)
    {

        if ($request->filled('password'))
            $request->merge([
                'password' => bcrypt($request->password),
                'raw_password' => $request->password
            ]);
        else
            $request->merge(['password' => $profile->password ]);

        if ($request->hasFile('attachment_image'))
            $request->merge([
                'src_img' => $this->handleUploadedImage($request->file('attachment_image'))
            ]);

        $profile->update($request->all());

        history(UserHistory::UPDATED,"Actualizó la cuenta");

        return back()->with('success', 'Registro actualizado');

    }

}
