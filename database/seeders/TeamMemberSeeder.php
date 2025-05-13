<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeamMember;
use Faker\Factory as FakerFactory;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create();
        // $faker_fr = FakerFactory::create('fr_FR');
        // $faker_ar = FakerFactory::create('ar_SA');

        TeamMember::truncate(); // Optionnel: vider la table avant de la peupler

        $teamMembers = [
            [
                'name' => ['en' => 'Aisha Diallo', 'fr' => 'Aisha Diallo', 'ar' => 'عائشة ديالو'],
                'role' => ['en' => 'Founder & CEO', 'fr' => 'Fondatrice & PDG', 'ar' => 'المؤسس والرئيس التنفيذي'],
                'image' => 'placeholders/team_member_1.jpg',
                'bio' => [
                    'en' => 'A passionate leader with over 15 years of experience in non-profit management and community development, driving ONG NAMA\'s vision forward.',
                    'fr' => 'Une dirigeante passionnée avec plus de 15 ans d\'expérience en gestion d\'OSBL et en développement communautaire, portant la vision de l\'ONG NAMA.',
                    'ar' => 'قائدة شغوفة تتمتع بخبرة تزيد عن 15 عامًا في إدارة المنظمات غير الربحية وتنمية المجتمع، وتقود رؤية منظمة نماء إلى الأمام.',
                ],
                'order' => 1, 'is_active' => true,
            ],
            [
                'name' => ['en' => 'Omar Ibrahim', 'fr' => 'Omar Ibrahim', 'ar' => 'عمر إبراهيم'],
                'role' => ['en' => 'Program Director', 'fr' => 'Directeur des Programmes', 'ar' => 'مدير البرامج'],
                'image' => 'placeholders/team_member_2.jpg',
                'bio' => [
                    'en' => 'Omar meticulously oversees all field projects, ensuring they achieve maximum impact and operational efficiency for the communities we serve.',
                    'fr' => 'Omar supervise méticuleusement tous nos projets sur le terrain, veillant à ce qu\'ils atteignent un impact maximal et une efficacité opérationnelle pour les communautés que nous servons.',
                    'ar' => 'يشرف عمر بدقة على جميع مشاريعنا الميدانية، مما يضمن تحقيقها أقصى تأثير وكفاءة تشغيلية للمجتمعات التي نخدمها.',
                ],
                'order' => 2, 'is_active' => true,
            ],
            [
                'name' => ['en' => 'Fatima Zahra Hassan', 'fr' => 'Fatima Zahra Hassan', 'ar' => 'فاطمة الزهراء حسن'],
                'role' => ['en' => 'Head of Communications & Outreach', 'fr' => 'Responsable Communication & Sensibilisation', 'ar' => 'رئيس قسم الاتصالات والتوعية'],
                'image' => 'placeholders/team_member_3.jpg',
                'bio' => [
                    'en' => 'Fatima is dedicated to amplifying our mission, sharing impactful stories of change, and fostering strong connections with our supporters and partners worldwide.',
                    'fr' => 'Fatima se consacre à amplifier notre mission, à partager des histoires de changement percutantes et à favoriser des liens solides avec nos sympathisants et partenaires du monde entier.',
                    'ar' => 'فاطمة مكرسة لتضخيم مهمتنا، ومشاركة قصص التغيير المؤثرة، وتعزيز الروابط القوية مع داعمينا وشركائنا في جميع أنحاء العالم.',
                ],
                'order' => 3, 'is_active' => true,
            ],
            [
                'name' => ['en' => 'Youssef Ben Ali', 'fr' => 'Youssef Ben Ali', 'ar' => 'يوسف بن علي'],
                'role' => ['en' => 'Finance & Administration Manager', 'fr' => 'Responsable Financier & Administratif', 'ar' => 'مدير المالية والإدارة'],
                'image' => 'placeholders/team_member_4.jpg', // Placeholder path
                'bio' => [
                    'en' => 'With a keen eye for detail and a commitment to excellence, Youssef ensures our financial operations are transparent, compliant, and sustainable, supporting our long-term goals.',
                    'fr' => 'Avec un sens aigu du détail et un engagement envers l\'excellence, Youssef veille à ce que nos opérations financières soient transparentes, conformes et durables, soutenant nos objectifs à long terme.',
                    'ar' => 'بعين ثاقبة للتفاصيل والتزام بالتميز، يضمن يوسف أن عملياتنا المالية شفافة ومتوافقة ومستدامة، مما يدعم أهدافنا طويلة الأجل.',
                ],
                'order' => 4, 'is_active' => true,
            ]
        ];

        foreach ($teamMembers as $memberData) {
            TeamMember::create($memberData);
        }
    }
}