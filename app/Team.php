<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Team  extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
    ];

    public function teamUsers()
    {
        return $this->hasMany('App\TeamUser');
    }

}