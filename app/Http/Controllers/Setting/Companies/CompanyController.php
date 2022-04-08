<?php

namespace App\Http\Controllers\Setting\Companies;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Companies\Company;
use App\Repositories\Companies\Requests\CompanyRequest;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\UploadableTrait;

class CompanyController extends Controller
{
    use UploadableTrait;

    public function edit(Company $company)
    {
        return view('setting.companies.edit',[
            'model' => $company
        ]);
    }

    public function update(CompanyRequest $request, Company $company)
    {
        if ($request->hasFile('attachment_image'))
            $request->merge([
                'src_img' => $this->handleUploadedImage($request->file('attachment_image'))
            ]);

        $company->update($request->all());

        history(UserHistory::UPDATED,"Actualizó la empresa");

        return back()->with('success', 'Registro actualizado');
    }

}
