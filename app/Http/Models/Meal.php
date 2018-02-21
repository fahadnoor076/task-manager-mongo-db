<?php

namespace App\Http\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

//use Mail;
// load models

class Meal extends Eloquent
{
    protected $collection = 'Users';


    //public function studentFee() {
        //return $this->belongsTo('App\Models\StudentFee');
    //}
}
