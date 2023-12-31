<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Coupon;

class CouponValid implements Rule
{
    public function passes($attribute, $value)
    {
        $coupon = Coupon::where(function ($query) use ($value) {
            $query->where('id', $value)
                  ->orWhere('code', $value);
        })
        ->whereRaw('(used < max AND onhold = false)')
        ->first();

        return $coupon ? true : false;
    }

    public function message()
    {
        return __('translations.invalid_coupon');
    }
}
