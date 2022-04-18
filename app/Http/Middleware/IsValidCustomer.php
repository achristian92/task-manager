<?php

namespace App\Http\Middleware;

use App\Repositories\Customers\Repository\ICustomer;
use Closure;
use Illuminate\Http\Request;

class IsValidCustomer
{
    private ICustomer $customerRepo;

    public function __construct(ICustomer $ICustomer)
    {
        $this->customerRepo = $ICustomer;
    }

    public function handle(Request $request, Closure $next)
    {
        $id = $request->segment(3);
        if (is_numeric($id))
            if (! in_array($id,$this->customerRepo->listAllCustomers()->modelKeys()))
                abort(404);

        return $next($request);
    }
}
