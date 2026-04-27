<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string  $uuid
 * @property int  $user_id
 * @property int|null  $category_id
 * @property int|null  $recurring_task_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category|null $category
 * @property-read RecurringTask|null $recurringTask
 * @property-read User|null $user
 * @method static Builder<static>|Task newModelQuery()
 * @method static Builder<static>|Task newQuery()
 * @method static Builder<static>|Task query()
 * @method static Builder<static>|Task whereCreatedAt($value)
 * @method static Builder<static>|Task whereId($value)
 * @method static Builder<static>|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{

    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'description',
        'category_id',
        'recurring_task_id',
        'task_date'
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'task_date' => 'datetime'
        ];
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function recurringTask(): BelongsTo
    {
        return $this->belongsTo(RecurringTask::class);
    }
}
