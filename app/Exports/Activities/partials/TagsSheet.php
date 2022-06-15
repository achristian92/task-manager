<?php

namespace App\Exports\Activities\partials;

use App\Repositories\Tags\Tag;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class TagsSheet  implements FromView, WithTitle, ShouldAutoSize
{

    private array $tags;

    public function __construct(array $tags)
    {
        $this->tags = $tags;
    }

    public function view(): View
    {
        return view('reports.matriz.tags', [
            'tags' => $this->tags
        ]);
    }

    public function title(): string
    {
        return 'Etiquetas';
    }
}


