<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'address_id' => 'required|integer',
            'cart_id' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => '用户id不能为空',
            'user_id.integer' => '用户id必须是整型',
            'address_id.required' => '地址id不能为空',
            'address_id.integer' => '地址id必须是整型',
            'cart_id.required' => '购物车id不能为空',
            'cart_id.array' => '购物车id必须传递数组形式',
        ];
    }
}
