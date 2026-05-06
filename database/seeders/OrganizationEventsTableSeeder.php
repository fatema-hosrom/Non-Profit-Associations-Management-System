<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationEvent;

class OrganizationEventsTableSeeder extends Seeder
{
    public function run()
    {
        $events = [
            // فعاليات جمعية الرحمة العالمية - سوريا
            [
                'organization_id' => 1,
                'title' => 'مشروع كفالة الأيتام',
                'description' => 'توفير كفالة شهرية للأيتام السوريين وتغطية احتياجاتهم التعليمية والصحية.',
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'location' => 'ريف حلب - سوريا',
                'status' => 'upcoming',
                'image' => 'orphans_sponsorship.png',
                'external_url' => 'https://alrahmahsyria.org',
                'created_by' => 2,
            ],
            [
                'organization_id' => 1,
                'title' => 'حملة السلال الغذائية',
                'description' => 'توزيع سلال غذائية للأسر المتضررة من النزاع في سوريا.',
                'start_date' => '2026-03-01',
                'end_date' => '2026-03-31',
                'location' => 'إدلب - سوريا',
                'status' => 'upcoming',
                'image' => 'food_basket_rahma.png',
                'external_url' => 'https://alrahmahsyria.org',
                'created_by' => 2,
            ],
            // فعاليات منظمة شام الإنسانية
            [
                'organization_id' => 2,
                'title' => 'مشروع العيادات المتنقلة',
                'description' => 'تقديم خدمات طبية متنقلة للنازحين في المخيمات والمناطق النائية.',
                'start_date' => '2026-02-10',
                'end_date' => '2026-02-28',
                'location' => 'مخيمات شمال سوريا',
                'status' => 'upcoming',
                'image' => 'mobile_clinics_sham.png',
                'external_url' => 'https://shamrelief.org',
                'created_by' => 2,
            ],
            [
                'organization_id' => 2,
                'title' => 'حملة الشتاء الدافئ',
                'description' => 'توزيع بطانيات وملابس شتوية على الأسر المحتاجة في المخيمات.',
                'start_date' => '2026-12-01',
                'end_date' => '2026-12-20',
                'location' => 'مخيمات إدلب',
                'status' => 'upcoming',
                'image' => 'winter_campaign_sham.png',
                'external_url' => 'https://shamrelief.org',
                'created_by' => 2,
            ],
            // فعاليات منظمة بنفسج للإغاثة والتنمية
            [
                'organization_id' => 3,
                'title' => 'مشروع التعليم في المخيمات',
                'description' => 'توفير التعليم والدعم النفسي للأطفال في مخيمات النزوح.',
                'start_date' => '2026-09-01',
                'end_date' => '2026-09-30',
                'location' => 'مخيمات أعزاز',
                'status' => 'upcoming',
                'image' => 'camp_education_banafsaj.png',
                'external_url' => 'https://banafsaj.org',
                'created_by' => 2,
            ],
            [
                'organization_id' => 3,
                'title' => 'حملة المياه النظيفة',
                'description' => 'توفير مياه شرب نظيفة للمخيمات والمناطق المحرومة.',
                'start_date' => '2026-05-01',
                'end_date' => '2026-05-31',
                'location' => 'ريف إدلب',
                'status' => 'upcoming',
                'image' => 'clean_water_banafsaj.png',
                'external_url' => 'https://banafsaj.org',
                'created_by' => 2,
            ],
            // فعاليات سوريا للإغاثة والتنمية (SRD)
            [
                'organization_id' => 4,
                'title' => 'حملة الصحة المجتمعية',
                'description' => 'تقديم خدمات صحية وتوعوية للأسر في المناطق الريفية.',
                'start_date' => '2026-04-01',
                'end_date' => '2026-04-30',
                'location' => 'ريف حماة',
                'status' => 'upcoming',
                'image' => 'community_health_srd.png',
                'external_url' => 'https://srdsy.org',
                'created_by' => 2,
            ],
            [
                'organization_id' => 4,
                'title' => 'مشروع دعم الأمومة والطفولة',
                'description' => 'تقديم رعاية صحية وغذائية للأمهات والأطفال في المناطق المتضررة.',
                'start_date' => '2026-06-01',
                'end_date' => '2026-06-30',
                'location' => 'حلب - سوريا',
                'status' => 'upcoming',
                'image' => 'mother_child_srd.png',
                'external_url' => 'https://srdsy.org',
                'created_by' => 2,
            ],
            // فعاليات الهيئة السورية للإغاثة والتنمية (UOSSM)
            [
                'organization_id' => 5,
                'title' => 'حملة العيادات الطبية المتنقلة',
                'description' => 'تقديم خدمات طبية متنقلة في المناطق النائية والريفية.',
                'start_date' => '2026-03-10',
                'end_date' => '2026-03-25',
                'location' => 'ريف إدلب - سوريا',
                'status' => 'upcoming',
                'image' => 'mobile_clinics_uossm.png',
                'external_url' => 'https://uossm.org',
                'created_by' => 2,
            ],
            [
                'organization_id' => 5,
                'title' => 'مشروع الدعم النفسي الاجتماعي',
                'description' => 'تقديم جلسات دعم نفسي واجتماعي للأطفال والنساء المتضررين من النزاع.',
                'start_date' => '2026-07-01',
                'end_date' => '2026-07-20',
                'location' => 'إدلب - سوريا',
                'status' => 'upcoming',
                'image' => 'psychosocial_support_uossm.png',
                'external_url' => 'https://uossm.org',
                'created_by' => 2,
            ],
            // فعالية الهلال الأحمر العربي السوري – معرض دمشق الدولي
            [
                'organization_id' => 6,
                'title' => 'مشاركة الهلال الأحمر العربي السوري في معرض دمشق الدولي',
                'description' => 'شارك الهلال الأحمر العربي السوري في معرض دمشق الدولي عبر تقديم خدمات إسعاف أولي، فرق الطوارئ، وبرامج توعية حول الاستجابة لحالات الطوارئ للصغار والكبار.',
                'start_date' => '2025-10-10',
                'end_date' => '2025-10-20',
                'location' => 'معرض دمشق الدولي – دمشق',
                'status' => 'completed',
                'image' => 'sarc_damascus_fair_2025.png',
                'external_url' => 'https://sana.sy/en/local/2263976/',
                'created_by' => 2,
            ],

            [
                'organization_id' => 6,
                'title' => 'حملة الإغاثة الشتوية',
                'description' => 'توزيع بطانيات وملابس شتوية ومواد غذائية للأسر المتضررة من النزاع.',
                'start_date' => '2026-12-01',
                'end_date' => '2026-12-15',
                'location' => 'ريف حلب - سوريا',
                'status' => 'upcoming',
                'image' => 'winter_relief_sy.png',
                'external_url' => 'https://sarc.sy',
                'created_by' => 2,
            ],
            // فعاليات جمعية ساعد الخيرية
            [
                'organization_id' => 7,
                'title' => 'مشروع ترميم المنازل',
                'description' => 'ترميم منازل الأسر الفقيرة والمتضررة من الحرب في حمص.',
                'start_date' => '2026-05-10',
                'end_date' => '2026-06-10',
                'location' => 'حمص - سوريا',
                'status' => 'upcoming',
                'image' => 'home_renovation_sy.png',
                'external_url' => 'https://saedcharity.org',
                'created_by' => 2,
            ],
            [
                'organization_id' => 7,
                'title' => 'حملة توزيع السلال الغذائية',
                'description' => 'توزيع سلال غذائية للأسر اللاجئة في ريف إدلب.',
                'start_date' => '2026-02-20',
                'end_date' => '2026-02-28',
                'location' => 'ريف إدلب - سوريا',
                'status' => 'upcoming',
                'image' => 'food_basket_sy.png',
                'external_url' => 'https://saedcharity.org',
                'created_by' => 2,
            ],
        ];

        foreach ($events as $event) {
            OrganizationEvent::create($event);
        }
    }
}
