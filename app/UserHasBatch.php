<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Batch;

class UserHasBatch extends Model
{
    protected $fillable = [
        'created_by','updated_by','created_at_np','user_id','batch_id'
    ];

    public static function getBatch(Request $request){
        return Batch::all();
    }

    public function Batch(){
        return $this->belongsTo(Batch::class,'batch_id','id');
    }
    
    public function getUser()
    {
    	return $this->belongsTo('App\User','created_by','id');
    }
    public function getStudentBatch(){
    	return $this->belongsTo('App\Student','user_id','user_id');
    } 
    public function getStudentUserBatch()
    {
        return $this->hasOne('App\User','id','user_id');
    }
    public function getTeacherBatch(){
        return $this->belongsTo('App\Teacher','user_id','user_id');
    }

    public function getTeacherUserBatch()
    {
        return $this->hasOne('App\User','id','user_id');
    }
    public function getTeacherUserBatchShift()
    {
        return $this->belongsTo('App\Teacher_has_shift','user_id','user_id');
    }
    public function getTeacherUserBatchPeriod()
    {
      return $this->hasMany('App\Teacher_has_period','teacher_id','user_id');
    }

}
