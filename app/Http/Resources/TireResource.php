<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TireResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /*return parent::toArray($request);*/

        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand->name,
            'price' => $this->price_opt,
            'quantity' => $this->quantity,
            'model' => $this->model,
            'width' => $this->twidth,
            'profile' => $this->tprofile,
            'diameter' => $this->tdiameter,
            'load_index' => $this->load_index,
            'speed_index' => $this->speed_index,
            'season' => $this->tseason,
            'spike' => $this->spike,
            'cae' => $this->tcae,
            'image' => url('images/' . $this->image . '.jpg'),
        ];
    }
}
