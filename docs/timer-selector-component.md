# TimerSelector Livewire Volt Component

## Deskripsi
TimerSelector adalah Livewire Volt component yang digunakan untuk memilih timer pada soal (Question). Component ini terintegrasi dengan TimerEnum dan secara otomatis mengupdate data Question ketika selection berubah.

## Lokasi
- **Component**: `app/Livewire/TimerSelector.php`
- **View**: `resources/views/livewire/timer-selector.blade.php`
- **Integration**: Terintegrasi dalam `resources/views/livewire/question-detail-viewer.blade.php`

## Fitur
- ✅ Pemilihan timer dari TimerEnum (30 detik - 10 menit)
- ✅ Update otomatis data Question ketika selection berubah
- ✅ Real-time update dengan `wire:model.live`
- ✅ Loading state saat proses update (disable selector)
- ✅ Error handling dengan auto-rollback jika gagal
- ✅ Notifikasi sukses/error ketika timer berhasil/gagal diupdate
- ✅ Responsive design (mobile & desktop)
- ✅ Dark mode support
- ✅ Event dispatch untuk sinkronisasi dengan parent component

## Cara Penggunaan
```blade
<livewire:timer-selector :question="$question" />
```

## Timer Options
- 30 detik
- 45 detik  
- 1 menit
- 1,5 menit
- 3 menit
- 5 menit
- 10 menit

## Events
- `timer-updated`: Dipatch ketika timer berhasil diupdate
- `notify`: Menampilkan notifikasi ke user

## Behavior
### Loading State
- **Saat Update**: Selector akan disabled dengan opacity 50% dan cursor not-allowed
- **Loading Indicator**: Spinner animation muncul di tengah selector
- **Status Text**: "Menyimpan..." muncul sebagai indikator proses
- **Auto-reset**: Loading state akan otomatis hilang setelah proses selesai (sukses/gagal)

### Error Handling
- **Auto-rollback**: Jika gagal, selection akan kembali ke nilai original
- **Error Notification**: Menampilkan pesan error jika proses gagal
- **Graceful Recovery**: Component tetap fungsional setelah error

## Dependencies
- Livewire Volt
- TimerEnum
- Question Model
- TailwindCSS (untuk styling)

## Integration
Component ini sudah terintegrasi dengan:
1. **QuestionDetailViewer**: Menampilkan timer selector di header
2. **Question Model**: Otomatis save timer ke database
3. **TimerEnum**: Menggunakan enum untuk timer options
4. **Event System**: Sinkronisasi antar component
