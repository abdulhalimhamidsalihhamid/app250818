<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentResult extends Model
{
    // اسم الجدول (اختياري — Eloquent سيعرفه تلقائيًا، نذكره للوضوح)
    protected $table = 'student_results';

    // الحقول القابلة للملء
    protected $fillable = [
        'student_id',      // FK → students.id
        'subject',         // اسم المادة
        'mark',            // الدرجة
        'term',            // الفصل (الفصل الأول/الثاني)
        'year',            // السنة
        'specialization',  // علمي/أدبي (للتتبع)
    ];

    // التحويلات
    protected $casts = [
        'mark' => 'decimal:2',
        'year' => 'integer',
    ];

    /**
     * العلاقة: النتيجة تخص طالبًا واحدًا
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * سكوبات مساعدة (اختياري)
     */
    public function scopeForTerm($query, string $term, int $year)
    {
        return $query->where('term', $term)->where('year', $year);
    }

    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}
