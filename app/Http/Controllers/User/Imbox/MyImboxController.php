<?php

namespace App\Http\Controllers\User\Imbox;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Users\Repository\IUser;
use Illuminate\Support\Facades\Auth;

class MyImboxController extends Controller
{
    use ActivityTransformable, ActivityTrait, DatesTrait;

    private $userRepo, $activityRepo, $tagRepo;

    public function __construct(IUser $IUser, ITag $ITag, IActivity $IActivity)
    {
        $this->userRepo = $IUser;
        $this->tagRepo = $ITag;
        $this->activityRepo = $IActivity;
    }

    public function __invoke()
    {
        $user = \Auth::user();
        $request = [
            'month' => request()->input('yearAndMonth',now()),
            'view' => request()->input('typeTab','today'),
        ];

        $dateFormat = $this->getDateFormats($request['month']);


        $transListToGeneral = $this->activityRepo->listActivityByUser($user->id,$dateFormat['from'],$dateFormat['to'])
            ->transform(function ($activity) {
            return $this->transformActivityAdvance($activity);
        });


        $filterByTab      = $this->applyFilterByTypeTab($transListToGeneral,$request['view']);
        $filterActPartial = $transListToGeneral->where('status',Activity::TYPE_PARTIAL);

        return view('user.imbox.index', [
            'myImbox'   => $user->id,
            'customers' => $this->userRepo->listCustomers($user),
            'tags'      => $this->tagRepo->listTags(),
            'activities'=> collect($filterByTab)->merge(collect($filterActPartial))->unique('id'),
            'title'     => $this->title_view_es($request['view']),
            'tab'       => $request['view']

        ]);
    }

    private function title_view_es($view = 'today'): string
    {
        if ($view === 'proximate')
            $title_es = 'Actividades próximas';
        else if ($view === 'overdue')
            $title_es = 'Actividades vencidas';
        else
            $title_es = 'Actividades de hoy';

        return $title_es;
    }
}
