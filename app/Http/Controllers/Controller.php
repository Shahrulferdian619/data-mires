<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDay($date){
        $dateTime = new Carbon($date);
        return $dateTime->isoFormat('dddd, D MMMM Y');
    }

    public function filterDataInvoice($data){
        $final_data = [];
        $grub_data = [];

        foreach ($data as $key => $value) {
            array_push($grub_data, $value->pelanggan->provinsi);
        }
        $data_distinct = array_unique($grub_data);

        foreach ($data_distinct as $key => $value) {
            $count = 0;
            foreach($grub_data as $item){
                if($value == $item){
                    $count++;
                }
            }
            array_push($final_data, [
                'province' => $value,
                'total' => $count
            ]);
        }

        return $final_data;
    }

}
