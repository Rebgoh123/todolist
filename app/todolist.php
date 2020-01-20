<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class todolist extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['deadline', 'format_date'];
    protected $hidden = ['file'];

    public function category(){
        return $this->hasOne('App\category', 'id', 'category_id');
    }

    public function getDeadlineAttribute() {
        $deadline = null;
        $deadlineString = '';
        if($this->due_on){
            $deadline = Carbon::parse($this->due_on);
            $diffDays = Carbon::now()->startOfDay()->diffInDays($deadline, false);

            if($diffDays < 0) {
                $deadlineString = "Overdue";
            }else{
                if($diffDays <= 7 && $diffDays >=2) {
                    $deadlineString = $deadline->format('D');
                }else{
                    switch( $diffDays ) {
                        case 0:
                            $deadlineString = "Today";
                            break;
                        case +1:
                            $deadlineString = "Tomorrow";
                            break;
                        default:
                            $deadlineString = $deadline->format('d-m');
                    }
                }


            }
    }

        return $deadlineString;
    }

    public function getFormatDateAttribute() {
        return Carbon::parse($this->due_on)->format('yy-m-d');
    }
}
