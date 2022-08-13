<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function discount($total)
    {
        if (!$this->checkDate() || !$this->checkUsedTimes()){
            return 0;
        }
        return $this->checkGreaterThan($total) ? $this->doProcess($total) : 0;
    }

    protected function checkDate()
    {
        return $this->expire_date != '' ? (Carbon::now()->between($this->start_date, $this->expire_date, true)) ? true : false : true;
    }

    protected function checkUsedTimes()
    {
        return $this->use_times != '' ? ( $this->use_times > $this->used_times ) ? true : false : true;
    }

    protected function checkGreaterThan($total)
    {
        return $this->greater_than != '' ? ($this->greater_than <= $total) ? true : false : true;
    }

    protected function doProcess($total)
    {
        switch ($this->type) {
            case 'fixed':
                return $this->value;
            case 'percentage':
                return ($this->value / 100) * $total;
            default:
                return 0;
        }
    }

}
