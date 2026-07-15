<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SummitHighlightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $highlights = [
            'attendee_count' => 933,
            'speakers' => [
                [
                    'name' => 'Titi Ojo PMP, NBDSP',
                    'title' => 'Executive Secretary, NPNEN',
                    'bio' => "Trade and export development specialist with over 23 years’ experience driving organisational leadership, agricultural value chain development, and SME competitiveness. She currently serves as National Coordinator for the British Standards Institution’s Standards Partnership Programme in Nigeria.\n\nShe has previously held key roles on the GIZ-funded Nigeria Competitiveness Project, the 2SCALE programme, and the UK FCDO Policy Development Facility Programme.\n\nA Hubert Humphrey Fellow, Certified Management Consultant, Project Management Professional (PMP), and Fellow of the Institute of Agribusiness Management.\n\nRole: Session 2A Presenter + Panel Moderator (Session 3)\nCity: Lagos",
                ],
                [
                    'name' => 'Mr Onoja Innocent Ella',
                    'title' => 'Deputy Director, NEPC',
                    'bio' => "Over 27 years’ experience in export development at NEPC, specialising in commodity development and export trade facilitation. Holds a Master’s degree in International Affairs and Strategic Studies from the University of Maiduguri.\n\nRole: Session 2A Presenter\nCity: Kano",
                ],
                [
                    'name' => 'Mr John Okorie',
                    'title' => 'Director, Export Development, NEPC',
                    'bio' => "Director at NEPC with extensive experience across export development, trade promotion, and exporter capacity building. Has risen through the ranks from Senior Trade Promotion Officer to Director, serving across multiple NEPC departments. Holds a degree in Food Science and Technology and a Master's degree in Public Administration.\n\nRole: Session 2A Presenter + Panellist (Session 3)\nCity: Port Harcourt",
                ],
                [
                    'name' => 'Mr Bamidele Odufuwa',
                    'title' => 'Access Bank',
                    'bio' => "Finance and international trade professional with extensive experience in export financing, trade products, and cross-border transactions. Holds a B.Sc in Economics and an MSc and MBA in Finance and Management.\n\nRole: Session 2B Presenter + Panellist (Session 3)\nCity: Lagos",
                ],
                [
                    'name' => 'Mr Adeniran Olatunde Ige FCA',
                    'title' => 'Zenith Bank',
                    'bio' => "Accomplished finance and international trade professional with extensive experience in Nigeria’s banking sector. Fellow of the Institute of Chartered Accountants of Nigeria. Specialises in trade finance, export credit, and banking sector compliance.\n\nRole: Session 2B Presenter + Panellist (Session 3)\nCity: Kano",
                ],
                [
                    'name' => 'Mr Oluseye Thomas Emmanuel',
                    'title' => 'Head, Export Businesses, First Bank',
                    'bio' => "International trade and development finance strategist with over 20 years of experience in banking and trade finance. Leading export growth and digital trade initiatives, he specializes in cross-border payments, structured trade solutions, and PAPSS integration. A Chartered Banker and MBA holder recognized for driving business transformation and non-oil export expansion across Africa.\n\nRole: 2B Presenter + Panellist\nCity: Port Harcourt",
                ],
                [
                    'name' => 'Mr Olasunkanmi Owoyemi',
                    'title' => 'MD, Sunbeth Global Concepts',
                    'bio' => "Leads Sunbeth Global Concepts, a diversified commodities, logistics and energy group with operations spanning agricultural commodity trading, supply chain management, and energy services. Instrumental in expanding Sunbeth’s footprint across multiple markets, structuring complex trade transactions, and driving sustainable growth.\n\nRole: Session 2C Presenter + Fireside Chat Interviewer (Session 4)\nCity: Lagos",
                ],
                [
                    'name' => 'Alhaji Bashir Muhammad Dankullu',
                    'title' => 'MD, Kanotan S. A. Limited',
                    'bio' => "Alhaji Bashir Muhammad Dankullu is a seasoned entrepreneur in the hides and skins industry with over three decades of experience. He began as a supplier to major tanneries and later expanded into tanning operations by utilizing dormant facilities. In 2018, he acquired Kanotan S. A. Limited, significantly increasing its processing capacity from 10,000 to 20,000 pieces per day. Today, the company employs over 500 people and exports finished leather products to international markets, contributing to industry growth and economic development.\n\nRole: Session 2C Presenter\nCity: Kano",
                ],
                [
                    'name' => 'Emmanuel Olamilekan Idowu',
                    'title' => 'Supply Chain Lead (Africa), Wilmar',
                    'bio' => "Emmanuel Olamilekan Idowu is a Chemical Engineer, registered in Nigeria and Malaysia, with an MBA from Nexford University. He has nearly a decade of experience across engineering, sustainable agriculture, renewable energy, and international trade, with projects spanning multiple continents.\n\nAs Supply Chain & Traceability Lead (Africa) at Wilmar International, he focuses on improving supply chain efficiency, sustainability compliance, and market access, working with global standards such as RSPO and ISCC.\n\nRole: Session 2C Presenter\nCity: Port Harcourt",
                ],
                [
                    'name' => 'Mr Kolawole Awe Esq.',
                    'title' => 'CEO, XPT Group',
                    'bio' => "Seasoned export trade and logistics expert with over two decades of experience across the non-oil export value chain, spanning commodities, logistics, compliance, and market access. Legal practitioner by training with deep practical expertise in export market entry and trade facilitation.\n\nRole: Session 2D Presenter\nCity: Lagos",
                ],
                [
                    'name' => 'Mr Abdullahi Sidi Aliyu',
                    'title' => 'Director, ITFA',
                    'bio' => "Development and export trade professional with over 30 years of service, including 10 years as Director at ITFA. Holds an M.Sc in Development Studies and a B.Sc in Economics. Extensive experience in market access, trade facilitation, and export promotion across West Africa.\n\nRole: Session 2D Presenter + Panel Moderator (Session 3)\nCity: Kano",
                ],
                [
                    'name' => 'Aliyu Bunu Sheriff',
                    'title' => 'SA to the President (Export Expansion), OVP',
                    'bio' => "A Chartered Accountant and export finance specialist, Aliyu Bunu Sheriff supports initiatives to strengthen Nigeria’s non-oil export sector, promote international trade partnerships, and expand market access. He is a member of Institute of Chartered Accountants of Nigeria, holds ACCA and CITN qualifications, and a Certificate in Islamic Banking and Finance.\n\nHis prior experience includes roles at the Nigeria Export-Import (NEXIM) Bank.\n\nRole: Session 2D Presenter (Session 3)\nCity: Kano",
                ],
                [
                    'name' => 'Dr Ofonasaha Udofia',
                    'title' => 'ES/CEO, IEOM',
                    'bio' => "International trade expert and certified trainer with over 20 years’ experience in import/export operations, manufacturing, and trade facilitation. Has worked with the International Trade Centre (ITC), African Development Bank (AfDB), and NEPC. Fellow of the Chartered Institute of Export and International Trade.\n\nRole: Session 2D Presenter + Panel Moderator (Session 3)\nCity: Port Harcourt",
                ],
                [
                    'name' => 'Mrs Rukayat Folake Yusuf',
                    'title' => 'Founder and CEO, Jaunty Natural Organic',
                    'bio' => "Export-focused entrepreneur producing plant-based food products for global markets. Products are listed on Amazon and comply with FDA, HACCP, ISO 22000:2018, BRC, and Halal standards. Operates a fulfilment network with warehouses in the US and UK, she is also a graduate of the WEnA SCALE Programme.\n\nRole: Fireside Chat Guest\nCity: Lagos",
                ],
                [
                    'name' => 'Chief (Mrs) Chinwe Ezenwa FCILT, FNIS, FOSHA',
                    'title' => 'MD/CEO, Lelook Nigeria Limited',
                    'bio' => "Over 40 years in manufacturing and exporting African fabric bags. Lelook Nigeria is one of Africa’s leading manufacturers of jute and polypropylene bags. First Nigerian exporter under the AfCFTA Guided Trade Initiative. Authorised Economic Operator. Founder of Lelook Bags Academy, which has trained over 500 students. Chairperson, Manufacturers Association of Nigeria (MAN), Anambra/Enugu Chapter.\n\nRole: Fireside Chat Guest\nCity: Kano",
                ],
                [
                    'name' => 'Mr Ejimadu Onyema',
                    'title' => 'Founder/GMD, Onyma Holdings Ltd',
                    'bio' => "Entrepreneur and business leader driving multi-sector operations across mining, agriculture, logistics, and international trade. Leads a diversified group with active participation in solid minerals export and agro-industrial value chains. Has established cross-border trade linkages and strategic partnerships across supply chain, finance, and logistics networks.\n\nRole: Fireside Chat Guest\nCity: Port Harcourt",
                ],
            ],
            'key_moments' => [
                [
                    'icon' => 'flag',
                    'title' => 'Opening Ceremony',
                    'description' => 'National Anthem, Introduction of Dignitaries, and Keynote Address.',
                ],
                [
                    'icon' => 'assignment',
                    'title' => 'Export Procedures',
                    'description' => 'Detailed sessions on documentation, NEPC registration, and compliance incentives.',
                    'featuring' => 'Titi Ojo, Onoja Innocent Ella, John Okorie',
                ],
                [
                    'icon' => 'account_balance',
                    'title' => 'Export Financing',
                    'description' => 'Insights into trade finance, NXP processing, and bankability.',
                    'featuring' => 'Bamidele Odufuwa, Adeniran Olatunde Ige, Oluseye Thomas Emmanuel',
                ],
                [
                    'icon' => 'trending_up',
                    'title' => 'Exporter’s Journey',
                    'description' => 'Real-life experiences, challenges, and practical advice from seasoned exporters.',
                    'featuring' => 'Olasunkanmi Owoyemi, Bashir Muhammad Dankullu, Emmanuel Olamilekan Idowu',
                ],
                [
                    'icon' => 'public',
                    'title' => 'Export Markets',
                    'description' => 'Market intelligence, AfCFTA, and packaging standards for global trade.',
                    'featuring' => 'Kolawole Awe, Abdullahi Sidi Aliyu, Aliyu Bunu Sheriff, Dr Ofonasaha Udofia',
                ],
                [
                    'icon' => 'groups',
                    'title' => 'Stakeholder Panel',
                    'description' => 'Cross-sector discussions and Q&A with industry regulators.',
                    'featuring' => 'Doris Okonkwo, Kunle Ajai, Hyacinth Chukwu',
                ],
            ],
            'committee' => [
                ['name' => 'Mr Wale Edun, OFR', 'role' => 'Hon. Minister of Finance', 'avatar' => 'committee/wale_edun.png'],
                ['name' => 'Dr Jumoke Oduwole, MFR', 'role' => 'Hon. Minister of Industry, Trade and Investment', 'avatar' => 'committee/jumoke_oduwole.png'],
                ['name' => 'Sen. Abubakar Kyari, CON', 'role' => 'Hon. Minister of Agriculture and Food Security', 'avatar' => 'committee/abubakar_kyari.png'],
                ['name' => 'Dr Bashir Adewale Adeniyi, MFR', 'role' => 'Comptroller-General, Nigeria Customs Service', 'avatar' => 'committee/bashir_adeniyi.png'],
                ['name' => 'Prof Mojisola Christianah Adeyeye', 'role' => 'Director-General, NAFDAC', 'avatar' => 'committee/mojisola_adeyeye.png'],
                ['name' => 'Mrs Oritsemeyiwa Eyesan', 'role' => 'CEO, NUPRC', 'avatar' => 'committee/Oritsemeyiwa_Eyesan.png'],
                ['name' => 'Mr Mele Kyari', 'role' => 'Group CEO, NNPCL'],
                ['name' => 'Engr Bashir Bayo Ojulari', 'role' => 'Executive Director/CEO, NEPC', 'avatar' => 'committee/Bashir_Ojulari.png'],
                ['name' => 'Mrs Nonye Ayeni', 'role' => 'Executive Director, NEPC', 'avatar' => 'committee/Nonye_Ayeni.png'],
                ['name' => 'Dr Akutah Pius Ukeyima, MON', 'role' => 'ES/CEO, Nigerian Shippers’ Council', 'avatar' => 'committee/Akutah_Ukeyima.png'],
                ['name' => 'Dr Abubakar Dantsoho', 'role' => 'MD, Nigerian Ports Authority', 'avatar' => 'committee/Abubakar_Dantsoho.png'],
                ['name' => 'Dr Musa Nakorji', 'role' => 'Chairman, NESS Technical Committee', 'avatar' => 'committee/Musa_Nakorji.png'],
            ],
            'chairman_message' => [
                'title' => 'Driving Collaboration for Effective Export Supervision',
                'content' => "Effective export supervision requires not only strong frameworks but also sustained collaboration across institutions.\n\nThe Nigerian Export Supervision Scheme was established to promote transparency, accountability, and effective monitoring of export transactions originating from Nigeria.\n\n“Strong institutions and coordinated systems are the backbone of effective export supervision.”\n\nAt the heart of the Scheme is the NESS Technical Committee, which brings together representatives from key regulatory and stakeholder institutions to provide strategic guidance and ensure coordinated implementation.\n\nIn a dynamic global trade environment, collaboration across institutions is essential to addressing emerging challenges and maintaining the integrity of export processes.\n\n“Collaboration across the export ecosystem is essential for sustainable growth.”\n\nThe NESS 2026 Sensitisation Seminars provide an important platform for stakeholders to engage, share perspectives, and deepen their understanding of the operational framework of the Scheme.\n\nThrough continuous dialogue and shared commitment, we can strengthen Nigeria’s export supervision system and build a more resilient and responsive export ecosystem.",
            ],
            'photos' => [
                'highlights/Screenshot 2026-04-23 at 19.05.42.png',
                'highlights/Screenshot 2026-04-23 at 19.05.59.png',
                'highlights/Screenshot 2026-04-23 at 19.06.14.png',
                'highlights/Screenshot 2026-04-23 at 19.06.26.png',
                'highlights/Screenshot 2026-04-23 at 19.06.38.png',
                'highlights/Screenshot 2026-04-23 at 19.06.49.png',
                'highlights/Screenshot 2026-04-23 at 19.07.01.png',
                'highlights/Screenshot 2026-04-23 at 19.07.15.png',
                'highlights/Screenshot 2026-04-23 at 19.07.28.png',
                'highlights/Screenshot 2026-04-23 at 19.07.39.png',
                'highlights/Screenshot 2026-04-23 at 19.07.50.png',
                'highlights/Screenshot 2026-04-23 at 19.08.01.png',
                'highlights/Screenshot 2026-04-23 at 19.08.15.png',
                'highlights/Screenshot 2026-04-23 at 19.08.31.png',
                'highlights/Screenshot 2026-04-23 at 19.08.43.png',
                'highlights/Screenshot 2026-04-23 at 19.08.59.png',
                'highlights/Screenshot 2026-04-23 at 19.09.36.png',
                'highlights/Screenshot 2026-04-23 at 19.09.48.png',
                'highlights/Screenshot 2026-04-23 at 19.09.57.png',
            ]
        ];

        \App\Models\Summit::whereIn('city', ['Lagos', 'Kano', 'Port Harcourt'])
            ->update([
                'hasHighlights' => true,
                'highlights_data' => $highlights
            ]);
    }
}
