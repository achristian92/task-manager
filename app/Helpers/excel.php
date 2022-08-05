<?php

function EventExportImageLogo($coordinates = "B2")
{
    $path = public_path('/img/task-manager-logo.png');
    if(Auth::user()->company->src_logo_local)
        $path = public_path(Auth::user()->company->src_logo_local);

    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath($path);
    $drawing->setHeight(100);
    $drawing->setWidth(150);
    $drawing->setCoordinates($coordinates);

    return $drawing;
}

function EventExportStyles()
{
    return [
        'TITLE'=> [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'font' => [
                'bold' => true,
                'name' => 'Arial',
                'size' => 12,
                'color' => ['argb' => 'ffffff'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '292B57',
                ],
            ],
        ],
        'HEADER' => [
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'font' => [
                'bold' => true,
                'name' => 'Arial',
                'size' => 11,
                'color' => ['argb' => '434448'],
            ],
        ],
        'DATA' => [
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        ]
    ];
}
