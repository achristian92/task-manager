<?php


namespace App\Http\Controllers\Admin\Customers;


use App\Http\Controllers\Controller;
use App\Repositories\Customers\Customer;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Customers\Requests\CustomerRequest;

use App\Repositories\History\UserHistory;
use App\Repositories\Tools\UploadableTrait;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use UploadableTrait;

    private $customerRepo;

    public function __construct(ICustomer $ICustomer)
    {
        $this->customerRepo = $ICustomer;
    }

    public function index()
    {
        return view('admin.customers.index', [
            'customers' => $this->customerRepo->listAllCustomers()
        ]);
    }

    public function create()
    {
        return view('admin.customers.create',[ 'model' => new Customer ]);
    }

    public function store(CustomerRequest $request)
    {
        if ($request->hasFile('attachment_image'))
            $request->merge([
                'src_img' => $this->handleUploadedImage($request->file('attachment_image'))
            ]);

        $customer = $this->customerRepo->createCustomer($request->all());

        history(UserHistory::STORE,"Creó al cliente $customer->name",$customer);

        return redirect()->route('admin.customers.index')->with('success','Nuevo cliente creado.');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit',[ 'model' =>$customer ]);
    }

    public function show(int $customer_id)
    {
        return view('admin.customers.show',compact('customer_id'));
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        if ($request->hasFile('attachment_image'))
            $request->merge([
                'src_img' => $this->handleUploadedImage($request->file('attachment_image'))
            ]);

        $this->customerRepo->updateCustomer($request->all(),$customer->id);

        history(UserHistory::UPDATED,"Actualizó al cliente $customer->name",$customer);

        return redirect()->route('admin.customers.index')->with('success','Cliente actualizado.');
    }

    public function destroy(int $customer_id)
    {
        $this->customerRepo->deleteCustomer($customer_id);

        return back()->with('success', '¡Eliminación exitosa!');
    }


    public function import(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|file|mimes:xls,xlsx'
        ]);

        Excel::import(new CustomerImport(), $request->file('file_upload'));

        return redirect()->route('admin.customers.index')->with('success', 'Información cargada');
    }
    public function export()
    {
        $customer = Customer::orderBy('name','asc')->get(['name','ruc','address'])->toArray();
        return Excel::download(new CustomerExport($customer), 'LISTA-CLIENTES.xlsx');
    }

}
