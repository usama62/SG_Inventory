<?php

namespace App\Http\Requests\Api\PurchaseOrder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $company = company();
        $convertedId = Hashids::decode($this->route('purchase_order'));
        $id = $convertedId[0];

        $rules = [
            'order_date' => 'required',
            'product_items' => 'required',
            'supplier_company_name' => 'required',
            'supplier_address' => 'required',
            'supplier_phone' => 'required',
            'ship_to_name' => 'required',
            'ship_to_company_name' => 'required',
            'ship_to_address' => 'required',
            'ship_to_phone' => 'required',
        ];

        if ($this->has('invoice_number') && $this->invoice_number != '') {
            $rules['invoice_number'] = [
                'required',
                Rule::unique('orders', 'invoice_number')->where(function ($query) use ($company, $id) {
                    return $query->where('company_id', $company->id)
                        ->where('id', '!=', $id);
                }),
            ];
        } else {
            $rules['invoice_number'] = 'required';
        }

        return $rules;
    }
}
