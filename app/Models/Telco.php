<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Telco
 * 
 * @property int $id
 * @property int $contract_id
 * @property string $payment_mode
 * @property string $contract_type
 * @property int $order_id
 * @property string $status
 * @property Carbon|null $registration_date
 * @property Carbon|null $activation_date
 * @property string $scenario
 * @property string $base_product_name
 * @property string $supervisor_firstname
 * @property int $commission
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Telco extends Model
{
	protected $table = 'telco';

	protected $casts = [
		'contract_id' => 'int',
		'order_id' => 'int',
		'registration_date' => 'datetime',
		'activation_date' => 'datetime',
		'commission' => 'int'
	];

	protected $fillable = [
		'contract_id',
		'payment_mode',
		'contract_type',
		'order_id',
		'status',
		'registration_date',
		'activation_date',
		'scenario',
		'base_product_name',
		'supervisor_firstname',
		'commission'
	];
}
