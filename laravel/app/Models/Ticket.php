<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @method static Builder createdSince(CarbonInterface $since)
 */
class Ticket extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * @var string[]
     */
    protected $fillable = [
        'customer_id',
        'subject',
        'message',
        'status',
        'answered_at',
    ];

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'answered_at' => 'datetime',
            'status' => TicketStatus::class,
        ];
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    /**
     * @param Builder $query
     * @param CarbonInterface $since
     * @return Builder
     */
    public function scopeCreatedSince(Builder $query, CarbonInterface $since): Builder
    {
        return $query->where('created_at', '>=', $since);
    }
}
