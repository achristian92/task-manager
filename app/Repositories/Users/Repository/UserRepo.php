<?php


namespace App\Repositories\Users\Repository;

use App\Mail\SendEmailNewUser;
use App\Repositories\Customers\Customer;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Supervisors\Supervisor;
use App\Repositories\Users\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepo extends BaseRepository implements IUser
{

    public function model()
    {
        return User::class;
    }

    public function listUsers(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        return $this->model::whereCompanyId(companyID())
            ->whereIsActive(true)
            ->orderBy($orderBy,$sortBy)
            ->get($columns);
    }

    public function listAllUsers(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        return $this->model::with(['roles'])->whereCompanyId(companyID())
            ->orderBy($orderBy,$sortBy)
            ->get($columns);
    }

    public function listAdminAndSupervisor()
    {
        return $this->model::with('roles')
            ->whereCompanyId(companyID())
            ->orderBy('name')
            ->get()
            ->filter(function (User $user){
                return $user->hasAnyRole('Admin','Supervisor');
            })
            ->transform(function (User $user) {
                return [
                    'id' => $user->id,
                    'name' => $user->full_name,
                ];
            });
    }

    public function createUser(array $params): User
    {
        $plainPassword          = add4NumRand($params['name']);
        $params['raw_password'] = $plainPassword;
        $params["password"]     = bcrypt($plainPassword);
        $user                   = $this->model->create($params);

        $this->sendEmailNewCredentials($user);

        return $user;
    }

    public function deleteUser(User $user): bool
    {
        $isDelete = true;
        if ($user->activities()->exists()) {
            $user->update(['status' => false]);
            $isDelete = false;
            history(UserHistory::DISABLE,"Desactivo al usuario $user->full_name");
        } else {
            history(UserHistory::DELETE,"Eliminó al usuario $user->full_name");
            $user->customers()->detach();
            $user->supervise()->detach();
            $user->roles()->detach();
            $user->history()->delete();
            $user->documents()->delete();
            $user->delete();
        }
        return $isDelete;
    }

    public function sendEmailNewCredentials(User $user)
    {
        $setting = $user->company;

        if ($setting->send_credentials) {
            history(UserHistory::NOTIFY,"Envió credenciales del usuario $user->full_name");
            Mail::to($user->email)->send(new SendEmailNewUser($user));
        }
    }

    public function listAssignedUsers(User $user, string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        $direct =  DB::table('supervisor_user')
            ->where('supervisor_id',$user->id)
            ->pluck('user_id');

        $users = $this->model::whereCompanyId($user->company_id)->whereCanBeCheckAll(true)->get()->modelKeys();

        $ids = collect($direct->merge($users))->unique();

        return $this->model::orderBy($orderBy,$sortBy)->whereIn('id',$ids)->get($columns);
    }

    public function listUsersCanSee(array $columns = ['*'])
    {
        $supervisor = Supervisor::find(\Auth::id());

        $usersFreeIds = $this->model::where('can_be_check_all',true)->pluck('id');
        $usersIds = $usersFreeIds->merge($supervisor->users->pluck('id'))->unique()->diff(1);

        return $this->model::whereIn('id',$usersIds)->orderBy('name','asc')->get($columns);
    }

    public function listCustomers(User $user, $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        if (!$user->can_check_all_customers)
            return $user->customers()->orderBy($orderBy,$sortBy)->get($columns);

        return Customer::whereCompanyId($user->company_id)
                ->orderBy($orderBy,$sortBy)
                ->whereIsActive(true)
                ->get($columns);
    }
}
