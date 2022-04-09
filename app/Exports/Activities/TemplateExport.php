<?php

namespace App\Exports\Activities;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\BaseDrawing;

class TemplateExport implements FromCollection,WithEvents,WithDrawings
{

    public function collection()
    {
        return collect([]);
    }

    public function drawings()
    {
        return EventExportImageLogo("B2");
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                /** NOTES */
                $event->sheet->getDelegate()
                    ->setCellValue('C1','CAMPOS OBLIGATORIOS:')
                    ->setCellValue('C2','* Fecha, Cliente, Actividad, Horas y Etiquetas son obligatorias')
                    ->setCellValue('C3','NOTAS:')
                    ->setCellValue('C4','* El id del cliente y etiqueta obtener desde la matriz. Si la actividad tiene priodad alta ingresar "x"')
                    ->setCellValue('C5','* La fecha debe ser d/m/Y y hora debe ser H:m (ej: 05:30)');
                /** Headers */
                $event->sheet->getDelegate()
                    ->setCellValue('A6','PLAN DE TRABAJO')
                    ->mergeCells('A6:G6');
                $event->sheet->getDelegate()
                    ->setCellValue('A7','FECHA')
                    ->setCellValue('B7','ID CLIENTE')
                    ->setCellValue('C7','ACTIVIDAD')
                    ->setCellValue('D7','HORAS')
                    ->setCellValue('E7','PRIORIDAD')
                    ->setCellValue('F7','ID ETIQUETA')
                    ->setCellValue('G7','DESCRIPCIÓN');

                /* Row Height */
                $event->sheet->getDelegate()->getRowDimension(6)->setRowHeight(25);
                $event->sheet->getDelegate()->getRowDimension(7)->setRowHeight(25);
                $largeColumns = ['B','C','G'];
                $smallColumns = ['E','F'];
                foreach ($largeColumns as $colum) {
                    $event->sheet->getDelegate()->getColumnDimension($colum)->setWidth(30);
                }

                foreach ($smallColumns as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(15);
                }
                /**
                 *ADD STYLES
                 */
                $style = EventExportStyles();
                $event->sheet->getDelegate()
                    ->getStyle('C1')
                    ->applyFromArray([
                        'font' => array(
                            'name'      =>  'Calibri',
                            'size'      =>  12,
                            'bold'      =>  true
                        )
                    ]);
                $event->sheet->getDelegate()
                    ->getStyle('C3')
                    ->applyFromArray([
                        'font' => array(
                            'name'      =>  'Calibri',
                            'size'      =>  12,
                            'bold'      =>  true
                        )
                    ]);
                $event->sheet->getDelegate()
                    ->getStyle('A1:G5')
                    ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
                //TITLE
                $event->sheet->getDelegate()
                    ->getStyle('A6:G6')
                    ->applyFromArray($style['TITLE']);
                // HEADER
                $event->sheet->getDelegate()
                    ->getStyle('A7:G7')
                    ->applyFromArray($style['HEADER']);
            }
        ];
    }
}
