<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Multipleapi extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'multipleapi';
    public static function validator($input){
        $rules = array(
            'type'=>'required|string|max:255',
            'country_code'=>'required|string|max:50',
            'url'=>'required|string|max:255',
            'credentials_detaila'=>'required|string|max:255',
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = false;
    protected $fillable = array(
        'id',
        'type',
        'country_code',
        'url',
        'credentials_detaila',
        'status',
        'created_at'
    );
}