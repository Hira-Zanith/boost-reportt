<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
    'staff_name',
    'product',
    'spend',
    'invoice_amount',
    'messages',
    'new_id',
    'user_id',
];

// បន្ថែមទំនាក់ទំនង (Relationship) ទៅកាន់ User
public function user()
{
    return $this->belongsTo(User::class);
}
}
