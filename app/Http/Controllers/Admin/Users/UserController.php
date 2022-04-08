<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Documents\Repository\IUserDocument;
use App\Repositories\Histories\Repository\IUserHistory;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableTrait;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\Requests\UserRequest;
use App\Repositories\Users\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use UploadableTrait, DatesTrait;

    private $userRepo, $customerRepo;
    private IUserHistory $userHistoryRepo;
    private IUserDocument $userDocumentRepo;

    public function __construct(IUser $IUser, ICustomer $ICustomer, IUserHistory $IUserHistory, IUserDocument $IUserDocument)
    {
        $this->userRepo         = $IUser;
        $this->customerRepo     = $ICustomer;
        $this->userHistoryRepo  = $IUserHistory;
        $this->userDocumentRepo = $IUserDocument;
    }

    public function index()
    {
        return view('admin.users.index',[
            'users' => $this->userRepo->listAllUsers()
        ]);
    }

    public function create()
    {
        return view('admin.users.create',[
            'model'     => new User(),
            'roles'     => Role::all(),
            'customers' => $this->customerRepo->listCustomers(),
            'users'     => $this->userRepo->listAdminAndSupervisor()
        ]);
    }

    public function store(UserRequest $request)
    {
        if ($request->hasFile('attachment_image'))
            $request->merge([
                'src_img' => $this->handleUploadedImage($request->file('attachment_image'))
            ]);

        $user = $this->userRepo->createUser($request->all());

        if ($request->has('roles'))
            $user->roles()->sync($request->input('roles'));
        else
            $user->roles()->detach();

        if (! $request->has('can_be_check_all')) {
            if ($request->has('superviseBy'))
                $user->supervise()->sync($request->input('superviseBy'));
             else
                 $user->supervise()->detach();

        }

        if (! $request->has('can_check_all_customers')) {
            if ($request->has('customers'))
                $user->customers()->sync($request->input('customers'));
            else
                $user->customers()->detach();

        }

        history(UserHistory::STORE,"Creó al usuario $user->full_name",$user);

        return redirect()->route('admin.users.index')->with('success',"Registro creado");

    }

    public function edit(User $user)
    {
        return view('admin.users.edit',[
            'users'            => $this->userRepo->listAdminAndSupervisor(),
            'rolesIDSUser'     => $user->roles->modelKeys(),
            'customerIDSUser'  => $user->customers->modelKeys(),
            'superviseIDSUser' => $user->supervise->modelKeys(),
            'customers'        => $this->customerRepo->listCustomers(),
            'roles'            => Role::all(),
            'model'            => $user
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        if ($request->hasFile('attachment_image'))
            $request->merge([
                'src_img' => $this->handleUploadedImage($request->file('attachment_image'))
            ]);

        $data = $request->all();
        $data['can_be_check_all'] = $request->get('can_be_check_all',false);
        $data['can_check_all_customers'] = $request->get('can_check_all_customers',false);

        $user->update($data);


        if ($request->has('roles'))
            $user->roles()->sync($request->input('roles'));
        else
            $user->roles()->detach();


        if (!$data['can_be_check_all']) {
            if ($request->has('superviseBy'))
                $user->supervise()->sync($request->input('superviseBy'));
            else
                $user->supervise()->detach();

        }

        if (! $data['can_check_all_customers']) {
            if ($request->has('customers'))
                $user->customers()->sync($request->input('customers'));
             else
                 $user->customers()->detach();
        }

        history(UserHistory::UPDATED,"Actualizó al usuario $user->full_name", $user->id);

        return redirect()->route('admin.users.index')->with('success',"Usuario actualizado");
    }

    public function destroy(User $user)
    {
        $message = 'Usuario eliminado';
        $message = $this->userRepo->deleteUser($user) ? $message : 'Usuario desactivado';

        return redirect()->route('admin.users.index')->with('success',$message);
    }

    public function history(Request $request, User $user)
    {
        $date = $this->getDateFormats($request->input('yearAndMonth'));

        return view('admin.users.history',[
            'user'    => $user,
            'history' => $this->userHistoryRepo->listHistoryByUser($user->id,$date['from'],$date['to'])
        ]);
    }

    public function document(User $user)
    {
        return view('admin.users.document',[
            'user'      => $user,
            'documents' => $this->userDocumentRepo->listDocumentsByUser($user->id)
        ]);
    }

}
