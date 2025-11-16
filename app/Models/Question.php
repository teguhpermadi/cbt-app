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
        'options',      // JSON array/object
        'key_answer',   // JSON array/object (kunci jawaban/rubrik)
        'explanation',  // Penjelasan/Pembahasan soal (dapat mengandung LaTeX)
        'score_value',
        'is_active',
        'is_approved',
    ];

    protected $casts = [
        'question_type' => QuestionTypeEnum::class,
        'difficulty_level' => DifficultyLevelEnum::class,
        'timer' => TimerEnum::class,
        'options' => 'array',     // Untuk opsi jawaban, termasuk media ULID
        'key_answer' => 'array',  // Untuk kunci jawaban, termasuk rubrik/urutan
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

    /**
     * Accessor untuk content dengan LaTeX rendering
     */
    public function getFormattedContentAttribute(): string
    {
        return $this->formatLatex($this->content);
    }

    /**
     * Accessor untuk explanation dengan LaTeX rendering
     */
    public function getFormattedExplanationAttribute(): string
    {
        return $this->explanation ? $this->formatLatex($this->explanation) : '';
    }

    /**
     * Accessor untuk key_answer dengan LaTeX rendering
     */
    public function getFormattedKeyAnswerAttribute(): string
    {
        if (is_array($this->key_answer)) {
            return $this->formatLatex(json_encode($this->key_answer, JSON_UNESCAPED_UNICODE));
        }
        return $this->formatLatex($this->key_answer);
    }

    /**
     * Helper method untuk memformat LaTeX expressions
     */
    private function formatLatex(string $text): string
    {
        // Convert inline LaTeX $$...$$ to MathJax format
        $text = preg_replace('/\$\$(.*?)\$\$/', '$$\\1$$', $text);
        
        // Convert other LaTeX patterns if needed
        $text = preg_replace('/\\\(\\\frac\{(.*?)\}\{(.*?)\)\\\)/', '\\frac{\\1}{\\2}', $text);
        $text = preg_replace('/\\\(\\\sqrt\{(.*?)\)\\\)/', '\\sqrt{\\1}', $text);
        
        return $text;
    }

    /**
     * Check if content contains LaTeX expressions
     */
    public function hasLatexContent(): bool
    {
        return str_contains($this->content, '$$') || 
               str_contains($this->content, '\\frac') || 
               str_contains($this->content, '\\sqrt') ||
               str_contains($this->content, '\\sum') ||
               str_contains($this->content, '\\pi');
    }

    /**
     * Check if explanation contains LaTeX expressions
     */
    public function hasLatexExplanation(): bool
    {
        if (!$this->explanation) return false;
        
        return str_contains($this->explanation, '$$') || 
               str_contains($this->explanation, '\\frac') || 
               str_contains($this->explanation, '\\sqrt') ||
               str_contains($this->explanation, '\\sum') ||
               str_contains($this->explanation, '\\pi');
    }

    /**
     * Check if key_answer contains LaTeX expressions
     */
    public function hasLatexKeyAnswer(): bool
    {
        $keyAnswerText = is_array($this->key_answer) ? 
            json_encode($this->key_answer, JSON_UNESCAPED_UNICODE) : 
            $this->key_answer;
            
        return str_contains($keyAnswerText, '$$') || 
               str_contains($keyAnswerText, '\\frac') || 
               str_contains($keyAnswerText, '\\sqrt') ||
               str_contains($keyAnswerText, '\\sum') ||
               str_contains($keyAnswerText, '\\pi');
    }

    /**
     * Get LaTeX expressions from content
     */
    public function getLatexExpressions(): array
    {
        preg_match_all('/\$\$(.*?)\$\$/', $this->content, $matches);
        return $matches[1] ?? [];
    }

    /**
     * Get LaTeX expressions from explanation
     */
    public function getLatexExplanationExpressions(): array
    {
        if (!$this->explanation) return [];
        
        preg_match_all('/\$\$(.*?)\$\$/', $this->explanation, $matches);
        return $matches[1] ?? [];
    }

    /**
     * Get LaTeX expressions from key_answer
     */
    public function getLatexKeyAnswerExpressions(): array
    {
        $keyAnswerText = is_array($this->key_answer) ? 
            json_encode($this->key_answer, JSON_UNESCAPED_UNICODE) : 
            $this->key_answer;
            
        preg_match_all('/\$\$(.*?)\$\$/', $keyAnswerText, $matches);
        return $matches[1] ?? [];
    }

    // --- SPATIE CONFIGURATIONS ---

    /**
     * Konfigurasi untuk Media Library (Media di Soal dan Opsi)
     */
    public function registerMediaCollections(): void
    {
        // Koleksi untuk media yang muncul di Konten Soal (gambar, audio, video)
        $this->addMediaCollection('question_content')
            ->useDisk('public'); 

        // Koleksi untuk media yang digunakan di dalam Opsi Jawaban
        $this->addMediaCollection('option_media')
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