<?php

namespace App\Filament\Resources\Exams\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Informasi Umum')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Ujian')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Select::make('subject_id')
                                    ->relationship('subject', 'name')
                                    ->label('Mata Pelajaran')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive(), // Make it reactive so we can filter question banks

                                Select::make('question_bank_id')
                                    ->relationship('questionBank', 'name', function ($query, $get) {
                                        // Filter by subject if selected
                                        if ($subjectId = $get('subject_id')) {
                                            $query->where('subject_id', $subjectId);
                                        }
                                        return $query;
                                    })
                                    ->label('Ambil Soal dari Bank Soal')
                                    ->searchable()
                                    ->preload()
                                    ->required() // Optional if they want to create manual questions later, but for now user implies copy
                                    ->helperText('Soal akan disalin dari bank soal yang dipilih saat ujian dibuat.'),

                                Select::make('grade_id')
                                    ->relationship('grade', 'name')
                                    ->label('Kelas/Jenjang')
                                    ->required(),

                                Select::make('academic_year_id')
                                    ->relationship('academicYear', 'year')
                                    ->label('Tahun Ajaran')
                                    ->default(fn() => \App\Models\AcademicYear::where('is_active', true)->first()?->id)
                                    ->required(),
                            ])
                            ->columns(2),

                        Section::make('Konfigurasi Ujian')
                            ->schema([
                                Select::make('exam_type')
                                    ->label('Tipe Ujian')
                                    ->options(\App\Enums\ExamTypeEnum::class)
                                    ->required(),

                                TextInput::make('duration')
                                    ->label('Durasi (Menit)')
                                    ->numeric()
                                    ->required()
                                    ->default(60),

                                // TextInput::make('total_questions')
                                //     ->label('Jumlah Soal')
                                //     ->numeric()
                                //     ->required()
                                //     ->minValue(1),

                                TextInput::make('passing_score')
                                    ->label('KKM (Nilai Lulus)')
                                    ->numeric()
                                    ->default(75)
                                    ->required(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Jadwal & Status')
                            ->schema([
                                DateTimePicker::make('start_time')
                                    ->label('Waktu Mulai')
                                    ->seconds(false)
                                    ->required(),

                                DateTimePicker::make('end_time')
                                    ->label('Waktu Selesai')
                                    ->seconds(false)
                                    ->required()
                                    ->after('start_time'),

                                Toggle::make('is_published')
                                    ->label('Terbitkan Ujian')
                                    ->helperText('Jika aktif, ujian akan terlihat oleh siswa pada waktunya.')
                                    ->default(false),

                                Toggle::make('is_randomized')
                                    ->label('Acak Soal')
                                    ->default(true),
                            ]),

                        Hidden::make('teacher_id')
                            ->default(fn() => auth()->id()),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
