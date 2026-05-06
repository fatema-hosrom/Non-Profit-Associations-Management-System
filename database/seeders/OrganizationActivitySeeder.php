<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationActivity;
use App\Models\ActivityDonationSettings;
use App\Models\ActivityVolunteerRequirements;

class OrganizationActivitySeeder extends Seeder
{
    public function run()
    {
        $activities = [

            // ==================== 1 ====================
            [
                'data' => [
                    'title' => 'مشروع إعمار مسجد النور - سوريا',
                    'description' => 'جمع تبرعات مالية لإعمار وتجهيز مسجد النور في ريف إدلب وتوفير كافة الاحتياجات من فرش وصيانة ومصاحف.',
                    'activity_type' => 'donation',
                    'location' => 'ريف إدلب - سوريا',
                    'start_date' => '2026-01-10',
                    'end_date' => '2026-03-10',
                    'image' => 'mosque_renovation_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'donation' => [
                    'target_amount' => 20000,
                    'collected_amount' => 3500,
                    'donation_status' => 'open',
                ],
            ],

            // ==================== 2 ====================
            [
                'data' => [
                    'title' => 'حفر بئر الرحمة - سوريا',
                    'description' => 'جمع تبرعات لحفر بئر ماء في قرية نائية في ريف حلب لتوفير مياه شرب نظيفة للأهالي والأطفال.',
                    'activity_type' => 'donation',
                    'location' => 'ريف حلب - سوريا',
                    'start_date' => '2026-02-01',
                    'end_date' => '2026-04-01',
                    'image' => 'well_project_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'donation' => [
                    'target_amount' => 8000,
                    'collected_amount' => 1200,
                    'donation_status' => 'open',
                ],
            ],

            // ==================== 3 ====================
            [
                'data' => [
                    'title' => 'دعم دار الأيتام النموذجية - سوريا',
                    'description' => 'توفير احتياجات دار الأيتام من ملابس وقرطاسية وأجهزة تعليمية عبر التبرعات المالية في دمشق.',
                    'activity_type' => 'donation',
                    'location' => 'دمشق - سوريا',
                    'start_date' => '2026-03-15',
                    'end_date' => '2026-05-15',
                    'image' => 'orphanage_support_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'donation' => [
                    'target_amount' => 10000,
                    'collected_amount' => 2500,
                    'donation_status' => 'open',
                ],
            ],

            // ==================== 4 ====================
            [
                'data' => [
                    'title' => 'رعاية كبار السن في دار المسنين - سوريا',
                    'description' => 'جمع تبرعات مالية لتوفير أجهزة طبية وأدوية واحتياجات معيشية لكبار السن في دار المسنين بحمص.',
                    'activity_type' => 'donation',
                    'location' => 'حمص - سوريا',
                    'start_date' => '2026-04-10',
                    'end_date' => '2026-06-10',
                    'image' => 'elderly_care_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'donation' => [
                    'target_amount' => 7000,
                    'collected_amount' => 900,
                    'donation_status' => 'open',
                ],
            ],

            // ==================== 5 ====================
            [
                'data' => [
                    'title' => 'تجهيز مستشفى الأمل الخيري - سوريا',
                    'description' => 'تجهيز مستشفى الأمل الخيري بالأجهزة الطبية الحديثة وغرف العمليات من خلال التبرعات المالية في حماة.',
                    'activity_type' => 'donation',
                    'location' => 'حماة - سوريا',
                    'start_date' => '2026-05-01',
                    'end_date' => '2026-07-01',
                    'image' => 'hospital_equipment_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'donation' => [
                    'target_amount' => 25000,
                    'collected_amount' => 4000,
                    'donation_status' => 'open',
                ],
            ],

            // ========== من هنا تبدأ الأنشطة التطوعية ==========
            [
                'data' => [
                    'title' => 'حملة تنظيف شواطئ اللاذقية',
                    'description' => 'تنظيم أكبر حملة تطوعية مفتوحة لتنظيف شواطئ اللاذقية.',
                    'activity_type' => 'volunteer',
                    'location' => 'اللاذقية - سوريا',
                    'start_date' => '2026-06-01',
                    'end_date' => '2026-06-03',
                    'image' => 'beach_cleaning_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'volunteer' => [
                    'required_volunteers' => 150,
                    'volunteers_count' => 25,
                    'min_age' => 16,
                    'gender_requirement' => 'both',
                    'volunteer_mode' => 'manual',
                    'skills_required' => 'العمل الجماعي، الالتزام البيئي',
                    'min_hours' => 6,
                ]
            ],

            // ==================== 7 ====================
            [
                'data' => [
                    'title' => 'زيارة مستشفيات الأطفال - دمشق',
                    'description' => 'زيارات تطوعية لتقديم الدعم النفسي والهدايا للأطفال المرضى.',
                    'activity_type' => 'volunteer',
                    'location' => 'دمشق - سوريا',
                    'start_date' => '2026-07-10',
                    'end_date' => '2026-07-12',
                    'image' => 'hospital_visit_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'volunteer' => [
                    'required_volunteers' => 20,
                    'volunteers_count' => 7,
                    'min_age' => 18,
                    'gender_requirement' => 'female',
                    'volunteer_mode' => 'auto',
                    'skills_required' => 'التواصل مع الأطفال، التعاطف',
                    'min_hours' => 4,
                ]
            ],

            // ==================== 8 ====================
            [
                'data' => [
                    'title' => 'برنامج تعليم الأطفال اللاجئين',
                    'description' => 'تقديم دروس تقوية ودعم نفسي للأطفال اللاجئين.',
                    'activity_type' => 'volunteer',
                    'location' => 'مخيم الزعتري - سوريا',
                    'start_date' => '2026-08-01',
                    'end_date' => '2026-08-20',
                    'image' => 'refugee_children_edu_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'volunteer' => [
                    'required_volunteers' => 30,
                    'volunteers_count' => 10,
                    'min_age' => 20,
                    'gender_requirement' => 'both',
                    'volunteer_mode' => 'auto',
                    'skills_required' => 'التدريس، الصبر، التعامل مع الأطفال',
                    'min_hours' => 8,
                ]
            ],

            // ==================== 9 ====================
            [
                'data' => [
                    'title' => 'تنظيم مهرجان ثقافي تطوعي',
                    'description' => 'تنظيم مهرجان ثقافي لتعزيز التراث السوري.',
                    'activity_type' => 'volunteer',
                    'location' => 'حلب - سوريا',
                    'start_date' => '2026-09-15',
                    'end_date' => '2026-09-18',
                    'image' => 'cultural_festival_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'volunteer' => [
                    'required_volunteers' => 25,
                    'volunteers_count' => 8,
                    'min_age' => 18,
                    'gender_requirement' => 'male',
                    'volunteer_mode' => 'manual',
                    'skills_required' => 'تنظيم الفعاليات، القيادة',
                    'min_hours' => 5,
                ]
            ],

            // ==================== 10 ====================
            [
                'data' => [
                    'title' => 'دورة إسعافات أولية تطوعية',
                    'description' => 'تدريب المتطوعين على مهارات الإسعافات الأولية.',
                    'activity_type' => 'volunteer',
                    'location' => 'طرطوس - سوريا',
                    'start_date' => '2026-10-05',
                    'end_date' => '2026-10-07',
                    'image' => 'first_aid_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'volunteer' => [
                    'required_volunteers' => 15,
                    'volunteers_count' => 5,
                    'min_age' => 19,
                    'gender_requirement' => 'both',
                    'volunteer_mode' => 'auto',
                    'skills_required' => 'الإسعافات الأولية، سرعة الاستجابة',
                    'min_hours' => 6,
                ]
            ],

            // ==================== 11 (both) ====================
            [
                'data' => [
                    'title' => 'حملة إفطار صائم - سوريا',
                    'description' => 'توزيع وجبات إفطار وجمع تبرعات.',
                    'activity_type' => 'both',
                    'location' => 'ريف دمشق - سوريا',
                    'start_date' => '2026-03-15',
                    'end_date' => '2026-04-10',
                    'image' => 'iftar_campaign_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'donation' => [
                    'target_amount' => 12000,
                    'collected_amount' => 2500,
                    'donation_status' => 'open',
                ],
                'volunteer' => [
                    'required_volunteers' => 120,
                    'volunteers_count' => 35,
                    'min_age' => 17,
                    'gender_requirement' => 'both',
                    'volunteer_mode' => 'manual',
                    'skills_required' => 'توزيع الطعام، العمل الجماعي',
                    'min_hours' => 5,
                ]
            ],

            // ==================== 12 ====================
            [
                'data' => [
                    'title' => 'حملة كسوة الشتاء - سوريا',
                    'description' => 'توزيع ملابس شتوية على الأسر المحتاجة.',
                    'activity_type' => 'both',
                    'location' => 'حلب - سوريا',
                    'start_date' => '2026-11-01',
                    'end_date' => '2026-11-20',
                    'image' => 'winter_clothes_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'donation' => [
                    'target_amount' => 9000,
                    'collected_amount' => 1800,
                    'donation_status' => 'open',
                ],
                'volunteer' => [
                    'required_volunteers' => 35,
                    'volunteers_count' => 10,
                    'min_age' => 18,
                    'gender_requirement' => 'both',
                    'volunteer_mode' => 'auto',
                    'skills_required' => 'توزيع الملابس، التنظيم',
                    'min_hours' => 4,
                ]
            ],

            // ==================== 13 ====================
            [
                'data' => [
                    'title' => 'دعم تعليم الأطفال - سوريا',
                    'description' => 'جمع تبرعات وتنظيم دروس تقوية تطوعية.',
                    'activity_type' => 'both',
                    'location' => 'درعا - سوريا',
                    'start_date' => '2026-09-01',
                    'end_date' => '2026-09-30',
                    'image' => 'education_support_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'donation' => [
                    'target_amount' => 6000,
                    'collected_amount' => 900,
                    'donation_status' => 'open',
                ],
                'volunteer' => [
                    'required_volunteers' => 20,
                    'volunteers_count' => 6,
                    'min_age' => 20,
                    'gender_requirement' => 'both',
                    'volunteer_mode' => 'auto',
                    'skills_required' => 'التدريس، التوزيع، الصبر',
                    'min_hours' => 6,
                ]
            ],

            // ==================== 14 ====================
            [
                'data' => [
                    'title' => 'ترميم منازل الأسر الفقيرة - سوريا',
                    'description' => 'جمع تبرعات وترتيب فرق تطوعية لترميم المنازل.',
                    'activity_type' => 'both',
                    'location' => 'حمص - سوريا',
                    'start_date' => '2026-05-20',
                    'end_date' => '2026-06-15',
                    'image' => 'home_renovation_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'donation' => [
                    'target_amount' => 15000,
                    'collected_amount' => 3200,
                    'donation_status' => 'open',
                ],
                'volunteer' => [
                    'required_volunteers' => 30,
                    'volunteers_count' => 9,
                    'min_age' => 19,
                    'gender_requirement' => 'male',
                    'volunteer_mode' => 'manual',
                    'skills_required' => 'النجارة، البناء، العمل الجماعي',
                    'min_hours' => 7,
                ]
            ],

            // ==================== 15 ====================
            [
                'data' => [
                    'title' => 'قافلة صحية تطوعية وتبرعية - سوريا',
                    'description' => 'تقديم خدمات طبية مجانية وجمع تبرعات للأدوية.',
                    'activity_type' => 'both',
                    'location' => 'ريف حماة - سوريا',
                    'start_date' => '2026-08-10',
                    'end_date' => '2026-08-20',
                    'image' => 'medical_convoy_sy.png',
                    'status' => 'active',
                    'is_published' => true,
                    'manager_id' => 2,
                    'approved_by' => null,
                ],
                'donation' => [
                    'target_amount' => 11000,
                    'collected_amount' => 2100,
                    'donation_status' => 'open',
                ],
                'volunteer' => [
                    'required_volunteers' => 18,
                    'volunteers_count' => 5,
                    'min_age' => 21,
                    'gender_requirement' => 'both',
                    'volunteer_mode' => 'auto',
                    'skills_required' => 'التمريض، الطب، التوزيع',
                    'min_hours' => 8,
                ]
            ],

        ];

        foreach ($activities as $item) {

            // إنشاء النشاط الأساسي
            $activity = OrganizationActivity::create($item['data']);

            // إضافة بيانات التبرع إذا كانت موجودة
            if (isset($item['donation'])) {
                ActivityDonationSettings::create([
                    'activity_id' => $activity->id,
                    ...$item['donation']
                ]);
            }

            // إضافة بيانات التطوع إذا كانت موجودة
            if (isset($item['volunteer'])) {
                ActivityVolunteerRequirements::create([
                    'activity_id' => $activity->id,
                    ...$item['volunteer']
                ]);
            }
        }
    }
}
