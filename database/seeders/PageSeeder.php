<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // Page "À Propos de Nous"
        Page::updateOrCreate(
            ['slug->fr' => 'a-propos-de-nous'],
            [
                'title' => ['fr' => 'À Propos de Nous', 'en' => 'About Us', 'ar' => 'من نحن'],
                'slug' => ['fr' => 'a-propos-de-nous', 'en' => 'about-us', 'ar' => 'من-نحن'],
                'body' => [
                    'fr' => '<p>NAMA Développement est une organisation engagée à améliorer la vie des communautés défavorisées. Depuis notre création en [Année de création], nous avons mené de nombreux projets impactants dans les domaines de l\'éducation, de la santé, de l\'accès à l\'eau potable et du développement économique local.</p><p>Notre mission est de fournir des solutions durables qui favorisent l\'autonomie et la résilience. Nous travaillons en étroite collaboration avec les populations locales, les autorités et d\'autres partenaires pour assurer la pertinence et l\'efficacité de nos interventions.</p><h3>Notre Vision</h3><p>Un monde où chaque individu a la possibilité de réaliser son plein potentiel et de vivre dans la dignité.</p><h3>Nos Valeurs</h3><ul><li><strong>Intégrité :</strong> Agir avec transparence et responsabilité.</li><li><strong>Collaboration :</strong> Travailler ensemble pour un impact maximal.</li><li><strong>Innovation :</strong> Rechercher constamment des solutions créatives et efficaces.</li><li><strong>Durabilité :</strong> Mettre en œuvre des projets pérennes.</li></ul>',
                    'en' => '<p>NAMA Development is an organization committed to improving the lives of disadvantaged communities. Since our inception in [Year of creation], we have led numerous impactful projects in education, health, access to clean water, and local economic development.</p><p>Our mission is to provide sustainable solutions that foster self-reliance and resilience. We work closely with local populations, authorities, and other partners to ensure the relevance and effectiveness of our interventions.</p><h3>Our Vision</h3><p>A world where every individual has the opportunity to reach their full potential and live with dignity.</p><h3>Our Values</h3><ul><li><strong>Integrity:</strong> Acting with transparency and accountability.</li><li><strong>Collaboration:</strong> Working together for maximum impact.</li><li><strong>Innovation:</strong> Constantly seeking creative and effective solutions.</li><li><strong>Sustainability:</strong> Implementing long-lasting projects.</li></ul>',
                    'ar' => '<p>نما للتنمية هي منظمة ملتزمة بتحسين حياة المجتمعات المحرومة. منذ تأسيسنا في [سنة التأسيس]، قمنا بالعديد من المشاريع المؤثرة في مجالات التعليم، الصحة، الوصول إلى المياه النظيفة، والتنمية الاقتصادية المحلية.</p><p>مهمتنا هي توفير حلول مستدامة تعزز الاعتماد على الذات والمرونة. نعمل عن كثب مع السكان المحليين والسلطات والشركاء الآخرين لضمان أهمية وفعالية تدخلاتنا.</p><h3>رؤيتنا</h3><p>عالم يتمتع فيه كل فرد بفرصة تحقيق كامل إمكاناته والعيش بكرامة.</p><h3>قيمنا</h3><ul><li><strong>النزاهة:</strong> العمل بشفافية ومسؤولية.</li><li><strong>التعاون:</strong> العمل معًا لتحقيق أقصى تأثير.</li><li><strong>الابتكار:</strong> البحث باستمرار عن حلول إبداعية وفعالة.</li><li><strong>الاستدامة:</strong> تنفيذ مشاريع دائمة.</li></ul>'
                ],
                'is_published' => true,
                'show_in_navbar' => true, 'navbar_order' => 1,
                'show_in_footer_navigation' => true, 'footer_navigation_order' => 1,
                'meta_title' => ['fr' => 'À Propos de NAMA Développement', 'en' => 'About NAMA Development', 'ar' => 'عن منظمة نما للتنمية'],
                'meta_description' => ['fr' => 'Découvrez la mission, la vision et les valeurs de NAMA Développement, organisation dédiée à l\'amélioration des conditions de vie.', 'en' => 'Learn about the mission, vision, and values of NAMA Development, an organization dedicated to improving living conditions.', 'ar' => 'تعرف على مهمة ورؤية وقيم منظمة نما للتنمية، وهي منظمة مكرسة لتحسين الظروف المعيشية.'],
            ]
        );

        // Page "Contactez-Nous"
        Page::updateOrCreate(
            ['slug->fr' => 'contactez-nous'],
            [
                'title' => ['fr' => 'Contactez-Nous', 'en' => 'Contact Us', 'ar' => 'اتصل بنا'],
                'slug' => ['fr' => 'contactez-nous', 'en' => 'contact-us', 'ar' => 'اتصل-بنا'],
                'body' => [
                    'fr' => '<h2>Nous Contacter</h2><p>Nous sommes toujours heureux d\'entendre parler de vous. Si vous avez des questions, des suggestions, ou si vous souhaitez collaborer avec nous, n\'hésitez pas à nous joindre via les informations ci-dessous ou en utilisant le formulaire de contact sur notre page dédiée.</p><p>Vous pouvez également nous rendre visite à notre siège (voir adresse et carte dans le pied de page).</p>',
                    'en' => '<h2>Contact Us</h2><p>We are always happy to hear from you. If you have any questions, suggestions, or wish to collaborate with us, please do not hesitate to reach out using the information below or by using the contact form on our dedicated page.</p><p>You can also visit us at our headquarters (see address and map in the footer).</p>',
                    'ar' => '<h2>اتصل بنا</h2><p>يسعدنا دائمًا أن نسمع منك. إذا كانت لديك أي أسئلة أو اقتراحات أو ترغب في التعاون معنا، فلا تتردد في التواصل معنا عبر المعلومات الواردة أدناه أو باستخدام نموذج الاتصال الموجود في صفحتنا المخصصة.</p><p>يمكنك أيضًا زيارتنا في مقرنا الرئيسي (انظر العنوان والخريطة في تذييل الصفحة).</p>'
                ],
                'is_published' => true,
                'show_in_navbar' => true, 'navbar_order' => 5, // Plus loin dans la nav
                'show_in_footer_navigation' => true, 'footer_navigation_order' => 5,
                'meta_title' => ['fr' => 'Contact - NAMA Développement', 'en' => 'Contact - NAMA Development', 'ar' => 'اتصل بنا - نما للتنمية'],
            ]
        );

        // Page "Faire un Don"
        Page::updateOrCreate(
            ['slug->fr' => 'faire-un-don'],
            [
                'title' => ['fr' => 'Faire un Don', 'en' => 'Donate', 'ar' => 'تبرع الآن'],
                'slug' => ['fr' => 'faire-un-don', 'en' => 'donate', 'ar' => 'تبرع-الان'],
                'body' => [
                    'fr' => '<h2>Soutenez Nos Actions</h2><p>Votre générosité nous permet de continuer notre travail et d\'étendre notre impact. Chaque contribution, petite ou grande, fait une différence significative dans la vie des personnes que nous aidons. Découvrez comment vous pouvez soutenir NAMA Développement.</p> ',
                    'en' => '<h2>Support Our Actions</h2><p>Your generosity allows us to continue our work and expand our impact. Every contribution, small or large, makes a significant difference in the lives of the people we help. Find out how you can support NAMA Development.</p> ',
                    'ar' => '<h2>ادعم أعمالنا</h2><p>كرمكم يسمح لنا بمواصلة عملنا وتوسيع تأثيرنا. كل مساهمة، صغيرة كانت أم كبيرة، تحدث فرقًا كبيرًا في حياة الأشخاص الذين نساعدهم. اكتشف كيف يمكنك دعم نما للتنمية.</p> '
                ],
                'is_published' => true,
                'show_in_navbar' => false, // Peut-être pas dans la nav principale
                'show_in_footer_useful_links' => true, 'footer_useful_links_order' => 1,
                'meta_title' => ['fr' => 'Faire un Don à NAMA', 'en' => 'Donate to NAMA', 'ar' => 'تبرع لمنظمة نما'],
            ]
        );
        
        // Page "Devenir Bénévole"
        Page::updateOrCreate(
            ['slug->fr' => 'devenir-benevole'],
            [
                'title' => ['fr' => 'Devenir Bénévole', 'en' => 'Become a Volunteer', 'ar' => 'كن متطوعًا'],
                'slug' => ['fr' => 'devenir-benevole', 'en' => 'become-a-volunteer', 'ar' => 'كن-متطوعا'],
                'body' => [
                    'fr' => '<h2>Engagez-vous Avec Nous</h2><p>Le bénévolat est une excellente façon de contribuer directement à nos projets et de faire une différence concrète. Nous recherchons des personnes passionnées et compétentes dans divers domaines. Rejoignez notre équipe de bénévoles dévoués !</p>',
                    'en' => '<h2>Get Involved With Us</h2><p>Volunteering is a great way to contribute directly to our projects and make a tangible difference. We are looking for passionate and skilled individuals in various fields. Join our team of dedicated volunteers!</p>',
                    'ar' => '<h2>شارك معنا</h2><p>التطوع وسيلة رائعة للمساهمة مباشرة في مشاريعنا وإحداث فرق ملموس. نحن نبحث عن أفراد متحمسين ومهرة في مختلف المجالات. انضم إلى فريق المتطوعين المتفانين لدينا!</p>'
                ],
                'is_published' => true,
                'show_in_navbar' => false,
                'show_in_footer_useful_links' => true, 'footer_useful_links_order' => 2,
            ]
        );

        // Page "Politique de Confidentialité"
        Page::updateOrCreate(
            ['slug->fr' => 'politique-de-confidentialite'],
            [
                'title' => ['fr' => 'Politique de Confidentialité', 'en' => 'Privacy Policy', 'ar' => 'سياسة الخصوصية'],
                'slug' => ['fr' => 'politique-de-confidentialite', 'en' => 'privacy-policy', 'ar' => 'سياسة-الخصوصية'],
                'body' => [
                    'fr' => '<h2>Notre Engagement envers Votre Vie Privée</h2><p>Cette section détaille comment NAMA Développement collecte, utilise et protège vos informations personnelles. Votre confiance est importante pour nous.</p><p><em>Contenu détaillé de la politique de confidentialité à ajouter ici...</em></p>',
                    'en' => '<h2>Our Commitment to Your Privacy</h2><p>This section details how NAMA Development collects, uses, and protects your personal information. Your trust is important to us.</p><p><em>Detailed privacy policy content to be added here...</em></p>',
                    'ar' => '<h2>التزامنا بخصوصيتك</h2><p>يوضح هذا القسم كيف تقوم منظمة نما للتنمية بجمع واستخدام وحماية معلوماتك الشخصية. ثقتك مهمة بالنسبة لنا.</p><p><em>محتوى مفصل لسياسة الخصوصية يضاف هنا ...</em></p>'
                ],
                'is_published' => true,
                'show_in_navbar' => false,
                'show_in_footer_useful_links' => true, 'footer_useful_links_order' => 3,
            ]
        );
         // Page "Nos Domaines d'Intervention" (Exemple pour la navbar)
        Page::updateOrCreate(
            ['slug->fr' => 'domaines-intervention'],
            [
                'title' => ['fr' => "Domaines d'Intervention", 'en' => 'Areas of Intervention', 'ar' => 'مجالات التدخل'],
                'slug' => ['fr' => 'domaines-intervention', 'en' => 'areas-of-intervention', 'ar' => 'مجالات-التدخل'],
                'body' => [
                    'fr' => '<h2>Nos Pôles d\'Activité Stratégiques</h2><p>NAMA concentre ses efforts sur plusieurs domaines clés pour maximiser son impact :</p><ul><li>Éducation et Formation Professionnelle</li><li>Santé et Nutrition</li><li>Accès à l\'Eau et Assainissement</li><li>Développement Économique et Entrepreneuriat</li><li>Protection de l\'Environnement et Adaptation au Changement Climatique</li></ul><p>Chacun de ces domaines est crucial pour le développement holistique des communautés que nous servons.</p>',
                    'en' => '<h2>Our Strategic Areas of Activity</h2><p>NAMA focuses its efforts on several key areas to maximize its impact:</p><ul><li>Education and Vocational Training</li><li>Health and Nutrition</li><li>Water Access and Sanitation</li><li>Economic Development and Entrepreneurship</li><li>Environmental Protection and Climate Change Adaptation</li></ul><p>Each of these areas is crucial for the holistic development of the communities we serve.</p>',
                    'ar' => '<h2>محاور نشاطنا الاستراتيجية</h2><pتركز نما جهودها على عدة مجالات رئيسية لتعظيم تأثيرها:</p><ul><li>التعليم والتدريب المهني</li><li>الصحة والتغذية</li><li>الحصول على المياه والصرف الصحي</li><li>التنمية الاقتصادية وريادة الأعمال</li><li>حماية البيئة والتكيف مع تغير المناخ</li></ul><p>كل مجال من هذه المجالات حاسم للتنمية الشاملة للمجتمعات التي نخدمها.</p>'
                ],
                'is_published' => true,
                'show_in_navbar' => true, 'navbar_order' => 2, // Après "À Propos"
                'show_in_footer_navigation' => true, 'footer_navigation_order' => 2,
            ]
        );
    }
}