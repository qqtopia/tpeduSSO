<?php

namespace App;

use Log;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Providers\LdapServiceProvider;;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'idno', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_admin',
    ];
    
    protected $appends = [
	'ldap',
    ];
    
    protected $casts = [
	'is_admin' => 'boolean',
    ];

    public function getLdapAttribute()
    {
	$openldap = new LdapServiceProvider();
	$entry = $openldap->getUserEntry($this->attributes['idno']);
	$data = $openldap->getUserData($entry);
	if (array_key_exists('entryUUID', $data))
	    $this->attributes['uuid'] = $data['entryUUID'];
	if (array_key_exists('mail', $data))
	    $this->attributes['email'] = $data['mail'];
	if (array_key_exists('mobile', $data))
	    $this->attributes['mobile'] = $data['mobile'];
	if (array_key_exists('displayName', $data))
	    $this->attributes['name'] = $data['displayName'];
	if (array_key_exists('birthDate', $data))
	    $data['birthDate'] = substr($data['birthDate'],0,8);
	$sch_entry = $openldap->getOrgEntry($data['o']);
	$admins = $openldap->getOrgData($sch_entry, "tpAdministrator");
	$data['is_schoolAdmin'] = false;
	if (isset($admins['tpAdministrator'])) {
	    if (is_array($admins['tpAdministrator'])) {
		foreach ($admins['tpAdministrator'] as $admin) {
		    if ($this->attributes['idno'] == $admin) $data['is_schoolAdmin'] = true;
		}
	    } else {
		if ($this->attributes['idno'] == $admins['tpAdministrator']) $data['is_schoolAdmin'] = true;
	    }
	}
	return $data;
    }
    
    public function sendPasswordResetNotification($token)
    {
	$openldap = new LdapServiceProvider();
	$entry = $openldap->getUserEntry($this->attributes['idno']);
	$data = $openldap->getUserData($entry, 'uid');
	$accounts = '';
	if (is_array($data['uid'])) {
	    $accounts = implode('、', $data['uid']);
	} else {
	    $accounts = $data['uid'];
	}
	$this->notify(new ResetPasswordNotification($token, $accounts));
    }
    
    public function resetLdapPassword($value)
    {
	$openldap = new LdapServiceProvider();
	$ssha = $openldap->make_ssha_password($value);
	$new_passwd = array( 'userPassword' => $ssha );
	$accounts = array();
	if (is_array($this->ldap['uid'])) {
	    $accounts = $this->ldap['uid'];
	} else {
	    $accounts[] = $this->ldap['uid'];
	}
	$openldap->administrator();
	foreach ($accounts as $account) {
	    $entry = $openldap->getAccountEntry($account);
	    if ($entry) $openldap->updateData($entry,$new_passwd);
	}
	$entry = $openldap->getUserEntry($this->attributes['idno']);
	if ($entry) $openldap->updateData($entry,$new_passwd);
    }

    public function findForPassport($username)
    {
	$openldap = new LdapServiceProvider();
	$id = $openldap->checkAccount($username);
	if ($id) {
	    $user = $this->where('idno', $id)->first();
	    if (is_null($user)) {
		$entry = $openldap->getUserEntry($id);
		$data = $openldap->getUserData($entry);
	        $user = new \App\User();
	        $user->idno = $id;
	        $user->name = $data['displayName'];
		$user->uuid = $data['entryUUID'];
	        if (isset($data['mail'])) {
		    $user->email = $data['mail'];
		} else {
		    $user->email = null;
		}
	        if (isset($data['mobile'])) {
		    $user->mobile = $data['mobile'];
		} else {
		    $user->mobile = null;
		}
	        $user->password = \Hash::make(substr($id,-6));
	        $user->save();
	    }
	    return $user;
	}	
    }
}