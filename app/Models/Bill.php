<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bill
 * 
 * @property int $id
 * @property string $userfield_agent
 * @property string $agent
 * @property string $status
 * @property string $payment_type
 * @property string $bill
 * @property string $b2c_b2c
 * @property Carbon $inscription_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Bill extends Model
{
	protected $table = 'bills';

	protected $casts = [
		'inscription_date' => 'datetime'
	];

	protected $fillable = [
		'userfield_agent',
		'agent',
		'status',
		'payment_type',
		'bill',
		'b2c_b2c',
		'inscription_date'
	];
}
