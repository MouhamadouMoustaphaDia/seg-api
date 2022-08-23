<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Evenement
 *
 * @property int $id
 * @property string $description
 * @property int|null $etat
 * @property string $lieu
 * @property string $image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * @property int $user_id
 *
 * @property User $user
 *
 * @package App\Models
 */
class Evenement extends Model
{
	use SoftDeletes;
	protected $table = 'evenements';

	protected $casts = [
		'etat' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'description',
		'etat',
		'lieu',
		'image',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
