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
 * @property int $bill_id
 * @property string $userfield_agent
 * @property string $agent
 * @property string $status
 * @property string $payment_type
 * @property string $contract_type
 * @property string $product_type
 * @property string $bill
 * @property string $b2c_b2b
 * @property Carbon $inscription_date
 * @property float $consumption
 * @property int $commission
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Bill extends Model
{
	protected $table = 'bills';

	protected $casts = [
		'bill_id' => 'int',
		'inscription_date' => 'datetime',
		'consumption' => 'float',
		'commission' => 'int'
	];

	protected $fillable = [
		'bill_id',
		'userfield_agent',
		'agent',
		'status',
		'payment_type',
		'contract_type',
		'product_type',
		'bill',
		'b2c_b2b',
		'inscription_date',
		'consumption',
		'commission'
	];
}
