<?php

namespace App\Http\Requests\Api\PurchaseOrder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $company = company();

        $rules = [
            'product_items' => 'required',
            'order_date' => 'required',
            'supplier_company_name' => 'required',
            'supplier_address' => 'required',
            'supplier_phone' => 'required',
            'ship_to_name' => 'required',
            'ship_to_company_name' => 'required',
            'ship_to_address' => 'required',
            'ship_to_phone' => 'required',
        ];

        if ($this->invoice_number && $this->invoice_number != '') {
            $rules['invoice_number'] = [
                'required',
                Rule::unique('orders', 'invoice_number')->where(function ($query) use ($company) {
                    return $query->where('company_id', $company->id);
                }),
            ];
        }

        return $rules;
    }
}
