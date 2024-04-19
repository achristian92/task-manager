<?php

namespace App\Http\Controllers\Front\Customers;

use App\Http\Controllers\Controller;
use App\Repositories\Customers\Customer;
use App\Repositories\Files\File;
use App\Repositories\Tools\UploadableDocumentsTrait;
use App\Repositories\Tools\UploadableTrait;
use App\Voucher\Service\Services\Service;
use Illuminate\Http\Request;

class CustomerFileFrontController extends Controller
{
    use UploadableTrait;
    public function store(Request $request, Customer $customer)
    {

        $file = '';
        $name = $request->input('name');

        if ($request->hasFile('attachment_file')) {
            $file = $this->handleUploadedDocument($request->file('attachment_file'));
            if(!$name)
                $name = $this->getFullNameFile($request->file('attachment_file'));
        }

        $customer->files()->create([
            'name'     => $name,
            'src_file' => $file,
            'is_main'  => $request->input('is_main',false)
        ]);

        return response()->json([
            'msg' => 'Documento cargado',
            'route' => route('admin.customers.show',$customer->id)
        ]);
    }

    public function destroy(Request $request, Customer $customer, File $file)
    {
        dd($request->all(),$customer,$file);
    }
}
