<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\approval_condition; 

class ApprovalRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'For',
        'type_id',
        'RuleName',
        'start_date',
        'end_date',
        'status',
    ];

    
    public function type()
    {
        return $this->belongsTo(leavetype::class, 'type_id');
    }
    public function types()
    {
        return $this->belongsTo(ExpenseType::class, 'type_id');
    }
//     public function type()
// {
//     return $this->belongsTo(leavetype::class, 'type_id');
// }

// public function types()
// {
//     return $this->belongsTo(ExpenseType::class, 'type_id');
// }

// public function getTypeAttribute()
// {
//     if ($this->For === 'Expense') {
//         return $this->types;
//     } else {
//         return $this->type;
//     }
// }

  
    
    

    

     // Define the relationship to approval_conditions
    public function approvalConditions()
    {
        return $this->hasMany(approval_condition::class);
    }
}
