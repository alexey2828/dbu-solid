<?php
// App/Models/Customer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;
    
    protected $table = 'customer';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'address',
        'comment'
    ];
    
    protected $casts = [
        'id' => 'integer'
    ];
    
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'idCustomer', 'id');
    }
}