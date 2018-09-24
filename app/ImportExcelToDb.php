<?php

namespace App;

use Excel;
use Illuminate\Support\Str;

class ImportExcelToDb
{
    public $once = false;
    public $onceTruck = false;

    public function import($filename) {
            Excel::selectSheetsByIndex(0)->load($filename, function($reader) {
            $brands = new Brand();
            //$reader->ignoreEmpty();
            
            $reader = $reader->each(function($sheet){
            	/*$sheet['dia'] = number_format($sheet->dia, 1, '.', '');
            	if(str_contains($sheet['polnoe_naimenovanie'], '9999999999999')) {
            		$str = preg_replace('/([0-9]{2}|[0-9]{3})\.([0-9])9999999999999/', $sheet['dia'], $sheet['polnoe_naimenovanie']);
                    $sheet['polnoe_naimenovanie'] = $str;
            	}
            	if(str_contains($sheet['cai'], '9999999999999')) {
            		$str = preg_replace_callback('/([0-9])9999999999999/', function($matches) {
    					return $matches[1] + 1;
					}, $sheet['cai']);
					$sheet['cai'] = $str;
            	}*/
            });

            $result = $reader->all();
            foreach($result as $item) {
                $brand_name = Str::ucfirst(Str::lower(trim($item->brend)));
                $brands_check = $brands->where('name' , $brand_name);

                if(isset($item['brend']) and $item['brend'] != null and !isset($item['tip_diska'])) {
                    if (!$brands_check->first()) { // if brand's name doesn't exist in DB then add new brand name
                        $new_brand = $brands->create([
                            'name' => $brand_name,
                            'image' => ''
                        ]);
                    } else {
                        $new_brand = $brands_check->first();
                    }

                    if(str_contains($item['shirina'], ',')) {
                        $item['shirina'] = str_replace(',', '.', $item['shirina']);
                    }

                    $item['opt'] = str_replace(' ', '', $item['opt']);
                    $item['roznitsa'] = str_replace(' ', '', $item['roznitsa']);

                    $this->addDataTire(
                        $new_brand->id, $item['polnoe_naimenovanie'], $item['imya_fayla'], '',
                        $item['shirina'], $item['profil'], $item['diametr'], $item['indeks_narguzki'], $item['indeks_skorosti'],
                        $item['sezonnost'], $item['model'], $item['cai'], $item['ship'], '', $item['opt'], $item['roznitsa'],
                        $item['obshch._kol_vo'], $item['tip_shiny']
                    );

                    //truncate data
                    $t_tire = ($item['tip_shiny'] == 'Легковая' ? new Tire() : new Truck());

                    if ($t_tire instanceof Tire) {
                        if ($this->once == false) {
                            $t_tire->truncate();
                            $this->once = true;
                        }
                    } elseif ($t_tire instanceof Truck) {
                        if ($this->onceTruck == false) {
                            $t_tire->truncate();
                            $this->onceTruck = true;
                        }
                    }
                } elseif(isset($item['brend']) and $item['brend'] != null and isset($item['tip_diska'])) {
                    if (!$brands_check->first()) { // if brand's name doesn't exist in DB then add new brand name
                        $new_brand = $brands->create([
                            'name' => $brand_name,
                            'image' => ''
                        ]);
                    } else {
                        $new_brand = $brands_check->first();
                    }

                    if (str_contains($item['shirina_oboda'], ',')) {
                        $item['shirina_oboda'] = str_replace(',', '.', $item['shirina_oboda']);
                    }

                    if (str_contains($item['dia'], ',')) {
                        $item['dia'] = str_replace(',', '.', $item['dia']);
                    }

                    $this->addDataWheel(
                        $new_brand->id, $item['polnoe_naimenovanie'], $item['imya_fayla'], '', $item['model'],
                        $item['shirina_oboda'], $item['posadochnyy_diametr'], $item['kol_vo_otverstviy'], $item['psd'], $item['vylet_et'],
                        $item['dia'], $item['cai'], $item['tip_diska'], $item['opt'], $item['tsena_v_nalichii'], $item['obshch._kol.']
                    );

                    //truncate data
                    if ($this->once == false) {
                        $tire = new Wheel();
                        $tire->truncate();
                        $this->once = true;
                    }
                }
            }
        }, 'UTF-8');
    }

    /**
     * Create new record in DB with data from excel
     *
     * @param $brand_id
     * @param $name
     * @param $image
     * @param $code
     * @param $twidth
     * @param $tprofile
     * @param $tdiameter
     * @param $load_index
     * @param $speed_index
     * @param $tseason
     * @param $model
     * @param $tcae
     * @param $spike
     * @param $model_class
     * @param $price_opt
     * @param $price_roz
     * @param $quantity
     * @param $t_type | type of tires: Легковая | Грузовая | Спец
     *
     * return void
     */
    private function addDataTire($brand_id, $name, $image, $code, $twidth, $tprofile, $tdiameter, $load_index, $speed_index,
                            $tseason, $model, $tcae, $spike, $model_class, $price_opt, $price_roz, $quantity, $t_type) {

        $t_tire = ($t_type == 'Легковая' ? new Tire() : new Truck());

            $t_tire->create([
                'brand_id' => $brand_id,
                'name' => $name,
                'image' => $image,
                'code' => $code,
                'twidth' => isset($twidth) ? $twidth : 0,
                'tprofile' => isset($tprofile) ? $tprofile : 0,
                'tdiameter' => isset($tdiameter) ? $tdiameter : 0,
                'load_index' => $load_index,
                'speed_index' => $speed_index,
                'tseason' => $tseason,
                'model' => $model,
                'tcae' => trim($tcae),
                'spike' => ($spike == 'Да') ? 1 : 0,
                'model_class' => $model_class,
                'price_opt' => ($price_opt != 0) ? intval($price_opt) : 0,
                'price_roz' => ($price_roz != 0) ? intval($price_roz) : 0,
                'quantity' => ($quantity == null) ? 0 : $quantity,
            ]);
    }

    private function addDataWheel($brand_id, $name, $image, $code, $model, $twidth, $tdiameter, $hole_count, $pcd, $et, $dia, $tcae,
                                    $type, $price_opt, $price_roz, $quantity) {

        $wheels = new Wheel();

            $wheels->create([
                'brand_id' => $brand_id,
                'name' => $name,
                'image' => $image,
                'code' => $code,
                'twidth' => isset($twidth) ? $twidth : 0,
                'tdiameter' => isset($tdiameter) ? $tdiameter : 0,
                'hole_count' => $hole_count,
                'pcd' => $pcd,
                'et' => $et,
                'model' => isset($model) ? $model : '',
                'et' => $et,
                'dia' => floatval($dia),
                'tcae' => trim($tcae),
                'type' => $type,
                'price_opt' => ($price_opt != 0) ? $price_opt : 0,
                'price_roz' => ($price_roz != 0) ? $price_roz : 0,
                'quantity' => ($quantity == null) ? 0 : $quantity,
            ]);
    }
}
