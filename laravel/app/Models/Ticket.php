<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @method static Builder createdSince(CarbonInterface $since)
 * @method static Builder filter(Request $request)
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
     * @param Media|null $media
     * @return void
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 160, 160)
            ->nonQueued();
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

    /**
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeFilter(Builder $query, Request $request): Builder
    {
        $statuses = (array) $request->input('status', []);
        $statuses = array_values(array_filter($statuses, static fn ($v) => $v !== null && $v !== ''));

        $email = trim((string) $request->input('email', ''));
        $phone = trim((string) $request->input('phone', ''));

        return $query
            ->when(
                $statuses !== [],
                fn (Builder $q) =>
                $q->whereIn('status', $statuses)
            )
            ->when(
                $email !== '',
                fn (Builder $q) =>
                $q->whereHas(
                    'customer',
                    fn (Builder $cq) =>
                    $cq->where('email', 'like', "$email%")
                )
            )
            ->when(
                $phone !== '',
                fn (Builder $q) =>
                $q->whereHas(
                    'customer',
                    fn (Builder $cq) =>
                    $cq->where('phone_e164', 'like', "%$phone%")
                )
            )
            ->when(
                $request->date('from'),
                fn (Builder $q, $from) =>
                $q->whereDate('created_at', '>=', $from)
            )
            ->when(
                $request->date('to'),
                fn (Builder $q, $to) =>
                $q->whereDate('created_at', '<=', $to)
            );
    }
}
