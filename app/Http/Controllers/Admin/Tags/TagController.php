<?php

namespace App\Http\Controllers\Admin\Tags;

use App\Http\Controllers\Controller;
use App\Repositories\Tags\Repository\ITag;

class TagController extends Controller
{
    private $tagRepo;

    public function __construct(ITag $ITag)
    {
        $this->tagRepo = $ITag;
    }

    public function __invoke()
    {
        return view('admin.tags.index', [
            'tags' => $this->tagRepo->listAllTags()
        ]);
    }

}
