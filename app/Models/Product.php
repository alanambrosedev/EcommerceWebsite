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

    public static function productFilters(){
        //Product Filters
        $productsFilters['fabricArray'] = array('Cotton','Polyster','Wool');
        $productsFilters['sleeveArray'] = array('Full Sleeve','Half Sleeve','Short Sleeve','Sleeveless');
        $productsFilters['patternArray'] = array('Checked','Plain','Printed','Self','Solid');
        $productsFilters['fitArray'] = array('Regular','Slim');
        $productsFilters['occasionArray'] = array('Casual','Formal');
        return $productsFilters;
    }
}
