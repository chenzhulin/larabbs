<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Auth;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmailContract
{
	use HasRoles;
	use MustVerifyEmailTrait;

	use Notifiable{
	
		notify as protected laravelNotify;
	}

	public function notify($instance)
	{
		if($this->id == Auth::id()){
		
			return;
		}


		if(method_exists($instance,'toDatabase')){
		
			$this->increment('notification_count');
		}

		$this->laravelNotify($instance);
	
	}
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
	];

	
    public function topics()
    {

	    return $this->hasMany(Topic::class);
    }


    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }


    public function replies()
    {
    	return $this->hasMany(Reply::class);
    
    }

    public function markAsRead()
    {
    
    	$this->notification_count = 0;

	$this->save();

	$this->unreadNotifications->markAsRead();
    
    }
}
