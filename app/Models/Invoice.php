<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = [];

    public function section(){

        return $this->belongsTo(Section::class); // مشان نطلع اسم القسم من قائمة الفواتير بدال الايدي تبع القسم لازم نعمل علاقة هون وناخد اسم المتغير تبع العلاقة ونحطو بحلقة الفوريتش
    }
}
