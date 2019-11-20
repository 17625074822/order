<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'number'=>$this->number,
            'user_id'=>$this->user_id,
            'product_fee'=>$this->product_fee,
            'express_fee'=>$this->express_fee,
            'total_fee'=>$this->total_fee,
            'status'=>$this->status,
            'delivery_status'=>$this->delivery_status,
            'payment_status'=>$this->payment_status,
        ];
    }
}
