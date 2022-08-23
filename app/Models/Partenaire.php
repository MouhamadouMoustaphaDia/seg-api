<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Partenaire
 * 
 * @property int $id
 * @property string $name
 * @property string $adresse
 * @property string $contact
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Partenaire extends Model
{
	use SoftDeletes;
	protected $table = 'partenaires';

	protected $fillable = [
		'name',
		'adresse',
		'contact'
	];
}
