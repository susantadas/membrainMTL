<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Quarantine extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'quarantines';
    public static function validator($input){
        $rules = array(
            'filename'=>'required|string|max:255',
        );
        return Validator::make($input,$rules);
    }

    public function supplier() {
        return $this->hasMany('App\Supplier','id', 'supplier_id');
    }

    public function campaign() {
        return $this->hasMany('App\Campaign','id', 'campaign_id');
    }    

    public $timestamps = false;
    protected $fillable = array(
        'filename',
        'active'
    );
}
