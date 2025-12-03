<?php

namespace App\Models;

use App\Enums\DifficultyLevelEnum;
use App\Enums\QuestionTypeEnum;
use App\Enums\TimerEnum;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Question extends Model implements HasMedia
{
    use HasFactory, HasUlids, LogsActivity, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'question_bank_id',
        'reading_material_id',
        'question_type',
        'difficulty_level',
        'timer',
        'content',
        'score_value',
        'order',        // Nomor urut
        'is_active',
        'is_approved',
    ];

    protected $casts = [
        'question_type' => QuestionTypeEnum::class,
        'difficulty_level' => DifficultyLevelEnum::class,
        'timer' => TimerEnum::class,
        'is_active' => 'boolean',
        'is_approved' => 'boolean', // Status persetujuan Peer Review
    ];

    // --- RELATIONS ---

    public function questionBank(): BelongsTo
    {
        return $this->belongsTo(QuestionBank::class);
    }

    public function readingMaterial(): BelongsTo
    {
        return $this->belongsTo(ReadingMaterial::class);
    }

    public function peerReviews(): HasMany
    {
        return $this->hasMany(QuestionPeerReview::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class)->orderBy('order');
    }

    
    // --- ACCESSORS & MUTATORS ---

    /**
     * Accessor untuk question_type enum
     */
    public function getQuestionTypeAttribute($value): QuestionTypeEnum
    {
        return QuestionTypeEnum::from($value);
    }

    /**
     * Accessor untuk difficulty_level enum  
     */
    public function getDifficultyLevelAttribute($value): DifficultyLevelEnum
    {
        return DifficultyLevelEnum::from($value);
    }

    /**
     * Accessor untuk timer enum
     */
    public function getTimerAttribute($value): ?TimerEnum
    {
        return $value ? TimerEnum::from($value) : null;
    }

    // --- SPATIE CONFIGURATIONS ---

    /**
     * Konfigurasi untuk Media Library (Media di Soal)
     */
    public function registerMediaCollections(): void
    {
        // Koleksi untuk media yang muncul di Konten Soal (gambar, audio, video)
        $this->addMediaCollection('question_content')
            ->useDisk('public');
    }

    /**
     * Konfigurasi untuk Spatie Activity Log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['question_bank_id', 'question_type', 'difficulty_level', 'score_value', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Soal tipe {$this->question_type->value} telah di-{$eventName} di Bank Soal: {$this->questionBank->name}")
            ->useLogName('question');
    }
}
