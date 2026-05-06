<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationsTableSeeder extends Seeder
{
    public function run()
    {
        $organizations = [
            [
                'id' => 1,
                'name' => 'جمعية الرحمة العالمية - سوريا',
                'description' => 'جمعية خيرية سورية تقدم مشاريع إغاثية وتنموية للأسر المتضررة واللاجئين، تركز على الغذاء، الصحة، التعليم، وكفالة الأيتام.',
                'type' => 'local',
                'website_url' => 'https://alrahmahsyria.org',
                'contact_email' => 'info@alrahmahsyria.org',
                'contact_phone' => '+963944444444',
                'logo' => 'alrahma.png',
                'status' => 'active',
                'created_by' => 2,
            ],
            [
                'id' => 2,
                'name' => 'منظمة شام الإنسانية',
                'description' => 'منظمة إنسانية سورية غير ربحية تقدم خدمات الإغاثة والرعاية الصحية والتعليمية في سوريا والدول المجاورة.',
                'type' => 'local',
                'website_url' => 'https://shamrelief.org',
                'contact_email' => 'info@shamrelief.org',
                'contact_phone' => '+963933333333',
                'logo' => 'sham.png',
                'status' => 'active',
                'created_by' => 2,
            ],
            [
                'id' => 3,
                'name' => 'منظمة بنفسج للإغاثة والتنمية',
                'description' => 'منظمة سورية رائدة في العمل الإنساني والإغاثي، تقدم مشاريع في مجالات الصحة، التعليم، المياه، الإيواء، وحماية الطفل.',
                'type' => 'local',
                'website_url' => 'https://banafsaj.org',
                'contact_email' => 'info@banafsaj.org',
                'contact_phone' => '+963922222222',
                'logo' => 'banafsaj.png',
                'status' => 'active',
                'created_by' => 2,
            ],
            [
                'id' => 4,
                'name' => 'سوريا للإغاثة والتنمية (SRD)',
                'description' => 'منظمة سورية غير ربحية تقدم خدمات طبية، تعليمية، وإغاثية للنازحين واللاجئين داخل سوريا وخارجها.',
                'type' => 'local',
                'website_url' => 'https://srdsy.org',
                'contact_email' => 'info@srdsy.org',
                'contact_phone' => '+963911111111',
                'logo' => 'srd.png',
                'status' => 'active',
                'created_by' => 2,
            ],
            [
                'id' => 5,
                'name' => 'الهيئة السورية للإغاثة والتنمية (UOSSM)',
                'description' => 'منظمة طبية إنسانية سورية تقدم خدمات الرعاية الصحية والدعم النفسي للمتضررين من النزاع في سوريا.',
                'type' => 'local',
                'website_url' => 'https://uossm.org',
                'contact_email' => 'info@uossm.org',
                'contact_phone' => '+963988888888',
                'logo' => 'uossm.png',
                'status' => 'active',
                'created_by' => 2,
            ],
            [
                'id' => 6,
                'name' => 'جمعية الهلال الأحمر العربي السوري',
                'description' => 'من أكبر الجمعيات الإنسانية في سوريا، تقدم خدمات الإغاثة، الرعاية الصحية، حملات التبرع بالدم، ودعم المتضررين من الأزمات عبر شبكة فروع واسعة في جميع المحافظات السورية.',
                'type' => 'local',
                'website_url' => 'https://sarc.sy',
                'contact_email' => 'info@sarc.sy',
                'contact_phone' => '+963113327676',
                'logo' => 'sarc.png',
                'status' => 'active',
                'created_by' => 2,
            ],
            [
                'id' => 7,
                'name' => 'جمعية ساعد الخيرية',
                'description' => 'جمعية سورية غير ربحية مقرها تركيا، تركز على دعم الأسر السورية المتضررة من الحرب عبر مشاريع الغذاء، المياه، الإيواء، والحملات الإغاثية داخل سوريا وخارجها.',
                'type' => 'local',
                'website_url' => 'https://saedcharity.org',
                'contact_email' => 'info@saedcharity.org',
                'contact_phone' => '+905317818176',
                'logo' => 'saed.png',
                'status' => 'active',
                'created_by' => 2,
            ],
        ];

        foreach ($organizations as $org) {
            Organization::create($org);
        }
    }
}
