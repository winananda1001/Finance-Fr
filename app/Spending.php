<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spending extends Model {
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    // Accessors
    public function getFormattedAmountAttribute() {
        return number_format($this->amount / 100, 2);
    }

    public function getFormattedHappenedOnAttribute() {
        $secondsDifference = strtotime(date('Y-m-d')) - strtotime($this->happened_on);

        return ($secondsDifference / 60 / 60 / 24) . ' days ago';
    }

    // Mutators
    public function setAmountAttribute($value) {
        if (strpos($value, '.')) {
            $mutated = str_replace('.', '', $value);
        } else {
            $mutated = $value * 100;
        }

        $this->attributes['amount'] = $mutated;
    }

    // Relations
    public function tag() {
        return $this->belongsTo('App\Tag');
    }
}
