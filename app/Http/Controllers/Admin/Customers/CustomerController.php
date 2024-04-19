<?php


namespace App\Http\Controllers\Admin\Customers;


use App\Exports\Customers\CustomerExport;
use App\Http\Controllers\Controller;
use App\Imports\CustomerImport;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Customers\Customer;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Customers\Requests\CustomerRequest;
use App\Repositories\Files\File;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;


class CustomerController extends Controller
{
    use UploadableTrait, ActivityTransformable, DatesTrait;

    private ICustomer $customerRepo;
    private IActivity $activityRepo;
    private ITag $tagRepo;

    public function __construct(ICustomer $ICustomer, IActivity $IActivity, ITag $ITag)
    {
        $this->customerRepo = $ICustomer;
        $this->activityRepo = $IActivity;
        $this->tagRepo      = $ITag;
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
        if($request->filled('hours')){
            if(count(explode(':', $request->hours)) != 2)
                return back()->with('error',"Formato incorrecto de horas (H:m)");
        }

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

    public function show(Customer $customer)
    {
        $date = $this->getDateFormats(request()->input('yearAndMonth'));

        $activities = $this->activityRepo->listActivityByCustomer($customer->id,$date['from'],$date['to'])
            ->transform(function ($activity) {
                return $this->transformActivityAdvance($activity);
            });

        $qtyUsers = $activities->unique('userId')->count();
        $arrayEstimatedTime = Arr::pluck($activities,'estimatedTime');
        $arrayDuration = Arr::pluck($activities,'realTime');

        return view('admin.customers.show', [
            'customer'   => $customer,
            'qtyUsers'   => $qtyUsers,
            'timeWorked' => sumArraysTime($arrayEstimatedTime),
            'timeReal'   => sumArraysTime($arrayDuration),
            'progress'   => $this->activityRepo->progress($activities),
            'resume'     => $this->activityRepo->resume($activities),
            'tagHistory' => $this->tagRepo->historyHours($this->subMonths($date['from'])),
            'activities' => $activities,
        ]);
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        if($request->filled('hours')){
            if(count(explode(':', $request->hours)) != 2)
                return back()->with('error',"Formato incorrecto de horas (H:m)");
        }

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

    public function export()
    {
        $customers = $this->customerRepo->listAllCustomers();
        return \Excel::download(new CustomerExport($customers),'Lista-Clientes.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|file|mimes:xls,xlsx'
        ]);

        Excel::import(new CustomerImport(companyID()), $request->file('file_upload'));

        return redirect()->route('admin.customers.index')->with('success', 'Información cargada');
    }

    public function deleteFile(Customer $customer, File $file)
    {
        $file->delete();

        return redirect()->route('admin.customers.show',$customer->id)->with('success', 'Documento eliminado');
    }


}
