<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class VolunteerSeeder extends Seeder
{
    public function run()
    {
        // Clean old data without deleting table
        // Use delete instead of truncate to avoid Foreign Key Issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Volunteer::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $volunteers = [
            [
                'name' => 'أحمد العلي',
                'email' => 'ahmad@test.com',  // Clear email for testing
                'password' => Hash::make('password123'),  // Specific password
                'phone' => '+963944111111',
                'gender' => 'male',
                'age' => 25,
                'nationality' => 'سوري',
                'address' => 'دمشق - المزة',
                'skills' => 'تنظيم فعاليات',
                'experience' => 'سنة واحدة',
                'education_level' => 'جامعي',
                'availability' => 'مسائي',
                'preferred_roles' => 'تنظيم',
                'languages' => 'Arabic,English',
                'emergency_contact' => '+963944222222',
                'status' => 'active',  // Approved volunteer - can log in
            ],
            [
                'name' => 'محمد الخطيب',
                'email' => 'mohammad@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+963944111112',
                'gender' => 'male',
                'age' => 28,
                'nationality' => 'سوري',
                'address' => 'حلب - الجميلية',
                'skills' => 'إدارة فرق',
                'experience' => '3 سنوات',
                'education_level' => 'جامعي',
                'availability' => 'صباحي',
                'preferred_roles' => 'إشراف',
                'languages' => 'Arabic,English',
                'emergency_contact' => '+963944222223',
                'status' => 'active',  // متطوع مقبول
            ],
            [
                'name' => 'سارة يوسف',
                'email' => 'sarah@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+963944111113',
                'gender' => 'female',
                'age' => 22,
                'nationality' => 'سورية',
                'address' => 'اللاذقية - المشروع',
                'skills' => 'تصميم',
                'experience' => 'سنتان',
                'education_level' => 'جامعي',
                'availability' => 'مرن',
                'preferred_roles' => 'تصميم',
                'languages' => 'Arabic,French',
                'emergency_contact' => '+963944222224',
                'status' => 'pending',  // Under review
            ],
            [
                'name' => 'ليان الأحمد',
                'email' => 'layan@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+963944111114',
                'gender' => 'female',
                'age' => 20,
                'nationality' => 'سورية',
                'address' => 'حمص - الوعر',
                'skills' => 'كتابة محتوى',
                'experience' => 'سنة',
                'education_level' => 'جامعي',
                'availability' => 'مسائي',
                'preferred_roles' => 'إعلام',
                'languages' => 'Arabic,English',
                'emergency_contact' => '+963944222225',
                'status' => 'pending',
            ],
            [
                'name' => 'رامي درويش',
                'email' => 'rami@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+963944111115',
                'gender' => 'male',
                'age' => 30,
                'nationality' => 'سوري',
                'address' => 'طرطوس - الكورنيش',
                'skills' => 'محاسبة',
                'experience' => '5 سنوات',
                'education_level' => 'جامعي',
                'availability' => 'صباحي',
                'preferred_roles' => 'مالية',
                'languages' => 'Arabic',
                'emergency_contact' => '+963944222226',
                'status' => 'active',
            ],
            [
                'name' => 'نور الحسين',
                'email' => 'noor@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+963944111116',
                'gender' => 'female',
                'age' => 24,
                'nationality' => 'سورية',
                'address' => 'دمشق - برزة',
                'skills' => 'خدمة مجتمعية',
                'experience' => 'سنتان',
                'education_level' => 'جامعي',
                'availability' => 'مرن',
                'preferred_roles' => 'ميداني',
                'languages' => 'Arabic,English',
                'emergency_contact' => '+963944222227',
                'status' => 'active',
            ],
            [
                'name' => 'خالد العيسى',
                'email' => 'khaled@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+963944111117',
                'gender' => 'male',
                'age' => 27,
                'nationality' => 'سوري',
                'address' => 'حماة - مركز المدينة',
                'skills' => 'قيادة',
                'experience' => '4 سنوات',
                'education_level' => 'جامعي',
                'availability' => 'مسائي',
                'preferred_roles' => 'تنظيم',
                'languages' => 'Arabic',
                'emergency_contact' => '+963944222228',
                'status' => 'active',
            ],
            [
                'name' => 'ميساء ناصر',
                'email' => 'maysa@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+963944111118',
                'gender' => 'female',
                'age' => 23,
                'nationality' => 'سورية',
                'address' => 'درعا - البلد',
                'skills' => 'تدريس',
                'experience' => 'سنة',
                'education_level' => 'جامعي',
                'availability' => 'صباحي',
                'preferred_roles' => 'تعليم',
                'languages' => 'Arabic',
                'emergency_contact' => '+963944222229',
                'status' => 'pending',
            ],
            [
                'name' => 'ياسر محمود',
                'email' => 'yasser@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+963944111119',
                'gender' => 'male',
                'age' => 29,
                'nationality' => 'سوري',
                'address' => 'الرقة - الصناعة',
                'skills' => 'إسعافات أولية',
                'experience' => '3 سنوات',
                'education_level' => 'جامعي',
                'availability' => 'مرن',
                'preferred_roles' => 'طوارئ',
                'languages' => 'Arabic,English',
                'emergency_contact' => '+963944222230',
                'status' => 'active',
            ],
            [
                'name' => 'هبة سليمان',
                'email' => 'hiba@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+963944111120',
                'gender' => 'female',
                'age' => 26,
                'nationality' => 'سورية',
                'address' => 'إدلب - المدينة',
                'skills' => 'تنسيق',
                'experience' => 'سنتان',
                'education_level' => 'جامعي',
                'availability' => 'مسائي',
                'preferred_roles' => 'تنظيم',
                'languages' => 'Arabic',
                'emergency_contact' => '+963944222231',
                'status' => 'inactive',  // حساب معطل
            ],
        ];

        foreach ($volunteers as $volunteer) {
            Volunteer::create($volunteer);
        }

        echo "✅ تم إنشاء " . count($volunteers) . " متطوع\n";
        echo "✅ جاهزين للاختبار!\n";
    }
}
