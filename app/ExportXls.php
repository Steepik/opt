<?php

namespace App;

use Excel;

class ExportXls
{
    /**
     * Export all products to excel from DB which depend on product type
     *
     * @param string $type
     * @return bool
     */

    final public static function export($type = '')
    {
        if($type == 'tire') {
            Excel::create('Tires', function($excel){
                $excel->sheet('Tires', function($sheet){
                    $sheet->setAutoSize(true);
                    $sheet->rows(array(
                        array(
                            'Бренд',
                            'Наименование',
                            'Ширина',
                            'Профиль',
                            'Диаметр',
                            'Индекс нагрузки',
                            'Индекс скорости',
                            'Сезонность',
                            'Модель',
                            'CAE',
                            'Шипы',
                            'Цена оптовая',
                            'Цена розничная',
                            'Остаток',
                        ),
                    ));
                    $tires = Tire::where('quantity', '>', 0)->get();
                    foreach($tires as $tire) {
                        $sheet->rows(array(
                            array(
                                $tire->brand->name,
                                $tire->name,
                                $tire->twidth,
                                $tire->tprofile,
                                $tire->tdiameter,
                                $tire->load_index,
                                $tire->speed_index,
                                $tire->tseason,
                                $tire->model,
                                $tire->tcae,
                                $tire->spike == 0 ? 'Нет' : 'Да',
                                $tire->price_opt,
                                $tire->price_roz,
                                $tire->quantity > 8 ? '> 8' : $tire->quantity,
                            ),
                        ));
                    }
                });
            })->download('xls');
        } elseif($type == 'wheel') {
            Excel::create('Wheels', function($excel){
                $excel->sheet('Wheels', function($sheet){
                    $sheet->setAutoSize(true);
                    $sheet->rows(array(
                        array(
                            'Бренд',
                            'Наименование',
                            'Ширина',
                            'Диаметр',
                            'Модель',
                            'Кол-во отверстий',
                            'PCD',
                            'ET',
                            'DIA',
                            'CAE',
                            'Тип',
                            'Цена оптовая',
                            'Цена розничная',
                            'Остаток',
                        ),
                    ));
                    $wheels = Wheel::where('quantity', '>', 0)->get();
                    foreach($wheels as $wheel) {
                        $sheet->rows(array(
                            array(
                                $wheel->brand->name,
                                $wheel->name,
                                $wheel->twidth,
                                $wheel->tdiameter,
                                $wheel->model,
                                $wheel->hole_count,
                                $wheel->pcd,
                                $wheel->et,
                                $wheel->dia,
                                $wheel->tcae,
                                $wheel->type,
                                $wheel->price_opt,
                                $wheel->price_roz,
                                $wheel->quantity > 8 ? '> 8' : $wheel->quantity,
                            ),
                        ));
                    }
                });
            })->download('xls');
        } else {
            return false;
        }
    }
}
