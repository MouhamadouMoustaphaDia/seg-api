<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int|null $nbr_signalement
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * @property int $profil_id
 *
 * @property Profil $profil
 * @property Collection|Evenement[] $evenements
 *
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
	use SoftDeletes;
	protected $table = 'users';

	protected $casts = [
		'nbr_signalement' => 'int',
		'profil_id' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'name',
		'email',
		'password',
		'nbr_signalement',
		'profil_id'
	];

	public function profil()
	{
		return $this->belongsTo(Profil::class);
	}

	public function evenements()
	{
		return $this->hasMany(Evenement::class);
	}


	/**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
