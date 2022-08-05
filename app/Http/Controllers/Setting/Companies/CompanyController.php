<?php

namespace App\Http\Controllers\Setting\Companies;

use App\Http\Controllers\Controller;
use App\Repositories\Companies\Company;
use App\Repositories\Companies\Requests\CompanyRequest;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\UploadableTrait;
use Illuminate\Support\Str;
use Storage;

class CompanyController extends Controller
{
    CONST TEMP_FOLDER_LOGO = 'img/logo/';

    use UploadableTrait;

    public function edit(Company $company)
    {
        if ($company->id !== \Auth::user()->company_id)
            abort(404);

        return view('setting.companies.edit',[
            'model' => $company
        ]);
    }

    public function update(CompanyRequest $request, Company $company)
    {
        if ($request->hasFile('attachment_image')) {
            $file = $request->file('attachment_image');
            $name = Str::of(Str::slug($file->getClientOriginalName()))->basename($file->clientExtension());
            $filename = $name.'-'.Str::random(4).'.'.$file->clientExtension();
            $filePath = self::TEMP_FOLDER_LOGO. $filename;
            Storage::disk('public')->put($filePath,file_get_contents($file));

            $request->merge([
                'src_logo_local' => $filePath,
                'src_img' => $this->handleUploadedImage($request->file('attachment_image'))
            ]);
        }


        $company->update($request->all());

        history(UserHistory::UPDATED,"Actualizó la empresa");

        return back()->with('success', 'Registro actualizado');
    }

}
