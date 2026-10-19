<?php

namespace App\Models;

use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    /** @use HasFactory<MessageFactory> */
    use HasFactory;

    public const TYPE_SUCCESS = 'success';

    public const TYPE_INFO = 'info';

    public const TYPE_WARNING = 'warning';

    public const TYPE_ERROR = 'error';

    /**
     * @var array<int, string>
     */
    public const TYPES = [self::TYPE_SUCCESS, self::TYPE_INFO, self::TYPE_WARNING, self::TYPE_ERROR];

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'code',
        'url',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'code' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeTipe(Builder $query, ?string $type): Builder
    {
        if (blank($type) || ! in_array($type, self::TYPES, true)) {
            return $query;
        }

        return $query->where('type', $type);
    }

    public function scopeBelumDibaca(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    public function labelTipe(): string
    {
        return match ($this->type) {
            self::TYPE_SUCCESS => 'Sukses',
            self::TYPE_INFO => 'Info',
            self::TYPE_WARNING => 'Peringatan',
            self::TYPE_ERROR => 'Error',
            default => ucfirst($this->type),
        };
    }
}
