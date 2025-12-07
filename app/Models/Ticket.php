<?php

namespace App\Models;

use App\services\v1\RequestFilter;

use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @method static Builder filters(RequestFilter $filters)
 */
class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'data'
    ];

    public function author() :BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }



    protected function scopeFilters(Builder $builder,RequestFilter $filters): Builder
    {
        return $filters->apply($builder);
    }
}

