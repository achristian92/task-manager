<?php

namespace App\Http\Controllers\Front\Tags;

use App\Http\Controllers\Controller;
use App\Repositories\History\UserHistory;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Tags\Requests\TagRequest;
use App\Repositories\Tags\Tag;

class TagController extends Controller
{
    private ITag $tagRepo;

    public function __construct(ITag $ITag)
   {
       $this->tagRepo = $ITag;
   }

    public function store(TagRequest $request)
    {
        $tag = Tag::create($request->all());

        history(UserHistory::STORE,"Creó la etiqueta $tag->name",$tag);

        return response()->json([
            'route' => route('admin.tags.index'),
            'msg' => 'Registro creado'
        ],201);
    }

    public function edit(Tag $tag)
    {
        return response()->json(['tag' => $tag]);
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update($request->only(['name','color']));

        history(UserHistory::UPDATED,"Actualizó la etiqueta $tag->name",$tag);

        return response()->json([
            'route' => route('admin.tags.index'),
            'msg' => 'Registro actualizado'
        ],201);
    }

    public function destroy($id)
    {
        $message = "Etiqueta eliminada";
        $message = $this->tagRepo->deleteTag($id) ? $message : 'Etiqueta desactivada';

        return response()->json([
            'msg'   => $message,
            'route' => route('admin.tags.index'),
        ]);
    }

}
