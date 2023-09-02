<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id')->with('parentcategory');
    }

    public static function productFilters()
    {
        //Product Filters
        $productsFilters['fabricArray'] = ['Cotton', 'Polyster', 'Wool'];
        $productsFilters['sleeveArray'] = ['Full Sleeve', 'Half Sleeve', 'Short Sleeve', 'Sleeveless'];
        $productsFilters['patternArray'] = ['Checked', 'Plain', 'Printed', 'Self', 'Solid'];
        $productsFilters['fitArray'] = ['Regular', 'Slim'];
        $productsFilters['occasionArray'] = ['Casual', 'Formal'];

        return $productsFilters;
    }
}
