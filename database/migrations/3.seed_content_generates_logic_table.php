<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private $tags = [
        'content_regeneration' => [
            'de' => 'Inhaltsneugestaltung',
            'en' => 'content regeneration',
            'fr' => 'régénération de contenu',
            'hi' => 'सामग्री पुनर्जनन',
            'ja' => 'コンテンツ再生成',
            'ko' => '콘텐츠 재생성',
            'pt' => 'regeneração de conteúdo',
            'th' => 'การสร้างเนื้อหาใหม่',
            'tl' => 'muling pagbuo ng nilalaman',
            'zh' => '内容重生成',
        ],
        'code_generation' => [
            'de' => 'Codegenerierung',
            'en' => 'code generation',
            'fr' => 'génération de code',
            'hi' => 'कोड जनरेशन',
            'ja' => 'コード生成',
            'ko' => '코드 생성',
            'pt' => 'geração de código',
            'th' => 'การสร้างโค้ด',
            'tl' => 'paggawa ng code',
            'zh' => '代码生成',
        ],
        'publishing' => [
            'de' => 'Veröffentlichung',
            'en' => 'publishing',
            'fr' => 'publication',
            'hi' => 'प्रकाशन',
            'ja' => '出版',
            'ko' => '퍼블리싱',
            'pt' => 'publicação',
            'th' => 'การเผยแพร่',
            'tl' => 'paglalathala',
            'zh' => '出版',
        ],
        'development_requirements_definition' => [
            'de' => 'Entwicklungsanforderungsdefinition',
            'en' => 'development requirements definition',
            'fr' => 'définition des exigences de développement',
            'hi' => 'विकास आवश्यकताओं की परिभाषा',
            'ja' => '開発要件定義',
            'ko' => '개발요구사항정의서',
            'pt' => 'definição de requisitos de desenvolvimento',
            'th' => 'การกำหนดความต้องการในการพัฒนา',
            'tl' => 'pagbibigay-kahulugan sa mga kinakailangan sa pag-unlad',
            'zh' => '开发需求定义',
        ],
        'excel_generation' => [
            'de' => 'Excel-Generierung',
            'en' => 'Excel generation',
            'fr' => 'génération Excel',
            'hi' => 'एक्सेल जनरेशन',
            'ja' => 'Excel生成',
            'ko' => '엑셀 생성',
            'pt' => 'geração de Excel',
            'th' => 'การสร้าง Excel',
            'tl' => 'paggawa ng Excel',
            'zh' => 'Excel生成',
        ],
        'project_proposal' => [
            'de' => 'Projektvorschlag',
            'en' => 'project proposal',
            'fr' => 'proposition de projet',
            'hi' => 'परियोजना प्रस्ताव',
            'ja' => 'プロジェクト提案書',
            'ko' => '프로젝트 계획서',
            'pt' => 'proposta de projeto',
            'th' => 'ข้อเสนอโครงการ',
            'tl' => 'panukala ng proyekto',
            'zh' => '项目提案',
        ],
        'marketing_strategy' => [
            'de' => 'Marketingstrategie',
            'en' => 'marketing strategy',
            'fr' => 'stratégie marketing',
            'hi' => 'विपणन रणनीति',
            'ja' => 'マーケティング戦略',
            'ko' => '마케팅 전략',
            'pt' => 'estratégia de marketing',
            'th' => 'กลยุทธ์การตลาด',
            'tl' => 'estratehiya sa marketing',
            'zh' => '市场营销策略',
        ],
        'voice' => [
            'de' => 'Stimme',
            'en' => 'voice',
            'fr' => 'voix',
            'hi' => 'आवाज',
            'ja' => '音声',
            'ko' => '목소리',
            'pt' => 'voz',
            'th' => 'เสียง',
            'tl' => 'boses',
            'zh' => '声音',
        ],
        'business_plan' => [
            'de' => 'Geschäftsplan',
            'en' => 'business plan',
            'fr' => 'plan d\'affaires',
            'hi' => 'व्यवसाय योजना',
            'ja' => '事業計画書',
            'ko' => '사업계획서',
            'pt' => 'plano de negócios',
            'th' => 'แผนธุรกิจ',
            'tl' => 'plano ng negosyo',
            'zh' => '商业计划',
        ],
        'terms_of_service' => [
            'de' => 'Nutzungsbedingungen',
            'en' => 'terms of service',
            'fr' => 'conditions d\'utilisation',
            'hi' => 'सेवा की शर्तें',
            'ja' => '利用規約',
            'ko' => '이용약관',
            'pt' => 'termos de serviço',
            'th' => 'ข้อกำหนดการใช้บริการ',
            'tl' => 'mga tuntunin ng serbisyo',
            'zh' => '服务条款',
        ],
        'legal' => [
            'de' => 'Rechtliches',
            'en' => 'legal',
            'fr' => 'juridique',
            'hi' => 'कानूनी',
            'ja' => '法的',
            'ko' => '법률',
            'pt' => 'legal',
            'th' => 'กฎหมาย',
            'tl' => 'legal',
            'zh' => '法律',
        ],
        'business_analysis' => [
            'de' => 'Geschäftsanalyse',
            'en' => 'business analysis',
            'fr' => 'analyse commerciale',
            'hi' => 'व्यवसाय विश्लेषण',
            'ja' => 'ビジネス分析',
            'ko' => '비즈니스 분석',
            'pt' => 'análise de negócios',
            'th' => 'การวิเคราะห์ธุรกิจ',
            'tl' => 'pagsusuri ng negosyo',
            'zh' => '商业分析',
        ],
        'strategic_planning' => [
            'de' => 'Strategische Planung',
            'en' => 'strategic planning',
            'fr' => 'planification stratégique',
            'hi' => 'रणनीतिक योजना',
            'ja' => '戦略的計画',
            'ko' => '전략 계획',
            'pt' => 'planejamento estratégico',
            'th' => 'การวางแผนเชิงกลยุทธ์',
            'tl' => 'estratehikong pagpaplano',
            'zh' => '战略规划',
        ],
        'business' => [
            'de' => 'Geschäft',
            'en' => 'business',
            'fr' => 'entreprise',
            'hi' => 'व्यवसाय',
            'ja' => 'ビジネス',
            'ko' => '사업',
            'pt' => 'negócio',
            'th' => 'ธุรกิจ',
            'tl' => 'negosyo',
            'zh' => '商业',
        ],
        'business_model_canvas' => [
            'de' => 'Geschäftsmodell-Canvas',
            'en' => 'business model canvas',
            'fr' => 'canevas de modèle d\'affaires',
            'hi' => 'व्यवसाय मॉडल कैनवास',
            'ja' => 'ビジネスモデルキャンバス',
            'ko' => '비즈니스 모델 캔버스',
            'pt' => 'canvas de modelo de negócios',
            'th' => 'แคนวาสโมเดลธุรกิจ',
            'tl' => 'canvas ng modelo ng negosyo',
            'zh' => '商业模式画布',
        ],
        'user_story' => [
            'de' => 'Benutzergeschichte',
            'en' => 'user story',
            'fr' => 'récit utilisateur',
            'hi' => 'उपयोगकर्ता कहानी',
            'ja' => 'ユーザーストーリー',
            'ko' => '유저 스토리',
            'pt' => 'história de usuário',
            'th' => 'เรื่องราวผู้ใช้',
            'tl' => 'kuwento ng user',
            'zh' => '用户故事',
        ],
        'agile' => [
            'de' => 'Agil',
            'en' => 'agile',
            'fr' => 'agile',
            'hi' => 'एजाइल',
            'ja' => 'アジャイル',
            'ko' => '애자일',
            'pt' => 'ágil',
            'th' => 'อไจล์',
            'tl' => 'agile',
            'zh' => '敏捷',
        ],
        'product_development' => [
            'de' => 'Produktentwicklung',
            'en' => 'product development',
            'fr' => 'développement de produit',
            'hi' => 'उत्पाद विकास',
            'ja' => '製品開発',
            'ko' => '제품 개발',
            'pt' => 'desenvolvimento de produto',
            'th' => 'การพัฒนาผลิตภัณฑ์',
            'tl' => 'pagbuo ng produkto',
            'zh' => '产品开发',
        ],
        'use_case_diagram' => [
            'de' => 'Anwendungsfalldiagramm',
            'en' => 'use case diagram',
            'fr' => 'diagramme de cas d\'utilisation',
            'hi' => 'यूज केस डायग्राम',
            'ja' => 'ユースケース図',
            'ko' => '유스케이스 다이어그램',
            'pt' => 'diagrama de caso de uso',
            'th' => 'แผนภาพกรณีการใช้งาน',
            'tl' => 'diagram ng use case',
            'zh' => '用例图',
        ],
        'wireframe' => [
            'de' => 'Wireframe',
            'en' => 'wireframe',
            'fr' => 'maquette fonctionnelle',
            'hi' => 'वायरफ्रेम',
            'ja' => 'ワイヤーフレーム',
            'ko' => '와이어프레임',
            'pt' => 'wireframe',
            'th' => 'ไวร์เฟรม',
            'tl' => 'wireframe',
            'zh' => '线框图',
        ],
        'prototype' => [
            'de' => 'Prototyp',
            'en' => 'prototype',
            'fr' => 'prototype',
            'hi' => 'प्रोटोटाइप',
            'ja' => 'プロトタイプ',
            'ko' => '프로토타입',
            'pt' => 'protótipo',
            'th' => 'ต้นแบบ',
            'tl' => 'prototipo',
            'zh' => '原型',
        ],
        'project_plan' => [
            'de' => 'Projektplan',
            'en' => 'project plan',
            'fr' => 'plan de projet',
            'hi' => 'परियोजना योजना',
            'ja' => 'プロジェクト計画',
            'ko' => '프로젝트 계획',
            'pt' => 'plano de projeto',
            'th' => 'แผนโครงการ',
            'tl' => 'plano ng proyekto',
            'zh' => '项目计划',
        ],
        'project_management' => [
            'de' => 'Projektmanagement',
            'en' => 'project management',
            'fr' => 'gestion de projet',
            'hi' => 'परियोजना प्रबंधन',
            'ja' => 'プロジェクト管理',
            'ko' => '프로젝트 관리',
            'pt' => 'gerenciamento de projetos',
            'th' => 'การจัดการโครงการ',
            'tl' => 'pamamahala ng proyekto',
            'zh' => '项目管理',
        ],
        'status_report' => [
            'de' => 'Statusbericht',
            'en' => 'status report',
            'fr' => 'rapport d\'état',
            'hi' => 'स्थिति रिपोर्ट',
            'ja' => 'ステータスレポート',
            'ko' => '상태 보고',
            'pt' => 'relatório de status',
            'th' => 'รายงานสถานะ',
            'tl' => 'ulat ng katayuan',
            'zh' => '状态报告',
        ],
        'result_report' => [
            'de' => 'Ergebnisbericht',
            'en' => 'result report',
            'fr' => 'rapport de résultats',
            'hi' => 'परिणाम रिपोर्ट',
            'ja' => '結果報告書',
            'ko' => '결과 보고',
            'pt' => 'relatório de resultados',
            'th' => 'รายงานผลลัพธ์',
            'tl' => 'ulat ng resulta',
            'zh' => '结果报告',
        ],
        'risk_management' => [
            'de' => 'Risikomanagement',
            'en' => 'risk management',
            'fr' => 'gestion des risques',
            'hi' => 'जोखिम प्रबंधन',
            'ja' => 'リスク管理',
            'ko' => '위험 관리',
            'pt' => 'gestão de riscos',
            'th' => 'การจัดการความเสี่ยง',
            'tl' => 'pamamahala ng panganib',
            'zh' => '风险管理',
        ],
        'image' => [
            'de' => 'Bild',
            'en' => 'image',
            'fr' => 'image',
            'hi' => 'छवि',
            'ja' => '画像',
            'ko' => '이미지',
            'pt' => 'imagem',
            'th' => 'รูปภาพ',
            'tl' => 'larawan',
            'zh' => '图像',
        ],
        'review' => [
            'de' => 'Bewertung',
            'en' => 'review',
            'fr' => 'avis',
            'hi' => 'समीक्षा',
            'ja' => 'レビュー',
            'ko' => '리뷰',
            'pt' => 'avaliação',
            'th' => 'รีวิว',
            'tl' => 'pagsusuri',
            'zh' => '评论',
        ],
        'product' => [
            'de' => 'Produkt',
            'en' => 'product',
            'fr' => 'produit',
            'hi' => 'उत्पाद',
            'ja' => '製品',
            'ko' => '제품',
            'pt' => 'produto',
            'th' => 'สินค้า',
            'tl' => 'produkto',
            'zh' => '产品',
        ],
        'blog' => [
            'de' => 'Blog',
            'en' => 'blog',
            'fr' => 'blog',
            'hi' => 'ब्लॉग',
            'ja' => 'ブログ',
            'ko' => '블로그',
            'pt' => 'blog',
            'th' => 'บล็อก',
            'tl' => 'blog',
            'zh' => '博客',
        ],
        'event' => [
            'de' => 'Veranstaltung',
            'en' => 'event',
            'fr' => 'événement',
            'hi' => 'कार्यक्रम',
            'ja' => 'イベント',
            'ko' => '이벤트',
            'pt' => 'evento',
            'th' => 'กิจกรรม',
            'tl' => 'kaganapan',
            'zh' => '活动',
        ],
        'article' => [
            'de' => 'Artikel',
            'en' => 'article',
            'fr' => 'article',
            'hi' => 'लेख',
            'ja' => '記事',
            'ko' => '기사',
            'pt' => 'artigo',
            'th' => 'บทความ',
            'tl' => 'artikulo',
            'zh' => '文章',
        ],
        'news' => [
            'de' => 'Nachrichten',
            'en' => 'news',
            'fr' => 'actualités',
            'hi' => 'समाचार',
            'ja' => 'ニュース',
            'ko' => '뉴스',
            'pt' => 'notícias',
            'th' => 'ข่าว',
            'tl' => 'balita',
            'zh' => '新闻',
        ],
        'social_media' => [
            'de' => 'Soziale Medien',
            'en' => 'social media',
            'fr' => 'médias sociaux',
            'hi' => 'सोशल मीडिया',
            'ja' => 'ソーシャルメディア',
            'ko' => '소셜미디어',
            'pt' => 'mídias sociais',
            'th' => 'สื่อสังคม',
            'tl' => 'social media',
            'zh' => '社交媒体',
        ],
        'caption' => [
            'de' => 'Bildunterschrift',
            'en' => 'caption',
            'fr' => 'légende',
            'hi' => 'कैप्शन',
            'ja' => 'キャプション',
            'ko' => '캡션',
            'pt' => 'legenda',
            'th' => 'คำบรรยายภาพ',
            'tl' => 'caption',
            'zh' => '标题',
        ],
        'email' => [
            'de' => 'E-Mail',
            'en' => 'email',
            'fr' => 'e-mail',
            'hi' => 'ईमेल',
            'ja' => 'メール',
            'ko' => '이메일',
            'pt' => 'e-mail',
            'th' => 'อีเมล',
            'tl' => 'email',
            'zh' => '电子邮件',
        ],
        'customer_service' => [
            'de' => 'Kundenservice',
            'en' => 'customer service',
            'fr' => 'service client',
            'hi' => 'ग्राहक सेवा',
            'ja' => 'カスタマーサービス',
            'ko' => '고객서비스',
            'pt' => 'atendimento ao cliente',
            'th' => 'บริการลูกค้า',
            'tl' => 'serbisyo sa kostumer',
            'zh' => '客户服务',
        ],
        'document' => [
            'de' => 'Dokument',
            'en' => 'document',
            'fr' => 'document',
            'hi' => 'दस्तावेज़',
            'ja' => '文書',
            'ko' => '문서',
            'pt' => 'documento',
            'th' => 'เอกสาร',
            'tl' => 'dokumento',
            'zh' => '文档',
        ],
        'analysis' => [
            'de' => 'Analyse',
            'en' => 'analysis',
            'fr' => 'analyse',
            'hi' => 'विश्लेषण',
            'ja' => '分析',
            'ko' => '분석',
            'pt' => 'análise',
            'th' => 'การวิเคราะห์',
            'tl' => 'pagsusuri',
            'zh' => '分析',
        ],
        'summary' => [
            'de' => 'Zusammenfassung',
            'en' => 'summary',
            'fr' => 'résumé',
            'hi' => 'सारांश',
            'ja' => '要約',
            'ko' => '요약',
            'pt' => 'resumo',
            'th' => 'สรุป',
            'tl' => 'buod',
            'zh' => '摘要',
        ],
        'audio' => [
            'de' => 'Audio',
            'en' => 'audio',
            'fr' => 'audio',
            'hi' => 'ऑडियो',
            'ja' => 'オーディオ',
            'ko' => '오디오',
            'pt' => 'áudio',
            'th' => 'เสียง',
            'tl' => 'audio',
            'zh' => '音频',
        ],
        'meeting_minutes' => [
            'de' => 'Sitzungsprotokoll',
            'en' => 'meeting minutes',
            'fr' => 'compte-rendu de réunion',
            'hi' => 'बैठक का कार्यवृत्त',
            'ja' => '議事録',
            'ko' => '회의록',
            'pt' => 'ata de reunião',
            'th' => 'รายงานการประชุม',
            'tl' => 'minuta ng pulong',
            'zh' => '会议记录',
        ],
        'webpage' => [
            'de' => 'Webseite',
            'en' => 'webpage',
            'fr' => 'page web',
            'hi' => 'वेबपेज',
            'ja' => 'ウェブページ',
            'ko' => '웹페이지',
            'pt' => 'página web',
            'th' => 'หน้าเว็บ',
            'tl' => 'webpage',
            'zh' => '网页',
        ],
        'survey' => [
            'de' => 'Umfrage',
            'en' => 'survey',
            'fr' => 'sondage',
            'hi' => 'सर्वेक्षण',
            'ja' => 'アンケート',
            'ko' => '설문조사',
            'pt' => 'pesquisa',
            'th' => 'แบบสำรวจ',
            'tl' => 'survey',
            'zh' => '调查',
        ],
        'questionnaire' => [
            'de' => 'Fragebogen',
            'en' => 'questionnaire',
            'fr' => 'questionnaire',
            'hi' => 'प्रश्नावली',
            'ja' => '質問票',
            'ko' => '설문지',
            'pt' => 'questionário',
            'th' => 'แบบสอบถาม',
            'tl' => 'talatanungan',
            'zh' => '问卷',
        ],
        'market_research' => [
            'de' => 'Marktforschung',
            'en' => 'market research',
            'fr' => 'étude de marché',
            'hi' => 'बाजार अनुसंधान',
            'ja' => '市場調査',
            'ko' => '시장 조사',
            'pt' => 'pesquisa de mercado',
            'th' => 'วิจัยตลาด',
            'tl' => 'pananaliksik sa merkado',
            'zh' => '市场研究',
        ],
        'planner' => [
            'de' => 'Planer',
            'en' => 'planner',
            'fr' => 'planificateur',
            'hi' => 'योजनाकार',
            'ja' => 'プランナー',
            'ko' => '기획자',
            'pt' => 'planejador',
            'th' => 'นักวางแผน',
            'tl' => 'tagaplano',
            'zh' => '策划者',
        ],
        'designer' => [
            'de' => 'Designer',
            'en' => 'designer',
            'fr' => 'concepteur',
            'hi' => 'डिजाइनर',
            'ja' => 'デザイナー',
            'ko' => '디자이너',
            'pt' => 'designer',
            'th' => 'นักออกแบบ',
            'tl' => 'tagadisenyo',
            'zh' => '设计师',
        ],
        'developer' => [
            'de' => 'Entwickler',
            'en' => 'developer',
            'fr' => 'développeur',
            'hi' => 'डेवलपर',
            'ja' => '開発者',
            'ko' => '개발자',
            'pt' => 'desenvolvedor',
            'th' => 'นักพัฒนา',
            'tl' => 'developer',
            'zh' => '开发者',
        ],
        'marketer' => [
            'de' => 'Vermarkter',
            'en' => 'marketer',
            'fr' => 'spécialiste marketing',
            'hi' => 'विपणक',
            'ja' => 'マーケター',
            'ko' => '마케터',
            'pt' => 'profissional de marketing',
            'th' => 'นักการตลาด',
            'tl' => 'tagapagpalaganap',
            'zh' => '营销人员',
        ],
        'hr' => [
            'de' => 'Personalwesen',
            'en' => 'HR',
            'fr' => 'RH',
            'hi' => 'मानव संसाधन',
            'ja' => '人事',
            'ko' => 'HR',
            'pt' => 'RH',
            'th' => 'ฝ่ายบุคคล',
            'tl' => 'HR',
            'zh' => '人力资源',
        ],
        'sales' => [
            'de' => 'Vertrieb',
            'en' => 'sales',
            'fr' => 'ventes',
            'hi' => 'बिक्री',
            'ja' => '営業',
            'ko' => '영업',
            'pt' => 'vendas',
            'th' => 'ฝ่ายขาย',
            'tl' => 'benta',
            'zh' => '销售',
        ],
        'job_search' => [
            'de' => 'Arbeitssuche',
            'en' => 'job search',
            'fr' => 'recherche d\'emploi',
            'hi' => 'नौकरी की तलाश',
            'ja' => '就職活動',
            'ko' => '취업',
            'pt' => 'busca de emprego',
            'th' => 'การหางาน',
            'tl' => 'paghahanap ng trabaho',
            'zh' => '求职',
        ],
        'entrepreneurship' => [
            'de' => 'Unternehmertum',
            'en' => 'entrepreneurship',
            'fr' => 'entrepreneuriat',
            'hi' => 'उद्यमिता',
            'ja' => '起業',
            'ko' => '창업',
            'pt' => 'empreendedorismo',
            'th' => 'การเป็นผู้ประกอบการ',
            'tl' => 'pagnenegosyo',
            'zh' => '创业',
        ],
        'teacher' => [
            'de' => 'Lehrer',
            'en' => 'teacher',
            'fr' => 'enseignant',
            'hi' => 'शिक्षक',
            'ja' => '教師',
            'ko' => '교사',
            'pt' => 'professor',
            'th' => 'ครู',
            'tl' => 'guro',
            'zh' => '教师',
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up()
    {
        // UUID 생성 함수
        $generateUuid = function() {
            return uuid_create(UUID_TYPE_RANDOM);
        };

        // 현재 테이블의 최대 ID 값을 가져옵니다.
        $maxId = DB::table('logics')->max('id') ?? 0;

        $logicsData = [
            //이벤트
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Startupful Review Blog-Beitrag',
                    'en' => 'Startupful Review Blog Posting',
                    'fr' => 'Publication de blog de critique Startupful',
                    'hi' => 'स्टार्टअपफुल समीक्षा ब्लॉग पोस्टिंग',
                    'ja' => 'Startupful レビューブログ投稿',
                    'ko' => 'Startupful 리뷰 블로그 포스팅',
                    'pt' => 'Postagem de Blog de Avaliação Startupful',
                    'th' => 'การโพสต์บล็อกรีวิว Startupful',
                    'tl' => 'Pag-post ng Blog ng Pagsusuri ng Startupful',
                    'zh' => 'Startupful 评论博客发布',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Titel, ausgewählter Titel, Einleitung, Hauptteil, Schlussfolgerung und Inhaltsintegration für professionelle Nachrichtenartikel',
                    'en' => 'keyword > titles, selected title, introduction, main body, conclusion, and content integration for professional news articles',
                    'fr' => 'mot-clé > titres, titre sélectionné, introduction, corps principal, conclusion et intégration de contenu pour des articles de presse professionnels',
                    'hi' => 'कीवर्ड > शीर्षक, चयनित शीर्षक, परिचय, मुख्य भाग, निष्कर्ष, और पेशेवर समाचार लेखों के लिए सामग्री एकीकरण',
                    'ja' => 'キーワード > タイトル、選択されたタイトル、序論、本文、結論、そしてプロフェッショナルなニュース記事のためのコンテンツ統合',
                    'ko' => '키워드 > 제목, 선택된 제목, 서론, 본문, 결론 및 전문 블로그 콘텐츠를 위한 콘텐츠 통합',
                    'pt' => 'palavra-chave > títulos, título selecionado, introdução, corpo principal, conclusão e integração de conteúdo para artigos de notícias profissionais',
                    'th' => 'คำสำคัญ > ชื่อเรื่อง, ชื่อเรื่องที่เลือก, บทนำ, เนื้อหาหลัก, บทสรุป และการรวมเนื้อหาสำหรับบทความข่าวมืออาชีพ',
                    'tl' => 'keyword > mga pamagat, napiling pamagat, panimula, pangunahing bahagi, konklusyon, at pagsasama ng nilalaman para sa mga propesyonal na artikulo ng balita',
                    'zh' => '关键词 > 标题、选定标题、引言、正文、结论以及专业新闻文章的内容整合',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "time",
                                "description" => "",
                                "type" => "time",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "Features",
                                "description" => $this->getLocalizedText([
                                    'de' => "Bitte wählen Sie 1 Dienst aus, den Sie interessant fanden",
                                    'en' => "Please choose 1 service that you found interesting",
                                    'fr' => "Veuillez choisir 1 service que vous avez trouvé intéressant",
                                    'hi' => "कृपया 1 सेवा चुनें जो आपको रोचक लगी",
                                    'ja' => "興味深いと感じたサービスを1つ選んでください",
                                    'ko' => "흥미롭다고 생각한 서비스 1개를 선택해 주세요",
                                    'pt' => "Por favor, escolha 1 serviço que você achou interessante",
                                    'th' => "โปรดเลือก 1 บริการที่คุณพบว่าน่าสนใจ",
                                    'tl' => "Mangyaring pumili ng 1 serbisyo na natagpuan mong kawili-wili",
                                    'zh' => "请选择1项您觉得有趣的服务",
                                ]),
                                "type" => "select",
                                "options" => $this->getLocalizedText([
                                    'de' => '[Text] Blog-Inhalte generieren, [Text] SNS-Inhalte generieren, [Text] Dokumente generieren, [Text] Webseiten-Zusammenfassung, [Text] Dokumentenzusammenfassung, [Bild] Bild generieren, [UI/UX] Text-zu-Code, [UI/UX] Bild-zu-Code, [Bild] Anime-Bild generieren, [Bild] NSFW(18+) Bild generieren, [Audio] Text-zu-Sprache, [Avatar-Chat] Rollenspiel, [Avatar-Chat] Sprachlehrer, [Avatar-Chat] Verschiedene Berater, [Text] E-Mail-Antwort generieren, [Text] Nutzungsbedingungen generieren, [Text] Einwilligungserklärung für die Verwendung personenbezogener Daten generieren, [Docs] One-Pager generieren, [Docs] Six-Pager generieren, [Docs] Geschäftsplan generieren, [Docs] Anforderungsspezifikation generieren, [Bild] Logo-Bild generieren',
                                    'en' => '[text] blog contents generate, [text] sns contents generate, [text] docs generate, [text] webpage summary, [text] docs summary, [image] image generate, [UI/UX] text-to-code, [UI/UX] image-to-code, [image] anime image generate, [image] NSFW(18+) image generate, [audio] text-to-speech, [avatar-chat] Role Playing, [avatar-chat] language tutor, [avatar-chat] a variety of consultants, [text] email reply generate, [text] terms of service generate, [text] privacy policy generate, [docs] one-pager generate, [docs] six-pager generate, [docs] business plan generate, [docs] development requirements definition generate, [image] logo image generate',
                                    'fr' => '[Texte] génération de contenu de blog, [Texte] génération de contenu SNS, [Texte] génération de documents, [Texte] résumé de la page Web, [Texte] résumé de documents, [Image] génération d\'image, [UI/UX] texte en code, [UI/UX] image en code, [Image] génération d\'image anime, [Image] génération d\'image NSFW(18+), [Audio] texte en parole, [Avatar-Chat] Jeu de rôle, [Avatar-Chat] tuteur de langue, [Avatar-Chat] une variété de consultants, [Texte] génération de réponse par e-mail, [Texte] génération des conditions d\'utilisation, [Texte] génération de la politique de confidentialité, [Docs] génération de one-pager, [Docs] génération de six-pager, [Docs] génération de plan d\'affaires, [Docs] génération de cahier des charges, [Image] génération de logo',
                                    'hi' => '[पाठ] ब्लॉग सामग्री उत्पन्न करें, [पाठ] एसएनएस सामग्री उत्पन्न करें, [पाठ] दस्तावेज़ उत्पन्न करें, [पाठ] वेबपेज सारांश, [पाठ] दस्तावेज़ सारांश, [छवि] छवि उत्पन्न करें, [UI/UX] टेक्स्ट-से-कोड, [UI/UX] छवि-से-कोड, [छवि] एनीमे छवि उत्पन्न करें, [छवि] NSFW (18+) छवि उत्पन्न करें, [ऑडियो] टेक्स्ट-टू-स्पीच, [अवतार-चैट] भूमिका निभाना, [अवतार-चैट] भाषा शिक्षक, [अवतार-चैट] विभिन्न परामर्शदाता, [पाठ] ईमेल उत्तर उत्पन्न करें, [पाठ] सेवा की शर्तें उत्पन्न करें, [पाठ] गोपनीयता नीति उत्पन्न करें, [दस्तावेज़] वन-पेजर उत्पन्न करें, [दस्तावेज़] सिक्स-पेजर उत्पन्न करें, [दस्तावेज़] व्यापार योजना उत्पन्न करें, [दस्तावेज़] विकास आवश्यकताओं की परिभाषा उत्पन्न करें, [छवि] लोगो छवि उत्पन्न करें',
                                    'ja' => '[テキスト] ブログコンテンツ生成, [テキスト] SNSコンテンツ生成, [テキスト] ドキュメント生成, [テキスト] ウェブページの要約, [テキスト] ドキュメントの要約, [画像] 画像生成, [UI/UX] テキストをコードに変換, [UI/UX] 画像をコードに変換, [画像] アニメ画像生成, [画像] NSFW(18+)画像生成, [オーディオ] テキスト読み上げ, [アバターチャット] ロールプレイング, [アバターチャット] 言語チューター, [アバターチャット] 様々なコンサルタント, [テキスト] メール返信生成, [テキスト] 利用規約生成, [テキスト] プライバシーポリシー生成, [ドキュメント] ワンペイジャー生成, [ドキュメント] シックスペイジャー生成, [ドキュメント] ビジネスプラン生成, [ドキュメント] 開発要件定義生成, [画像] ロゴ画像生成',
                                    'ko' => '[텍스트] 블로그 콘텐츠 생성, [텍스트] SNS 콘텐츠 생성, [텍스트] 문서 생성, [텍스트] 웹페이지 요약, [텍스트] 문서 요약, [이미지] 이미지 생성, [UI/UX] 텍스트-코드 변환, [UI/UX] 이미지-코드 변환, [이미지] 애니메이션 이미지 생성, [이미지] NSFW(18+) 이미지 생성, [오디오] 텍스트-음성 변환, [아바타-챗] 역할 놀이, [아바타-챗] 언어 교사, [아바타-챗] 다양한 컨설턴트, [텍스트] 이메일 답변 생성, [텍스트] 이용약관 생성, [텍스트] 개인정보 이용 동의서 생성, [문서] 원페이저 생성, [문서] 식스페이저 생성, [문서] 사업계획서 생성, [문서] 개발요구사항 정의서 생성, [이미지] 로고 이미지 생성',
                                    'pt' => '[Texto] gerar conteúdo de blog, [Texto] gerar conteúdo de SNS, [Texto] gerar documentos, [Texto] resumo da página da Web, [Texto] resumo de documentos, [Imagem] gerar imagem, [UI/UX] texto para código, [UI/UX] imagem para código, [Imagem] gerar imagem de anime, [Imagem] gerar imagem NSFW(18+), [Áudio] texto para fala, [Avatar-chat] Jogar Role Playing, [Avatar-chat] tutor de línguas, [Avatar-chat] uma variedade de consultores, [Texto] gerar resposta de e-mail, [Texto] gerar termos de serviço, [Texto] gerar política de privacidade, [Docs] gerar one-pager, [Docs] gerar six-pager, [Docs] gerar plano de negócios, [Docs] gerar definição de requisitos de desenvolvimento, [Imagem] gerar logotipo',
                                    'th' => '[ข้อความ] สร้างเนื้อหาบล็อก, [ข้อความ] สร้างเนื้อหา SNS, [ข้อความ] สร้างเอกสาร, [ข้อความ] สรุปหน้าเว็บ, [ข้อความ] สรุปเอกสาร, [ภาพ] สร้างภาพ, [UI/UX] แปลงข้อความเป็นโค้ด, [UI/UX] แปลงภาพเป็นโค้ด, [ภาพ] สร้างภาพอนิเมะ, [ภาพ] สร้างภาพ NSFW(18+), [เสียง] แปลงข้อความเป็นเสียงพูด, [อวาตาร์-แชท] เล่นตามบทบาท, [อวาตาร์-แชท] ติวเตอร์ภาษา, [อวาตาร์-แชท] ที่ปรึกษาหลากหลายประเภท, [ข้อความ] สร้างการตอบกลับอีเมล, [ข้อความ] สร้างข้อกำหนดการใช้บริการ, [ข้อความ] สร้างนโยบายความเป็นส่วนตัว, [เอกสาร] สร้าง one-pager, [เอกสาร] สร้าง six-pager, [เอกสาร] สร้างแผนธุรกิจ, [เอกสาร] สร้างคำจำกัดความข้อกำหนดการพัฒนา, [ภาพ] สร้างภาพโลโก้',
                                    'tl' => '[Teksto] bumuo ng nilalaman ng blog, [Teksto] bumuo ng nilalaman ng SNS, [Teksto] bumuo ng mga dokumento, [Teksto] buod ng webpage, [Teksto] buod ng mga dokumento, [Larawan] bumuo ng imahe, [UI/UX] tekstong-ginawa sa code, [UI/UX] imahe-ginawa sa code, [Larawan] bumuo ng imahe ng anime, [Larawan] bumuo ng NSFW (18+) na imahe, [Audio] text-to-speech, [Avatar-chat] Role Playing, [Avatar-chat] tutor sa wika, [Avatar-chat] iba\'t ibang mga consultant, [Teksto] bumuo ng tugon sa email, [Teksto] bumuo ng mga tuntunin ng serbisyo, [Teksto] bumuo ng patakaran sa privacy, [Dokumento] bumuo ng one-pager, [Dokumento] bumuo ng six-pager, [Dokumento] bumuo ng plano ng negosyo, [Dokumento] bumuo ng kahulugan ng mga kinakailangan sa pag-unlad, [Larawan] bumuo ng imahe ng logo',
                                    'zh' => '[文本] 生成博客内容, [文本] 生成SNS内容, [文本] 生成文档, [文本] 网页摘要, [文本] 文档摘要, [图片] 生成图片, [UI/UX] 文本生成代码, [UI/UX] 图片生成代码, [图片] 生成动漫图片, [图片] 生成NSFW(18+)图片, [音频] 文本转语音, [头像聊天] 角色扮演, [头像聊天] 语言导师, [头像聊天] 各类顾问, [文本] 生成邮件回复, [文本] 生成服务条款, [文本] 生成隐私政策, [文档] 生成One-Pager, [文档] 生成Six-Pager, [文档] 生成商业计划书, [文档] 生成开发需求定义, [图片] 生成徽标图片',
                                ]),

                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the current time {{step1.input.time}}, generate a persona with the following characteristics:

                            Tone and Manner: [Determine based on the hour]
                            Writing Style: [Determine based on the minute]
                            Structure: [Determine based on the second]

                            Provide a brief description of the persona, explaining how these three characteristics will influence their writing style for a blog post about a startup service.
                            Example output:
                            \"This persona writes with a cool and detached tone, using a conversational style as if talking directly to the reader. They structure their post as a story, weaving the information about the startup service into a narrative.\"
                            Generate the persona description now.",
                                                    "background_information" => "The time is divided into three components: hours, minutes, and seconds. Each component determines a specific characteristic of the persona:

                            1. Hours (0-23): Tone and Manner
                            - 0-2: Formal
                            - 3-5: Informal
                            - 6-8: Professional
                            - 9-11: Passionate
                            - 12-14: Cool and detached
                            - 15-17: Humorous
                            - 18-20: Serious
                            - 21-22: Optimistic
                            - 23: Realistic


                            2. Minutes (0-59): Writing Style
                            - 0-9: Topic sentence first
                            - 10-19: Conclusion first
                            - 20-29: Both topic sentence and conclusion first
                            - 30-39: Main point in the middle
                            - 40-49: Conversational (as if talking to the reader)
                            - 50-59: Reflective (as if organizing one's own thoughts)

                            3. Seconds (0-59): Structure
                            - 0-11: Introduction-Body-Conclusion
                            - 12-23: Q&A format
                            - 24-35: Storytelling
                            - 36-47: Comparison with alternative services
                            - 48-59: Review and feedback format",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Generate 10 compelling blog post titles for Startupful's {{step1.input.Features}} service. These titles should focus on reviews, user experiences, and feedback. Your titles should:

                            Incorporate keywords from {{step1.input.Features}} to create strong hook points.
                            Reflect the tone and manner of the target persona: {{step2.output}}
                            Be interesting and click-worthy to drive high CTR (Click-Through Rate).
                            Highlight different aspects of the Startupful service from a user's perspective.
                            Convey the idea of a review or user feedback.

                            Guidelines:

                            Craft concise yet descriptive titles (aim for 50-60 characters).
                            Use power words and emotional triggers to capture attention.
                            Incorporate numbers or specific benefits where appropriate.
                            Consider using phrases like \"Our Experience with\", \"X Months Using\", \"The Truth About\", \"Honest Review of\", etc.
                            Ensure the titles align with the persona's preferred writing style.

                            Please provide a numbered list of 10 review-style titles.
                            Example output format:

                            [Review-Focused Title 1]
                            [Review-Focused Title 2]
                            [Review-Focused Title 3]
                            ...

                            Generate your top 10 review-style title recommendations now.",
                        "background_information" => "You are a world-renowned SEO expert who has achieved top rankings on global search engines for numerous clients. Your expertise in creating compelling, SEO-friendly content has made you the go-to professional for businesses looking to improve their online visibility. Today, you've been tasked with creating blog post titles for Startup Pool's {{step1.input.Features}} service.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.5,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Review the 10 blog post titles generated in Step 3 ({{step3.output}}). Select the single most effective title that best combines SEO potential, appeal to the target persona, and relevance to Startupful's {{step1.input.Features}} service.
                            Your response should only include the selected title, copied exactly as it appears in {{step3.output}}.
                            Example output:
                            [Selected Title]
                            Please provide your selection now.",
                        "background_information" => "You continue your role as a world-renowned SEO expert. Your task now is to analyze the 10 blog post titles generated in the previous step and select the most effective one for Startupful's {{step1.input.Features}} service.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create a detailed outline for an engaging, review-style blog post using the selected title: {{step4.output}}. The outline should effectively structure the content to captivate the target persona and highlight the key features of Startupful's {{step1.input.Features}} service from a user's perspective.
                            Guidelines for creating the outline:

                            1. Start with an introduction that immediately grabs the reader's attention and briefly introduces Startupful and its {{step1.input.Features}} service.
                            2. Create 4 main sections that logically flow from the title and cover key aspects of the service, focusing on user experience and feedback.
                            3. Under each main section, include 2-3 subsections that dive deeper into specific points or features.
                            4. Ensure that the outline reflects the tone and style preferred by the target persona ({{step2.output}}).
                            5. Structure the content to maintain reader interest throughout:
                            5.1. The first section should be provocative and attention-grabbing. Use a hook that resonates with common frustrations or skepticism about AI services.
                            5.2. Subsequent sections should maintain interest through storytelling, gradually building a narrative that addresses initial doubts and showcases the value of Startupful's service.

                            6. For each main section and subsection, create catchy, intriguing titles that:
                            6.1. Use power words, emotional triggers, or curiosity-inducing phrases
                            6.2. Incorporate elements of surprise, contrast, or mystery
                            6.3. Reflect the content while leaving room for curiosity
                            6.4. Are concise yet impactful

                            7. Include a section that addresses potential questions or concerns the target persona might have.
                            8. End with a conclusion that summarizes key points and provides a thoughtful reflection on the user's journey from skepticism to appreciation.
                            9. Provide the outline in a clear, hierarchical format using markdown. Use H2 (##) for main sections and H3 (###) for subsections.

                            - Example format (with sample catchy titles):
                            From Skeptic to Believer: My AI Awakening
                            The AI Promised Land: Too Good to Be True?
                            Startupful: Just Another Mirage in the Desert?
                            {{step1.input.Features}}: The Oasis I Never Knew I Needed
                            [Main Review Section 1: Battling the AI Demons]
                            [Subsection 1.1: When Algorithms Attacked: My AI Nightmares]
                            [Subsection 1.2: Startupful: Friend or Foe?]
                            [Main Review Section 2: The Moment Everything Changed]
                            [Subsection 2.1: Jaw-Dropping First Encounter]
                            [Subsection 2.2: Hidden Treasures: Unearthing the Unexpected]
                            [Main Review Section 3: Peeling Back the Curtain]
                            [Subsection 3.1: The Secret Sauce: What Makes It Tick?]
                            [Subsection 3.2: In the Driver's Seat: My {{step1.input.Features}} Journey]
                            [Main Review Section 4: The Proof is in the Pudding]
                            [Subsection 4.1: Numbers Don't Lie: My Performance Skyrocketed]
                            [Subsection 4.2: Future-Proofing: The Gift That Keeps on Giving]
                            The Verdict: From Cynic to Champion

                            Please generate the complete outline now, ensuring it follows this structure and maintains a compelling narrative arc from initial skepticism to informed appreciation. Remember to create unique, catchy titles that will hook the reader and maintain their interest throughout the post.",
                        "background_information" => "You are a content strategist specializing in creating engaging and SEO-friendly blog post structures. Your task is to develop a comprehensive outline for a blog post about Startupful's {{step1.input.Features}} service.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "prompt" => "1. Write only the introduction and the first main section (Section 1) of the blog post, following the outline provided in {{step5.output}}.

                            2. The introduction should:
                            2.1. Start with a provocative hook that resonates with common frustrations or skepticism about AI services.
                            2.2. Briefly introduce Startupful and its {{step1.input.Features}} service.
                            2.3. Set the stage for the review from a skeptic's perspective.

                            3. For Section 1 (\"The Skeptic's Journey\" or similar):
                            3.1. Dive deep into past disappointments with AI services.
                            3.2 Express initial doubts about Startupful's offering.
                            3.3 Use personal anecdotes or hypothetical scenarios to make the content relatable.

                            4. Maintain the tone and style that appeals to the target persona ({{step2.output}}).
                            4.1 Use proper markdown syntax throughout:

                            5. Use # for the main title, ## for section headings, and ### for subsections.
                            5.1 Use * or _ for italics, and ** or __ for bold text.
                            5.2 Use > for blockquotes if needed.
                            5.3 Use - or * for unordered lists, and 1. for ordered lists if necessary.

                            6. Aim for a length of about 2000-4000 words for this section.
                            7. End this section with a subtle cliffhanger or transition that entices the reader to continue to the next section.

                            Remember, you're writing from a reviewer's perspective, as if you have personal experience with various AI services and are approaching Startupful's offering with initial skepticism. Your goal is to capture the reader's attention and set the stage for the journey from doubt to appreciation.
                            Please generate the content for the introduction and Section 1 now, following these guidelines.",
                                                    "background_information" => "You are a professional content writer specializing in tech product reviews. Your task is to write a comprehensive, engaging, and informative blog post reviewing Startupful's {{step1.input.Features}} service.
                            Context

                            - Service: Startupful's {{step1.input.Features}}
                            - Blog Post Title: {{step4.output}}
                            - Target Persona: {{step2.output}}
                            - Outline: {{step5.output}}",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 7,
                        "uuid" => $generateUuid(),
                        "prompt" => "1. Write only the second main section (Section 2) of the blog post, following the outline provided in {{step5.output}}. This section should focus on the \"Turning Point\" in the reviewer's experience with Startupful's service.

                            2. For Section 2 (\"Turning Point\" or similar):
                            2.1. Begin with a smooth transition from the skepticism expressed in Section 1.
                            2.2. Describe the first impressions of actually using Startupful's {{step1.input.Features}} service.
                            2.3. Highlight any unexpected benefits or features that began to change your perspective.
                            2.4. Use specific examples or scenarios to illustrate how the service started to address your initial doubts.

                            3. Maintain the tone and style that appeals to the target persona ({{step2.output}}), but start shifting from skepticism to cautious optimism.

                            4. Use proper markdown syntax throughout:
                            4.1. Use ## for the section heading, and ### for subsections.
                            4.2. Use * or _ for italics, and ** or __ for bold text.
                            4.3. Use > for blockquotes if needed.
                            4.4. Use - or * for unordered lists, and 1. for ordered lists if necessary.

                            5. Aim for a length of about 2000-4000 words for this section.

                            6. Include at least one specific feature or aspect of Startupful's {{step1.input.Features}} service that particularly impressed you or started to change your mind.

                            7. End this section with a sense of growing interest and curiosity about the service, setting the stage for a deeper exploration in the next section.

                            Remember, you're writing from a reviewer's perspective, describing the journey from initial skepticism to growing interest. Your goal is to maintain the reader's engagement by showing how your own perspective began to shift, while still maintaining a balanced and honest approach.

                            Please generate the content for Section 2 now, following these guidelines.",
                                                    "background_information" => "You are a professional content writer specializing in tech product reviews. Your task is to write a comprehensive, engaging, and informative blog post reviewing Startupful's {{step1.input.Features}} service.
                            Context

                            - Service: Startupful's {{step1.input.Features}}
                            - Blog Post Title: {{step4.output}}
                            - Target Persona: {{step2.output}}
                            - Outline: {{step5.output}}",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 8,
                        "uuid" => $generateUuid(),
                        "prompt" => "1. Write only the third main section (Section 3) of the blog post, following the outline provided in {{step5.output}}. This section should focus on a \"Deep Dive into Features\" of Startupful's {{step1.input.Features}} service.

                            2. For Section 3 (\"Deep Dive into Features\" or similar):
                            2.1. Start with a brief transition from the previous section, acknowledging the growing interest in the service.
                            2.2. Provide a detailed analysis of key features of the {{step1.input.Features}} service.
                            2.3. Describe the user experience in depth, including interface, ease of use, and any unique aspects.
                            2.4. Use specific examples or use cases to illustrate how each feature works and its potential benefits.
                            2.5. Include both strengths and potential areas for improvement in your analysis.

                            3. Maintain the tone and style that appeals to the target persona ({{step2.output}}), now from the perspective of someone who has spent significant time with the service.

                            4. Use proper markdown syntax throughout:
                            4.1. Use ## for the section heading, and ### for subsections.
                            4.2. Use * or _ for italics, and ** or __ for bold text.
                            4.3. Use > for blockquotes if needed.
                            4.4. Use - or * for unordered lists, and 1. for ordered lists if necessary.

                            5. Aim for a length of about 2000-4000 words for this section, as it's a detailed exploration of features.

                            6. Include at least 3-4 specific features or aspects of Startupful's {{step1.input.Features}} service, providing a balanced view of each.

                            7. Where relevant, compare features to industry standards or competitor offerings, highlighting what makes Startupful's service unique or superior.

                            8. End this section with a summary of your findings from this deep dive, setting the stage for the final section on real-world impact.

                            Remember, you're writing from a reviewer's perspective who has now thoroughly explored the service. Your goal is to provide readers with a comprehensive understanding of what Startupful's {{step1.input.Features}} service offers, balancing technical details with practical insights.

                            Please generate the content for Section 3 now, following these guidelines.",
                                                    "background_information" => "You are a professional content writer specializing in tech product reviews. Your task is to write a comprehensive, engaging, and informative blog post reviewing Startupful's {{step1.input.Features}} service.
                            Context

                            - Service: Startupful's {{step1.input.Features}}
                            - Blog Post Title: {{step4.output}}
                            - Target Persona: {{step2.output}}
                            - Outline: {{step5.output}}",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 9,
                        "uuid" => $generateUuid(),
                        "prompt" => "1. Write the fourth main section (Section 4) and the conclusion of the blog post, following the outline provided in {{step5.output}}. This section should focus on the \"Real-World Impact\" of using Startupful's {{step1.input.Features}} service.

                            2. For Section 4 (\"Real-World Impact\" or similar):
                            2.1. Begin with a brief transition from the previous section's feature analysis.
                            2.2. Describe tangible performance improvements experienced while using the service.
                            2.3. Discuss long-term benefits and potential ROI for users.
                            2.4. Use specific examples, case studies, or hypothetical scenarios to illustrate the real-world application and impact of the service.
                            2.5. Address how the service solves problems or improves processes for the target persona.


                            3. For the Conclusion:
                            3.1. Summarize the key points from all sections of the review.
                            3.2. Reflect on the journey from initial skepticism to informed appreciation of the service.
                            3.3. Offer final thoughts on Startupful's place in the AI landscape.
                            3.4. Provide a balanced overall recommendation or assessment.

                            4. Maintain the tone and style that appeals to the target persona ({{step2.output}}), now from the perspective of someone who has fully explored and utilized the service.

                            5. Use proper markdown syntax throughout:
                            5.1. Use ## for the section heading and conclusion, and ### for subsections.
                            5.2. Use * or _ for italics, and ** or __ for bold text.
                            5.3. Use > for blockquotes if needed.
                            5.4. Use - or * for unordered lists, and 1. for ordered lists if necessary.

                            6. Aim for a length of about 2000-4000 words for this section and conclusion combined.

                            7. In the Real-World Impact section, include at least 2-3 specific examples of how the service has positively affected productivity, efficiency, or other relevant metrics.

                            8. In the conclusion, ensure you've addressed any remaining potential concerns or questions the target persona might have.

                            Remember, you're writing from a reviewer's perspective who has now fully experienced and evaluated the service. Your goal is to provide a compelling argument for the real-world value of Startupful's {{step1.input.Features}} service, while offering a fair and balanced overall assessment.

                            Please generate the content for Section 4 and the Conclusion now, following these guidelines.",
                                                    "background_information" => "You are a professional content writer specializing in tech product reviews. Your task is to write a comprehensive, engaging, and informative blog post reviewing Startupful's {{step1.input.Features}} service.
                            Context

                            - Service: Startupful's {{step1.input.Features}}
                            - Blog Post Title: {{step4.output}}
                            - Target Persona: {{step2.output}}
                            - Outline: {{step5.output}}",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 10,
                        "uuid" => $generateUuid(),
                        "prompt" => "You are a professional content writer tasked with creating a compelling call-to-action (CTA) section for a blog post review of Startupful's {{step1.input.Features}} service. This section will be added at the end of the review to address legal considerations and promote the service.
                            Guidelines for creating the CTA section:

                            1. Start by disclosing that the entire blog post, including this CTA, was generated in just 10 seconds using the Startupful plugin.
                            2. Clarify that the review and testimonials in the post are simulated for demonstration purposes and may not reflect actual user experiences.
                            3. Highlight that the Startupful plugin can generate various types of content beyond blog posts, including audio, images, and code.
                            4. List the current benefits of using Startupful's service:
                            4.1. Mention the possibility of using the service for free for up to 6 months. Break down the offer: 30 days for initial subscription, 30 days for blog upload, 30 days for social media upload, and 90 days for short-form content upload.
                            4.2. Emphasize that unlike other AI content generators, Startupful allows users to design the logic process itself, enabling the creation of a wide variety of content.
                            4.3. Highlight that Startupful's service is not cloud-based but uses a plugin installed on the user's own hosting, making it significantly cheaper (tens, hundreds, or thousands of times) than traditional AI services.

                            5. End with a compelling, hook-laden CTA that includes a link to https://startupful.io.
                            6. Use markdown formatting for better readability and emphasis where appropriate.
                            7. Keep the tone consistent with the rest of the blog post and appealing to the target persona ({{step2.output}}).
                            8. Aim for a length of about 2000-4000 words for this CTA section.

                            Example structure:
                            markdownCopy## The Truth Behind This Review (And Why It Matters to You)

                            [Disclosure about the blog post being AI-generated]

                            [Clarification about the simulated nature of the review]

                            [Highlight of Startupful's capabilities beyond blog posts]

                            ### Why Startupful Could Be Your Game-Changer

                            - [Free trial offer details]
                            - [Unique selling point about logic process design]
                            - [Cost-effectiveness compared to cloud-based AI services]

                            [Compelling CTA with link to https://startupful.io]
                            Please generate the CTA section now, following these guidelines and maintaining a tone that will resonate with the target persona while effectively promoting Startupful's service.",
                                                    "background_information" => "You are a professional content writer specializing in tech product reviews. Your task is to write a comprehensive, engaging, and informative blog post reviewing Startupful's {{step1.input.Features}} service.
                            Context

                            - Service: Startupful's {{step1.input.Features}}
                            - Blog Post Title: {{step4.output}}
                            - Target Persona: {{step2.output}}
                            - Outline: {{step5.output}}",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 11,
                        "uuid" => $generateUuid(),
                        "content_template" => "{{step6.output}}\n\n{{step7.output}}\n\n{{step8.output}}\n\n{{step9.output}}---{{step10.output}}"
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["event"])),
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Startupful Review SNS-Beitrag',
                    'en' => 'Startupful Review SNS Posting',
                    'fr' => 'Publication SNS de critique Startupful',
                    'hi' => 'स्टार्टअपफुल समीक्षा SNS पोस्टिंग',
                    'ja' => 'Startupful レビューSNS投稿',
                    'ko' => 'Startupful 리뷰 SNS 포스팅',
                    'pt' => 'Postagem de SNS de Avaliação Startupful',
                    'th' => 'การโพสต์ SNS รีวิว Startupful',
                    'tl' => 'Pag-post ng SNS ng Pagsusuri ng Startupful',
                    'zh' => 'Startupful 评论 SNS 发布',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Titel, ausgewählter Titel, Einleitung, Hauptteil, Schlussfolgerung und Inhaltsintegration für professionelle Nachrichtenartikel',
                    'en' => 'keyword > titles, selected title, introduction, main body, conclusion, and content integration for professional news articles',
                    'fr' => 'mot-clé > titres, titre sélectionné, introduction, corps principal, conclusion et intégration de contenu pour des articles de presse professionnels',
                    'hi' => 'कीवर्ड > शीर्षक, चयनित शीर्षक, परिचय, मुख्य भाग, निष्कर्ष, और पेशेवर समाचार लेखों के लिए सामग्री एकीकरण',
                    'ja' => 'キーワード > タイトル、選択されたタイトル、序論、本文、結論、そしてプロフェッショナルなニュース記事のためのコンテンツ統合',
                    'ko' => '키워드 > 제목, 선택된 제목, 서론, 본문, 결론 및 전문 뉴스 기사를 위한 콘텐츠 통합',
                    'pt' => 'palavra-chave > títulos, título selecionado, introdução, corpo principal, conclusão e integração de conteúdo para artigos de notícias profissionais',
                    'th' => 'คำสำคัญ > ชื่อเรื่อง, ชื่อเรื่องที่เลือก, บทนำ, เนื้อหาหลัก, บทสรุป และการรวมเนื้อหาสำหรับบทความข่าวมืออาชีพ',
                    'tl' => 'keyword > mga pamagat, napiling pamagat, panimula, pangunahing bahagi, konklusyon, at pagsasama ng nilalaman para sa mga propesyonal na artikulo ng balita',
                    'zh' => '关键词 > 标题、选定标题、引言、正文、结论以及专业新闻文章的内容整合',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "time",
                                "description" => "",
                                "type" => "time",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "Features",
                                "description" => $this->getLocalizedText([
                                    'de' => "Bitte wählen Sie 1 Dienst aus, den Sie interessant fanden",
                                    'en' => "Please choose 1 service that you found interesting",
                                    'fr' => "Veuillez choisir 1 service que vous avez trouvé intéressant",
                                    'hi' => "कृपया 1 सेवा चुनें जो आपको रोचक लगी",
                                    'ja' => "興味深いと感じたサービスを1つ選んでください",
                                    'ko' => "흥미롭다고 생각한 서비스 1개를 선택해 주세요",
                                    'pt' => "Por favor, escolha 1 serviço que você achou interessante",
                                    'th' => "โปรดเลือก 1 บริการที่คุณพบว่าน่าสนใจ",
                                    'tl' => "Mangyaring pumili ng 1 serbisyo na natagpuan mong kawili-wili",
                                    'zh' => "请选择1项您觉得有趣的服务",
                                ]),                            
                                "type" => "select",
                                "options" => $this->getLocalizedText([
                                    'de' => '[Text] Blog-Inhalte generieren, [Text] SNS-Inhalte generieren, [Text] Dokumente generieren, [Text] Webseiten-Zusammenfassung, [Text] Dokumentenzusammenfassung, [Bild] Bild generieren, [UI/UX] Text-zu-Code, [UI/UX] Bild-zu-Code, [Bild] Anime-Bild generieren, [Bild] NSFW(18+) Bild generieren, [Audio] Text-zu-Sprache, [Avatar-Chat] Rollenspiel, [Avatar-Chat] Sprachlehrer, [Avatar-Chat] Verschiedene Berater, [Text] E-Mail-Antwort generieren, [Text] Nutzungsbedingungen generieren, [Text] Einwilligungserklärung für die Verwendung personenbezogener Daten generieren, [Docs] One-Pager generieren, [Docs] Six-Pager generieren, [Docs] Geschäftsplan generieren, [Docs] Anforderungsspezifikation generieren, [Bild] Logo-Bild generieren',
                                    'en' => '[text] blog contents generate, [text] sns contents generate, [text] docs generate, [text] webpage summary, [text] docs summary, [image] image generate, [UI/UX] text-to-code, [UI/UX] image-to-code, [image] anime image generate, [image] NSFW(18+) image generate, [audio] text-to-speech, [avatar-chat] Role Playing, [avatar-chat] language tutor, [avatar-chat] a variety of consultants, [text] email reply generate, [text] terms of service generate, [text] privacy policy generate, [docs] one-pager generate, [docs] six-pager generate, [docs] business plan generate, [docs] development requirements definition generate, [image] logo image generate',
                                    'fr' => '[Texte] génération de contenu de blog, [Texte] génération de contenu SNS, [Texte] génération de documents, [Texte] résumé de la page Web, [Texte] résumé de documents, [Image] génération d\'image, [UI/UX] texte en code, [UI/UX] image en code, [Image] génération d\'image anime, [Image] génération d\'image NSFW(18+), [Audio] texte en parole, [Avatar-Chat] Jeu de rôle, [Avatar-Chat] tuteur de langue, [Avatar-Chat] une variété de consultants, [Texte] génération de réponse par e-mail, [Texte] génération des conditions d\'utilisation, [Texte] génération de la politique de confidentialité, [Docs] génération de one-pager, [Docs] génération de six-pager, [Docs] génération de plan d\'affaires, [Docs] génération de cahier des charges, [Image] génération de logo',
                                    'hi' => '[पाठ] ब्लॉग सामग्री उत्पन्न करें, [पाठ] एसएनएस सामग्री उत्पन्न करें, [पाठ] दस्तावेज़ उत्पन्न करें, [पाठ] वेबपेज सारांश, [पाठ] दस्तावेज़ सारांश, [छवि] छवि उत्पन्न करें, [UI/UX] टेक्स्ट-से-कोड, [UI/UX] छवि-से-कोड, [छवि] एनीमे छवि उत्पन्न करें, [छवि] NSFW (18+) छवि उत्पन्न करें, [ऑडियो] टेक्स्ट-टू-स्पीच, [अवतार-चैट] भूमिका निभाना, [अवतार-चैट] भाषा शिक्षक, [अवतार-चैट] विभिन्न परामर्शदाता, [पाठ] ईमेल उत्तर उत्पन्न करें, [पाठ] सेवा की शर्तें उत्पन्न करें, [पाठ] गोपनीयता नीति उत्पन्न करें, [दस्तावेज़] वन-पेजर उत्पन्न करें, [दस्तावेज़] सिक्स-पेजर उत्पन्न करें, [दस्तावेज़] व्यापार योजना उत्पन्न करें, [दस्तावेज़] विकास आवश्यकताओं की परिभाषा उत्पन्न करें, [छवि] लोगो छवि उत्पन्न करें',
                                    'ja' => '[テキスト] ブログコンテンツ生成, [テキスト] SNSコンテンツ生成, [テキスト] ドキュメント生成, [テキスト] ウェブページの要約, [テキスト] ドキュメントの要約, [画像] 画像生成, [UI/UX] テキストをコードに変換, [UI/UX] 画像をコードに変換, [画像] アニメ画像生成, [画像] NSFW(18+)画像生成, [オーディオ] テキスト読み上げ, [アバターチャット] ロールプレイング, [アバターチャット] 言語チューター, [アバターチャット] 様々なコンサルタント, [テキスト] メール返信生成, [テキスト] 利用規約生成, [テキスト] プライバシーポリシー生成, [ドキュメント] ワンペイジャー生成, [ドキュメント] シックスペイジャー生成, [ドキュメント] ビジネスプラン生成, [ドキュメント] 開発要件定義生成, [画像] ロゴ画像生成',
                                    'ko' => '[텍스트] 블로그 콘텐츠 생성, [텍스트] SNS 콘텐츠 생성, [텍스트] 문서 생성, [텍스트] 웹페이지 요약, [텍스트] 문서 요약, [이미지] 이미지 생성, [UI/UX] 텍스트-코드 변환, [UI/UX] 이미지-코드 변환, [이미지] 애니메이션 이미지 생성, [이미지] NSFW(18+) 이미지 생성, [오디오] 텍스트-음성 변환, [아바타-챗] 역할 놀이, [아바타-챗] 언어 교사, [아바타-챗] 다양한 컨설턴트, [텍스트] 이메일 답변 생성, [텍스트] 이용약관 생성, [텍스트] 개인정보 이용 동의서 생성, [문서] 원페이저 생성, [문서] 식스페이저 생성, [문서] 사업계획서 생성, [문서] 개발요구사항 정의서 생성, [이미지] 로고 이미지 생성',
                                    'pt' => '[Texto] gerar conteúdo de blog, [Texto] gerar conteúdo de SNS, [Texto] gerar documentos, [Texto] resumo da página da Web, [Texto] resumo de documentos, [Imagem] gerar imagem, [UI/UX] texto para código, [UI/UX] imagem para código, [Imagem] gerar imagem de anime, [Imagem] gerar imagem NSFW(18+), [Áudio] texto para fala, [Avatar-chat] Jogar Role Playing, [Avatar-chat] tutor de línguas, [Avatar-chat] uma variedade de consultores, [Texto] gerar resposta de e-mail, [Texto] gerar termos de serviço, [Texto] gerar política de privacidade, [Docs] gerar one-pager, [Docs] gerar six-pager, [Docs] gerar plano de negócios, [Docs] gerar definição de requisitos de desenvolvimento, [Imagem] gerar logotipo',
                                    'th' => '[ข้อความ] สร้างเนื้อหาบล็อก, [ข้อความ] สร้างเนื้อหา SNS, [ข้อความ] สร้างเอกสาร, [ข้อความ] สรุปหน้าเว็บ, [ข้อความ] สรุปเอกสาร, [ภาพ] สร้างภาพ, [UI/UX] แปลงข้อความเป็นโค้ด, [UI/UX] แปลงภาพเป็นโค้ด, [ภาพ] สร้างภาพอนิเมะ, [ภาพ] สร้างภาพ NSFW(18+), [เสียง] แปลงข้อความเป็นเสียงพูด, [อวาตาร์-แชท] เล่นตามบทบาท, [อวาตาร์-แชท] ติวเตอร์ภาษา, [อวาตาร์-แชท] ที่ปรึกษาหลากหลายประเภท, [ข้อความ] สร้างการตอบกลับอีเมล, [ข้อความ] สร้างข้อกำหนดการใช้บริการ, [ข้อความ] สร้างนโยบายความเป็นส่วนตัว, [เอกสาร] สร้าง one-pager, [เอกสาร] สร้าง six-pager, [เอกสาร] สร้างแผนธุรกิจ, [เอกสาร] สร้างคำจำกัดความข้อกำหนดการพัฒนา, [ภาพ] สร้างภาพโลโก้',
                                    'tl' => '[Teksto] bumuo ng nilalaman ng blog, [Teksto] bumuo ng nilalaman ng SNS, [Teksto] bumuo ng mga dokumento, [Teksto] buod ng webpage, [Teksto] buod ng mga dokumento, [Larawan] bumuo ng imahe, [UI/UX] tekstong-ginawa sa code, [UI/UX] imahe-ginawa sa code, [Larawan] bumuo ng imahe ng anime, [Larawan] bumuo ng NSFW (18+) na imahe, [Audio] text-to-speech, [Avatar-chat] Role Playing, [Avatar-chat] tutor sa wika, [Avatar-chat] iba\'t ibang mga consultant, [Teksto] bumuo ng tugon sa email, [Teksto] bumuo ng mga tuntunin ng serbisyo, [Teksto] bumuo ng patakaran sa privacy, [Dokumento] bumuo ng one-pager, [Dokumento] bumuo ng six-pager, [Dokumento] bumuo ng plano ng negosyo, [Dokumento] bumuo ng kahulugan ng mga kinakailangan sa pag-unlad, [Larawan] bumuo ng imahe ng logo',
                                    'zh' => '[文本] 生成博客内容, [文本] 生成SNS内容, [文本] 生成文档, [文本] 网页摘要, [文本] 文档摘要, [图片] 生成图片, [UI/UX] 文本生成代码, [UI/UX] 图片生成代码, [图片] 生成动漫图片, [图片] 生成NSFW(18+)图片, [音频] 文本转语音, [头像聊天] 角色扮演, [头像聊天] 语言导师, [头像聊天] 各类顾问, [文本] 生成邮件回复, [文本] 生成服务条款, [文本] 生成隐私政策, [文档] 生成One-Pager, [文档] 生成Six-Pager, [文档] 生成商业计划书, [文档] 生成开发需求定义, [图片] 生成徽标图片',
                                ]),
                                "file_type" => null
                            ],
                            [
                                "label" => "SNS",
                                "description" => $this->getLocalizedText([
                                    'de' => "Bitte wählen Sie das SNS aus, auf dem Sie hochladen möchten",
                                    'en' => "Please select the SNS you want to upload",
                                    'fr' => "Veuillez sélectionner le réseau social sur lequel vous souhaitez publier",
                                    'hi' => "कृपया वह SNS चुनें जिस पर आप अपलोड करना चाहते हैं",
                                    'ja' => "アップロードしたいSNSを選択してください",
                                    'ko' => "업로드하고 싶은 SNS를 선택해 주세요",
                                    'pt' => "Por favor, selecione o SNS em que deseja fazer o upload",
                                    'th' => "โปรดเลือก SNS ที่คุณต้องการอัปโหลด",
                                    'tl' => "Mangyaring piliin ang SNS na gusto mong i-upload",
                                    'zh' => "请选择您想要上传的社交网络",
                                ]),
                                "type" => "select",
                                "options" => "LinkedIn, Instagram, Facebook, Threads, Twitter(X)",
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the current time {{step1.input.time}}, generate a persona with the following characteristics:

                            Tone and Manner: [Determine based on the hour]
                            Writing Style: [Determine based on the minute]
                            Structure: [Determine based on the second]

                            Provide a brief description of the persona, explaining how these three characteristics will influence their writing style for a blog post about a startup service.
                            Example output:
                            \"This persona writes with a cool and detached tone, using a conversational style as if talking directly to the reader. They structure their post as a story, weaving the information about the startup service into a narrative.\"
                            Generate the persona description now.",
                                                    "background_information" => "The time is divided into three components: hours, minutes, and seconds. Each component determines a specific characteristic of the persona:

                            1. Hours (0-23): Tone and Manner
                            - 0-2: Formal
                            - 3-5: Informal
                            - 6-8: Professional
                            - 9-11: Passionate
                            - 12-14: Cool and detached
                            - 15-17: Humorous
                            - 18-20: Serious
                            - 21-22: Optimistic
                            - 23: Realistic


                            2. Minutes (0-59): Writing Style
                            - 0-9: Topic sentence first
                            - 10-19: Conclusion first
                            - 20-29: Both topic sentence and conclusion first
                            - 30-39: Main point in the middle
                            - 40-49: Conversational (as if talking to the reader)
                            - 50-59: Reflective (as if organizing one's own thoughts)

                            3. Seconds (0-59): Structure
                            - 0-11: Introduction-Body-Conclusion
                            - 12-23: Q&A format
                            - 24-35: Storytelling
                            - 36-47: Comparison with alternative services
                            - 48-59: Review and feedback format",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Generate 10 compelling SNS caption titles for Startupful's {{step1.input.Features}} service. These titles should focus on user experiences, feedback, and key features. Your titles should:

                            1. Incorporate keywords from {{step1.input.Features}} to create strong hook points.
                            2. Reflect the tone and manner of the target persona: {{step2.output}}
                            3. Be interesting and engaging to drive high interaction rates.
                            4. Highlight different aspects of the Startupful service from a user's perspective.
                            5. Convey the idea of a user experience or feedback.

                            Guidelines:
                            - Consider the characteristics and limitations of the chosen SNS platform ({{step1.input.SNS}}).
                            - Craft concise yet descriptive titles (aim for optimal length per platform).
                            - Use power words and emotional triggers to capture attention.
                            - Incorporate numbers or specific benefits where appropriate.
                            - Consider using phrases like \"Our Experience with\", \"X Days Using\", \"The Truth About\", \"Honest Take on\", etc.
                            - Ensure the titles align with the persona's preferred writing style.
                            - Tailor the content to match the atmosphere and tone of the specific SNS platform.
                            - Include elements that immediately grab attention at the beginning of the caption title.

                            Please provide a numbered list of 10 SNS caption titles.
                            Example output format:

                            [SNS-optimized Caption Title 1]
                            [SNS-optimized Caption Title 2]
                            [SNS-optimized Caption Title 3]
                            ...

                            Generate your top 10 SNS caption title recommendations now.",
                        "background_information" => "You are a world-renowned SEO expert who has achieved top rankings on global search engines for numerous clients. Your expertise in creating compelling, SEO-friendly content has made you the go-to professional for businesses looking to improve their online visibility. Today, you've been tasked with creating SNS caption titles for Startupful's {{step1.input.Features}} service.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.5,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Review the 10 sns caption titles generated in Step 3 ({{step3.output}}). Select the single most effective title that best combines SEO potential, appeal to the target persona, and relevance to Startupful's {{step1.input.Features}} service.
                            Your response should only include the selected title, copied exactly as it appears in {{step3.output}}.
                            Example output:
                            [Selected Title]
                            Please provide your selection now.",
                        "background_information" => "You continue your role as a world-renowned SEO expert. Your task now is to analyze the 10 sns caption titles generated in the previous step and select the most effective one for Startupful's {{step1.input.Features}} service.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "1. Write an extended SNS caption for Startupful's {{step1.input.Features}} service, based on the caption title provided in {{step4.output}}.

                            2. The caption should:
                            2.1. Start with an attention-grabbing hook that resonates with the target audience's interests or pain points.
                            2.2. Briefly introduce Startupful and its {{step1.input.Features}} service.
                            2.3. Highlight key benefits and features that set Startupful apart from competitors.

                            3. Content structure and length:
                            3.1. Create a caption of 3,000-4,000 characters, suitable for platforms that allow longer posts.
                            3.2. Use short paragraphs or bullet points for easy readability on mobile devices.
                            3.3. Include a call-to-action (CTA) that encourages engagement.

                            4. Storytelling and hooking elements: Weave the following points into a compelling narrative:
                            4.1. Express initial concerns about data security with other services, and how Startupful's installable solution alleviates these worries.
                            4.2. Describe the surprising ease of installation, even for someone not tech-savvy (setup in just 5 minutes).
                            4.3. Compare the cost-effectiveness of Startupful (hundreds of times cheaper per 100k tokens) to other services, emphasizing the freedom to generate content liberally.
                            4.4. Highlight the attractive pricing, including up to 6 months of free service for trying it out.
                            4.5. Emphasize the ability to design and manage your own logic, resulting in easy, high-quality content generation anytime.

                            5. Tone and style:
                            5.1. Maintain a tone and style that appeals to the target persona ({{step2.output}}).
                            5.2. Use a conversational and authentic voice that builds trust and relatability.

                            6. Platform-specific considerations: Optimize the content for {{step1.input.SNS}}, considering its unique features and audience expectations.

                            7. Engagement elements:
                            7.1. Include questions or prompts throughout the caption to encourage audience interaction.
                            7.2. Mention the time-sensitive offer (6 months free) to create urgency.

                            8. Branding:
                            8.1. Incorporate Startupful's brand voice and key messaging.
                            8.2. If there's a branded hashtag, include it naturally within the content.

                            Remember, you're writing from the perspective of someone who has hands-on experience with Startupful's {{step1.input.Features}} service. Your goal is to capture the audience's attention, maintain their interest throughout the longer caption, and convey the value of the service in a way that resonates with the target persona.

                            Please generate the extended SNS caption content now, following these guidelines and optimized for the platform {{step1.input.SNS}}.",
                                                    "background_information" => "You are a professional social media content creator specializing in tech product showcases. Your task is to write compelling and engaging SNS captions for Startupful's {{step1.input.Features}} service.

                            Context
                            - Service: Startupful's {{step1.input.Features}}
                            - Blog Post Title: {{step4.output}}
                            - Target Persona: {{step2.output}}",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "content_template" => "{{step5.output}}"
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["event"])),
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            //블로그
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Blogbeitrag-Generator',
                    'en' => 'Blog Post Generate',
                    'fr' => 'Générateur d\'articles de blog',
                    'hi' => 'ब्लॉग पोस्ट जनरेटर',
                    'ja' => 'ブログ記事ジェネレーター',
                    'ko' => '블로그 포스트 생성기',
                    'pt' => 'Gerador de Posts de Blog',
                    'th' => 'เครื่องมือสร้างบทความบล็อก',
                    'tl' => 'Tagabuo ng Post sa Blog',
                    'zh' => '博客文章生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Titel, Gliederung, Inhaltsgenerierung nach Abschnitten und Integration',
                    'en' => 'keyword > title, outline, sectioned content generation, and integration',
                    'fr' => 'mot-clé > titre, plan, génération de contenu par sections et intégration',
                    'hi' => 'कीवर्ड > शीर्षक, रूपरेखा, खंडित सामग्री निर्माण और एकीकरण',
                    'ja' => 'キーワード > タイトル、アウトライン、セクション別コンテンツ生成、統合',
                    'ko' => '키워드 > 제목, 개요, 섹션별 콘텐츠 생성 및 통합',
                    'pt' => 'palavra-chave > título, estrutura, geração de conteúdo por seções e integração',
                    'th' => 'คำสำคัญ > ชื่อเรื่อง, โครงร่าง, การสร้างเนื้อหาแบ่งตามส่วน และการผสมผสาน',
                    'tl' => 'keyword > pamagat, balangkas, pagbuo ng nilalaman ayon sa seksiyon, at pagsasama',
                    'zh' => '关键词 > 标题、大纲、分节内容生成和整合',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Hauptschlüsselwort für den Blogbeitrag ein',
                                    'en' => 'Enter the main keyword for the blog post',
                                    'fr' => 'Saisissez le mot-clé principal de l\'article de blog',
                                    'hi' => 'ब्लॉग पोस्ट के लिए मुख्य कीवर्ड दर्ज करें',
                                    'ja' => 'ブログ記事のメインキーワードを入力してください',
                                    'ko' => '블로그 포스트의 주요 키워드를 입력하세요',
                                    'pt' => 'Digite a palavra-chave principal para o post do blog',
                                    'th' => 'ป้อนคำสำคัญหลักสำหรับบทความบล็อก',
                                    'tl' => 'Ilagay ang pangunahing keyword para sa post sa blog',
                                    'zh' => '输入博客文章的主要关键词',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Generate 10 blog post titles based on the keyword '{{step1.input.keyword}}'. These titles should focus on the main topic, relevance to the target audience, and SEO optimization. Your titles should:\n\n- Incorporate the keyword '{{step1.input.keyword}}' naturally.\n- Be engaging, relevant, and suitable for search engine ranking.\n- Highlight potential benefits or key features related to the keyword.\n- Use attention-grabbing words or phrases to increase click-through rates (CTR).\n\nGuidelines:\n\n- Keep titles concise and descriptive (aim for 50-60 characters).\n- Where appropriate, use numbers, questions, or actionable phrases to increase engagement.\n- Ensure titles are informative and reflect the content's value proposition.\n\nPlease provide a numbered list of 10 SEO-optimized titles.",
                        "background_information" => "You are an SEO expert focused on creating blog post titles optimized for search engine ranking and audience engagement. These titles will help drive traffic based on the keyword '{{step1.input.keyword}}'.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "From the 10 titles generated in {{step2.output}}, select the most suitable one for our blog post. Return only the selected title, without any additional explanation or context.",
                         "background_information" => "-",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.5,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the selected title '{{step3.output}}', create a structured table of contents for the blog post. The table of contents should:\n\n1. Include an introduction section.\n2. Have exactly 4 main sections, each with 2-3 subsections.\n3. Include a conclusion section.\n4. Use clear, descriptive titles for each section and subsection.\n5. Incorporate the keyword '{{step1.input.keyword}}' naturally where appropriate.\n\nPresent the table of contents in a structured, numbered format.",
                        "background_information" => "You are a content strategist creating a detailed table of contents for an SEO-optimized blog post on the topic of '{{step1.input.keyword}}'.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the content for the introduction section of the blog post, based on the table of contents created in Step 4. The introduction should:\n\n1. Start with a strong hook related to '{{step1.input.keyword}}'.\n2. Provide context and explain the importance of the topic.\n3. Briefly outline what the reader will learn from the post.\n4. Naturally incorporate the keyword '{{step1.input.keyword}}'.\n5. Be engaging and set the tone for the rest of the article.\n\nAim for 2-3 paragraphs, totaling about 150-200 words.",
                        "background_information" => "You are a skilled content writer creating an engaging introduction for an SEO-optimized blog post on '{{step1.input.keyword}}'.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the content for the first main section of the blog post, based on the table of contents created in Step 4. This section should:\n\n1. Expand on the first main point or subtopic related to '{{step1.input.keyword}}'.\n2. Include relevant facts, statistics, or examples to support your points.\n3. Use subheadings for better structure and readability.\n4. Incorporate the keyword naturally throughout the content.\n5. End with a smooth transition to the next section.\n\nAim for 300-400 words, divided into 3-4 paragraphs with appropriate subheadings.",
                        "background_information" => "You are an expert content creator writing an informative and engaging section for an SEO-optimized blog post on '{{step1.input.keyword}}'.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 7,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the content for the second main section of the blog post, based on the table of contents created in Step 4. This section should:\n\n1. Explore the second main point or subtopic related to '{{step1.input.keyword}}'.\n2. Provide in-depth analysis or practical advice relevant to the reader.\n3. Use bullet points or numbered lists where appropriate for better readability.\n4. Include the keyword and related terms naturally in the content and subheadings.\n5. Ensure a logical flow from the previous section and to the next.\n\nAim for 300-400 words, structured with clear subheadings and a mix of paragraph styles for engagement.",
                        "background_information" => "You are a knowledgeable content writer creating an insightful and valuable section for an SEO-optimized blog post about '{{step1.input.keyword}}'.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 8,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the content for the third main section of the blog post, based on the table of contents created in Step 4. This section should:\n\n1. Address the third main point or subtopic related to '{{step1.input.keyword}}'.\n2. Offer unique insights, tips, or strategies that add value for the reader.\n3. Use engaging examples or case studies to illustrate your points.\n4. Incorporate the keyword and semantically related terms naturally.\n5. Maintain reader interest with varied sentence structures and paragraph lengths.\n\nAim for 300-400 words, with clear subheadings and a compelling narrative that builds on previous sections.",
                        "background_information" => "You are a creative content expert crafting an engaging and informative section for an SEO-optimized blog post focused on '{{step1.input.keyword}}'.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 9,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the content for the fourth main section of the blog post, based on the table of contents created in Step 4. This section should:\n\n1. Cover the fourth and final main point or subtopic related to '{{step1.input.keyword}}'.\n2. Provide a comprehensive analysis, practical applications, or future trends related to the topic.\n3. Use relevant examples, data, or expert opinions to support your arguments.\n4. Naturally incorporate the keyword and related terms in the content and subheadings.\n5. Ensure a smooth flow from previous sections and lead into the conclusion.\n\nAim for 300-400 words, with clear subheadings and a mix of informative and engaging content that completes the main body of the article.",
                        "background_information" => "You are an experienced content strategist creating the final main section for an SEO-optimized blog post about '{{step1.input.keyword}}', aiming to provide valuable insights and a strong finish to the article's body.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 10,
                        "uuid" => $generateUuid(),
                        "content_template" => "{{step4.output}}\n\n{{step5.output}}\n\n{{step6.output}}\n\n{{step7.output}}\n\n{{step8.output}}\n\n{{step9.output}}"
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["blog", "SEO"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Produktbewertung Generator',
                    'en' => 'Product Review Generate',
                    'fr' => 'Générateur d\'avis sur les produits',
                    'hi' => 'उत्पाद समीक्षा जनरेटर',
                    'ja' => '製品レビュー生成',
                    'ko' => '제품 리뷰 생성기',
                    'pt' => 'Gerador de Avaliação de Produto',
                    'th' => 'เครื่องมือสร้างรีวิวสินค้า',
                    'tl' => 'Tagabuo ng Pagsusuri ng Produkto',
                    'zh' => '产品评论生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Titel, Gliederung, Generierung von Bewertungsinhalten nach Abschnitten und Integration',
                    'en' => 'keyword > title, outline, sectioned review content generation, and integration',
                    'fr' => 'mot-clé > titre, plan, génération de contenu d\'avis par sections et intégration',
                    'hi' => 'कीवर्ड > शीर्षक, रूपरेखा, खंडित समीक्षा सामग्री निर्माण और एकीकरण',
                    'ja' => 'キーワード > タイトル、アウトライン、セクション別レビューコンテンツ生成、統合',
                    'ko' => '키워드 > 제목, 개요, 섹션별 리뷰 콘텐츠 생성 및 통합',
                    'pt' => 'palavra-chave > título, estrutura, geração de conteúdo de avaliação por seções e integração',
                    'th' => 'คำสำคัญ > ชื่อเรื่อง, โครงร่าง, การสร้างเนื้อหารีวิวแบ่งตามส่วน และการผสมผสาน',
                    'tl' => 'keyword > pamagat, balangkas, pagbuo ng nilalaman ng pagsusuri ayon sa seksiyon, at pagsasama',
                    'zh' => '关键词 > 标题、大纲、分节评论内容生成和整合',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "product_keyword",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Hauptschlüsselwort oder den Produktnamen für die Bewertung ein',
                                    'en' => 'Enter the main keyword or product name for the review',
                                    'fr' => 'Saisissez le mot-clé principal ou le nom du produit pour l\'avis',
                                    'hi' => 'समीक्षा के लिए मुख्य कीवर्ड या उत्पाद का नाम दर्ज करें',
                                    'ja' => 'レビューのメインキーワードまたは製品名を入力してください',
                                    'ko' => '리뷰를 위한 주요 키워드 또는 제품명을 입력하세요',
                                    'pt' => 'Digite a palavra-chave principal ou o nome do produto para a avaliação',
                                    'th' => 'ป้อนคำสำคัญหลักหรือชื่อผลิตภัณฑ์สำหรับรีวิว',
                                    'tl' => 'Ilagay ang pangunahing keyword o pangalan ng produkto para sa pagsusuri',
                                    'zh' => '输入评论的主要关键词或产品名称',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Generate 10 review titles based on the product '{{step1.input.product_keyword}}'. These titles should focus on the product review, user experience, and key features. Your titles should:\n\n- Incorporate the product name '{{step1.input.product_keyword}}' naturally.\n- Be engaging and reflect a genuine user review perspective.\n- Highlight potential benefits, key features, or user concerns.\n- Use attention-grabbing words or phrases to increase reader interest.\n\nGuidelines:\n\n- Keep titles concise and descriptive (aim for 50-60 characters).\n- Use phrases like 'My Experience with', 'X Months/Years with', 'Honest Review', etc.\n- Ensure titles are informative and reflect the content's value to potential buyers.\n\nPlease provide a numbered list of 10 review-oriented titles.",
                        "background_information" => "You are an expert product reviewer with over 10 years of experience in the tech industry. You have extensive knowledge of '{{step1.input.product_keyword}}' and its market position. Your expertise includes:\n\n1. In-depth understanding of the product's technical specifications\n2. Familiarity with user experience design principles\n3. Knowledge of current market trends and consumer preferences\n4. Ability to craft engaging and SEO-optimized titles\n5. Understanding of what drives user engagement in product reviews",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "From the 10 titles generated in {{step2.output}}, select the most suitable one for our review content. Return only the selected title, without any additional explanation or context.",
                        "background_information" => "-",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.5,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the selected title '{{step3.output}}', create a structured table of contents for the review. The table of contents should follow this structure:\n\n1. Introduction (Including motivation for purchase, initial concerns, etc.)\n2. Overall Review\n3. Product Specifications Comparison\n4. Pros of the Product\n5. Cons of the Product\n6. Conclusion (Summary, recommendations, and final thoughts)\n\nEach main section should have 2-3 subsections. Use clear, descriptive titles for each section and subsection, incorporating the product name '{{step1.input.product_keyword}}' naturally where appropriate. Present the table of contents in a structured, numbered format.",
                        "background_information" => "You are a senior content strategist specializing in product reviews. Your expertise includes:\n\n1. Creating comprehensive and logically structured review outlines\n2. Understanding the key aspects of '{{step1.input.product_keyword}}' that readers are most interested in\n3. Balancing technical details with user experience insights\n4. Crafting review structures that enhance readability and SEO performance\n5. Anticipating reader questions and concerns to address in the review",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the introduction for the review of '{{step1.input.product_keyword}}', based on the table of contents created in Step 4. The introduction should:\n\n1. Start with a personal anecdote or situation that led to considering this product.\n2. Discuss initial concerns or doubts about the product.\n3. Briefly mention the alternatives considered.\n4. Explain why you ultimately chose this product.\n5. Set the tone for an honest, user-centric review.\n\nAim for 2-3 paragraphs, totaling about 200-250 words. Ensure the content feels personal and relatable.",
                        "background_information" => "You are a professional tech reviewer with a background in consumer psychology. Your expertise includes:\n\n1. Crafting engaging introductions that immediately capture reader interest\n2. Understanding the decision-making process of tech consumers\n3. Articulating common concerns and considerations in the '{{step1.input.product_keyword}}' category\n4. Balancing personal anecdotes with broader market context\n5. Setting appropriate expectations for the depth and tone of the review",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the content for the 'Overall Review' section of the '{{step1.input.product_keyword}}' review, based on the table of contents created in Step 4. This section should:\n\n1. Provide a high-level overview of your experience with the product.\n2. Discuss your initial impressions and how they changed over time.\n3. Highlight the most impactful features or aspects of the product.\n4. Mention any surprises (positive or negative) you encountered.\n5. Give a general sense of whether the product met your expectations.\n\nAim for 300-400 words, divided into 3-4 paragraphs with appropriate subheadings. Use a conversational tone and include personal experiences.",
                        "background_information" => "You are a seasoned product tester with extensive experience in the '{{step1.input.product_keyword}}' category. Your expertise includes:\n\n1. Evaluating products over extended periods to provide comprehensive insights\n2. Identifying key features that significantly impact user experience\n3. Articulating complex product experiences in an accessible, engaging manner\n4. Balancing objectivity with personal insights to provide valuable user perspectives\n5. Understanding the evolving expectations and needs of users in this product category",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 7,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the 'Product Specifications Comparison' section for the '{{step1.input.product_keyword}}' review, based on the table of contents in Step 4. This section should:\n\n1. List the key specifications of '{{step1.input.product_keyword}}'.\n2. Compare these specifications with those of 1-2 competing products.\n3. Explain why these specifications made you choose '{{step1.input.product_keyword}}'.\n4. Discuss how the specifications translate to real-world performance.\n5. Use a table or bullet points for easy comparison where appropriate.\n\nAim for 300-400 words, with clear subheadings and a mix of technical details and user-friendly explanations. Focus on specifications that matter most to potential buyers.",
                        "background_information" => "You are a technical analyst specializing in '{{step1.input.product_keyword}}' and similar products. Your expertise includes:\n\n1. In-depth knowledge of technical specifications and their real-world implications\n2. Ability to compare and contrast features across multiple products in the category\n3. Understanding which specifications are most relevant to user needs and preferences\n4. Skill in translating technical jargon into user-friendly language\n5. Experience in creating clear, informative comparison tables and charts",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 8,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the 'Pros of the Product' section for the '{{step1.input.product_keyword}}' review, based on the table of contents in Step 4. This section should:\n\n1. List and explain 3-5 major advantages of the product.\n2. Provide specific examples or scenarios where these pros shine.\n3. Compare these advantages to competing products where relevant.\n4. Discuss how these pros have impacted your daily use or overall satisfaction.\n5. Use subheadings for each major pro for easy readability.\n\nAim for 300-400 words, with a positive but honest tone. Use personal anecdotes to illustrate the benefits where possible.",
                        "background_information" => "You are a product evaluation expert with a focus on user experience design. Your expertise includes:\n\n1. Identifying and articulating key strengths of '{{step1.input.product_keyword}}'\n2. Understanding how product features translate into user benefits\n3. Contextualizing advantages within the broader market landscape\n4. Providing concrete examples and use cases to illustrate product strengths\n5. Balancing enthusiasm for positive features with overall objectivity",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 9,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the 'Cons of the Product' section for the '{{step1.input.product_keyword}}' review, based on the table of contents in Step 4. This section should:\n\n1. Identify and explain 3-5 drawbacks or limitations of the product.\n2. Provide context for each con - how significant is it in day-to-day use?\n3. Suggest potential workarounds or solutions for each con, if applicable.\n4. Discuss whether these cons are deal-breakers or minor inconveniences.\n5. Use subheadings for each major con for easy readability.\n\nAim for 300-400 words, maintaining an objective tone. Be honest about the product's shortcomings while keeping the overall review balanced.",
                        "background_information" => "You are a critical analysis expert in consumer technology. Your expertise includes:\n\n1. Objectively identifying and assessing product limitations and drawbacks\n2. Understanding the relative importance of various features to different user groups\n3. Providing context for how shortcomings affect overall user experience\n4. Offering practical solutions or workarounds for common issues\n5. Maintaining a balanced perspective when discussing product weaknesses",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 10,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the conclusion for the '{{step1.input.product_keyword}}' review, based on the table of contents in Step 4. This section should:\n\n1. Summarize the key points from your review, touching on overall impressions, pros, and cons.\n2. Provide your final verdict on the product - would you recommend it? If so, to whom?\n3. Mention any caveats or conditions for your recommendation.\n4. Share any additional thoughts or long-term considerations for potential buyers.\n5. End with a strong, clear statement that encapsulates your experience with the product.\n\nAim for 200-250 words, with a tone that feels like you're giving advice to a friend. Be honest, balanced, and helpful to potential buyers in their decision-making process.",
                        "background_information" => "You are a senior product reviewer with years of experience in consumer advice. Your expertise includes:\n\n1. Synthesizing complex product information into clear, actionable conclusions\n2. Understanding the needs and preferences of different user segments\n3. Providing balanced, well-reasoned recommendations\n4. Anticipating long-term product performance and user satisfaction\n5. Crafting compelling closing statements that resonate with readers and aid in decision-making",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 11,
                        "uuid" => $generateUuid(),
                        "content_template" => "{{step4.output}}\n\n{{step5.output}}\n\n{{step6.output}}\n\n{{step7.output}}\n\n{{step8.output}}\n\n{{step9.output}}\n\n{{step10.output}}"
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["blog", "review", "product"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Nachrichtenartikel Generator',
                    'en' => 'News Article Generate',
                    'fr' => 'Générateur d\'articles de presse',
                    'hi' => 'समाचार लेख जनरेटर',
                    'ja' => 'ニュース記事生成',
                    'ko' => '뉴스 기사 생성기',
                    'pt' => 'Gerador de Artigos de Notícias',
                    'th' => 'เครื่องมือสร้างบทความข่าว',
                    'tl' => 'Tagabuo ng Artikulo ng Balita',
                    'zh' => '新闻文章生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Titel, ausgewählter Titel, Einleitung, Hauptteil, Schlussfolgerung und Inhaltsintegration für professionelle Nachrichtenartikel',
                    'en' => 'keyword > titles, selected title, introduction, main body, conclusion, and content integration for professional news articles',
                    'fr' => 'mot-clé > titres, titre sélectionné, introduction, corps principal, conclusion et intégration du contenu pour des articles de presse professionnels',
                    'hi' => 'कीवर्ड > शीर्षक, चयनित शीर्षक, परिचय, मुख्य भाग, निष्कर्ष और पेशेवर समाचार लेखों के लिए सामग्री एकीकरण',
                    'ja' => 'キーワード > タイトル、選択されたタイトル、導入、本文、結論、プロのニュース記事のためのコンテンツ統合',
                    'ko' => '키워드 > 제목, 선택된 제목, 서론, 본문, 결론 및 전문 뉴스 기사를 위한 콘텐츠 통합',
                    'pt' => 'palavra-chave > títulos, título selecionado, introdução, corpo principal, conclusão e integração de conteúdo para artigos de notícias profissionais',
                    'th' => 'คำสำคัญ > ชื่อเรื่อง, ชื่อเรื่องที่เลือก, บทนำ, เนื้อหาหลัก, บทสรุป และการผสมผสานเนื้อหาสำหรับบทความข่าวมืออาชีพ',
                    'tl' => 'keyword > mga pamagat, napiling pamagat, panimula, pangunahing bahagi, konklusyon, at pagsasama ng nilalaman para sa mga propesyonal na artikulo ng balita',
                    'zh' => '关键词 > 标题、选定标题、引言、正文、结论以及专业新闻文章的内容整合',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "article_topic",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Hauptthema oder Schlüsselwort für den Artikel ein',
                                    'en' => 'Enter the main topic or keyword for the article',
                                    'fr' => 'Saisissez le sujet principal ou le mot-clé de l\'article',
                                    'hi' => 'लेख के लिए मुख्य विषय या कीवर्ड दर्ज करें',
                                    'ja' => '記事のメインテーマまたはキーワードを入力してください',
                                    'ko' => '기사의 주요 주제 또는 키워드를 입력하세요',
                                    'pt' => 'Digite o tópico principal ou palavra-chave para o artigo',
                                    'th' => 'ป้อนหัวข้อหลักหรือคำสำคัญสำหรับบทความ',
                                    'tl' => 'Ilagay ang pangunahing paksa o keyword para sa artikulo',
                                    'zh' => '输入文章的主要主题或关键词',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Generate 10 professional news article titles based on the topic '{{step1.input.article_topic}}'. These titles should:\n\n1. Be clear, concise, and formal\n2. Incorporate the main topic '{{step1.input.article_topic}}' effectively\n3. Reflect key facts or developments accurately\n4. Use active language and proper journalistic style\n5. Be brief yet comprehensive (aim for 60-100 characters)\n\nProvide a numbered list of 10 news article titles.",
                        "background_information" => "You are a veteran journalist with extensive experience in professional news writing. Your expertise includes:\n\n1. Crafting headlines that adhere to formal journalistic standards\n2. Focusing on key facts and developments without sensationalism\n3. Using precise language to convey information efficiently\n4. Applying strict journalistic best practices for headline writing\n5. Adapting style to suit different news categories while maintaining professionalism\n\nFor this task, you are writing about: '{{step1.input.article_topic}}'.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "From the 10 titles generated in {{step2.output}}, select the most suitable one for our professional news article. Consider factors such as clarity, relevance, factual accuracy, and adherence to formal journalistic style. Return only the selected title, without any additional explanation or context.",
                        "background_information" => "-",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.5,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the introduction for the news article about '{{step1.input.article_topic}}'. The introduction should:\n\n1. Present the most critical information first, following the inverted pyramid structure\n2. Provide essential facts and context succinctly\n3. Outline key points of the story clearly\n4. Use formal, precise language appropriate for professional news articles\n5. Be informative and direct (aim for 2-3 short paragraphs, about 150-200 words)\n\nEnsure you use formal endings for sentences such as '이다', '한다', '밝혔다' instead of '입니다', '합니다'. Focus on presenting facts objectively and avoid any subjective or colloquial expressions. Do not include any headings, subheadings, or mention of the article title.",
                        "background_information" => "You are a seasoned news reporter for a major national publication. Your expertise includes:\n\n1. Presenting key information with precision and formal language\n2. Adhering strictly to factual, objective reporting standards\n3. Applying the inverted pyramid structure effectively\n4. Using clear, direct, and formal language consistently\n5. Adapting writing style to prioritize information delivery in a professional manner\n\nFor this article, you are writing about: '{{step1.input.article_topic}}'. Remember to maintain a formal, journalistic tone throughout.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the main body of the news article about '{{step1.input.article_topic}}'. The main body should:\n\n1. Expand on the key points introduced in the introduction with detailed information\n2. Present facts, quotes, and evidence clearly and objectively\n3. Provide necessary background information and context concisely\n4. Address all relevant aspects of the story comprehensively\n5. Maintain a formal, objective tone focusing on information delivery\n\nAim for 4-5 concise paragraphs (about 400-600 words). Use formal sentence structures and endings (e.g., '이다', '한다', '계획이라 밝혔다'). Include relevant quotes or data points to support the story, ensuring they are properly attributed (e.g., 'OOO 대표는 \"...\"라고 말했다.'). Avoid any colloquial expressions or subjective commentary.",
                        "background_information" => "You are an award-winning journalist known for clear, factual reporting. Your expertise includes:\n\n1. Presenting complex information in a clear, accessible, yet formal manner\n2. Structuring articles to prioritize key information while maintaining depth\n3. Balancing comprehensive detail with concise expression\n4. Explaining relevant context efficiently and objectively\n5. Adhering to the highest standards of journalistic integrity and formal writing\n\nFor this article, you are writing about: '{{step1.input.article_topic}}'. Ensure all content maintains a professional, journalistic tone.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "prompt" => "Write the conclusion for the news article about '{{step1.input.article_topic}}'. The conclusion should:\n\n1. Summarize the key points concisely\n2. Provide any necessary final context or implications\n3. Mention any future developments or next steps, if relevant\n4. End with a relevant, factual statement that encapsulates the article's significance\n5. Be concise and informative (aim for 1-2 short paragraphs, about 100-150 words)\n\nEnsure the conclusion reinforces the main facts of the story without introducing new information. Maintain a formal, objective tone and focus on information. Use formal sentence endings (e.g., '이다', '전망이다', '계획이다'). Do not include any headings, subheadings, or mention of the article title.",
                        "background_information" => "You are a respected news editor with decades of experience. Your expertise includes:\n\n1. Summarizing complex information effectively and formally\n2. Ensuring all crucial points are addressed comprehensively\n3. Providing clear, factual closures to news stories\n4. Maintaining strict objectivity and formality throughout the article\n5. Focusing on relevant information and implications without speculation\n\nFor this article, you are writing about: '{{step1.input.article_topic}}'. Ensure the conclusion maintains the professional tone established throughout the article.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 7,
                        "uuid" => $generateUuid(),
                        "content_template" => "# {{step3.output}}\n\n{{step4.output}}\n\n{{step5.output}}\n\n{{step6.output}}"
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["article", "news"])),
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'name' => $this->getLocalizedText([
                    'de' => 'SNS-Bildunterschrift Generator',
                    'en' => 'SNS Caption Generate',
                    'fr' => 'Générateur de légendes pour réseaux sociaux',
                    'hi' => 'सोशल मीडिया कैप्शन जनरेटर',
                    'ja' => 'SNSキャプション生成',
                    'ko' => 'SNS 캡션 생성기',
                    'pt' => 'Gerador de Legendas para Redes Sociais',
                    'th' => 'เครื่องมือสร้างคำบรรยายโซเชียลมีเดีย',
                    'tl' => 'Tagabuo ng Caption sa SNS',
                    'zh' => '社交媒体说明文生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Generieren Sie ansprechende Bildunterschriften für verschiedene Social-Media-Plattformen basierend auf einem bestimmten Thema',
                    'en' => 'Generate engaging captions for various social media platforms based on a given topic',
                    'fr' => 'Générez des légendes attrayantes pour diverses plateformes de médias sociaux en fonction d\'un sujet donné',
                    'hi' => 'दिए गए विषय के आधार पर विभिन्न सोशल मीडिया प्लेटफ़ॉर्म के लिए आकर्षक कैप्शन तैयार करें',
                    'ja' => '与えられたトピックに基づいて、様々なソーシャルメディアプラットフォーム用の魅力的なキャプションを生成します',
                    'ko' => '주어진 주제를 바탕으로 다양한 소셜 미디어 플랫폼을 위한 매력적인 캡션을 생성합니다',
                    'pt' => 'Gere legendas envolventes para várias plataformas de mídia social com base em um tópico específico',
                    'th' => 'สร้างคำบรรยายที่น่าสนใจสำหรับแพลตฟอร์มโซเชียลมีเดียต่างๆ ตามหัวข้อที่กำหนด',
                    'tl' => 'Bumuo ng nakaka-engganyong mga caption para sa iba\'t ibang platform ng social media batay sa isang ibinigay na paksa',
                    'zh' => '根据给定主题为各种社交媒体平台生成引人入胜的说明文',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "topic_keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Hauptthema oder Schlüsselwort für die Bildunterschrift ein',
                                    'en' => 'Enter the main topic or keyword for the caption',
                                    'fr' => 'Saisissez le sujet principal ou le mot-clé pour la légende',
                                    'hi' => 'कैप्शन के लिए मुख्य विषय या कीवर्ड दर्ज करें',
                                    'ja' => 'キャプションのメインテーマまたはキーワードを入力してください',
                                    'ko' => '캡션의 주요 주제 또는 키워드를 입력하세요',
                                    'pt' => 'Digite o tópico principal ou palavra-chave para a legenda',
                                    'th' => 'ป้อนหัวข้อหลักหรือคำสำคัญสำหรับคำบรรยาย',
                                    'tl' => 'Ilagay ang pangunahing paksa o keyword para sa caption',
                                    'zh' => '输入说明文的主要主题或关键词',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "sns",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Bitte wählen Sie das soziale Netzwerk aus, auf das Sie hochladen möchten',
                                    'en' => 'Please select the SNS you want to upload to',
                                    'fr' => 'Veuillez sélectionner le réseau social sur lequel vous souhaitez publier',
                                    'hi' => 'कृपया वह सोशल नेटवर्क चुनें जहां आप अपलोड करना चाहते हैं',
                                    'ja' => 'アップロードしたいSNSを選択してください',
                                    'ko' => '업로드하고 싶은 SNS를 선택해 주세요',
                                    'pt' => 'Por favor, selecione a rede social para a qual deseja fazer o upload',
                                    'th' => 'โปรดเลือกโซเชียลมีเดียที่คุณต้องการอัปโหลด',
                                    'tl' => 'Mangyaring piliin ang SNS na gusto mong i-upload',
                                    'zh' => '请选择您想要上传的社交网络',
                                ]),
                                "type" => "radio",
                                "options" => "LinkedIn, Facebook, Instagram, Threads, Twitter(X), Tumblr, TikTok",
                                "file_type" => null
                            ],
                            [
                                "label" => "tone",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Wählen Sie den Ton für Ihren Inhalt aus',
                                    'en' => 'Select the tone for your content',
                                    'fr' => 'Sélectionnez le ton pour votre contenu',
                                    'hi' => 'अपनी सामग्री के लिए टोन का चयन करें',
                                    'ja' => 'コンテンツのトーンを選択してください',
                                    'ko' => '콘텐츠의 톤을 선택하세요',
                                    'pt' => 'Selecione o tom para o seu conteúdo',
                                    'th' => 'เลือกโทนเสียงสำหรับเนื้อหาของคุณ',
                                    'tl' => 'Piliin ang tono para sa iyong nilalaman',
                                    'zh' => '选择您内容的语气',
                                ]),
                                "type" => "radio",
                                "options" => $this->getLocalizedText([
                                    'de' => 'Allgemein, Formal, Freundlich, Kritisch, Humorvoll, Inspirierend, Professionell, Sachlich, Kreativ, Überzeugend',
                                    'en' => 'General, Formal, Friendly, Critical, Humorous, Inspirational, Professional, Neutral, Creative, Persuasive',
                                    'fr' => 'Général, Formel, Amical, Critique, Humoristique, Inspirant, Professionnel, Neutre, Créatif, Persuasif',
                                    'hi' => 'सामान्य, औपचारिक, मैत्रीपूर्ण, आलोचनात्मक, हास्यपूर्ण, प्रेरणादायक, पेशेवर, तटस्थ, रचनात्मक, प्रेरक',
                                    'ja' => '一般, フォーマル, フレンドリー, 批判的, ユーモラス, インスピレーショナル, プロフェッショナル, 中立的, クリエイティブ, 説得力のある',
                                    'ko' => '일반, 공식적인, 친근한, 비판적인, 유머러스한, 영감을 주는, 전문적인, 중립적인, 창의적인, 설득력 있는',
                                    'pt' => 'Geral, Formal, Amigável, Crítico, Humorístico, Inspirador, Profissional, Neutro, Criativo, Persuasivo',
                                    'th' => 'ทั่วไป, เป็นทางการ, เป็นมิตร, วิพากษ์วิจารณ์, ตลกขบขัน, สร้างแรงบันดาลใจ, เป็นมืออาชีพ, เป็นกลาง, สร้างสรรค์, โน้มน้าวใจ',
                                    'tl' => 'Pangkalahatan, Pormal, Magiliw, Kritikal, Nakakatawa, Nakaka-inspire, Propesyonal, Walang kinikilingan, Malikhain, Mapanghikayat',
                                    'zh' => '通用, 正式, 友好, 批判, 幽默, 鼓舞人心, 专业, 中性, 创意, 说服力',
                                ]),
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Generate 10 engaging opening sentences for an SNS post about '{{step1.input.topic_keyword}}' suitable for {{step1.input.sns}}. These sentences should:
            
            1. Capture attention immediately
            2. Create curiosity or intrigue about the topic
            3. Be tailored to the tone and style of {{step1.input.sns}}
            4. Reflect the chosen tone: {{step1.input.tone}}
            5. Be concise yet impactful (aim for 10-15 words each)
            
            Provide a numbered list of 10 opening sentences.",
                        "background_information" => "You are a social media expert with a keen understanding of platform-specific content strategies. Your expertise includes:
            
            1. Crafting attention-grabbing openings for various social media platforms
            2. Understanding the unique audience and content style of different SNS
            3. Adapting tone and language to suit specific platforms and chosen styles
            4. Creating content that encourages engagement and further reading
            5. Balancing informative and intriguing elements in short-form content
            
            For this task, you are creating content for {{step1.input.sns}} about: '{{step1.input.topic_keyword}}' with a {{step1.input.tone}} tone.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "From the 10 opening sentences generated in {{step2.output}}, select the most suitable one for our {{step1.input.sns}} post about '{{step1.input.topic_keyword}}' with a {{step1.input.tone}} tone. Consider factors such as engagement potential, relevance, and alignment with the platform's style. Return only the selected opening sentence, without any additional explanation or context.",
                        "background_information" => "-",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.5,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create a compelling SNS caption for {{step1.input.sns}} about '{{step1.input.topic_keyword}}' using the opening sentence: '{{step3.output}}'. The caption should:
            
            1. Expand on the opening sentence with relevant, engaging content
            2. Maintain a {{step1.input.tone}} tone throughout
            3. Include appropriate hashtags, mentions, or calls-to-action for {{step1.input.sns}}
            4. Be formatted appropriately for {{step1.input.sns}} (e.g., use of line breaks, emojis if suitable)
            5. Provide value to the reader (information, entertainment, or inspiration)
            
            Tailor the content to the specific characteristics of {{step1.input.sns}}:
            - LinkedIn: Professional, industry insights, career-related
            - Facebook: Mix of personal and informative, community-oriented
            - Instagram: Visual-first, lifestyle-focused, creative
            - Threads: Conversational, text-based discussions, current topics
            - Twitter(X): Concise, newsy, trending topics
            - Tumblr: Long-form, creative, niche interests
            - TikTok: Trendy, entertaining, youth-oriented
            
            Aim for 1500-2000 characters, structured appropriately for the chosen platform.",
                        "background_information" => "You are a versatile social media content creator with expertise in crafting platform-specific posts. Your skills include:
            
            1. Adapting content style and tone to suit different social media platforms
            2. Balancing informative and engaging elements in social media captions
            3. Understanding platform-specific features and best practices
            4. Crafting content that encourages user engagement and sharing
            5. Incorporating relevant trends and hashtags effectively
            
            For this task, you are creating a {{step1.input.tone}} post for {{step1.input.sns}} about: '{{step1.input.topic_keyword}}'.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["sns", "social_media", "caption"])),
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
              'name' => $this->getLocalizedText([
                    'de' => 'E-Mail-Antwort-Generator',
                    'en' => 'Email Response Generator',
                    'fr' => 'Générateur de réponses par e-mail',
                    'hi' => 'ईमेल प्रतिक्रिया जनरेटर',
                    'ja' => 'メール返信生成ツール',
                    'ko' => '이메일 응답 생성기',
                    'pt' => 'Gerador de Respostas de E-mail',
                    'th' => 'เครื่องมือสร้างการตอบกลับอีเมล',
                    'tl' => 'Tagabuo ng Tugon sa Email',
                    'zh' => '电子邮件回复生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'E-Mail-Kontext-Eingabe > Strategische Analyse > Maßgeschneiderte E-Mail-Antwort-Generierung',
                    'en' => 'Email context input > Strategic analysis > Tailored email response generation',
                    'fr' => 'Saisie du contexte de l\'e-mail > Analyse stratégique > Génération de réponse e-mail personnalisée',
                    'hi' => 'ईमेल संदर्भ इनपुट > रणनीतिक विश्लेषण > अनुकूलित ईमेल प्रतिक्रिया उत्पादन',
                    'ja' => 'メールコンテキスト入力 > 戦略的分析 > カスタマイズされたメール返信生成',
                    'ko' => '이메일 맥락 입력 > 전략적 분석 > 맞춤형 이메일 응답 생성',
                    'pt' => 'Entrada de contexto do e-mail > Análise estratégica > Geração de resposta de e-mail personalizada',
                    'th' => 'ป้อนบริบทอีเมล > การวิเคราะห์เชิงกลยุทธ์ > การสร้างการตอบกลับอีเมลที่ปรับแต่ง',
                    'tl' => 'Input ng konteksto ng email > Estratehikong pagsusuri > Paggawa ng naka-customize na tugon sa email',
                    'zh' => '电子邮件上下文输入 > 策略分析 > 定制电子邮件回复生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "sender",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Name der Person oder des Dienstes, der die Antwort sendet',
                                    'en' => 'Name of the person or service sending the reply',
                                    'fr' => 'Nom de la personne ou du service qui envoie la réponse',
                                    'hi' => 'उत्तर भेजने वाले व्यक्ति या सेवा का नाम',
                                    'ja' => '返信を送信する人物またはサービスの名前',
                                    'ko' => '답장을 보내는 사람 또는 서비스의 이름',
                                    'pt' => 'Nome da pessoa ou serviço que está enviando a resposta',
                                    'th' => 'ชื่อของบุคคลหรือบริการที่ส่งการตอบกลับ',
                                    'tl' => 'Pangalan ng tao o serbisyo na nagpapadala ng tugon',
                                    'zh' => '发送回复的人员或服务的名称',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "recipient",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Name der Person oder des Dienstes, der die Antwort erhält',
                                    'en' => 'Name of the person or service receiving the reply',
                                    'fr' => 'Nom de la personne ou du service qui reçoit la réponse',
                                    'hi' => 'उत्तर प्राप्त करने वाले व्यक्ति या सेवा का नाम',
                                    'ja' => '返信を受け取る人物またはサービスの名前',
                                    'ko' => '답장을 받는 사람 또는 서비스의 이름',
                                    'pt' => 'Nome da pessoa ou serviço que está recebendo a resposta',
                                    'th' => 'ชื่อของบุคคลหรือบริการที่รับการตอบกลับ',
                                    'tl' => 'Pangalan ng tao o serbisyo na tumatanggap ng tugon',
                                    'zh' => '接收回复的人员或服务的名称',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "email_content",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Inhalt der ursprünglichen E-Mail, auf die geantwortet werden soll',
                                    'en' => 'Content of the original email to be replied to',
                                    'fr' => 'Contenu de l\'e-mail original auquel il faut répondre',
                                    'hi' => 'मूल ईमेल का विषय जिसका जवाब दिया जाना है',
                                    'ja' => '返信する元のメールの内容',
                                    'ko' => '답장할 원본 이메일의 내용',
                                    'pt' => 'Conteúdo do e-mail original a ser respondido',
                                    'th' => 'เนื้อหาของอีเมลต้นฉบับที่จะตอบกลับ',
                                    'tl' => 'Nilalaman ng orihinal na email na sasagutin',
                                    'zh' => '需要回复的原始电子邮件内容',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Analyze the following email content and context:\n\nSender: {{step1.input.sender}}\nRecipient: {{step1.input.recipient}}\nEmail Content:\n{{step1.input.email_content}}\n\nBased on this information, develop a strategic approach for responding to this email. Consider the following aspects:\n\n1. Tone and formality level appropriate for the sender-recipient relationship\n2. Key points that need to be addressed in the response\n3. Any additional information or context that might be needed\n4. Potential concerns or sensitivities to be aware of\n5. Overall goal of the response (e.g., to inform, to resolve an issue, to maintain a relationship)\n\nProvide a detailed strategy outlining how to craft an effective response. Include recommendations for:\n\n- Opening greeting\n- Main content structure\n- Closing remarks\n- Any specific phrases or points to include or avoid\n\nFormat your strategy as a structured list with main points and sub-points.",
                        "background_information" => "You are an expert communication strategist with extensive experience in business correspondence and relationship management. Your expertise lies in analyzing communication contexts and developing effective response strategies that achieve specific goals while maintaining appropriate tone and professionalism.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the following information and strategy, generate a tailored email response:\n\nSender: {{step1.input.sender}}\nRecipient: {{step1.input.recipient}}\nOriginal Email Content:\n{{step1.input.email_content}}\n\nResponse Strategy:\n{{step2.output}}\n\nCraft a complete email response that adheres to the outlined strategy. Ensure that the response:\n\n1. Uses an appropriate greeting and closing\n2. Addresses all key points identified in the strategy\n3. Maintains the recommended tone and formality level\n4. Is clear, concise, and professional\n5. Achieves the overall goal identified in the strategy\n\nFormat the email response with appropriate line breaks and paragraphing for readability.",
                        "background_information" => "You are a skilled professional writer with expertise in crafting effective business correspondence. Your writing is clear, concise, and tailored to specific contexts and audiences. You excel at translating communication strategies into polished, impactful email responses.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["email", "customer_service"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 요약
            [
                'name' => $this->getLocalizedText([
                    'de' => 'PDF-Dokument-Zusammenfasser',
                    'en' => 'PDF Document Summarizer',
                    'fr' => 'Résumeur de documents PDF',
                    'hi' => 'पीडीएफ दस्तावेज़ सारांशक',
                    'ja' => 'PDF文書要約ツール',
                    'ko' => 'PDF 문서 요약기',
                    'pt' => 'Resumidor de Documentos PDF',
                    'th' => 'เครื่องมือสรุปเอกสาร PDF',
                    'tl' => 'Tagabuod ng Dokumentong PDF',
                    'zh' => 'PDF文档摘要生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'PDF-Dokument-Eingabe > Umfassende Zusammenfassungsgenerierung mit Kernpunkten und Erklärungen',
                    'en' => 'PDF document input > Comprehensive summary generation with key points and explanations',
                    'fr' => 'Saisie de document PDF > Génération de résumé complet avec points clés et explications',
                    'hi' => 'पीडीएफ दस्तावेज़ इनपुट > मुख्य बिंदुओं और व्याख्याओं के साथ व्यापक सारांश उत्पादन',
                    'ja' => 'PDF文書入力 > 要点と説明を含む包括的な要約生成',
                    'ko' => 'PDF 문서 입력 > 주요 포인트와 설명이 포함된 종합적인 요약 생성',
                    'pt' => 'Entrada de documento PDF > Geração de resumo abrangente com pontos-chave e explicações',
                    'th' => 'ป้อนเอกสาร PDF > การสร้างสรุปที่ครอบคลุมพร้อมประเด็นสำคัญและคำอธิบาย',
                    'tl' => 'Input ng dokumentong PDF > Komprehensibong paggawa ng buod na may pangunahing punto at paliwanag',
                    'zh' => 'PDF文档输入 > 生成包含关键点和解释的综合摘要',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "file",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Laden Sie das zu zusammenfassende PDF-Dokument hoch',
                                    'en' => 'Upload the PDF document to be summarized',
                                    'fr' => 'Téléchargez le document PDF à résumer',
                                    'hi' => 'सारांश किए जाने वाले पीडीएफ दस्तावेज़ को अपलोड करें',
                                    'ja' => '要約するPDF文書をアップロードしてください',
                                    'ko' => '요약할 PDF 문서를 업로드하세요',
                                    'pt' => 'Faça o upload do documento PDF a ser resumido',
                                    'th' => 'อัปโหลดเอกสาร PDF ที่ต้องการสรุป',
                                    'tl' => 'I-upload ang dokumentong PDF na bubuuin',
                                    'zh' => '上传需要摘要的PDF文档',
                                ]),
                                "type" => "file",
                                "options" => null,
                                "file_type" => "document"
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Analyze the content of the uploaded PDF document and create a comprehensive, Wikipedia-style summary. Follow these guidelines:
            
            1. Start with a main title (use # for h1) that captures the document's primary topic or purpose.
            
            2. ## Overview
               Provide a concise introductory paragraph that gives a high-level overview of the document's content, its purpose, and its significance. This should be informative and factual, covering the most important aspects.
            
            3. ## Key Summary Points
               Create a more detailed summary of the content. This should give a comprehensive overview of the document, its main arguments, methodologies (if applicable), and key findings or conclusions. Use bullet points (- ) for clarity and easy reading.
            
            4. ## Core Themes
               Identify and list 3-5 core themes or main topics discussed in the document. Use bullet points (- ) for each theme, providing a brief (1-2 sentence) explanation for each.
            
            5. ## Detailed Analysis
               For each core theme identified:
               a. Use ### for the theme title.
               b. Provide a detailed explanation of the theme, including:
                  - Its significance within the context of the document
                  - Key arguments or data supporting this theme
                  - Any counterarguments or limitations mentioned
                  - Implications or applications of this theme
               c. Use appropriate substructures:
                  - Paragraphs for general explanations
                  - Unordered lists (- ) for related items or examples
                  - Ordered lists (1., 2., 3.) for sequential processes or ranked items
               d. Include relevant quotes or data from the document, using > for blockquotes, prefaced with 'an excerpt from the original text:'.
               e. Use Markdown syntax for emphasis (**bold**, *italic*) to highlight key terms or ideas.
            
            6. ## Conclusions and Implications
               Summarize the main takeaways from the document and discuss their broader implications or significance in the relevant field or industry.
            
            7. Ensure the summary is informative, factual, and well-structured, highlighting the most important aspects of the document while maintaining a professional and objective tone.
            
            Please generate the summary based on the content of the uploaded PDF document.",
                        "background_information" => "You are an expert analyst and technical writer with extensive experience in various fields. Your expertise lies in quickly comprehending complex documents, identifying key themes and information, and presenting them in a clear, structured, and accessible manner. You have a keen eye for detail and the ability to distill large amounts of information into concise, informative summaries. Your writing style is professional, objective, and tailored to convey technical or specialized information to both expert and non-expert audiences.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => "{{step1.input.file}}"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["PDF", "document", "summary"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'PPT-Dokument-Zusammenfasser',
                    'en' => 'PPT Document Summarizer',
                    'fr' => 'Résumeur de documents PPT',
                    'hi' => 'PPT दस्तावेज़ सारांश',
                    'ja' => 'PPT文書要約ツール',
                    'ko' => 'PPT 문서 요약기',
                    'pt' => 'Resumidor de Documentos PPT',
                    'th' => 'เครื่องมือสรุปเอกสาร PPT',
                    'tl' => 'Tagabuod ng Dokumentong PPT',
                    'zh' => 'PPT文档摘要生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'PPT-Dokument-Eingabe > Umfassende Zusammenfassungsgenerierung mit Kernpunkten und Erklärungen',
                    'en' => 'PPT document input > Comprehensive summary generation with key points and explanations',
                    'fr' => 'Saisie de document PPT > Génération de résumé complet avec points clés et explications',
                    'hi' => 'पीपीटी दस्तावेज़ इनपुट > प्रमुख बिंदुओं और स्पष्टीकरणों के साथ व्यापक सारांश उत्पादन',
                    'ja' => 'PPT文書入力 > 要点と説明を含む包括的な要約生成',
                    'ko' => 'PPT 문서 입력 > 주요 포인트와 설명이 포함된 종합적인 요약 생성',
                    'pt' => 'Entrada de documento PPT > Geração de resumo abrangente com pontos-chave e explicações',
                    'th' => 'ป้อนเอกสาร PPT > การสร้างสรุปที่ครอบคลุมพร้อมประเด็นสำคัญและคำอธิบาย',
                    'tl' => 'Input ng dokumentong PPT > Komprehensibong paggawa ng buod na may pangunahing punto at paliwanag',
                    'zh' => 'PPT文档输入 > 生成包含关键点和解释的综合摘要',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "file",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Laden Sie das zu zusammenfassende PPT-Dokument hoch',
                                    'en' => 'Upload the PPT document to be summarized',
                                    'fr' => 'Téléchargez le document PPT à résumer',
                                    'hi' => 'सारांशित करने के लिए PPT दस्तावेज अपलोड करें',
                                    'ja' => '要約するPPT文書をアップロードしてください',
                                    'ko' => '요약할 PPT 문서를 업로드하세요',
                                    'pt' => 'Faça o upload do documento PPT a ser resumido',
                                    'th' => 'อัปโหลดเอกสาร PPT ที่ต้องการสรุป',
                                    'tl' => 'I-upload ang dokumentong PPT na bubuuin',
                                    'zh' => '上传需要摘要的PPT文档',
                                ]),
                                "type" => "file",
                                "options" => null,
                                "file_type" => "presentation"
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Analyze the content of the uploaded PPT document and create a comprehensive, Wikipedia-style summary. Follow these guidelines:
            
            1. Start with a main title (use # for h1) that captures the document's primary topic or purpose.
            
            2. ## Overview
               Provide a concise introductory paragraph that gives a high-level overview of the document's content, its purpose, and its significance. This should be informative and factual, covering the most important aspects.
            
            3. ## Key Summary Points
               Create a more detailed summary of the content. This should give a comprehensive overview of the document, its main arguments, methodologies (if applicable), and key findings or conclusions. Use bullet points (- ) for clarity and easy reading.
            
            4. ## Core Themes
               Identify and list 3-5 core themes or main topics discussed in the document. Use bullet points (- ) for each theme, providing a brief (1-2 sentence) explanation for each.
            
            5. ## Detailed Analysis
               For each core theme identified:
               a. Use ### for the theme title.
               b. Provide a detailed explanation of the theme, including:
                  - Its significance within the context of the document
                  - Key arguments or data supporting this theme
                  - Any counterarguments or limitations mentioned
                  - Implications or applications of this theme
               c. Use appropriate substructures:
                  - Paragraphs for general explanations
                  - Unordered lists (- ) for related items or examples
                  - Ordered lists (1., 2., 3.) for sequential processes or ranked items
               d. Include relevant quotes or data from the document, using > for blockquotes, prefaced with 'an excerpt from the original text:'.
               e. Use Markdown syntax for emphasis (**bold**, *italic*) to highlight key terms or ideas.
            
            6. ## Conclusions and Implications
               Summarize the main takeaways from the document and discuss their broader implications or significance in the relevant field or industry.
            
            7. Ensure the summary is informative, factual, and well-structured, highlighting the most important aspects of the document while maintaining a professional and objective tone.
            
            Please generate the summary based on the content of the uploaded PPT document.",
                        "background_information" => "You are an expert analyst and technical writer with extensive experience in various fields. Your expertise lies in quickly comprehending complex documents, identifying key themes and information, and presenting them in a clear, structured, and accessible manner. You have a keen eye for detail and the ability to distill large amounts of information into concise, informative summaries. Your writing style is professional, objective, and tailored to convey technical or specialized information to both expert and non-expert audiences.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => "{{step1.input.file}}"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["PPT", "document", "summary"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Audio-Zusammenfasser',
                    'en' => 'Audio Summarizer',
                    'fr' => 'Résumeur Audio',
                    'hi' => 'ऑडियो सारांशक',
                    'ja' => '音声要約ツール',
                    'ko' => '오디오 요약기',
                    'pt' => 'Resumidor de Áudio',
                    'th' => 'เครื่องมือสรุปเสียง',
                    'tl' => 'Tagabuod ng Audio',
                    'zh' => '音频摘要生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Audiodatei-Eingabe > Umfassende Zusammenfassungsgenerierung mit Kernpunkten und Erklärungen',
                    'en' => 'Audio file input > Comprehensive summary generation with key points and explanations',
                    'fr' => 'Entrée de fichier audio > Génération de résumé complet avec points clés et explications',
                    'hi' => 'ऑडियो फ़ाइल इनपुट > मुख्य बिंदुओं और व्याख्याओं के साथ व्यापक सारांश उत्पादन',
                    'ja' => '音声ファイル入力 > 要点と説明を含む包括的な要約生成',
                    'ko' => '오디오 파일 입력 > 주요 포인트와 설명이 포함된 종합적인 요약 생성',
                    'pt' => 'Entrada de arquivo de áudio > Geração de resumo abrangente com pontos-chave e explicações',
                    'th' => 'ป้อนไฟล์เสียง > การสร้างสรุปที่ครอบคลุมพร้อมประเด็นสำคัญและคำอธิบาย',
                    'tl' => 'Input ng file ng audio > Komprehensibong paggawa ng buod na may pangunahing punto at paliwanag',
                    'zh' => '音频文件输入 > 生成包含关键点和解释的综合摘要',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "file",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Laden Sie die zu zusammenfassende Audiodatei hoch',
                                    'en' => 'Upload the audio file to be summarized',
                                    'fr' => 'Téléchargez le fichier audio à résumer',
                                    'hi' => 'सारांश किए जाने वाली ऑडियो फ़ाइल अपलोड करें',
                                    'ja' => '要約する音声ファイルをアップロードしてください',
                                    'ko' => '요약할 오디오 파일을 업로드하세요',
                                    'pt' => 'Faça o upload do arquivo de áudio a ser resumido',
                                    'th' => 'อัปโหลดไฟล์เสียงที่ต้องการสรุป',
                                    'tl' => 'I-upload ang file ng audio na bubuuin',
                                    'zh' => '上传需要摘要的音频文件',
                                ]),
                                "type" => "file",
                                "options" => null,
                                "file_type" => "audio"
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Analyze the content of the uploaded audio file and create a comprehensive, Wikipedia-style summary. Follow these guidelines:
            
            1. Start with a main title (use # for h1) that captures the audio's primary topic or purpose.
            
            2. ## Overview
               Provide a concise introductory paragraph that gives a high-level overview of the audio content, its purpose, and its significance. This should be informative and factual, covering the most important aspects.
            
            3. ## Key Summary Points
               Create a more detailed summary of the content. This should give a comprehensive overview of the audio, its main arguments, key discussions, and important conclusions. Use bullet points (- ) for clarity and easy reading.
            
            4. ## Core Themes
               Identify and list 3-5 core themes or main topics discussed in the audio. Use bullet points (- ) for each theme, providing a brief (1-2 sentence) explanation for each.
            
            5. ## Detailed Analysis
               For each core theme identified:
               a. Use ### for the theme title.
               b. Provide a detailed explanation of the theme, including:
                  - Its significance within the context of the audio
                  - Key arguments or points supporting this theme
                  - Any counterarguments or alternative viewpoints mentioned
                  - Implications or applications of this theme
               c. Use appropriate substructures:
                  - Paragraphs for general explanations
                  - Unordered lists (- ) for related items or examples
                  - Ordered lists (1., 2., 3.) for sequential processes or ranked items
               d. Include relevant quotes or key statements from the audio, using > for blockquotes, prefaced with 'a key statement from the audio:'.
               e. Use Markdown syntax for emphasis (**bold**, *italic*) to highlight key terms or ideas.
            
            6. ## Conclusions and Implications
               Summarize the main takeaways from the audio and discuss their broader implications or significance in the relevant field or industry.
            
            7. ## Additional Notes
               If applicable, mention any notable aspects of the audio such as the speaker's tone, any background information provided, or the context in which the audio was created.
            
            8. Ensure the summary is informative, factual, and well-structured, highlighting the most important aspects of the audio while maintaining a professional and objective tone.
            
            Please generate the summary based on the content of the uploaded audio file.",
                        "background_information" => "You are an expert audio analyst and content summarizer with extensive experience in various fields. Your expertise lies in carefully listening to and comprehending complex audio content, identifying key themes and information, and presenting them in a clear, structured, and accessible manner. You have a keen ear for detail and the ability to distill large amounts of auditory information into concise, informative summaries. Your writing style is professional, objective, and tailored to convey spoken information to both expert and non-expert audiences. You are skilled at capturing not just the words spoken, but also the context, tone, and implications of audio content.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => "{{step1.input.file}}"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["audio", "summary"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Audio-Besprechungsprotokoll-Generator',
                    'en' => 'Audio Meeting Minutes Generator',
                    'fr' => 'Générateur de Procès-verbal Audio de Réunion',
                    'hi' => 'ऑडियो बैठक कार्यवृत्त जनरेटर',
                    'ja' => '音声会議議事録生成ツール',
                    'ko' => '오디오 회의록 생성기',
                    'pt' => 'Gerador de Atas de Reunião em Áudio',
                    'th' => 'เครื่องมือสร้างรายงานการประชุมจากไฟล์เสียง',
                    'tl' => 'Tagabuo ng Minutas ng Pulong mula sa Audio',
                    'zh' => '音频会议纪要生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Audiodatei-Eingabe > Umfassende Generierung von Besprechungsprotokollen mit Kernpunkten, Aktionspunkten und Schlussfolgerungen',
                    'en' => 'Audio file input > Comprehensive meeting minutes generation with key points, action items, and conclusions',
                    'fr' => 'Entrée de fichier audio > Génération complète de procès-verbal de réunion avec points clés, actions à mener et conclusions',
                    'hi' => 'ऑडियो फ़ाइल इनपुट > मुख्य बिंदुओं, कार्य वस्तुओं और निष्कर्षों के साथ व्यापक बैठक कार्यवृत्त उत्पादन',
                    'ja' => '音声ファイル入力 > 主要ポイント、アクションアイテム、結論を含む包括的な会議議事録の生成',
                    'ko' => '오디오 파일 입력 > 주요 포인트, 액션 아이템 및 결론이 포함된 종합적인 회의록 생성',
                    'pt' => 'Entrada de arquivo de áudio > Geração abrangente de atas de reunião com pontos-chave, itens de ação e conclusões',
                    'th' => 'ป้อนไฟล์เสียง > การสร้างรายงานการประชุมที่ครอบคลุมพร้อมประเด็นสำคัญ รายการที่ต้องดำเนินการ และข้อสรุป',
                    'tl' => 'Input ng file ng audio > Komprehensibong paggawa ng minutas ng pulong na may pangunahing punto, mga bagay na dapat gawin, at konklusyon',
                    'zh' => '音频文件输入 > 生成包含关键点、行动项目和结论的综合会议纪要',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "file",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Laden Sie die zu analysierende Audiodatei der Besprechung hoch',
                                    'en' => 'Upload the audio file of the meeting to be analyzed',
                                    'fr' => 'Téléchargez le fichier audio de la réunion à analyser',
                                    'hi' => 'विश्लेषण के लिए बैठक की ऑडियो फ़ाइल अपलोड करें',
                                    'ja' => '分析する会議の音声ファイルをアップロードしてください',
                                    'ko' => '회의록을 생성할 미팅 오디오 파일을 업로드하세요',
                                    'pt' => 'Faça o upload do arquivo de áudio da reunião a ser analisada',
                                    'th' => 'อัปโหลดไฟล์เสียงของการประชุมที่ต้องการวิเคราะห์',
                                    'tl' => 'I-upload ang file ng audio ng pulong na susuriin',
                                    'zh' => '上传需要分析的会议音频文件',
                                ]),
                                "type" => "file",
                                "options" => null,
                                "file_type" => "audio"
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Analyze the content of the uploaded audio file and create comprehensive meeting minutes. Follow these guidelines:
            
            1. Start with a main title (use # for h1) that includes the meeting name.
            
            2. ## Meeting Overview
               Provide a brief overview of the meeting, including the context, attendees (if mentioned), and any other relevant background information.
            
            3. ## Meeting Object(Goal)
               Clearly state the purpose or objectives of the meeting. Use bullet points (- ) if there are multiple goals.
            
            4. ## Meeting main key points
               Summarize the main points discussed in the meeting. Use subheadings (### ) for different agenda items or topics. Under each subheading:
               - Provide a concise summary of the discussion
               - Note any important decisions made
               - Highlight key points or arguments presented
               Use bullet points (- ) for clarity and easy reading where appropriate.
            
            5. ## Conclusions(Agreements)
               Summarize the main conclusions reached during the meeting and any agreements made. Use bullet points (- ) for multiple items.
            
            6. ## To-do-list
               Create a list of action items or tasks assigned during the meeting. For each item:
               - Clearly state the task
               - Assign responsibility (if mentioned)
               - Include deadlines (if specified)
               Use a numbered list (1., 2., 3.) for easy reference.
            
            7. ## Appendix
               Include any additional notes, future considerations, or follow-up items that don't fit into the above categories. This could include:
               - Upcoming related meetings
               - Documents or resources mentioned that need to be shared
               - Any unresolved issues that need further discussion
            
            8. Use Markdown syntax for emphasis (**bold**, *italic*) to highlight key terms, names, or important points throughout the document.
            
            9. If exact quotes are crucial, use > for blockquotes, prefaced with 'Direct quote from the meeting:'.
            
            10. Ensure the meeting minutes are clear, concise, and well-organized, capturing all essential information while maintaining a professional tone.
            
            Please generate the meeting minutes based on the content of the uploaded audio file.",
                        "background_information" => "You are an expert meeting facilitator and professional minute-taker with extensive experience in various corporate and organizational settings. Your expertise lies in carefully listening to and comprehending complex meeting discussions, identifying key points, decisions, and action items, and presenting them in a clear, structured, and accessible manner. You have a keen ear for detail and the ability to distill lengthy discussions into concise, actionable meeting minutes. Your writing style is professional, objective, and tailored to provide clear and useful information to all meeting participants and stakeholders. You are skilled at capturing not just what was said, but also the context, decisions made, and next steps agreed upon during the meeting.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => "{{step1.input.file}}"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "audio", "meeting_minutes", "summary"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Webseiten-Zusammenfasser',
                    'en' => 'Web Page Summarizer',
                    'fr' => 'Résumeur de Page Web',
                    'hi' => 'वेब पेज सारांशक',
                    'ja' => 'ウェブページ要約ツール',
                    'ko' => '웹 페이지 요약기',
                    'pt' => 'Resumidor de Página Web',
                    'th' => 'เครื่องมือสรุปหน้าเว็บ',
                    'tl' => 'Tagabuod ng Webpage',
                    'zh' => '网页摘要生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'URL-Eingabe > Webseiten-Scraping > Umfassende Zusammenfassungsgenerierung mit Kernpunkten und Analyse',
                    'en' => 'URL input > Web page scraping > Comprehensive summary generation with key points and analysis',
                    'fr' => 'Saisie d\'URL > Extraction de page Web > Génération de résumé complet avec points clés et analyse',
                    'hi' => 'URL इनपुट > वेब पेज स्क्रैपिंग > मुख्य बिंदुओं और विश्लेषण के साथ व्यापक सारांश उत्पादन',
                    'ja' => 'URL入力 > ウェブページスクレイピング > 要点と分析を含む包括的な要約生成',
                    'ko' => 'URL 입력 > 웹 페이지 스크래핑 > 주요 포인트와 분석이 포함된 종합적인 요약 생성',
                    'pt' => 'Entrada de URL > Raspagem de página web > Geração de resumo abrangente com pontos-chave e análise',
                    'th' => 'ป้อน URL > การดึงข้อมูลหน้าเว็บ > การสร้างสรุปที่ครอบคลุมพร้อมประเด็นสำคัญและการวิเคราะห์',
                    'tl' => 'Input ng URL > Pag-scrape ng webpage > Komprehensibong paggawa ng buod na may pangunahing punto at pagsusuri',
                    'zh' => 'URL输入 > 网页抓取 > 生成包含关键点和分析的综合摘要',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "url",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie die URL der zusammenzufassenden Webseite ein',
                                    'en' => 'Enter the URL of the web page to be summarized',
                                    'fr' => 'Saisissez l\'URL de la page web à résumer',
                                    'hi' => 'सारांश किए जाने वाले वेब पेज का URL दर्ज करें',
                                    'ja' => '要約するウェブページのURLを入力してください',
                                    'ko' => '요약할 웹 페이지의 URL을 입력하세요',
                                    'pt' => 'Digite o URL da página web a ser resumida',
                                    'th' => 'ป้อน URL ของหน้าเว็บที่ต้องการสรุป',
                                    'tl' => 'Ilagay ang URL ng webpage na bubuuin',
                                    'zh' => '输入需要摘要的网页URL',
                                ]),
                                "type" => "text",
                                "options" => null,
                            ]
                        ]
                    ],
                    [
                        "type" => "scrap_webpage",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "url_source" => "user_input",
                        "fixed_url" => null,
                        "extraction_type" => "text_only"
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Analyze the content of the scraped web page and create a comprehensive summary. Follow these guidelines:
            
            1. Start with a main title (use # for h1) that captures the web page's primary topic or purpose.
            
            2. ## Overview
               Provide a concise introductory paragraph that gives a high-level overview of the web page's content, its purpose, and its significance. Include the source URL.
            
            3. ## Key Points
               Summarize the main points or arguments presented on the web page. Use bullet points (- ) for clarity and easy reading.
            
            4. ## Detailed Analysis
               Break down the content into major themes or sections. For each:
               a. Use ### for the theme title.
               b. Provide a detailed explanation of the theme, including:
                  - Its significance within the context of the web page
                  - Key arguments or data supporting this theme
                  - Any counterarguments or limitations mentioned
               c. Use appropriate substructures:
                  - Paragraphs for general explanations
                  - Unordered lists (- ) for related items or examples
                  - Ordered lists (1., 2., 3.) for sequential processes or ranked items
               d. Include relevant quotes from the web page, using > for blockquotes, prefaced with 'Quoted from the web page:'.
            
            5. ## Visual Content Summary (if applicable)
               If the web page contains significant visual elements (images, infographics, videos), briefly describe their content and relevance.
            
            6. ## Conclusions and Implications
               Summarize the main takeaways from the web page and discuss their broader implications or significance.
            
            7. ## Additional Resources
               If the web page links to other relevant resources or references, list them here.
            
            8. Use Markdown syntax for emphasis (**bold**, *italic*) to highlight key terms or ideas.
            
            9. Ensure the summary is informative, factual, and well-structured, highlighting the most important aspects of the web page while maintaining a professional and objective tone.
            
            Please generate the summary based on the content of the scraped web page.
            webpage info: [{{step2.output}}]",
                        "background_information" => "You are an expert web content analyst and summarizer with extensive experience in various fields. Your expertise lies in quickly comprehending and analyzing web content, identifying key themes and information, and presenting them in a clear, structured, and accessible manner. You have a keen eye for detail and the ability to distill large amounts of information into concise, informative summaries. Your writing style is professional, objective, and tailored to convey online content to both expert and non-expert audiences. You are skilled at capturing not just the text, but also the context, structure, and implications of web content, including consideration of any visual elements.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_content" => "{{step2.output}}"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["webpage", "summary"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Webseiten-Inhalts-Regenerator',
                    'en' => 'Web Page Content Regenerator',
                    'fr' => 'Régénérateur de Contenu de Page Web',
                    'hi' => 'वेब पेज सामग्री पुनर्जनक',
                    'ja' => 'ウェブページコンテンツ再生成ツール',
                    'ko' => '웹 페이지 콘텐츠 재생성기',
                    'pt' => 'Regenerador de Conteúdo de Página Web',
                    'th' => 'เครื่องมือสร้างเนื้อหาหน้าเว็บใหม่',
                    'tl' => 'Tagabuo Muli ng Nilalaman ng Webpage',
                    'zh' => '网页内容重生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'URL-Eingabe > Webseiten-Scraping > Hochwertige Inhaltsregenerierung basierend auf gescrapten Inhalten',
                    'en' => 'URL input > Web page scraping > High-quality content regeneration based on scraped content',
                    'fr' => 'Saisie d\'URL > Extraction de page Web > Régénération de contenu de haute qualité basée sur le contenu extrait',
                    'hi' => 'URL इनपुट > वेब पेज स्क्रैपिंग > स्क्रैप की गई सामग्री के आधार पर उच्च-गुणवत्ता वाली सामग्री पुनर्जनन',
                    'ja' => 'URL入力 > ウェブページスクレイピング > スクレイピングされたコンテンツに基づく高品質なコンテンツの再生成',
                    'ko' => 'URL 입력 > 웹 페이지 스크래핑 > 스크래핑된 콘텐츠를 기반으로 한 고품질 콘텐츠 재생성',
                    'pt' => 'Entrada de URL > Raspagem de página web > Regeneração de conteúdo de alta qualidade baseada no conteúdo raspado',
                    'th' => 'ป้อน URL > การดึงข้อมูลหน้าเว็บ > การสร้างเนื้อหาคุณภาพสูงใหม่บนพื้นฐานของเนื้อหาที่ดึงมา',
                    'tl' => 'Input ng URL > Pag-scrape ng webpage > Muling paggawa ng mataas na kalidad na nilalaman batay sa na-scrape na content',
                    'zh' => 'URL输入 > 网页抓取 > 基于抓取内容的高质量内容重新生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "url",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie die URL der Webseite ein, die als Grundlage für die Inhaltsregenerierung verwendet werden soll',
                                    'en' => 'Enter the URL of the web page to be used as a basis for content regeneration',
                                    'fr' => 'Saisissez l\'URL de la page web à utiliser comme base pour la régénération de contenu',
                                    'hi' => 'सामग्री पुनर्जनन के लिए आधार के रूप में उपयोग किए जाने वाले वेब पेज का URL दर्ज करें',
                                    'ja' => 'コンテンツ再生成の基礎として使用するウェブページのURLを入力してください',
                                    'ko' => '콘텐츠 재생성의 기반으로 사용될 웹 페이지의 URL을 입력하세요',
                                    'pt' => 'Digite o URL da página web a ser usada como base para a regeneração de conteúdo',
                                    'th' => 'ป้อน URL ของหน้าเว็บที่จะใช้เป็นพื้นฐานสำหรับการสร้างเนื้อหาใหม่',
                                    'tl' => 'Ilagay ang URL ng webpage na gagamitin bilang batayan para sa muling paggawa ng nilalaman',
                                    'zh' => '输入作为内容重新生成基础的网页URL',
                                ]),
                                "type" => "text",
                                "options" => null,
                            ]
                        ]
                    ],
                    [
                        "type" => "scrap_webpage",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "url_source" => "user_input",
                        "fixed_url" => null,
                        "extraction_type" => "text_only"
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the content of the scraped web page, generate a high-quality title and table of contents with up to 4 main sections. The title should be catchy and informative, while the table of contents should cover the main topics in a logical and engaging manner. Your output should be in the following format:
            
            Title: [Your generated title]
            
            Table of Contents:
            1. [First main section]
            2. [Second main section]
            3. [Third main section]
            4. [Fourth main section]
            
            Ensure that the title and table of contents are more engaging, comprehensive, and well-structured than the original content. Focus on creating a flow that will result in a higher quality article.
            
            webpage info: [{{step2.output}}]",
                        "background_information" => "You are an expert content strategist and outline creator with extensive experience in various fields. Your expertise lies in quickly analyzing existing content, identifying key themes and potential improvements, and creating engaging, well-structured outlines for high-quality content. You have a keen eye for organization and the ability to craft titles and table of contents that capture reader interest while promising valuable information.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the title and table of contents generated in the previous step, create high-quality content for the first section. Your content should be more comprehensive, engaging, and valuable than the original web page content. Include relevant examples, data, or case studies to support your points. Use appropriate subheadings, bullet points, or numbered lists to organize the information clearly. Aim for a length of 300-500 words for this section.
            
            Title and Table of Contents:
            {{step3.output}}
            
            webpage info: [{{step2.output}}]",
                        "background_information" => "You are an expert content creator with deep knowledge across various fields. Your writing is engaging, informative, and tailored to provide maximum value to the reader. You excel at expanding on ideas, providing relevant examples, and explaining complex concepts in an accessible manner. Your content is always well-researched, logically structured, and more comprehensive than typical web articles.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the title and table of contents generated earlier, create high-quality content for the second section. Your content should be more comprehensive, engaging, and valuable than the original web page content. Include relevant examples, data, or case studies to support your points. Use appropriate subheadings, bullet points, or numbered lists to organize the information clearly. Aim for a length of 300-500 words for this section.
            
            Title and Table of Contents:
            {{step3.output}}
            
            webpage info: [{{step2.output}}]",
                        "background_information" => "You are an expert content creator with deep knowledge across various fields. Your writing is engaging, informative, and tailored to provide maximum value to the reader. You excel at expanding on ideas, providing relevant examples, and explaining complex concepts in an accessible manner. Your content is always well-researched, logically structured, and more comprehensive than typical web articles.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the title and table of contents generated earlier, create high-quality content for the third section. Your content should be more comprehensive, engaging, and valuable than the original web page content. Include relevant examples, data, or case studies to support your points. Use appropriate subheadings, bullet points, or numbered lists to organize the information clearly. Aim for a length of 300-500 words for this section.
            
            Title and Table of Contents:
            {{step3.output}}
            
            webpage info: [{{step2.output}}]",
                        "background_information" => "You are an expert content creator with deep knowledge across various fields. Your writing is engaging, informative, and tailored to provide maximum value to the reader. You excel at expanding on ideas, providing relevant examples, and explaining complex concepts in an accessible manner. Your content is always well-researched, logically structured, and more comprehensive than typical web articles.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 7,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the title and table of contents generated earlier, create high-quality content for the fourth section. Your content should be more comprehensive, engaging, and valuable than the original web page content. Include relevant examples, data, or case studies to support your points. Use appropriate subheadings, bullet points, or numbered lists to organize the information clearly. Aim for a length of 300-500 words for this section.
            
            Title and Table of Contents:
            {{step3.output}}
            
            webpage info: [{{step2.output}}]",
                        "background_information" => "You are an expert content creator with deep knowledge across various fields. Your writing is engaging, informative, and tailored to provide maximum value to the reader. You excel at expanding on ideas, providing relevant examples, and explaining complex concepts in an accessible manner. Your content is always well-researched, logically structured, and more comprehensive than typical web articles.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 8,
                        "uuid" => $generateUuid(),
                        "content_template" => "{{step4.output}}\n\n{{step5.output}}\n\n{{step6.output}}\n\n{{step7.output}}"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["webpage", "content_regeneration"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // UI/UX
            [
                'name' => $this->getLocalizedText([
                    'de' => '[Bild-zu-Code] UI/UX-Code-Generator',
                    'en' => '[image-to-code] UI/UX Code Generator',
                    'fr' => 'Générateur de Code UI/UX [image-vers-code]',
                    'hi' => '[छवि-से-कोड] UI/UX कोड जनरेटर',
                    'ja' => '[画像からコード] UI/UXコードジェネレーター',
                    'ko' => '[이미지-코드 변환] UI/UX 코드 생성기',
                    'pt' => 'Gerador de Código UI/UX [imagem-para-código]',
                    'th' => 'เครื่องมือสร้างโค้ด UI/UX [รูปภาพเป็นโค้ด]',
                    'tl' => 'Tagabuo ng Code ng UI/UX [larawan-patungong-code]',
                    'zh' => '[图像转代码] UI/UX代码生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Bildeingabe > Generierung von UI/UX-Code mit Tailwind CSS basierend auf dem hochgeladenen Bild',
                    'en' => 'Image input > Generate UI/UX code using Tailwind CSS based on the uploaded image',
                    'fr' => 'Entrée d\'image > Génération de code UI/UX utilisant Tailwind CSS basé sur l\'image téléchargée',
                    'hi' => 'छवि इनपुट > अपलोड की गई छवि के आधार पर Tailwind CSS का उपयोग करके UI/UX कोड उत्पन्न करें',
                    'ja' => '画像入力 > アップロードされた画像に基づいてTailwind CSSを使用してUI/UXコードを生成',
                    'ko' => '이미지 입력 > 업로드된 이미지를 바탕으로 Tailwind CSS를 사용하여 UI/UX 코드 생성',
                    'pt' => 'Entrada de imagem > Gerar código UI/UX usando Tailwind CSS com base na imagem carregada',
                    'th' => 'ป้อนรูปภาพ > สร้างโค้ด UI/UX โดยใช้ Tailwind CSS ตามรูปภาพที่อัปโหลด',
                    'tl' => 'Input ng larawan > Bumuo ng code ng UI/UX gamit ang Tailwind CSS batay sa na-upload na larawan',
                    'zh' => '图像输入 > 基于上传的图像使用Tailwind CSS生成UI/UX代码',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "file",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Laden Sie das Bild des UI/UX-Designs hoch',
                                    'en' => 'Upload the image of the UI/UX design',
                                    'fr' => 'Téléchargez l\'image du design UI/UX',
                                    'hi' => 'UI/UX डिज़ाइन की छवि अपलोड करें',
                                    'ja' => 'UI/UXデザインの画像をアップロードしてください',
                                    'ko' => 'UI/UX 디자인의 이미지를 업로드하세요',
                                    'pt' => 'Faça o upload da imagem do design UI/UX',
                                    'th' => 'อัปโหลดรูปภาพของการออกแบบ UI/UX',
                                    'tl' => 'I-upload ang larawan ng disenyo ng UI/UX',
                                    'zh' => '上传UI/UX设计的图像',
                                ]),
                                "type" => "file",
                                "options" => null,
                                "file_type" => "image"
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_ui_ux",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Analyze the uploaded image of a UI/UX design and generate only the corresponding HTML code using Tailwind CSS classes to recreate this design. Follow these guidelines:

                            1. Generate a complete HTML structure including <!DOCTYPE html>, <html>, <head>, and <body> tags.

                            2. In the <head> section:
                            - Include a meta viewport tag for responsiveness.
                            - Link to the Tailwind CSS CDN.
                            - If necessary, include a <style> tag for any custom styles using @apply directives.

                            3. In the <body> section:
                            - Use semantic HTML5 tags where appropriate (e.g., <header>, <nav>, <main>, <footer>).
                            - Apply Tailwind utility classes for all styling, including layout, spacing, colors, typography, and responsive design.
                            - Implement a mobile-first responsive design approach using Tailwind's responsive prefixes (sm:, md:, lg:, xl:).
                            - Ensure the layout closely matches the uploaded image, including positioning, sizing, and overall structure.

                            4. Color and Typography:
                            - Use Tailwind's color classes that most closely match the colors in the image.
                            - Apply appropriate text styles (size, weight, line height) using Tailwind classes to match the design.

                            5. For any necessary interactive elements visible in the image:
                            - Include minimal JavaScript within a <script> tag at the end of the <body> section.
                            - Use vanilla JavaScript or Alpine.js (linked via CDN if used) for simplicity.

                            6. Add brief comments only where absolutely necessary to explain complex structures or functionality that may not be immediately obvious from the HTML structure alone.

                            7. Ensure the code is well-structured, properly indented, and follows current HTML5 and Tailwind CSS best practices.

                            8. The output should be solely the HTML code, without any additional explanations, summaries, or suggestions outside of the HTML structure.

                            Generate only the HTML code with Tailwind CSS classes based on the uploaded UI/UX design image, aiming to recreate the visual design as accurately as possible.",
                        "background_information" => "You are an expert front-end developer and UI/UX specialist with extensive experience in Tailwind CSS. Your skills include a deep understanding of HTML5, CSS3, modern JavaScript, and you're particularly adept at leveraging Tailwind's utility-first approach to rapidly build custom designs. You have a keen eye for design details and can efficiently translate complex layouts and interactions into Tailwind classes. Your code is always clean, well-commented, and follows best practices for performance, accessibility, and responsive design. You're also familiar with current web design trends and can suggest modern alternatives or improvements to older design patterns when appropriate, always within the context of Tailwind CSS.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.5,
                        "reference_file" => "{{step1.input.file}}"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["UI/UX", "code_generation", "publishing"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => '[Text-zu-Code] UI/UX-Code-Generator',
                    'en' => '[text-to-code] UI/UX Code Generator',
                    'fr' => 'Générateur de Code UI/UX [texte-vers-code]',
                    'hi' => '[पाठ-से-कोड] UI/UX कोड जनरेटर',
                    'ja' => '[テキストからコード] UI/UXコードジェネレーター',
                    'ko' => '[텍스트-코드 변환] UI/UX 코드 생성기',
                    'pt' => 'Gerador de Código UI/UX [texto-para-código]',
                    'th' => 'เครื่องมือสร้างโค้ด UI/UX [ข้อความเป็นโค้ด]',
                    'tl' => 'Tagabuo ng Code ng UI/UX [teksto-patungong-code]',
                    'zh' => '[文本转代码] UI/UX代码生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Texteingabe der Beschreibung > Generierung von UI/UX HTML-Code mit Tailwind CSS basierend auf der bereitgestellten Beschreibung',
                    'en' => 'Text description input > Generate UI/UX HTML code using Tailwind CSS based on the provided description',
                    'fr' => 'Saisie de la description textuelle > Génération de code HTML UI/UX utilisant Tailwind CSS basé sur la description fournie',
                    'hi' => 'पाठ विवरण इनपुट > प्रदान किए गए विवरण के आधार पर Tailwind CSS का उपयोग करके UI/UX HTML कोड उत्पन्न करें',
                    'ja' => 'テキスト説明入力 > 提供された説明に基づいてTailwind CSSを使用してUI/UX HTMLコードを生成',
                    'ko' => '텍스트 설명 입력 > 제공된 설명을 바탕으로 Tailwind CSS를 사용하여 UI/UX HTML 코드 생성',
                    'pt' => 'Entrada de descrição de texto > Gerar código HTML UI/UX usando Tailwind CSS com base na descrição fornecida',
                    'th' => 'ป้อนคำอธิบายเป็นข้อความ > สร้างโค้ด HTML UI/UX โดยใช้ Tailwind CSS ตามคำอธิบายที่ให้มา',
                    'tl' => 'Input ng paglalarawan sa teksto > Bumuo ng code ng HTML UI/UX gamit ang Tailwind CSS batay sa ibinigay na paglalarawan',
                    'zh' => '文本描述输入 > 基于提供的描述使用Tailwind CSS生成UI/UX HTML代码',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "UI/UX Description",
                                "description"=> $this->getLocalizedText([
                                    'de' => 'Geben Sie eine detaillierte Beschreibung des UI/UX-Designs ein, das Sie erstellen möchten',
                                    'en' => 'Provide a detailed description of the UI/UX design you want to create',
                                    'fr' => 'Fournissez une description détaillée du design UI/UX que vous souhaitez créer',
                                    'hi' => 'आप जो UI/UX डिज़ाइन बनाना चाहते हैं उसका विस्तृत विवरण प्रदान करें',
                                    'ja' => '作成したいUI/UXデザインの詳細な説明を提供してください',
                                    'ko' => '만들고자 하는 UI/UX 디자인에 대한 자세한 설명을 제공하세요',
                                    'pt' => 'Forneça uma descrição detalhada do design UI/UX que você deseja criar',
                                    'th' => 'ให้คำอธิบายโดยละเอียดเกี่ยวกับการออกแบบ UI/UX ที่คุณต้องการสร้าง',
                                    'tl' => 'Magbigay ng detalyadong paglalarawan ng disenyo ng UI/UX na gusto mong likhain',
                                    'zh' => '提供你想要创建的UI/UX设计的详细描述',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "placeholder" => "Describe the layout, colors, components, and any specific features of your UI/UX design..."
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_ui_ux",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the provided UI/UX design description, generate only the corresponding HTML code using Tailwind CSS classes to create this design. Here's the UI/UX description:
                            {{step1.input.UI/UX_Description}}
                            Follow these guidelines:

                            1. Generate a complete HTML structure including <!DOCTYPE html>, <html>, <head>, and <body> tags.

                            2. In the <head> section:
                            - Include a meta viewport tag for responsiveness.
                            - Link to the Tailwind CSS CDN.
                            - If necessary, include a <style> tag for any custom styles using @apply directives.


                            3. In the <body> section:
                            - Use semantic HTML5 tags where appropriate (e.g., <header>, <nav>, <main>, <footer>).
                            - Apply Tailwind utility classes for all styling, including layout, spacing, colors, typography, and responsive design.
                            - Implement a mobile-first responsive design approach using Tailwind's responsive prefixes (sm:, md:, lg:, xl:).

                            4. For any necessary interactive elements:
                            - Include minimal JavaScript within a <script> tag at the end of the <body> section.
                            - Use vanilla JavaScript or Alpine.js (linked via CDN if used) for simplicity.

                            5. Add brief comments only where absolutely necessary to explain complex structures or functionality.
                            6. Ensure the code is well-structured, properly indented, and follows current HTML5 and Tailwind CSS best practices.
                            7. The output should be solely the HTML code, without any additional explanations, summaries, or suggestions outside of the HTML structure.

                            Generate only the HTML code with Tailwind CSS classes based on the provided UI/UX design description.",
                        "background_information" => "You are an expert front-end developer specializing in creating clean, efficient HTML markup with Tailwind CSS. Your task is to translate textual UI/UX descriptions into complete, ready-to-use HTML documents. You excel at interpreting design requirements and implementing them using Tailwind's utility classes, ensuring responsive and accessible designs. Your output is always valid HTML5, optimized for immediate use, and requires no further explanation or context.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.5,
                        "reference_text" => "{{step1.input.UI/UX Description}}"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["UI/UX", "code_generation", "publishing"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 엑셀
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Entwicklungsanforderungen-Spezifikation Excel-Generator',
                    'en' => 'Development Requirements Spec Excel Generator',
                    'fr' => 'Générateur Excel de Spécifications des Exigences de Développement',
                    'hi' => 'विकास आवश्यकताओं की विशिष्टता एक्सेल जनरेटर',
                    'ja' => '開発要件仕様Excelジェネレーター',
                    'ko' => '개발 요구사항 정의서 Excel 생성기',
                    'pt' => 'Gerador Excel de Especificações de Requisitos de Desenvolvimento',
                    'th' => 'เครื่องมือสร้างข้อกำหนดความต้องการการพัฒนาในรูปแบบ Excel',
                    'tl' => 'Tagabuo ng Excel para sa Mga Detalyadong Kinakailangan sa Pagbuo',
                    'zh' => '开发需求规格Excel生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Generieren Sie eine detaillierte Entwicklungsanforderungen-Spezifikation im Excel-Format basierend auf Projekttyp und erforderlichen Funktionen',
                    'en' => 'Generate a detailed Excel-format Development Requirements Specification based on project type and required features',
                    'fr' => 'Générez une spécification détaillée des exigences de développement au format Excel basée sur le type de projet et les fonctionnalités requises',
                    'hi' => 'परियोजना के प्रकार और आवश्यक सुविधाओं के आधार पर विस्तृत एक्सेल-प्रारूप विकास आवश्यकता विनिर्देश उत्पन्न करें',
                    'ja' => 'プロジェクトタイプと必要な機能に基づいて、詳細なExcel形式の開発要件仕様を生成します',
                    'ko' => '프로젝트 유형과 필요한 기능을 기반으로 상세한 Excel 형식의 개발 요구사항 정의서를 생성합니다',
                    'pt' => 'Gere uma Especificação de Requisitos de Desenvolvimento detalhada em formato Excel com base no tipo de projeto e recursos necessários',
                    'th' => 'สร้างข้อกำหนดความต้องการการพัฒนาแบบละเอียดในรูปแบบ Excel ตามประเภทโครงการและคุณสมบัติที่ต้องการ',
                    'tl' => 'Bumuo ng detalyadong Espesipikasyon ng Mga Kinakailangan sa Pagbuo sa format ng Excel batay sa uri ng proyekto at kinakailangang mga tampok',
                    'zh' => '根据项目类型和所需功能生成详细的Excel格式开发需求规格',
                ]),
                'steps' => json_encode([
                [
                "type" => "input",
                "step_number" => 1,
                "uuid" => $generateUuid(),
                "input_fields" => [
                [
                "label" => "Project Type",
                "description" => $this->getLocalizedText([
                    'de' => 'Wählen Sie den Typ des Projekts aus, das Sie entwickeln',
                    'en' => "Select the type of project you're developing",
                    'fr' => 'Sélectionnez le type de projet que vous développez',
                    'hi' => 'आप जो परियोजना विकसित कर रहे हैं उसका प्रकार चुनें',
                    'ja' => '開発しているプロジェクトのタイプを選択してください',
                    'ko' => '개발 중인 프로젝트의 유형을 선택하세요',
                    'pt' => 'Selecione o tipo de projeto que você está desenvolvendo',
                    'th' => 'เลือกประเภทของโครงการที่คุณกำลังพัฒนา',
                    'tl' => 'Piliin ang uri ng proyekto na iyong binubuo',
                    'zh' => '选择您正在开发的项目类型',
                ]),
                "type" => "radio",
                "options" => $this->getLocalizedText([
                    'de' => 'Webanwendung, Mobile App, Responsive Web App, Spiel, Community-Plattform, E-Commerce-Plattform, Unternehmenssoftware, IoT-Anwendung, KI/ML-Lösung, Blockchain-Anwendung',
                    'en' => 'Web Application, Mobile App, Responsive Web App, Game, Community Platform, E-Commerce Platform, Enterprise Software, IoT Application, AI/ML Solution, Blockchain Application',
                    'fr' => 'Application Web, Application mobile, Application Web responsive, Jeu, Plateforme communautaire, Plateforme E-Commerce, Logiciel d\'entreprise, Application IoT, Solution IA/ML, Application Blockchain',
                    'hi' => 'वेब एप्लिकेशन, मोबाइल ऐप, रिस्पॉन्सिव वेब ऐप, गेम, कम्युनिटी प्लेटफ़ॉर्म, ई-कॉमर्स प्लेटफ़ॉर्म, एंटरप्राइज़ सॉफ़्टवेयर, IoT एप्लिकेशन, AI/ML समाधान, ब्लॉकचेन एप्लिकेशन',
                    'ja' => 'Webアプリケーション, モバイルアプリ, レスポンシブWebアプリ, ゲーム, コミュニティプラットフォーム, Eコマースプラットフォーム, 企業向けソフトウェア, IoTアプリケーション, AI/MLソリューション, ブロックチェーンアプリケーション',
                    'ko' => '웹 애플리케이션, 모바일 앱, 반응형 웹 앱, 게임, 커뮤니티 플랫폼, 전자상거래 플랫폼, 기업용 소프트웨어, IoT 애플리케이션, AI/ML 솔루션, 블록체인 애플리케이션',
                    'pt' => 'Aplicação Web, Aplicativo Móvel, Aplicação Web Responsiva, Jogo, Plataforma Comunitária, Plataforma de E-Commerce, Software Empresarial, Aplicação IoT, Solução de IA/ML, Aplicação Blockchain',
                    'th' => 'แอปพลิเคชันเว็บ, แอปมือถือ, แอปเว็บที่ตอบสนอง, เกม, แพลตฟอร์มชุมชน, แพลตฟอร์มอีคอมเมิร์ซ, ซอฟต์แวร์องค์กร, แอปพลิเคชัน IoT, โซลูชัน AI/ML, แอปพลิเคชันบล็อกเชน',
                    'tl' => 'Web Application, Mobile App, Responsive Web App, Laro, Community Platform, E-Commerce Platform, Enterprise Software, IoT Application, AI/ML Solution, Blockchain Application',
                    'zh' => 'Web 应用程序, 移动应用程序, 响应式 Web 应用程序, 游戏, 社区平台, 电子商务平台, 企业软件, 物联网应用, AI/ML 解决方案, 区块链应用程序',
                ]),
                ],
                [
                "label" => "Key Features",
                "description" => $this->getLocalizedText([
                    'de' => 'Wählen Sie die Hauptfunktionen aus, die für Ihr Projekt erforderlich sind',
                    'en' => 'Select the main features required for your project',
                    'fr' => 'Sélectionnez les principales fonctionnalités requises pour votre projet',
                    'hi' => 'अपनी परियोजना के लिए आवश्यक मुख्य सुविधाओं का चयन करें',
                    'ja' => 'プロジェクトに必要な主な機能を選択してください',
                    'ko' => '프로젝트에 필요한 주요 기능을 선택하세요',
                    'pt' => 'Selecione as principais características necessárias para o seu projeto',
                    'th' => 'เลือกคุณสมบัติหลักที่จำเป็นสำหรับโครงการของคุณ',
                    'tl' => 'Piliin ang mga pangunahing tampok na kinakailangan para sa iyong proyekto',
                    'zh' => '选择项目所需的主要功能',
                ]),
                "type" => "multiselect",
                "options" => $this->getLocalizedText([
                    'de' => 'Benutzerauthentifizierung, Integration von sozialen Medien, Echtzeit-Messaging, Zahlungsgateway, Content-Management-System, Suchfunktion, Benutzerprofile, Analyse-Dashboard, Benachrichtigungssystem, Geolokalisierungsdienste, Datei-Upload/Download, Mehrsprachige Unterstützung, API-Integration, Admin-Panel, Benutzerbewertungen/Bewertungen, Abonnementverwaltung, Bestandsverwaltung, Buchungs-/Reservierungssystem, Soziales Teilen, Videostreaming, Chat-Support, Sicherheitsfunktionen, Leistungsüberwachung',
                    'en' => 'User Authentication, Social Media Integration, Real-time Messaging, Payment Gateway, Content Management System, Search Functionality, User Profiles, Analytics Dashboard, Notification System, Geolocation Services, File Upload/Download, Multi-language Support, API Integration, Admin Panel, User Reviews/Ratings, Subscription Management, Inventory Management, Booking/Reservation System, Social Sharing, Video Streaming, Chat Support, Security Features, Performance Monitoring',
                    'fr' => 'Authentification des utilisateurs, Intégration des médias sociaux, Messagerie en temps réel, Passerelle de paiement, Système de gestion de contenu, Fonctionnalité de recherche, Profils utilisateur, Tableau de bord analytique, Système de notification, Services de géolocalisation, Téléchargement/partage de fichiers, Prise en charge multilingue, Intégration API, Panneau d\'administration, Avis/évaluations des utilisateurs, Gestion des abonnements, Gestion des stocks, Système de réservation, Partage social, Streaming vidéo, Support de chat, Fonctionnalités de sécurité, Surveillance des performances',
                    'hi' => 'उपयोगकर्ता प्रमाणीकरण, सोशल मीडिया एकीकरण, रियल-टाइम मैसेजिंग, भुगतान गेटवे, कंटेंट मैनेजमेंट सिस्टम, खोज कार्यक्षमता, उपयोगकर्ता प्रोफाइल, विश्लेषण डैशबोर्ड, सूचनात्मक प्रणाली, जियोलोकेशन सेवाएं, फाइल अपलोड/डाउनलोड, बहु-भाषा समर्थन, एपीआई एकीकरण, एडमिन पैनल, उपयोगकर्ता समीक्षाएं/रेटिंग्स, सदस्यता प्रबंधन, इन्वेंट्री प्रबंधन, बुकिंग/रिजर्वेशन सिस्टम, सामाजिक साझाकरण, वीडियो स्ट्रीमिंग, चैट समर्थन, सुरक्षा सुविधाएँ, प्रदर्शन निगरानी',
                    'ja' => 'ユーザー認証, ソーシャルメディア統合, リアルタイムメッセージング, 支払いゲートウェイ, コンテンツ管理システム, 検索機能, ユーザープロファイル, 分析ダッシュボード, 通知システム, ジオロケーションサービス, ファイルのアップロード/ダウンロード, 多言語対応, API統合, 管理パネル, ユーザーレビュー/評価, サブスクリプション管理, 在庫管理, 予約システム, ソーシャル共有, ビデオストリーミング, チャットサポート, セキュリティ機能, パフォーマンス監視',
                    'ko' => '사용자 인증, 소셜 미디어 통합, 실시간 메시징, 결제 게이트웨이, 콘텐츠 관리 시스템, 검색 기능, 사용자 프로필, 분석 대시보드, 알림 시스템, 지리적 위치 서비스, 파일 업로드/다운로드, 다국어 지원, API 통합, 관리자 패널, 사용자 리뷰/평점, 구독 관리, 재고 관리, 예약 시스템, 소셜 공유, 비디오 스트리밍, 채팅 지원, 보안 기능, 성능 모니터링',
                    'pt' => 'Autenticação de Usuários, Integração de Mídias Sociais, Mensagens em Tempo Real, Gateway de Pagamento, Sistema de Gerenciamento de Conteúdo, Funcionalidade de Pesquisa, Perfis de Usuário, Painel de Análise, Sistema de Notificação, Serviços de Geolocalização, Upload/Download de Arquivos, Suporte Multilíngue, Integração de API, Painel de Administração, Avaliações/Resenhas de Usuários, Gerenciamento de Assinaturas, Gerenciamento de Inventário, Sistema de Reserva/Agendamento, Compartilhamento Social, Transmissão de Vídeo, Suporte de Chat, Funcionalidades de Segurança, Monitoramento de Desempenho',
                    'th' => 'การรับรองความถูกต้องของผู้ใช้, การรวมโซเชียลมีเดีย, การส่งข้อความแบบเรียลไทม์, เกตเวย์การชำระเงิน, ระบบการจัดการเนื้อหา, ฟังก์ชันการค้นหา, โปรไฟล์ผู้ใช้, แดชบอร์ดการวิเคราะห์, ระบบการแจ้งเตือน, บริการระบุตำแหน่งทางภูมิศาสตร์, อัปโหลด/ดาวน์โหลดไฟล์, รองรับหลายภาษา, การรวม API, แผงควบคุม, รีวิว/การให้คะแนนของผู้ใช้, การจัดการการสมัครสมาชิก, การจัดการสินค้าคงคลัง, ระบบการจอง, การแชร์บนโซเชียล, สตรีมวิดีโอ, การสนับสนุนการแชท, ฟีเจอร์ด้านความปลอดภัย, การตรวจสอบประสิทธิภาพ',
                    'tl' => 'Pagpapatunay ng User, Pagsasama ng Social Media, Pagmemensahe sa Real-time, Payment Gateway, Content Management System, Functionality sa Paghahanap, Mga Profile ng User, Dashboard ng Analytics, Sistema ng Pag-abiso, Mga Serbisyo ng Geolocation, Pag-upload/Pag-download ng File, Suporta sa Multi-language, Pagsasama ng API, Admin Panel, Mga Review/Ratings ng User, Pamamahala sa Subscription, Pamamahala ng Imbentaryo, Booking/Reservation System, Social Sharing, Video Streaming, Suporta ng Chat, Mga Tampok sa Seguridad, Pagmamanman ng Pagganap',
                    'zh' => '用户认证, 社交媒体集成, 实时消息传递, 支付网关, 内容管理系统, 搜索功能, 用户资料, 分析仪表盘, 通知系统, 地理定位服务, 文件上传/下载, 多语言支持, API 集成, 管理面板, 用户评论/评分, 订阅管理, 库存管理, 预订系统, 社交分享, 视频流媒体, 聊天支持, 安全功能, 性能监控',
                ]),
                ]
                ]
                ],
                [
                "type" => "generate_excel",
                "step_number" => 2,
                "uuid" => $generateUuid(),
                "prompt" => "Generate a comprehensive Development Requirements Specification for a {{step1.input.Project_Type}} project incorporating {{step1.input.Key_Features}}. Follow these guidelines:
                
                User Roles: Define clear user roles (e.g., End-User, Administrator, Moderator) based on the project type and features.
                Feature Categorization: Organize features into Primary Categories (e.g., User Management, Content Management), Secondary Categories (e.g., Authentication, Profile Management), and Tertiary Categories (e.g., Email Verification, Password Reset) where applicable.
                Detailed Functionality: For each feature, provide:
                
                A concise name/title
                A detailed description of its purpose and functionality
                Any specific requirements or constraints
                Potential integration points with other features
                
                
                Non-Functional Requirements: Include relevant non-functional requirements such as:
                
                Performance expectations
                Security considerations
                Scalability requirements
                Compatibility and platform support
                Accessibility standards to be met
                
                
                Technical Considerations: Highlight any specific technical considerations or challenges for each feature, such as:
                
                Proposed technology stack or frameworks
                Third-party services or APIs to be integrated
                Data storage and management approaches
                
                
                User Experience: Provide high-level UX guidelines or considerations for key features, focusing on usability and interface design principles.
                Regulatory Compliance: If applicable, note any regulatory requirements (e.g., GDPR, CCPA) that impact feature implementation.
                Prioritization: Assign a priority level (e.g., Must-Have, Should-Have, Nice-to-Have) to each feature or sub-feature.
                Dependencies: Identify any dependencies between features or external systems.
                Future Considerations: Note any potential future expansions or iterations of key features.
                
                Ensure the specification is detailed, professionally formatted, and provides a clear roadmap for development teams to understand project requirements and scope.",
                "background_information" => "You are an experienced Software Requirements Specialist with expertise in creating detailed, actionable development requirement specifications across various types of software projects.",
                "ai_provider" => "openai",
                "ai_model" => "gpt-4o-2024-05-13",
                "temperature" => 0.7,
                "excel_columns" => [
                [
                "sheet_name" => "Requirements Spec",
                "global_header_background" => "#6466de",
                "global_header_text_color" => "#ffffff",
                "global_header_height" => "20",
                "global_header_alignment" => "center",
                "columns" => [
                [
                "name" => "User Role",
                "description" => "Specific user type (e.g., End-User, Admin, Guest)",
                "width" => "30",
                "alignment" => "center",
                "merge_duplicates" => true
                ],
                [
                "name" => "Primary Category",
                "description" => "Main feature category (e.g., User Management, Content Management)",
                "width" => "30",
                "alignment" => "center",
                "merge_duplicates" => true
                ],
                [
                "name" => "Secondary Category",
                "description" => "Sub-category of the primary feature (e.g., Authentication, Profile Management)",
                "width" => "35",
                "alignment" => "center",
                "merge_duplicates" => true
                ],
                [
                "name" => "Tertiary Category",
                "description" => "Further categorization if needed (e.g., Email Verification, Password Reset)",
                "width" => "35",
                "alignment" => "center",
                "merge_duplicates" => true
                ],
                [
                "name" => "Feature Name",
                "description" => "Concise title of the specific feature or functionality",
                "width" => "40",
                "alignment" => "left",
                "merge_duplicates" => false
                ],
                [
                "name" => "Description",
                "description" => "Detailed explanation of the feature's purpose and functionality",
                "width" => "120",
                "alignment" => "left",
                "merge_duplicates" => false
                ],
                [
                "name" => "Requirements",
                "description" => "Specific requirements or constraints for the feature",
                "width" => "80",
                "alignment" => "left",
                "merge_duplicates" => false
                ],
                [
                "name" => "Technical Considerations",
                "description" => "Relevant technical details, integrations, or challenges",
                "width" => "80",
                "alignment" => "left",
                "merge_duplicates" => false
                ],
                [
                "name" => "Priority",
                "description" => "Importance level (e.g., Must-Have, Should-Have, Nice-to-Have)",
                "width" => "25",
                "alignment" => "center",
                "merge_duplicates" => false
                ],
                [
                "name" => "Dependencies",
                "description" => "Related features or systems this feature depends on",
                "width" => "60",
                "alignment" => "left",
                "merge_duplicates" => false
                ],
                [
                "name" => "Non-Functional Requirements",
                "description" => "Performance, security, scalability, or other non-functional aspects",
                "width" => "80",
                "alignment" => "left",
                "merge_duplicates" => false
                ]
                ]
                ]
                ]
                ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "development_requirements_definition", "excel_generation"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Projektplan Excel-Generator',
                    'en' => 'Project Plan Excel Generator',
                    'fr' => 'Générateur Excel de Plan de Projet',
                    'hi' => 'परियोजना योजना एक्सेल जनरेटर',
                    'ja' => 'プロジェクト計画Excelジェネレーター',
                    'ko' => '프로젝트 계획 Excel 생성기',
                    'pt' => 'Gerador Excel de Plano de Projeto',
                    'th' => 'เครื่องมือสร้างแผนโครงการในรูปแบบ Excel',
                    'tl' => 'Tagabuo ng Excel para sa Plano ng Proyekto',
                    'zh' => '项目计划Excel生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Generieren Sie einen umfassenden Projektplan im Excel-Format basierend auf Projekttyp, Umfang und Hauptzielen',
                    'en' => 'Generate a comprehensive Excel-format Project Plan based on project type, scale, and key objectives',
                    'fr' => 'Générez un plan de projet complet au format Excel basé sur le type de projet, l\'échelle et les objectifs clés',
                    'hi' => 'परियोजना के प्रकार, पैमाने और प्रमुख उद्देश्यों के आधार पर एक व्यापक एक्सेल-प्रारूप परियोजना योजना उत्पन्न करें',
                    'ja' => 'プロジェクトタイプ、規模、主要目標に基づいて包括的なExcel形式のプロジェクト計画を生成します',
                    'ko' => '프로젝트 유형, 규모 및 주요 목표를 기반으로 포괄적인 Excel 형식의 프로젝트 계획을 생성합니다',
                    'pt' => 'Gere um Plano de Projeto abrangente em formato Excel com base no tipo de projeto, escala e objetivos principais',
                    'th' => 'สร้างแผนโครงการแบบครอบคลุมในรูปแบบ Excel ตามประเภทโครงการ ขนาด และวัตถุประสงค์หลัก',
                    'tl' => 'Bumuo ng komprehensibong Plano ng Proyekto sa format ng Excel batay sa uri ng proyekto, saklaw, at pangunahing mga layunin',
                    'zh' => '根据项目类型、规模和关键目标生成全面的Excel格式项目计划',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "Project Type",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Wählen Sie den Typ des Projekts aus, das Sie planen',
                                    'en' => "Select the type of project you're planning",
                                    'fr' => 'Sélectionnez le type de projet que vous planifiez',
                                    'hi' => 'आप जिस प्रकार की परियोजना की योजना बना रहे हैं, उसका चयन करें',
                                    'ja' => '計画しているプロジェクトのタイプを選択してください',
                                    'ko' => '계획 중인 프로젝트의 유형을 선택하세요',
                                    'pt' => 'Selecione o tipo de projeto que você está planejando',
                                    'th' => 'เลือกประเภทของโครงการที่คุณกำลังวางแผน',
                                    'tl' => 'Piliin ang uri ng proyekto na iyong pinaplano',
                                    'zh' => '选择您正在计划的项目类型',
                                ]),
                                "type" => "radio",
                                "options" => $this->getLocalizedText([
                                    'de' => 'Softwareentwicklung, Marketingkampagne, Produkteinführung, Geschäftsausweitung, Forschungsprojekt, Eventplanung, Infrastruktur-Upgrade, Organisationsrestrukturierung, Fusion und Übernahme, Compliance-Implementierung, Digitale Transformation, Markteintritt, Kundenbindungsstrategie',
                                    'en' => 'Software Development, Marketing Campaign, Product Launch, Business Expansion, Research Project, Event Planning, Infrastructure Upgrade, Organizational Restructuring, Merger and Acquisition, Compliance Implementation, Digital Transformation, New Market Entry, Customer Engagement Strategy',
                                    'fr' => 'Développement de logiciel, Campagne marketing, Lancement de produit, Expansion commerciale, Projet de recherche, Planification d\'événement, Mise à niveau de l\'infrastructure, Restructuration organisationnelle, Fusion et acquisition, Mise en conformité, Transformation numérique, Entrée sur un nouveau marché, Stratégie d\'engagement client',
                                    'hi' => 'सॉफ़्टवेयर विकास, मार्केटिंग अभियान, उत्पाद लॉन्च, व्यापार विस्तार, शोध परियोजना, इवेंट योजना, इन्फ्रास्ट्रक्चर अपग्रेड, संगठनात्मक पुनर्गठन, विलय और अधिग्रहण, अनुपालन कार्यान्वयन, डिजिटल परिवर्तन, नए बाजार में प्रवेश, ग्राहक सहभागिता रणनीति',
                                    'ja' => 'ソフトウェア開発, マーケティングキャンペーン, 製品ローンチ, 事業拡大, 研究プロジェクト, イベント計画, インフラアップグレード, 組織再編, 合併と買収, コンプライアンスの実施, デジタルトランスフォーメーション, 新市場参入, 顧客エンゲージメント戦略',
                                    'ko' => '소프트웨어 개발, 마케팅 캠페인, 제품 출시, 비즈니스 확장, 연구 프로젝트, 이벤트 기획, 인프라 업그레이드, 조직 재구성, 인수 합병, 컴플라이언스 구현, 디지털 전환, 신시장 진입, 고객 참여 전략',
                                    'pt' => 'Desenvolvimento de Software, Campanha de Marketing, Lançamento de Produto, Expansão de Negócios, Projeto de Pesquisa, Planejamento de Eventos, Atualização de Infraestrutura, Reestruturação Organizacional, Fusão e Aquisição, Implementação de Compliance, Transformação Digital, Entrada em Novo Mercado, Estratégia de Engajamento do Cliente',
                                    'th' => 'การพัฒนาซอฟต์แวร์, แคมเปญการตลาด, การเปิดตัวผลิตภัณฑ์, การขยายธุรกิจ, โครงการวิจัย, การวางแผนกิจกรรม, การอัปเกรดโครงสร้างพื้นฐาน, การปรับโครงสร้างองค์กร, การควบรวมกิจการ, การดำเนินการปฏิบัติตามกฎระเบียบ, การเปลี่ยนแปลงทางดิจิทัล, การเข้าสู่ตลาดใหม่, กลยุทธ์การมีส่วนร่วมของลูกค้า',
                                    'tl' => 'Pag-unlad ng Software, Kampanya sa Marketing, Paglulunsad ng Produkto, Pagpapalawak ng Negosyo, Proyekto sa Pananaliksik, Pagpaplano ng Kaganapan, Pag-upgrade ng Imprastruktura, Pagbabagong Pampangkat, Pagsasama at Pagkuha, Pagpapatupad ng Pagsunod, Digital na Pagbabago, Pagpasok sa Bagong Pamilihan, Estratehiya ng Pakikipag-ugnayan ng Customer',
                                    'zh' => '软件开发, 营销活动, 产品发布, 业务扩展, 研究项目, 活动策划, 基础设施升级, 组织重组, 并购, 合规实施, 数字化转型, 新市场进入, 客户参与策略',
                                ]),
                                "placeholder" => "Choose the most appropriate project type..."
                            ],
                            [
                                "label" => "Project Scale",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Wählen Sie den Umfang Ihres Projekts aus',
                                    'en' => 'Select the scale of your project',
                                    'fr' => 'Sélectionnez l\'échelle de votre projet',
                                    'hi' => 'अपनी परियोजना के पैमाने का चयन करें',
                                    'ja' => 'プロジェクトの規模を選択してください',
                                    'ko' => '프로젝트의 규모를 선택하세요',
                                    'pt' => 'Selecione a escala do seu projeto',
                                    'th' => 'เลือกขนาดของโครงการของคุณ',
                                    'tl' => 'Piliin ang saklaw ng iyong proyekto',
                                    'zh' => '选择您项目的规模',
                                ]),
                                "type" => "radio",
                                "options" => $this->getLocalizedText([
                                    'de' => 'Klein (1-3 Monate), Mittel (3-6 Monate), Groß (6-12 Monate), Enterprise (1+ Jahre)',
                                    'en' => 'Small (1-3 months), Medium (3-6 months), Large (6-12 months), Enterprise (1+ years)',
                                    'fr' => 'Petit (1-3 mois), Moyen (3-6 mois), Grand (6-12 mois), Entreprise (1+ ans)',
                                    'hi' => 'छोटा (1-3 महीने), मध्यम (3-6 महीने), बड़ा (6-12 महीने), एंटरप्राइज (1+ साल)',
                                    'ja' => '小規模 (1-3ヶ月), 中規模 (3-6ヶ月), 大規模 (6-12ヶ月), エンタープライズ (1年以上)',
                                    'ko' => '소규모 (1-3개월), 중간 규모 (3-6개월), 대규모 (6-12개월), 엔터프라이즈 (1년 이상)',
                                    'pt' => 'Pequeno (1-3 meses), Médio (3-6 meses), Grande (6-12 meses), Empresarial (1+ anos)',
                                    'th' => 'ขนาดเล็ก (1-3 เดือน), ขนาดกลาง (3-6 เดือน), ขนาดใหญ่ (6-12 เดือน), องค์กร (1+ ปี)',
                                    'tl' => 'Maliit (1-3 buwan), Katamtaman (3-6 buwan), Malaki (6-12 buwan), Pang-enterprise (1+ taon)',
                                    'zh' => '小型 (1-3个月), 中型 (3-6个月), 大型 (6-12个月), 企业级 (1年以上)',
                                ]),
                                "placeholder" => "Select the project scale..."
                            ],
                            [
                                "label" => "Key Objectives",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Wählen Sie die Hauptziele Ihres Projekts aus',
                                    'en' => 'Select the main objectives of your project',
                                    'fr' => 'Sélectionnez les principaux objectifs de votre projet',
                                    'hi' => 'अपनी परियोजना के मुख्य उद्देश्यों का चयन करें',
                                    'ja' => 'プロジェクトの主要な目標を選択してください',
                                    'ko' => '프로젝트의 주요 목표를 선택하세요',
                                    'pt' => 'Selecione os principais objetivos do seu projeto',
                                    'th' => 'เลือกวัตถุประสงค์หลักของโครงการของคุณ',
                                    'tl' => 'Piliin ang mga pangunahing layunin ng iyong proyekto',
                                    'zh' => '选择您项目的主要目标',
                                ]),
                                "type" => "multiselect",
                                "options" => $this->getLocalizedText([
                                    'de' => 'Umsatz steigern, Kosten senken, Effizienz verbessern, Kundenzufriedenheit erhöhen, Marktanteil ausbauen, Neues Produkt/Dienstleistung entwickeln, Qualität verbessern, Produktivität steigern, Einhaltung von Vorschriften sicherstellen, Markenbekanntheit steigern, Neue Technologien implementieren, Mitarbeiterzufriedenheit verbessern, Umweltauswirkungen reduzieren, Innovation steigern, Sicherheitsstandards verbessern, Sicherheit erhöhen, Stakeholder-Engagement verbessern, Ressourcenoptimierung',  
                                    'en' => 'Increase Revenue, Reduce Costs, Improve Efficiency, Enhance Customer Satisfaction, Expand Market Share, Develop New Product/Service, Improve Quality, Increase Productivity, Ensure Regulatory Compliance, Enhance Brand Awareness, Implement New Technology, Improve Employee Satisfaction, Reduce Environmental Impact, Increase Innovation, Improve Safety Standards, Enhance Security, Improve Stakeholder Engagement, Optimize Resource Allocation',
                                    'fr' => 'Augmenter les revenus, Réduire les coûts, Améliorer l\'efficacité, Améliorer la satisfaction client, Augmenter la part de marché, Développer un nouveau produit/service, Améliorer la qualité, Augmenter la productivité, Assurer la conformité réglementaire, Augmenter la notoriété de la marque, Mettre en œuvre de nouvelles technologies, Améliorer la satisfaction des employés, Réduire l\'impact environnemental, Stimuler l\'innovation, Améliorer les normes de sécurité, Renforcer la sécurité, Améliorer l\'engagement des parties prenantes, Optimiser l\'allocation des ressources',
                                    'hi' => 'राजस्व बढ़ाएं, लागत कम करें, दक्षता में सुधार करें, ग्राहक संतुष्टि बढ़ाएं, बाजार हिस्सेदारी बढ़ाएं, नया उत्पाद/सेवा विकसित करें, गुणवत्ता में सुधार करें, उत्पादकता बढ़ाएं, नियामक अनुपालन सुनिश्चित करें, ब्रांड जागरूकता बढ़ाएं, नई तकनीक लागू करें, कर्मचारी संतुष्टि में सुधार करें, पर्यावरणीय प्रभाव कम करें, नवाचार बढ़ाएं, सुरक्षा मानकों में सुधार करें, सुरक्षा में वृद्धि करें, हितधारक सहभागिता में सुधार करें, संसाधन आवंटन का अनुकूलन करें',
                                    'ja' => '収益を増やす, コストを削減する, 効率を向上させる, 顧客満足度を高める, 市場シェアを拡大する, 新製品/サービスを開発する, 品質を向上させる, 生産性を向上させる, 規制遵守を確保する, ブランド認知度を向上させる, 新技術を導入する, 従業員満足度を向上させる, 環境への影響を減らす, イノベーションを促進する, 安全基準を改善する, セキュリティを強化する, ステークホルダーエンゲージメントを改善する, リソース配分を最適化する',
                                    'ko' => '수익 증대, 비용 절감, 효율성 개선, 고객 만족도 향상, 시장 점유율 확대, 신제품/서비스 개발, 품질 개선, 생산성 증대, 규정 준수 보장, 브랜드 인지도 향상, 새로운 기술 구현, 직원 만족도 향상, 환경 영향 감소, 혁신 증가, 안전 기준 개선, 보안 강화, 이해관계자 참여 개선, 자원 할당 최적화',
                                    'pt' => 'Aumentar a Receita, Reduzir Custos, Melhorar a Eficiência, Aumentar a Satisfação do Cliente, Expandir a Participação no Mercado, Desenvolver Novo Produto/Serviço, Melhorar a Qualidade, Aumentar a Produtividade, Garantir a Conformidade Regulatória, Aumentar a Conscientização da Marca, Implementar Nova Tecnologia, Melhorar a Satisfação dos Funcionários, Reduzir o Impacto Ambiental, Aumentar a Inovação, Melhorar os Padrões de Segurança, Aumentar a Segurança, Melhorar o Envolvimento das Partes Interessadas, Otimizar a Alocação de Recursos',
                                    'th' => 'เพิ่มรายได้, ลดต้นทุน, ปรับปรุงประสิทธิภาพ, ยกระดับความพึงพอใจของลูกค้า, ขยายส่วนแบ่งการตลาด, พัฒนาผลิตภัณฑ์/บริการใหม่, ปรับปรุงคุณภาพ, เพิ่มผลผลิต, มั่นใจในการปฏิบัติตามกฎระเบียบ, เพิ่มการรับรู้แบรนด์, นำเทคโนโลยีใหม่มาใช้, เพิ่มความพึงพอใจของพนักงาน, ลดผลกระทบต่อสิ่งแวดล้อม, เพิ่มนวัตกรรม, ปรับปรุงมาตรฐานความปลอดภัย, เพิ่มความปลอดภัย, ปรับปรุงการมีส่วนร่วมของผู้มีส่วนได้ส่วนเสีย, เพิ่มประสิทธิภาพการจัดสรรทรัพยากร',
                                    'tl' => 'Dagdagan ang Kita, Bawasan ang Gastos, Pagbutihin ang Kahusayan, Palakasin ang Kasiyahan ng Customer, Palawakin ang Pagbabahagi ng Merkado, Bumuo ng Bagong Produkto/Serbisyo, Pagbutihin ang Kalidad, Dagdagan ang Produktibo, Tiyakin ang Pagsunod sa Regulasyon, Pagbutihin ang Kamalayan ng Brand, Magpatupad ng Bagong Teknolohiya, Pagbutihin ang Kasiyahan ng Empleyado, Bawasan ang Epekto sa Kapaligiran, Dagdagan ang Inobasyon, Pagbutihin ang mga Pamantayan ng Kaligtasan, Pagandahin ang Seguridad, Pagbutihin ang Pakikipag-ugnayan ng Stakeholder, I-optimize ang Alokasyon ng Mapagkukunan',
                                    'zh' => '增加收入, 降低成本, 提高效率, 提升客户满意度, 扩大市场份额, 开发新产品/服务, 提高质量, 提高生产力, 确保合规, 提升品牌知名度, 实施新技术, 提高员工满意度, 减少环境影响, 增加创新, 提高安全标准, 增强安全性, 改善利益相关者参与, 优化资源分配',
                                ]),
                                "placeholder" => "Select one or more key objectives for your project..."
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_excel",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Generate a comprehensive Project Plan for a {{step1.input.Project_Type}} project of {{step1.input.Project_Scale}} scale with the following key objectives: {{step1.input.Key_Objectives}}. Follow these guidelines:
            
                        1. Executive Summary: Provide a brief overview of the project, its goals, and expected outcomes.
            
                        2. Project Scope: Clearly define what is included and excluded from the project.
            
                        3. Objectives and Deliverables: List specific, measurable objectives and tangible deliverables based on the selected key objectives.
            
                        4. Project Timeline: Create a high-level timeline with major milestones (without specific dates).
            
                        5. Resource Allocation: Outline the human, financial, and material resources required for the project.
            
                        6. Task Breakdown: Provide a detailed Work Breakdown Structure (WBS) with main tasks and subtasks.
            
                        7. Risk Assessment: Identify potential risks and mitigation strategies.
            
                        8. Communication Plan: Outline how project information will be communicated to stakeholders.
            
                        9. Budget: Provide a high-level budget breakdown.
            
                        10. Quality Assurance: Describe quality control measures and standards to be maintained.
            
                        11. Stakeholder Analysis: Identify key stakeholders and their roles/responsibilities.
            
                        12. Success Criteria: Define clear criteria for measuring project success based on the selected objectives.
            
                        13. Change Management: Outline procedures for handling changes to the project scope or timeline.
            
                        Ensure the plan is detailed, professionally formatted, and provides a clear roadmap for project execution and management. Do not include specific dates, but rather focus on the sequence and dependencies of tasks and milestones.",
                        "background_information" => "You are an experienced Project Management Professional with expertise in creating comprehensive project plans across various industries and project types.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "excel_columns" => [
                            [
                                "sheet_name" => "Project Plan",
                                "global_header_background" => "#6466de",
                                "global_header_text_color" => "#ffffff",
                                "global_header_height" => "20",
                                "global_header_alignment" => "center",
                                "columns" => [
                                    [
                                        "name" => "Section",
                                        "description" => "Main section of the project plan",
                                        "width" => "30",
                                        "alignment" => "center",
                                        "merge_duplicates" => true
                                    ],
                                    [
                                        "name" => "Subsection",
                                        "description" => "Specific subsection or category within the main section",
                                        "width" => "35",
                                        "alignment" => "center",
                                        "merge_duplicates" => true
                                    ],
                                    [
                                        "name" => "Item",
                                        "description" => "Specific item, task, or point within the subsection",
                                        "width" => "40",
                                        "alignment" => "left",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Description",
                                        "description" => "Detailed explanation or content for the item",
                                        "width" => "120",
                                        "alignment" => "left",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Resources",
                                        "description" => "Human, financial, or material resources required",
                                        "width" => "60",
                                        "alignment" => "left",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Dependencies",
                                        "description" => "Other tasks or factors this item depends on",
                                        "width" => "60",
                                        "alignment" => "left",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Priority",
                                        "description" => "Importance or urgency of the item or task",
                                        "width" => "25",
                                        "alignment" => "center",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Status",
                                        "description" => "Current status of the item or task",
                                        "width" => "25",
                                        "alignment" => "center",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Notes",
                                        "description" => "Additional information, risks, or considerations",
                                        "width" => "80",
                                        "alignment" => "left",
                                        "merge_duplicates" => false
                                    ]
                                ]
                            ]
                        ]
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "project_proposal", "excel_generation"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Marketing-Strategie Excel-Generator',
                    'en' => 'Marketing Strategy Excel Generator',
                    'fr' => 'Générateur Excel de Stratégie Marketing',
                    'hi' => 'मार्केटिंग रणनीति एक्सेल जनरेटर',
                    'ja' => 'マーケティング戦略Excelジェネレーター',
                    'ko' => '마케팅 전략 Excel 생성기',
                    'pt' => 'Gerador Excel de Estratégia de Marketing',
                    'th' => 'เครื่องมือสร้างกลยุทธ์การตลาดในรูปแบบ Excel',
                    'tl' => 'Tagabuo ng Excel para sa Istratehiya ng Marketing',
                    'zh' => '营销策略Excel生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Generieren Sie ein umfassendes Marketing-Strategiedokument im Excel-Format basierend auf Kampagnentyp, Zielgruppe, Budget und Hauptzielen',
                    'en' => 'Generate a comprehensive Excel-format Marketing Strategy Document based on campaign type, target audience, budget, and key objectives',
                    'fr' => 'Générez un document de stratégie marketing complet au format Excel basé sur le type de campagne, le public cible, le budget et les objectifs clés',
                    'hi' => 'अभियान प्रकार, लक्षित दर्शकों, बजट और प्रमुख उद्देश्यों के आधार पर एक व्यापक एक्सेल-प्रारूप मार्केटिंग रणनीति दस्तावेज़ उत्पन्न करें',
                    'ja' => 'キャンペーンタイプ、ターゲット層、予算、主要目標に基づいて包括的なExcel形式のマーケティング戦略文書を生成します',
                    'ko' => '캠페인 유형, 대상 고객, 예산 및 주요 목표를 기반으로 포괄적인 Excel 형식의 마케팅 전략 문서를 생성합니다',
                    'pt' => 'Gere um Documento de Estratégia de Marketing abrangente em formato Excel com base no tipo de campanha, público-alvo, orçamento e objetivos principais',
                    'th' => 'สร้างเอกสารกลยุทธ์การตลาดแบบครอบคลุมในรูปแบบ Excel ตามประเภทแคมเปญ กลุ่มเป้าหมาย งบประมาณ และวัตถุประสงค์หลัก',
                    'tl' => 'Bumuo ng komprehensibong Dokumento ng Istratehiya sa Marketing sa format ng Excel batay sa uri ng kampanya, target na audience, badyet, at pangunahing mga layunin',
                    'zh' => '根据营销活动类型、目标受众、预算和关键目标生成全面的Excel格式营销策略文档',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "Campaign Type",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Wählen Sie den Typ der Marketingkampagne aus',
                                    'en' => 'Select the type of marketing campaign',
                                    'fr' => 'Sélectionnez le type de campagne marketing',
                                    'hi' => 'मार्केटिंग अभियान का प्रकार चुनें',
                                    'ja' => 'マーケティングキャンペーンのタイプを選択してください',
                                    'ko' => '마케팅 캠페인의 유형을 선택하세요',
                                    'pt' => 'Selecione o tipo de campanha de marketing',
                                    'th' => 'เลือกประเภทของแคมเปญการตลาด',
                                    'tl' => 'Piliin ang uri ng kampanya sa marketing',
                                    'zh' => '选择营销活动的类型',
                                ]),
                                "type" => "radio",
                                "options" => $this->getLocalizedText([
                                    'de' => 'Produkteinführung, Markenbekanntheit, Lead-Generierung, Kundenbindung, Verkaufsförderung, Content-Marketing, Social-Media-Kampagne, E-Mail-Marketing, SEO/SEM-Kampagne, Influencer-Marketing, Event-Marketing, Affiliate-Marketing, Empfehlungsprogramm',
                                    'en' => 'Product Launch, Brand Awareness, Lead Generation, Customer Retention, Sales Promotion, Content Marketing, Social Media Campaign, Email Marketing, SEO/SEM Campaign, Influencer Marketing, Event Marketing, Affiliate Marketing, Referral Program',
                                    'fr' => 'Lancement de produit, Notoriété de la marque, Génération de leads, Fidélisation client, Promotion des ventes, Marketing de contenu, Campagne sur les réseaux sociaux, Marketing par e-mail, Campagne SEO/SEM, Marketing d\'influence, Marketing événementiel, Marketing d\'affiliation, Programme de parrainage',
                                    'hi' => 'उत्पाद लॉन्च, ब्रांड जागरूकता, लीड जनरेशन, ग्राहक प्रतिधारण, बिक्री संवर्धन, सामग्री विपणन, सोशल मीडिया अभियान, ईमेल विपणन, SEO/SEM अभियान, प्रभावशाली विपणन, इवेंट विपणन, सहबद्ध विपणन, रेफरल कार्यक्रम',
                                    'ja' => '製品ローンチ, ブランド認知, リード生成, 顧客維持, セールスプロモーション, コンテンツマーケティング, ソーシャルメディアキャンペーン, メールマーケティング, SEO/SEMキャンペーン, インフルエンサーマーケティング, イベントマーケティング, アフィリエイトマーケティング, リファラルプログラム',
                                    'ko' => '제품 출시, 브랜드 인지도, 리드 생성, 고객 유지, 판촉, 콘텐츠 마케팅, 소셜 미디어 캠페인, 이메일 마케팅, SEO/SEM 캠페인, 인플루언서 마케팅, 이벤트 마케팅, 제휴 마케팅, 추천 프로그램',
                                    'pt' => 'Lançamento de Produto, Conscientização da Marca, Geração de Leads, Retenção de Clientes, Promoção de Vendas, Marketing de Conteúdo, Campanha de Mídia Social, E-mail Marketing, Campanha de SEO/SEM, Marketing de Influenciadores, Marketing de Eventos, Marketing de Afiliados, Programa de Indicações',
                                    'th' => 'เปิดตัวผลิตภัณฑ์, การรับรู้แบรนด์, การสร้างลูกค้าเป้าหมาย, การรักษาลูกค้า, การส่งเสริมการขาย, การตลาดเนื้อหา, แคมเปญโซเชียลมีเดีย, การตลาดทางอีเมล, แคมเปญ SEO/SEM, การตลาดผู้มีอิทธิพล, การตลาดผ่านกิจกรรม, การตลาดแบบพันธมิตร, โปรแกรมการอ้างอิง',
                                    'tl' => 'Paglulunsad ng Produkto, Kamalayan sa Brand, Pagbuo ng Leads, Pagpapanatili ng Customer, Promosyon sa Pagbebenta, Content Marketing, Kampanya sa Social Media, Email Marketing, Kampanya ng SEO/SEM, Influencer Marketing, Event Marketing, Affiliate Marketing, Referral Program',
                                    'zh' => '产品发布, 品牌认知, 潜在客户生成, 客户保持, 促销, 内容营销, 社交媒体活动, 电子邮件营销, SEO/SEM 活动, 影响者营销, 事件营销, 联盟营销, 推荐计划',
                                ]),
                                "placeholder" => "Choose the most appropriate campaign type..."
                            ],
                            [
                                "label" => "Target Audience",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Wählen Sie die primäre Zielgruppe für Ihre Kampagne aus',
                                    'en' => 'Select the primary target audience for your campaign',
                                    'fr' => 'Sélectionnez le public cible principal pour votre campagne',
                                    'hi' => 'अपने अभियान के लिए प्राथमिक लक्षित दर्शकों का चयन करें',
                                    'ja' => 'キャンペーンの主要なターゲット層を選択してください',
                                    'ko' => '캠페인의 주요 대상 고객을 선택하세요',
                                    'pt' => 'Selecione o público-alvo principal para sua campanha',
                                    'th' => 'เลือกกลุ่มเป้าหมายหลักสำหรับแคมเปญของคุณ',
                                    'tl' => 'Piliin ang pangunahing target na audience para sa iyong kampanya',
                                    'zh' => '选择您营销活动的主要目标受众',
                                ]),
                                "type" => "radio",
                                "options" => $this->getLocalizedText([
                                    'de' => 'B2C Allgemeine Verbraucher, B2B Unternehmen, Millennials, Generation Z, Babyboomer, Kleine Geschäftsinhaber, C-Level-Führungskräfte, Technikbegeisterte, Eltern, Studenten, Freiberufler, Gesundheitsfachkräfte, Non-Profit-Organisationen',
                                    'en' => 'B2C General Consumers, B2B Businesses, Millennials, Gen Z, Baby Boomers, Small Business Owners, C-Level Executives, Tech Enthusiasts, Parents, Students, Freelancers, Healthcare Professionals, Non-Profit Organizations',
                                    'fr' => 'B2C Consommateurs généraux, B2B Entreprises, Millennials, Génération Z, Baby-boomers, Propriétaires de petites entreprises, Cadres C-Level, Passionnés de technologie, Parents, Étudiants, Freelancers, Professionnels de la santé, Organisations à but non lucratif',
                                    'hi' => 'B2C सामान्य उपभोक्ता, B2B व्यवसाय, मिलेनियल्स, जेन जेड, बेबी बूमर्स, छोटे व्यवसाय के मालिक, सी-लेवल कार्यकारी, टेक उत्साही, माता-पिता, छात्र, फ्रीलांसर, स्वास्थ्य देखभाल पेशेवर, गैर-लाभकारी संगठन',
                                    'ja' => 'B2C 一般消費者, B2B 企業, ミレニアル世代, Z世代, ベビーブーマー, 小規模ビジネスオーナー, Cレベルの経営者, テクノロジー愛好家, 親, 学生, フリーランサー, 医療従事者, 非営利団体',
                                    'ko' => 'B2C 일반 소비자, B2B 기업, 밀레니얼 세대, Z세대, 베이비붐 세대, 소규모 사업주, C레벨 임원, 기술 애호가, 부모, 학생, 프리랜서, 의료 전문가, 비영리 단체',
                                    'pt' => 'B2C Consumidores Gerais, B2B Negócios, Millennials, Geração Z, Baby Boomers, Pequenos Empresários, Executivos de Nível C, Entusiastas de Tecnologia, Pais, Estudantes, Freelancers, Profissionais de Saúde, Organizações Sem Fins Lucrativos',
                                    'th' => 'B2C ผู้บริโภคทั่วไป, B2B ธุรกิจ, คนรุ่นมิลเลนเนียล, Gen Z, เบบี้บูมเมอร์, เจ้าของธุรกิจขนาดเล็ก, ผู้บริหารระดับ C, ผู้ที่ชื่นชอบเทคโนโลยี, ผู้ปกครอง, นักเรียน, ฟรีแลนซ์, ผู้เชี่ยวชาญด้านสุขภาพ, องค์กรไม่แสวงหาผลกำไร',
                                    'tl' => 'B2C Pangkalahatang Consumer, B2B Negosyo, Millennials, Gen Z, Baby Boomers, Maliit na May-ari ng Negosyo, C-Level Executive, Mga Tagahanga ng Teknolohiya, Mga Magulang, Mga Estudyante, Freelancer, Mga Propesyonal sa Pangangalagang Pangkalusugan, Mga Non-Profit na Organisasyon',
                                    'zh' => 'B2C 普通消费者, B2B 企业, 千禧一代, Z 世代, 婴儿潮一代, 小企业主, C 级高管, 技术爱好者, 父母, 学生, 自由职业者, 医疗专业人员, 非营利组织',
                                ]),
                                "placeholder" => "Select your primary target audience..."
                            ],
                            [
                                "label" => "Budget Range",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Wählen Sie den Budgetbereich für Ihre Marketingkampagne aus',
                                    'en' => 'Select the budget range for your marketing campaign',
                                    'fr' => 'Sélectionnez la fourchette budgétaire pour votre campagne marketing',
                                    'hi' => 'अपने मार्केटिंग अभियान के लिए बजट सीमा का चयन करें',
                                    'ja' => 'マーケティングキャンペーンの予算範囲を選択してください',
                                    'ko' => '마케팅 캠페인의 예산 범위를 선택하세요',
                                    'pt' => 'Selecione a faixa de orçamento para sua campanha de marketing',
                                    'th' => 'เลือกช่วงงบประมาณสำหรับแคมเปญการตลาดของคุณ',
                                    'tl' => 'Piliin ang hanay ng badyet para sa iyong kampanya sa marketing',
                                    'zh' => '选择您营销活动的预算范围',
                                ]),
                                "type" => "radio",
                                "options" => $this->getLocalizedText([
                                    'de' => 'Niedriges Budget (1.000 - 10.000 $), Mittleres Budget (10.000 - 50.000 $), Hohes Budget (50.000 - 200.000 $), Enterprise-Budget (200.000 $+)',
                                    'en' => 'Low Budget ($1,000 - $10,000), Medium Budget ($10,000 - $50,000), High Budget ($50,000 - $200,000), Enterprise Budget ($200,000+)',
                                    'fr' => 'Petit Budget (1 000 - 10 000 $), Budget Moyen (10 000 - 50 000 $), Budget Élevé (50 000 - 200 000 $), Budget Entreprise (200 000 $+)',
                                    'hi' => 'कम बजट ($1,000 - $10,000), मध्यम बजट ($10,000 - $50,000), उच्च बजट ($50,000 - $200,000), एंटरप्राइज बजट ($200,000+)',
                                    'ja' => '低予算 ($1,000 - $10,000), 中予算 ($10,000 - $50,000), 高予算 ($50,000 - $200,000), エンタープライズ予算 ($200,000+)',
                                    'ko' => '저예산 ($1,000 - $10,000), 중간 예산 ($10,000 - $50,000), 고예산 ($50,000 - $200,000), 엔터프라이즈 예산 ($200,000+)',
                                    'pt' => 'Baixo Orçamento ($1.000 - $10.000), Orçamento Médio ($10.000 - $50.000), Alto Orçamento ($50.000 - $200.000), Orçamento Empresarial ($200.000+)',
                                    'th' => 'งบประมาณต่ำ ($1,000 - $10,000), งบประมาณปานกลาง ($10,000 - $50,000), งบประมาณสูง ($50,000 - $200,000), งบประมาณองค์กร ($200,000+)',
                                    'tl' => 'Mababang Badyet ($1,000 - $10,000), Katamtamang Badyet ($10,000 - $50,000), Mataas na Badyet ($50,000 - $200,000), Enterprise Badyet ($200,000+)',
                                    'zh' => '低预算 ($1,000 - $10,000), 中等预算 ($10,000 - $50,000), 高预算 ($50,000 - $200,000), 企业预算 ($200,000+)',
                                ]),
                                "placeholder" => "Select your budget range..."
                            ],
                            [
                                "label" => "Key Objectives",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Wählen Sie die Hauptziele Ihrer Marketingkampagne aus',
                                    'en' => 'Select the main objectives of your marketing campaign',
                                    'fr' => 'Sélectionnez les principaux objectifs de votre campagne marketing',
                                    'hi' => 'अपने मार्केटिंग अभियान के मुख्य उद्देश्यों का चयन करें',
                                    'ja' => 'マーケティングキャンペーンの主要な目標を選択してください',
                                    'ko' => '마케팅 캠페인의 주요 목표를 선택하세요',
                                    'pt' => 'Selecione os principais objetivos da sua campanha de marketing',
                                    'th' => 'เลือกวัตถุประสงค์หลักของแคมเปญการตลาดของคุณ',
                                    'tl' => 'Piliin ang mga pangunahing layunin ng iyong kampanya sa marketing',
                                    'zh' => '选择您营销活动的主要目标',
                                ]),
                                "type" => "multiselect",
                                "options" => $this->getLocalizedText([
                                    'de' => 'Markenbekanntheit steigern, Leads generieren, Umsatz steigern, Kundenbindung verbessern, Online-Präsenz erhöhen, Website-Traffic steigern, Konversionsraten verbessern, Kundenloyalität aufbauen, Marktanteil ausbauen, Neues Produkt/Dienstleistung einführen, Soziale Medien Follower erhöhen, Kundenzufriedenheit steigern, Gemeinschaftsengagement stärken',
                                    'en' => 'Increase Brand Awareness, Generate Leads, Boost Sales, Improve Customer Engagement, Enhance Online Presence, Increase Website Traffic, Improve Conversion Rates, Build Customer Loyalty, Expand Market Share, Introduce New Product/Service, Increase Social Media Followers, Enhance Customer Satisfaction, Strengthen Community Engagement',
                                    'fr' => 'Augmenter la notoriété de la marque, Générer des leads, Augmenter les ventes, Améliorer l\'engagement client, Renforcer la présence en ligne, Augmenter le trafic sur le site web, Améliorer les taux de conversion, Renforcer la fidélité des clients, Augmenter la part de marché, Lancer un nouveau produit/service, Augmenter les abonnés sur les réseaux sociaux, Améliorer la satisfaction des clients, Renforcer l\'engagement communautaire',
                                    'hi' => 'ब्रांड जागरूकता बढ़ाएं, लीड उत्पन्न करें, बिक्री बढ़ाएं, ग्राहक सहभागिता में सुधार करें, ऑनलाइन उपस्थिति बढ़ाएं, वेबसाइट ट्रैफ़िक बढ़ाएं, रूपांतरण दर में सुधार करें, ग्राहक वफादारी बनाएं, बाजार हिस्सेदारी का विस्तार करें, नया उत्पाद/सेवा पेश करें, सोशल मीडिया फॉलोअर्स बढ़ाएं, ग्राहक संतुष्टि में सुधार करें, सामुदायिक जुड़ाव को मजबूत करें',
                                    'ja' => 'ブランド認知度を向上させる, リードを生成する, 売上を増加させる, 顧客エンゲージメントを向上させる, オンラインプレゼンスを強化する, ウェブサイトトラフィックを増加させる, コンバージョン率を向上させる, 顧客ロイヤルティを構築する, 市場シェアを拡大する, 新しい製品/サービスを導入する, ソーシャルメディアフォロワーを増加させる, 顧客満足度を向上させる, コミュニティエンゲージメントを強化する',
                                    'ko' => '브랜드 인지도 향상, 리드 생성, 매출 증대, 고객 참여 개선, 온라인 존재 강화, 웹사이트 트래픽 증가, 전환율 개선, 고객 충성도 구축, 시장 점유율 확대, 신제품/서비스 도입, 소셜 미디어 팔로워 증가, 고객 만족도 향상, 커뮤니티 참여 강화',
                                    'pt' => 'Aumentar o Reconhecimento da Marca, Gerar Leads, Aumentar Vendas, Melhorar o Engajamento do Cliente, Aumentar a Presença Online, Aumentar o Tráfego do Site, Melhorar as Taxas de Conversão, Construir Lealdade do Cliente, Expandir a Participação de Mercado, Introduzir Novo Produto/Serviço, Aumentar Seguidores nas Redes Sociais, Melhorar a Satisfação do Cliente, Fortalecer o Engajamento Comunitário',
                                    'th' => 'เพิ่มการรับรู้แบรนด์, สร้างลูกค้าเป้าหมาย, ส่งเสริมยอดขาย, ปรับปรุงการมีส่วนร่วมของลูกค้า, เพิ่มสถานะออนไลน์, เพิ่มการเข้าชมเว็บไซต์, ปรับปรุงอัตราการแปลง, สร้างความภักดีของลูกค้า, ขยายส่วนแบ่งการตลาด, แนะนำผลิตภัณฑ์/บริการใหม่, เพิ่มจำนวนผู้ติดตามในโซเชียลมีเดีย, เพิ่มความพึงพอใจของลูกค้า, เสริมสร้างการมีส่วนร่วมของชุมชน',
                                    'tl' => 'Dagdagan ang Kamalayan sa Brand, Bumuo ng Mga Lead, Palakasin ang Pagbebenta, Pagbutihin ang Pakikipag-ugnayan ng Customer, Palakasin ang Presensya sa Online, Dagdagan ang Trapiko sa Website, Pagbutihin ang Mga Rate ng Conversion, Bumuo ng Katapatan ng Customer, Palawakin ang Bahagi ng Merkado, Ipakilala ang Bagong Produkto/Serbisyo, Dagdagan ang Mga Tagasunod sa Social Media, Pagandahin ang Kasiyahan ng Customer, Palakasin ang Pakikipag-ugnayan sa Komunidad',
                                    'zh' => '提高品牌知名度, 生成潜在客户, 提升销售, 提高客户参与度, 增强在线存在感, 增加网站流量, 提高转化率, 建立客户忠诚度, 扩大市场份额, 推出新产品/服务, 增加社交媒体粉丝, 提高客户满意度, 加强社区参与',
                                ]),
                                "placeholder" => "Select one or more key objectives for your campaign..."
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_excel",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Generate a comprehensive Marketing Strategy Document for a {{step1.input.Campaign_Type}} campaign targeting {{step1.input.Target_Audience}} with a {{step1.input.Budget_Range}} and the following key objectives: {{step1.input.Key_Objectives}}. Follow these guidelines:
            
                        1. Executive Summary: Provide a brief overview of the marketing strategy, its goals, and expected outcomes.
            
                        2. Situation Analysis: 
                           - Market Analysis: Current market trends and competitor analysis
                           - SWOT Analysis: Strengths, Weaknesses, Opportunities, and Threats
            
                        3. Target Audience: Detailed persona of the target audience, including demographics, psychographics, and behavioral characteristics.
            
                        4. Marketing Objectives: Clear, specific, measurable, achievable, relevant, and time-bound (SMART) objectives based on the selected key objectives.
            
                        5. Marketing Mix (4Ps):
                           - Product: Key features and benefits
                           - Price: Pricing strategy and positioning
                           - Place: Distribution channels
                           - Promotion: Marketing channels and tactics
            
                        6. Content Strategy: Types of content to be created and distributed across different channels.
            
                        7. Channel Strategy: Specific platforms and mediums to be used for marketing activities.
            
                        8. Budget Allocation: Breakdown of how the budget will be distributed across different marketing activities.
            
                        9. Timeline: High-level timeline of marketing activities and milestones.
            
                        10. Key Performance Indicators (KPIs): Metrics to measure the success of the marketing campaign.
            
                        11. Risk Assessment: Potential risks and mitigation strategies.
            
                        12. Evaluation and Control: Methods for monitoring and adjusting the strategy as needed.
            
                        Ensure the strategy is detailed, data-driven where possible, and aligns with the selected campaign type, target audience, budget, and objectives.",
                        "background_information" => "You are an experienced Marketing Strategist with expertise in creating comprehensive marketing strategies across various industries and campaign types.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "excel_columns" => [
                            [
                                "sheet_name" => "Marketing Strategy",
                                "global_header_background" => "#6466de",
                                "global_header_text_color" => "#ffffff",
                                "global_header_height" => "20",
                                "global_header_alignment" => "center",
                                "columns" => [
                                    [
                                        "name" => "Section",
                                        "description" => "Main section of the marketing strategy",
                                        "width" => "30",
                                        "alignment" => "center",
                                        "merge_duplicates" => true
                                    ],
                                    [
                                        "name" => "Subsection",
                                        "description" => "Specific subsection or category within the main section",
                                        "width" => "35",
                                        "alignment" => "center",
                                        "merge_duplicates" => true
                                    ],
                                    [
                                        "name" => "Item",
                                        "description" => "Specific item, strategy, or point within the subsection",
                                        "width" => "40",
                                        "alignment" => "left",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Description",
                                        "description" => "Detailed explanation or content for the item",
                                        "width" => "120",
                                        "alignment" => "left",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Metrics/KPIs",
                                        "description" => "Relevant metrics or KPIs for measuring success",
                                        "width" => "60",
                                        "alignment" => "left",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Budget Allocation",
                                        "description" => "Estimated budget allocation for the item or activity",
                                        "width" => "40",
                                        "alignment" => "center",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Timeline",
                                        "description" => "Estimated timeline or duration for the item or activity",
                                        "width" => "40",
                                        "alignment" => "center",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Priority",
                                        "description" => "Importance or urgency of the item or activity",
                                        "width" => "25",
                                        "alignment" => "center",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Status",
                                        "description" => "Current status of the item or activity",
                                        "width" => "25",
                                        "alignment" => "center",
                                        "merge_duplicates" => false
                                    ],
                                    [
                                        "name" => "Notes",
                                        "description" => "Additional information, risks, or considerations",
                                        "width" => "80",
                                        "alignment" => "left",
                                        "merge_duplicates" => false
                                    ]
                                ]
                            ]
                        ]
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["marketer", "marketing_strategy", "excel_generation"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 오디오
            // Alloy Voice Generator
            [
                'name' => $this->getLocalizedText([
                    'de' => '[TTS] Alloy - Professioneller Sprecher',
                    'en' => '[TTS] Alloy - Professional Narrator',
                    'fr' => '[TTS] Alloy - Narrateur professionnel',
                    'hi' => '[TTS] Alloy - पेशेवर कथाकार',
                    'ja' => '[TTS] Alloy - プロフェッショナルナレーター',
                    'ko' => '[TTS] Alloy - 전문 내레이터',
                    'pt' => '[TTS] Alloy - Narrador Profissional',
                    'th' => '[TTS] Alloy - ผู้บรรยายมืออาชีพ',
                    'tl' => '[TTS] Alloy - Propesyonal na Tagapagsalaysay',
                    'zh' => '[TTS] Alloy - 专业旁白',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Erstellen Sie ein professionelles, neutrales Voice-Over mit Alloy.',
                    'en' => 'Generate a professional, neutral voice-over using Alloy.',
                    'fr' => 'Générez une voix off professionnelle et neutre avec Alloy.',
                    'hi' => 'Alloy का उपयोग करके एक पेशेवर, तटस्थ वॉयस-ओवर उत्पन्न करें।',
                    'ja' => 'Alloy を使用してプロフェッショナルでニュートラルなボイスオーバーを生成します。',
                    'ko' => 'Alloy를 사용하여 전문적이고 중립적인 성우 녹음을 생성하세요.',
                    'pt' => 'Gere uma narração profissional e neutra usando Alloy.',
                    'th' => 'สร้างการพากย์เสียงแบบมืออาชีพและเป็นกลางโดยใช้ Alloy',
                    'tl' => 'Bumuo ng isang propesyonal at neutral na voice-over gamit ang Alloy.',
                    'zh' => '使用Alloy生成专业的中性配音。',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie Ihr Skript für das professionelle Voice-Over ein',
                                    'en' => 'Enter your script for the professional voice-over',
                                    'fr' => 'Entrez votre script pour la voix off professionnelle',
                                    'hi' => 'पेशेवर वॉयस-ओवर के लिए अपनी स्क्रिप्ट दर्ज करें',
                                    'ja' => 'プロフェッショナルなボイスオーバーのためのスクリプトを入力してください',
                                    'ko' => '전문 성우 녹음을 위한 스크립트를 입력하세요',
                                    'pt' => 'Insira seu script para a narração profissional',
                                    'th' => 'ป้อนสคริปต์ของคุณสำหรับการพากย์เสียงแบบมืออาชีพ',
                                    'tl' => 'Ilagay ang iyong script para sa propesyonal na voice-over',
                                    'zh' => '输入您的专业配音脚本',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_audio",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "audio_text" => "{{step1.input.script}}",
                        "ai_provider" => "openai",
                        "audio_model" => "tts-1-hd",
                        "voice" => "alloy"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["audio", "voice"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Echo Voice Generator
            [
                'name' => $this->getLocalizedText([
                    'de' => '[TTS] Echo - Sanfter Geschichtenerzähler',
                    'en' => '[TTS] Echo - Gentle Storyteller',
                    'fr' => '[TTS] Echo - Conteur doux',
                    'hi' => '[TTS] Echo - सौम्य कहानीकार',
                    'ja' => '[TTS] Echo - 穏やかなストーリーテラー',
                    'ko' => '[TTS] Echo - 부드러운 스토리텔러',
                    'pt' => '[TTS] Echo - Contador de Histórias Gentil',
                    'th' => '[TTS] Echo - นักเล่าเรื่องที่อ่อนโยน',
                    'tl' => '[TTS] Echo - Banayad na Manunulat ng Kuwento',
                    'zh' => '[TTS] Echo - 温柔的故事讲述者',
                ]),

                'description' => $this->getLocalizedText([
                    'de' => 'Erstellen Sie eine weiche, beruhigende Erzählung mit Echo.',
                    'en' => 'Create a soft, soothing narration using Echo.',
                    'fr' => 'Créez une narration douce et apaisante avec Echo.',
                    'hi' => 'Echo का उपयोग करके एक मुलायम, सुकून भरी कहानी तैयार करें।',
                    'ja' => 'Echoを使用して、柔らかで心地よいナレーションを作成します。',
                    'ko' => 'Echo를 사용하여 부드럽고 편안한 내레이션을 만드세요.',
                    'pt' => 'Crie uma narração suave e calmante usando Echo.',
                    'th' => 'สร้างการบรรยายที่นุ่มนวลและผ่อนคลายโดยใช้ Echo',
                    'tl' => 'Lumikha ng banayad at nakapapawing pagod na pagsasalaysay gamit ang Echo.',
                    'zh' => '使用Echo创建柔和、舒缓的叙述。',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie Ihr Skript für das professionelle Voice-Over ein',
                                    'en' => 'Enter your script for the professional voice-over',
                                    'fr' => 'Entrez votre script pour la voix off professionnelle',
                                    'hi' => 'पेशेवर वॉयस-ओवर के लिए अपनी स्क्रिप्ट दर्ज करें',
                                    'ja' => 'プロフェッショナルなボイスオーバーのためのスクリプトを入力してください',
                                    'ko' => '전문 성우 녹음을 위한 스크립트를 입력하세요',
                                    'pt' => 'Insira seu script para a narração profissional',
                                    'th' => 'ป้อนสคริปต์ของคุณสำหรับการพากย์เสียงแบบมืออาชีพ',
                                    'tl' => 'Ilagay ang iyong script para sa propesyonal na voice-over',
                                    'zh' => '输入您的专业配音脚本',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_audio",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "audio_text" => "{{step1.input.script}}",
                        "ai_provider" => "openai",
                        "audio_model" => "tts-1-hd",
                        "voice" => "echo"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["audio", "voice"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fable Voice Generator
            [
                'name' => $this->getLocalizedText([
                    'de' => '[TTS] Fable - Magischer Märchenerzähler',
                    'en' => '[TTS] Fable - Magical Fairytale Narrator',
                    'fr' => '[TTS] Fable - Narrateur de contes de fées magiques',
                    'hi' => '[TTS] Fable - जादुई परी कथा कथावाचक',
                    'ja' => '[TTS] Fable - 魔法のようなフェアリーテールナレーター',
                    'ko' => '[TTS] Fable - 마법 같은 동화 내레이터',
                    'pt' => '[TTS] Fable - Narrador de Contos de Fadas Mágicos',
                    'th' => '[TTS] Fable - ผู้บรรยายเทพนิยายมหัศจรรย์',
                    'tl' => '[TTS] Fable - Mahiwagang Tagapagsalaysay ng Kuwentong Pambata',
                    'zh' => '[TTS] Fable - 魔法童话讲述者',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Erwecken Sie Märchen mit der bezaubernden Stimme von Fable zum Leben.',
                    'en' => 'Bring fairytales to life with the enchanting Fable voice.',
                    'fr' => 'Donnez vie aux contes de fées avec la voix envoûtante de Fable.',
                    'hi' => 'मंत्रमुग्ध करने वाली Fable आवाज़ के साथ परियों की कहानियों को जीवंत बनाएं।',
                    'ja' => '魅惑的なFableの声でおとぎ話を生き生きと語りましょう。',
                    'ko' => '마법 같은 Fable 목소리로 동화를 생생하게 만들어 보세요.',
                    'pt' => 'Dê vida aos contos de fadas com a encantadora voz do Fable.',
                    'th' => 'ทำให้เทพนิยายมีชีวิตชีวาด้วยเสียง Fable ที่น่าหลงใหล',
                    'tl' => 'Gawing makatotohanan ang mga kuwentong pambata gamit ang mapang-akit na boses ni Fable.',
                    'zh' => '用迷人的Fable声音让童话故事栩栩如生。',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie Ihr Skript für das professionelle Voice-Over ein',
                                    'en' => 'Enter your script for the professional voice-over',
                                    'fr' => 'Entrez votre script pour la voix off professionnelle',
                                    'hi' => 'पेशेवर वॉयस-ओवर के लिए अपनी स्क्रिप्ट दर्ज करें',
                                    'ja' => 'プロフェッショナルなボイスオーバーのためのスクリプトを入力してください',
                                    'ko' => '전문 성우 녹음을 위한 스크립트를 입력하세요',
                                    'pt' => 'Insira seu script para a narração profissional',
                                    'th' => 'ป้อนสคริปต์ของคุณสำหรับการพากย์เสียงแบบมืออาชีพ',
                                    'tl' => 'Ilagay ang iyong script para sa propesyonal na voice-over',
                                    'zh' => '输入您的专业配音脚本',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_audio",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "audio_text" => "{{step1.input.script}}",
                        "ai_provider" => "openai",
                        "audio_model" => "tts-1-hd",
                        "voice" => "fable"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["audio", "voice"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Onyx Voice Generator
            [
                'name' => $this->getLocalizedText([
                    'de' => '[TTS] Onyx - Autoritativer Präsentator',
                    'en' => '[TTS] Onyx - Authoritative Presenter',
                    'fr' => '[TTS] Onyx - Présentateur autoritaire',
                    'hi' => '[TTS] Onyx - प्राधिकृत प्रस्तुतकर्ता',
                    'ja' => '[TTS] Onyx - 権威あるプレゼンター',
                    'ko' => '[TTS] Onyx - 권위 있는 발표자',
                    'pt' => '[TTS] Onyx - Apresentador Autoritário',
                    'th' => '[TTS] Onyx - ผู้บรรยายที่ทรงอำนาจ',
                    'tl' => '[TTS] Onyx - Mapangyarihang Tagapagsalaysay',
                    'zh' => '[TTS] Onyx - 权威演示者',
                ]),

                'description' => $this->getLocalizedText([
                    'de' => 'Erzeugen Sie eine kraftvolle, autoritative Stimme mit Onyx.',
                    'en' => 'Generate a powerful, commanding voice using Onyx.',
                    'fr' => 'Générez une voix puissante et autoritaire avec Onyx.',
                    'hi' => 'Onyx का उपयोग करके एक शक्तिशाली, प्रभावी आवाज़ उत्पन्न करें।',
                    'ja' => 'Onyxを使用して力強く威厳のある声を生成します。',
                    'ko' => 'Onyx를 사용하여 강력하고 명령적인 목소리를 생성하세요.',
                    'pt' => 'Gere uma voz poderosa e autoritária usando Onyx.',
                    'th' => 'สร้างเสียงที่ทรงพลังและน่าเกรงขามโดยใช้ Onyx',
                    'tl' => 'Bumuo ng isang makapangyarihang at nangingibabaw na boses gamit ang Onyx.',
                    'zh' => '使用Onyx生成强大、指挥性的声音。',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie Ihr Skript für das professionelle Voice-Over ein',
                                    'en' => 'Enter your script for the professional voice-over',
                                    'fr' => 'Entrez votre script pour la voix off professionnelle',
                                    'hi' => 'पेशेवर वॉयस-ओवर के लिए अपनी स्क्रिप्ट दर्ज करें',
                                    'ja' => 'プロフェッショナルなボイスオーバーのためのスクリプトを入力してください',
                                    'ko' => '전문 성우 녹음을 위한 스크립트를 입력하세요',
                                    'pt' => 'Insira seu script para a narração profissional',
                                    'th' => 'ป้อนสคริปต์ของคุณสำหรับการพากย์เสียงแบบมืออาชีพ',
                                    'tl' => 'Ilagay ang iyong script para sa propesyonal na voice-over',
                                    'zh' => '输入您的专业配音脚本',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_audio",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "audio_text" => "{{step1.input.script}}",
                        "ai_provider" => "openai",
                        "audio_model" => "tts-1-hd",
                        "voice" => "onyx"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["audio", "voice"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Nova Voice Generator
            [
               'name' => $this->getLocalizedText([
                    'de' => '[TTS] Nova - Energischer Ansager',
                    'en' => '[TTS] Nova - Energetic Announcer',
                    'fr' => '[TTS] Nova - Annonceur énergique',
                    'hi' => '[TTS] Nova - ऊर्जावान उद्घोषक',
                    'ja' => '[TTS] Nova - エネルギッシュなアナウンサー',
                    'ko' => '[TTS] Nova - 에너지 넘치는 아나운서',
                    'pt' => '[TTS] Nova - Anunciador Energético',
                    'th' => '[TTS] Nova - ผู้ประกาศที่มีพลัง',
                    'tl' => '[TTS] Nova - Energetikong Tagapagbalita',
                    'zh' => '[TTS] Nova - 充满活力的播音员',
                ]),

                'description' => $this->getLocalizedText([
                    'de' => 'Erstellen Sie lebendige, begeisterte Audioinhalte mit Nova.',
                    'en' => 'Create vibrant, enthusiastic audio content with Nova.',
                    'fr' => 'Créez du contenu audio vibrant et enthousiaste avec Nova.',
                    'hi' => 'Nova का उपयोग करके जीवंत, उत्साही ऑडियो सामग्री बनाएं।',
                    'ja' => 'Novaを使用して活気に満ちた、熱意あるオーディオコンテンツを作成します。',
                    'ko' => 'Nova를 사용하여 활기차고 열정적인 오디오 콘텐츠를 만드세요.',
                    'pt' => 'Crie conteúdo de áudio vibrante e entusiástico com Nova.',
                    'th' => 'สร้างเนื้อหาเสียงที่มีชีวิตชีวาและกระตือรือร้นด้วย Nova',
                    'tl' => 'Lumikha ng buhay na buhay at masigasig na audio content gamit si Nova.',
                    'zh' => '使用Nova创建充满活力、热情的音频内容。',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie Ihr Skript für das professionelle Voice-Over ein',
                                    'en' => 'Enter your script for the professional voice-over',
                                    'fr' => 'Entrez votre script pour la voix off professionnelle',
                                    'hi' => 'पेशेवर वॉयस-ओवर के लिए अपनी स्क्रिप्ट दर्ज करें',
                                    'ja' => 'プロフェッショナルなボイスオーバーのためのスクリプトを入力してください',
                                    'ko' => '전문 성우 녹음을 위한 스크립트를 입력하세요',
                                    'pt' => 'Insira seu script para a narração profissional',
                                    'th' => 'ป้อนสคริปต์ของคุณสำหรับการพากย์เสียงแบบมืออาชีพ',
                                    'tl' => 'Ilagay ang iyong script para sa propesyonal na voice-over',
                                    'zh' => '输入您的专业配音脚本',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_audio",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "audio_text" => "{{step1.input.script}}",
                        "ai_provider" => "openai",
                        "audio_model" => "tts-1-hd",
                        "voice" => "nova"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["audio", "voice"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Shimmer Voice Generator
            [
            'name' => $this->getLocalizedText([
                'de' => '[TTS] Shimmer - Eleganter Sprecher',
                'en' => '[TTS] Shimmer - Elegant Spokesperson',
                'fr' => '[TTS] Shimmer - Porte-parole élégant',
                'hi' => '[TTS] Shimmer - सुरुचिपूर्ण प्रवक्ता',
                'ja' => '[TTS] Shimmer - 優雅なスポークスパーソン',
                'ko' => '[TTS] Shimmer - 우아한 대표자',
                'pt' => '[TTS] Shimmer - Porta-voz Elegante',
                'th' => '[TTS] Shimmer - โฆษกที่สง่างาม',
                'tl' => '[TTS] Shimmer - Eleganteng Tagapagsalita',
                'zh' => '[TTS] Shimmer - 优雅的代言人',
            ]),
            'description' => $this->getLocalizedText([
                'de' => 'Erstellen Sie anspruchsvolle, raffinierte Audioinhalte mit Shimmer.',
                'en' => 'Generate sophisticated, refined audio with Shimmer.',
                'fr' => 'Générez un contenu audio sophistiqué et raffiné avec Shimmer.',
                'hi' => 'Shimmer का उपयोग करके परिष्कृत, परिपक्व ऑडियो उत्पन्न करें।',
                'ja' => 'Shimmerを使用して洗練された上品なオーディオを生成します。',
                'ko' => 'Shimmer를 사용하여 세련되고 정교한 오디오를 생성하세요.',
                'pt' => 'Gere áudio sofisticado e refinado com Shimmer.',
                'th' => 'สร้างเสียงที่ซับซ้อนและประณีตด้วย Shimmer',
                'tl' => 'Bumuo ng sopistikado at pinong audio gamit si Shimmer.',
                'zh' => '使用Shimmer生成精致、高雅的音频。',
            ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie Ihr Skript für das professionelle Voice-Over ein',
                                    'en' => 'Enter your script for the professional voice-over',
                                    'fr' => 'Entrez votre script pour la voix off professionnelle',
                                    'hi' => 'पेशेवर वॉयस-ओवर के लिए अपनी स्क्रिप्ट दर्ज करें',
                                    'ja' => 'プロフェッショナルなボイスオーバーのためのスクリプトを入力してください',
                                    'ko' => '전문 성우 녹음을 위한 스크립트를 입력하세요',
                                    'pt' => 'Insira seu script para a narração profissional',
                                    'th' => 'ป้อนสคริปต์ของคุณสำหรับการพากย์เสียงแบบมืออาชีพ',
                                    'tl' => 'Ilagay ang iyong script para sa propesyonal na voice-over',
                                    'zh' => '输入您的专业配音脚本',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_audio",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "audio_text" => "{{step1.input.script}}",
                        "ai_provider" => "openai",
                        "audio_model" => "tts-1-hd",
                        "voice" => "shimmer"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["audio", "voice"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 사업
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Überarbeiteter PSST-Geschäftsplan-Generator',
                    'en' => 'Revised PSST Business Plan Generator',
                    'fr' => 'Générateur de plan d\'affaires PSST révisé',
                    'hi' => 'संशोधित PSST व्यापार योजना जनरेटर',
                    'ja' => '改訂PSSTビジネスプラン生成',
                    'ko' => 'PSST 기반 사업 계획서 생성기',
                    'pt' => 'Gerador de Plano de Negócios PSST Revisado',
                    'th' => 'ตัวสร้างแผนธุรกิจ PSST ที่ปรับปรุงใหม่',
                    'tl' => 'Binagong PSST na Tagabuo ng Plano sa Negosyo',
                    'zh' => '修订版PSST商业计划生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Erstellen Sie einen umfassenden Geschäftsplan gemäß der detaillierten PSST-Struktur (Problem, Lösung, Skalierung, Team) mit einer einzigen abschließenden Integration',
                    'en' => 'Generate a comprehensive business plan following the detailed PSST (Problem, Solution, Scale-Up, Team) structure with a single final integration',
                    'fr' => 'Générez un plan d\'affaires complet en suivant la structure PSST détaillée (Problème, Solution, Évolutivité, Équipe) avec une intégration finale unique',
                    'hi' => 'विस्तृत PSST (समस्या, समाधान, स्केल-अप, टीम) संरचना का पालन करते हुए एक व्यापक व्यापार योजना उत्पन्न करें जिसमें एक अंतिम एकीकरण हो',
                    'ja' => 'PSST（問題、解決策、スケールアップ、チーム）の詳細な構造に従って、単一の最終統合を伴う包括的なビジネスプランを生成',
                    'ko' => 'PSST(문제, 해결책, 스케일업, 팀) 구조에 따라 포괄적인 사업 계획서를 설계 및 생성',
                    'pt' => 'Gere um plano de negócios abrangente seguindo a estrutura PSST detalhada (Problema, Solução, Expansão, Equipe) com uma única integração final',
                    'th' => 'สร้างแผนธุรกิจที่ครอบคลุมตามโครงสร้าง PSST (ปัญหา โซลูชัน ขยายทีม) พร้อมการผสานรวมครั้งสุดท้ายเพียงครั้งเดียว',
                    'tl' => 'Bumuo ng isang komprehensibong plano sa negosyo na sumusunod sa detalyadong PSST (Problema, Solusyon, Pag-scale, Koponan) na istruktura na may isang pangwakas na pagsasama',
                    'zh' => '根据详细的PSST（问题，解决方案，扩展，团队）结构生成一个综合的商业计划，并进行一次最终整合',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "business_name",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie Ihren Firmennamen ein',
                                    'en' => 'Enter your business name',
                                    'fr' => 'Entrez le nom de votre entreprise',
                                    'hi' => 'अपना व्यापार नाम दर्ज करें',
                                    'ja' => 'ビジネス名を入力してください',
                                    'ko' => '회사 이름을 입력하세요',
                                    'pt' => 'Insira o nome do seu negócio',
                                    'th' => 'ป้อนชื่อธุรกิจของคุณ',
                                    'tl' => 'Ilagay ang pangalan ng iyong negosyo',
                                    'zh' => '输入您的公司名称',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "product_service",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Beschreiben Sie kurz Ihr Hauptprodukt oder Ihre Hauptdienstleistung',
                                    'en' => 'Briefly describe your main product or service',
                                    'fr' => 'Décrivez brièvement votre produit ou service principal',
                                    'hi' => 'संक्षेप में अपने मुख्य उत्पाद या सेवा का वर्णन करें',
                                    'ja' => 'あなたの主な製品またはサービスを簡単に説明してください',
                                    'ko' => '주요 제품 또는 서비스를 간략하게 설명하세요',
                                    'pt' => 'Descreva brevemente seu principal produto ou serviço',
                                    'th' => 'อธิบายสั้น ๆ เกี่ยวกับผลิตภัณฑ์หรือบริการหลักของคุณ',
                                    'tl' => 'Maikling ilarawan ang iyong pangunahing produkto o serbisyo',
                                    'zh' => '简要描述您的主要产品或服务',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', identify and describe key social or everyday life challenges that this product or service aims to address. Focus on:\n\n1. Specific social issues or common problems people face in their daily lives\n2. The impact of these problems on individuals or society\n3. Why existing solutions (if any) are inadequate\n\nProvide a detailed analysis with concrete examples and, where possible, include relevant statistics or data to illustrate the significance of these problems. This analysis should clearly establish the background and motivation for the development of the product or service.",
                        "background_information" => "You are a social researcher identifying key societal or everyday challenges that a new product or service aims to solve.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "For the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', analyze the competitive landscape and identify areas for improvement compared to competitors. Your analysis should:\n\n1. Name specific competitors (use full names) in the market\n2. Identify areas where competitors currently have an advantage\n3. Highlight any unique expertise or assets (e.g., patents, specialized knowledge) that could give this business an edge\n\nProvide a detailed, objective analysis of the competitive landscape and potential areas for differentiation.",
                        "background_information" => "You are a market analyst specializing in competitive analysis for startups and new product launches.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "For the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', identify and analyze customer needs and potential areas for improvement. Your analysis should:\n\n1. Clearly define the target customer segments\n2. Describe specific needs or pain points of these customers\n3. Explain why customers might choose this product/service over alternatives\n4. Identify any unmet needs or areas where customer expectations are not being fully met\n\nProvide concrete examples and, where possible, cite relevant data or trends to support your analysis.",
                        "background_information" => "You are a customer insights specialist helping a new business understand and address its target market's needs.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "For the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', outline a detailed development or improvement plan. Your plan should:\n\n1. List specific features or functionalities to be developed or improved\n2. Indicate whether each development will be done in-house or outsourced\n3. Provide a timeline for development, including current progress and future milestones\n4. Describe the process for implementing these improvements\n\nBe as specific as possible, using a table or structured format to clearly present the development plan.",
                        "background_information" => "You are a product development manager creating a roadmap for a new product or service improvement.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "prompt" => "For the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', develop a strategy to address customer requirements and feedback. Your strategy should:\n\n1. Clearly identify the target customer segments\n2. List specific customer requirements or pain points\n3. Propose concrete solutions or improvements for each requirement\n4. Describe how these improvements will be implemented and measured\n\nUse specific, quantifiable metrics where possible to show the expected impact of these improvements.",
                        "background_information" => "You are a customer experience strategist developing a plan to improve a product or service based on customer feedback.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 7,
                        "uuid" => $generateUuid(),
                        "prompt" => "For the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', develop a strategy to establish and enhance market competitiveness. Your strategy should:\n\n1. Identify key differentiators from competitors\n2. Describe how to leverage or enhance these competitive advantages\n3. Address any areas where the business may be at a competitive disadvantage\n4. Outline specific steps to improve market position\n5. Propose methods to reach and acquire the target customer base\n\nUse concrete, measurable objectives and provide specific tactics for each aspect of the strategy.",
                        "background_information" => "You are a business strategist developing a plan to establish a strong market position for a new product or service.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 8,
                        "uuid" => $generateUuid(),
                        "prompt" => "For the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', create a detailed funding plan. Your plan should:\n\n1. Estimate the total funding required, broken down by major expense categories\n2. Identify potential funding sources (e.g., government grants, investor funding, personal capital)\n3. Provide a timeline for when different funding amounts will be needed\n4. Explain how the funding will be used to support business growth\n\nBe specific about amounts, timelines, and use of funds. Justify the need for each major expense category.",
                        "background_information" => "You are a financial planner helping a startup determine its funding needs and develop a strategy to secure necessary capital.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 9,
                        "uuid" => $generateUuid(),
                        "prompt" => "For the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', develop a market entry and performance strategy. Your strategy should:\n\n1. Define the target market segments for both domestic and international markets\n2. Outline specific strategies for entering these markets\n3. Set realistic performance targets (e.g., market share, revenue) for the first 3-5 years\n4. Describe marketing and sales approaches to achieve these targets\n5. If applicable, include plans for global expansion and establishing export channels\n\nProvide concrete, measurable goals and specific tactics for achieving them.",
                        "background_information" => "You are a market entry strategist helping a new business plan its launch and growth in both domestic and international markets.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 10,
                        "uuid" => $generateUuid(),
                        "prompt" => "For the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', develop an exit strategy. Your strategy should consider:\n\n1. Potential for attracting investment (angel investors, venture capital, crowdfunding)\n2. Possibilities for mergers and acquisitions (M&A) as a medium to long-term strategy\n3. Prospects for an initial public offering (IPO) as a long-term goal\n4. Opportunities for securing government support or R&D funding\n\nFor each potential exit route, provide a brief plan including estimated timelines and key milestones to achieve. If certain exit strategies seem unlikely, explain why and suggest alternatives based on industry trends or similar company examples.",
                        "background_information" => "You are a business strategy consultant specializing in long-term planning and exit strategies for startups and growing businesses.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 11,
                        "uuid" => $generateUuid(),
                        "prompt" => "For the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', outline the skills and experience of the core team. Your description should:\n\n1. List key team members and their roles\n2. Highlight relevant experience, skills, and qualifications of each team member\n3. Explain how each member's background contributes to the business's success\n4. Identify any skills gaps in the current team and how they might be addressed\n\nBe specific about each team member's contributions and how their expertise aligns with the business needs.",
                        "background_information" => "You are an HR specialist assessing and describing the capabilities of a startup team.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 12,
                        "uuid" => $generateUuid(),
                        "prompt" => "For the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', describe the company's technological capabilities. Your description should:\n\n1. List key technologies or technical skills possessed by the company\n2. Describe any proprietary technologies or methodologies\n3. Outline the company's R&D capabilities, including any specialized equipment or facilities\n4. Explain how these technological capabilities give the company a competitive edge\n\nBe specific about the technologies and how they relate to the product or service being offered.",
                        "background_information" => "You are a technology assessment specialist evaluating the technical capabilities of a new business.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 13,
                        "uuid" => $generateUuid(),
                        "prompt" => "For the business '{{step1.input.business_name}}' offering '{{step1.input.product_service}}', outline a plan for implementing social value initiatives. Your plan should:\n\n1. Propose specific programs or policies to create high-quality jobs (e.g., profit-sharing, converting temporary positions to permanent)\n2. Suggest ways to improve work-life balance (e.g., flexible hours, remote work options)\n3. Outline any environmental or community-focused initiatives\n4. Provide a timeline for implementing these initiatives\n\nBe specific about each proposed initiative, including its expected impact and how it aligns with the company's overall mission and values.",
                        "background_information" => "You are a corporate social responsibility consultant helping a new business develop a plan for creating positive social impact.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 14,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create an executive summary for the business plan of '{{step1.input.business_name}}' offering '{{step1.input.product_service}}'. This summary should concisely cover all aspects of the detailed PSST framework:\n\n1. Problem: Key social or everyday challenges, competitive landscape, and customer needs\n2. Solution: Development plans, customer response strategies, and market competitiveness approaches\n3. Scale-Up: Funding plans, market entry strategies, and exit strategies\n4. Team: Core skills, technological capabilities, and social value initiatives\n\nThe executive summary should be approximately 1000 words, providing a comprehensive overview of the entire business plan. It should be engaging and highlight the most critical points from each section of the detailed PSST analysis.",
                        "background_information" => "You are a seasoned business plan writer creating a detailed yet concise executive summary that captures the essence of a startup's vision, strategy, and potential.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 15,
                        "uuid" => $generateUuid(),
                        "content_template" => "# Executive Summary\n\n{{step14.output}}\n\n# Detailed PSST Analysis\n\n## Problem Analysis\n\n### Social or Everyday Challenges\n{{step2.output}}\n\n### Competitive Landscape\n{{step3.output}}\n\n### Customer Needs and Improvements\n{{step4.output}}\n\n## Solution Feasibility\n\n### Product/Service Development Plan\n{{step5.output}}\n\n### Customer Requirements Strategy\n{{step6.output}}\n\n### Market Competitiveness Strategy\n{{step7.output}}\n\n## Scale-Up Strategy\n\n### Funding Plan\n{{step8.output}}\n\n### Market Entry and Performance Strategy\n{{step9.output}}\n\n### Exit Strategy\n{{step10.output}}\n\n## Team Composition\n\n### Core Team Skills and Experience\n{{step11.output}}\n\n### Technological Capabilities\n{{step12.output}}\n\n### Social Value Initiatives\n{{step13.output}}"
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["entrepreneurship", "business_plan", "PSST"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Generator für koreanische Nutzungsbedingungen',
                    'en' => 'Korean Style Terms of Service Generator',
                    'fr' => 'Générateur de conditions d\'utilisation de style coréen',
                    'hi' => 'कोरियाई शैली की सेवा शर्तों का जनरेटर',
                    'ja' => '韓国式利用規約ジェネレーター',
                    'ko' => '서비스 이용약관 생성기',
                    'pt' => 'Gerador de Termos de Serviço Estilo Coreano',
                    'th' => 'เครื่องมือสร้างข้อกำหนดการใช้บริการแบบเกาหลี',
                    'tl' => 'Tagabuo ng Mga Tuntunin ng Serbisyo sa Estilo ng Korea',
                    'zh' => '韩式服务条款生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Erstellen Sie angepasste Nutzungsbedingungen basierend auf koreanischen Rechtsstandards und Geschäftspraktiken',
                    'en' => 'Generate customized Terms of Service based on Korean legal standards and business practices',
                    'fr' => 'Générez des conditions d\'utilisation personnalisées basées sur les normes juridiques et les pratiques commerciales coréennes',
                    'hi' => 'कोरियाई कानूनी मानकों और व्यावसायिक प्रथाओं के आधार पर अनुकूलित सेवा शर्तें तैयार करें',
                    'ja' => '韓国の法的基準とビジネス慣行に基づいてカスタマイズされた利用規約を生成します',
                    'ko' => '법률 기준과 비즈니스 관행에 따른 맞춤형 서비스 이용약관 생성',
                    'pt' => 'Gere Termos de Serviço personalizados com base em padrões legais e práticas comerciais coreanas',
                    'th' => 'สร้างข้อกำหนดการใช้บริการที่ปรับแต่งตามมาตรฐานทางกฎหมายและแนวปฏิบัติทางธุรกิจของเกาหลี',
                    'tl' => 'Lumikha ng customized na Mga Tuntunin ng Serbisyo batay sa mga pamantayang legal at kasanayan sa negosyo ng Korea',
                    'zh' => '根据韩国法律标准和商业惯例生成定制的服务条款',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "Company_Name",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie den Namen Ihres Unternehmens ein',
                                    'en' => 'Enter the name of your company',
                                    'fr' => 'Entrez le nom de votre entreprise',
                                    'hi' => 'अपनी कंपनी का नाम दर्ज करें',
                                    'ja' => '会社名を入力してください',
                                    'ko' => '회사 이름을 입력하세요',
                                    'pt' => 'Insira o nome da sua empresa',
                                    'th' => 'ป้อนชื่อบริษัทของคุณ',
                                    'tl' => 'Ilagay ang pangalan ng iyong kumpanya',
                                    'zh' => '输入您的公司名称',
                                ]),
                                "type" => "text",
                                "placeholder" => "e.g., ABC Corporation"
                            ],
                            [
                                "label" => "Service_Name",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie den Namen Ihres Dienstes ein',
                                    'en' => 'Enter the name of your service',
                                    'fr' => 'Entrez le nom de votre service',
                                    'hi' => 'अपनी सेवा का नाम दर्ज करें',
                                    'ja' => 'サービス名を入力してください',
                                    'ko' => '서비스 이름을 입력하세요',
                                    'pt' => 'Insira o nome do seu serviço',
                                    'th' => 'ป้อนชื่อบริการของคุณ',
                                    'tl' => 'Ilagay ang pangalan ng iyong serbisyo',
                                    'zh' => '输入您的服务名称',
                                ]),
                                "type" => "text",
                                "placeholder" => "e.g., ABC Business Services"
                            ],
                            [
                                "label" => "Service_Description",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Beschreiben Sie kurz Ihren Service',
                                    'en' => 'Briefly describe your service',
                                    'fr' => 'Décrivez brièvement votre service',
                                    'hi' => 'अपनी सेवा का संक्षेप में वर्णन करें',
                                    'ja' => 'サービスを簡単に説明してください',
                                    'ko' => '서비스를 간략하게 설명하세요',
                                    'pt' => 'Descreva brevemente seu serviço',
                                    'th' => 'อธิบายบริการของคุณอย่างสั้น ๆ',
                                    'tl' => 'Maikling ilarawan ang iyong serbisyo',
                                    'zh' => '简要描述您的服务',
                                ]),
                                "type" => "textarea",
                                "placeholder" => "e.g., Online business platform for corporate customers"
                            ],
                            [
                                "label" => "Specific_Services",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Listen Sie die spezifischen Dienstleistungen auf, die Sie anbieten',
                                    'en' => 'List the specific services you offer',
                                    'fr' => 'Listez les services spécifiques que vous proposez',
                                    'hi' => 'आप जो विशिष्ट सेवाएँ प्रदान करते हैं, उन्हें सूचीबद्ध करें',
                                    'ja' => '提供する特定のサービスをリストアップしてください',
                                    'ko' => '제공하는 특정 서비스를 나열하세요',
                                    'pt' => 'Liste os serviços específicos que você oferece',
                                    'th' => 'แสดงรายการบริการเฉพาะที่คุณเสนอ',
                                    'tl' => 'Ilista ang mga tiyak na serbisyong inaalok mo',
                                    'zh' => '列出您提供的具体服务',
                                ]),
                                "type" => "multiselect",
                                "options" => $this->getLocalizedText([
                                    'de' => 'Werbedienste, Einkaufspartner, Zahlungsdienste, Datenanalyse, Kundenmanagement, Community, Store, Punkteprogramm, Sonstiges',
                                    'en' => 'Advertising Services, Shopping Partners, Payment Services, Data Analysis, Customer Management, Community, Store, Points Program, Other',
                                    'fr' => 'Services publicitaires, Partenaires d\'achat, Services de paiement, Analyse de données, Gestion des clients, Communauté, Boutique, Programme de points, Autre',
                                    'hi' => 'विज्ञापन सेवाएँ, खरीदारी भागीदार, भुगतान सेवाएँ, डेटा विश्लेषण, ग्राहक प्रबंधन, समुदाय, स्टोर, पॉइंट्स कार्यक्रम, अन्य',
                                    'ja' => '広告サービス, ショッピングパートナー, 支払いサービス, データ分析, 顧客管理, コミュニティ, ストア, ポイントプログラム, その他',
                                    'ko' => '광고 서비스, 쇼핑 파트너, 결제 서비스, 데이터 분석, 고객 관리, 커뮤니티, 스토어, 포인트 프로그램, 기타',
                                    'pt' => 'Serviços de publicidade, Parceiros de compras, Serviços de pagamento, Análise de dados, Gestão de clientes, Comunidade, Loja, Programa de Pontos, Outro',
                                    'th' => 'บริการโฆษณา, พันธมิตรการช้อปปิ้ง, บริการชำระเงิน, การวิเคราะห์ข้อมูล, การจัดการลูกค้า, ชุมชน, ร้านค้า, โปรแกรมคะแนน, อื่น ๆ',
                                    'tl' => 'Mga Serbisyo sa Pag-aanunsyo, Mga Kasosyo sa Pamimili, Mga Serbisyo sa Pagbabayad, Pagsusuri ng Data, Pamamahala ng Customer, Komunidad, Tindahan, Programa ng Mga Puntos, Iba pa',
                                    'zh' => '广告服务, 购物合作伙伴, 支付服务, 数据分析, 客户管理, 社区, 商店, 积分计划, 其他',
                                ]),
                                "placeholder" => "Select the services you offer..."
                            ],
                            [
                                "label" => "Membership_Type",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Wählen Sie den Mitgliedschaftstyp aus, den Sie anbieten',
                                    'en' => 'Select the type of membership you offer',
                                    'fr' => 'Sélectionnez le type d\'abonnement que vous proposez',
                                    'hi' => 'आप जो सदस्यता प्रदान करते हैं उसका प्रकार चुनें',
                                    'ja' => '提供するメンバーシップの種類を選択してください',
                                    'ko' => '제공하는 멤버십 유형을 선택하세요',
                                    'pt' => 'Selecione o tipo de associação que você oferece',
                                    'th' => 'เลือกประเภทของสมาชิกที่คุณเสนอ',
                                    'tl' => 'Piliin ang uri ng membership na inaalok mo',
                                    'zh' => '选择您提供的会员类型',
                                ]),
                                "type" => "select",
                                "options" => $this->getLocalizedText([
                                    'de' => 'Allgemeine Mitglieder, Unternehmensmitglieder, Einzelunternehmen-Mitglieder',
                                    'en' => 'General Members, Corporate Members, Individual Business Members',
                                    'fr' => 'Membres généraux, Membres d\'entreprise, Membres d\'affaires individuels',
                                    'hi' => 'सामान्य सदस्य, कॉर्पोरेट सदस्य, व्यक्तिगत व्यवसाय सदस्य',
                                    'ja' => '一般会員, 法人会員, 個人事業主会員',
                                    'ko' => '일반 회원, 기업 회원, 개인 사업자 회원',
                                    'pt' => 'Membros Gerais, Membros Corporativos, Membros de Negócios Individuais',
                                    'th' => 'สมาชิกทั่วไป, สมาชิกองค์กร, สมาชิกธุรกิจรายบุคคล',
                                    'tl' => 'Pangkalahatang Miyembro, Mga Miyembro ng Korporasyon, Mga Miyembro ng Indibidwal na Negosyo',
                                    'zh' => '普通会员, 公司会员, 个体商户会员',
                                ]),
                                "placeholder" => "Select the membership type..."
                            ],
                            [
                                "label" => "Account_Inactivity_Period",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie den Zeitraum ein, nach dem ein Konto als inaktiv gilt (in Monaten)',
                                    'en' => 'Enter the period after which an account is considered inactive (in months)',
                                    'fr' => 'Entrez la période après laquelle un compte est considéré comme inactif (en mois)',
                                    'hi' => 'वह अवधि दर्ज करें जिसके बाद एक खाता निष्क्रिय माना जाता है (महीनों में)',
                                    'ja' => 'アカウントが非アクティブと見なされる期間を入力してください（ヶ月）',
                                    'ko' => '계정이 비활성으로 간주되는 기간을 입력하세요 (개월)',
                                    'pt' => 'Insira o período após o qual uma conta é considerada inativa (em meses)',
                                    'th' => 'ป้อนระยะเวลาหลังจากที่บัญชีถือว่าไม่ใช้งาน (เป็นเดือน)',
                                    'tl' => 'Ilagay ang panahon pagkatapos kung kailan itinuturing na hindi aktibo ang isang account (sa buwan)',
                                    'zh' => '输入账户被视为不活跃的期限（以月为单位）',
                                ]),
                                "type" => "number",
                                "placeholder" => "e.g., 12"
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Generate Terms of Service for {{step1.input.Service_Name}} provided by {{step1.input.Company_Name}}. The terms should be structured as follows:
            
            Chapter 1: General Provisions
            1. Purpose
            2. Definitions
            3. Effect and Amendment of Terms
            4. Other Applicable Rules
            
            Chapter 2: Membership Registration and Service Use
            5. Membership Registration
            6. Provision of Services
            7. Restriction, Suspension, and Modification of Services
            
            Chapter 3: Obligations of Contracting Parties
            8. Member Obligations
            9. Protection and Use of Personal Information
            10. Notifications and Announcements
            
            Chapter 4: Termination of Service Agreement
            11. Termination of Service Agreement
            12. Compensation for Damages and Exemption from Liability
            13. Copyright Ownership and Usage Restrictions
            14. Dispute Resolution
            
            Each section should be tailored to {{step1.input.Service_Name}} and include the following details:
            - Service description: {{step1.input.Service_Description}}
            - Specific services: {{step1.input.Specific_Services}}
            - Membership type: {{step1.input.Membership_Type}}
            - Account inactivity period: {{step1.input.Account_Inactivity_Period}} months
            
            Use formal legal language suitable for Terms of Service while ensuring compliance with Korean laws and regulations. The terms should be comprehensive, clear, and protective of both the company and users' rights.",
                        "background_information" => "You are a legal expert specializing in drafting Terms of Service for online business platforms, with a focus on Korean legal standards. Your task is to create comprehensive and legally sound Terms of Service that adhere to Korean legal standards and business practices, presented in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "terms_of_service", "legal"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
               'name' => $this->getLocalizedText([
                    'de' => 'SWOT-Analyse-Generator',
                    'en' => 'SWOT Analysis Generator',
                    'fr' => 'Générateur d\'Analyse SWOT',
                    'hi' => 'SWOT विश्लेषण जनरेटर',
                    'ja' => 'SWOT分析ジェネレーター',
                    'ko' => 'SWOT 분석 생성기',
                    'pt' => 'Gerador de Análise SWOT',
                    'th' => 'เครื่องมือสร้างการวิเคราะห์ SWOT',
                    'tl' => 'Tagabuo ng Pagsusuri ng SWOT',
                    'zh' => 'SWOT分析生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Eingabe der Servicebeschreibung > Generierung der SWOT-Analyse',
                    'en' => 'Service description input > SWOT analysis generation',
                    'fr' => 'Saisie de la description du service > Génération de l\'analyse SWOT',
                    'hi' => 'सेवा विवरण इनपुट > SWOT विश्लेषण उत्पादन',
                    'ja' => 'サービス説明入力 > SWOT分析生成',
                    'ko' => '서비스 설명 입력 > SWOT 분석 생성',
                    'pt' => 'Entrada da descrição do serviço > Geração da análise SWOT',
                    'th' => 'ป้อนคำอธิบายบริการ > สร้างการวิเคราะห์ SWOT',
                    'tl' => 'Input ng paglalarawan ng serbisyo > Paggawa ng pagsusuri ng SWOT',
                    'zh' => '服务描述输入 > SWOT分析生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "service_description",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie eine kurze Beschreibung des Dienstes oder Unternehmens an',
                                    'en' => 'Provide a brief description of the service or business',
                                    'fr' => 'Fournissez une brève description du service ou de l\'entreprise',
                                    'hi' => 'सेवा या व्यवसाय का संक्षिप्त विवरण प्रदान करें',
                                    'ja' => 'サービスまたは事業の簡単な説明を提供してください',
                                    'ko' => '서비스 또는 비즈니스에 대한 간단한 설명을 제공하세요',
                                    'pt' => 'Forneça uma breve descrição do serviço ou negócio',
                                    'th' => 'ให้คำอธิบายสั้นๆ เกี่ยวกับบริการหรือธุรกิจ',
                                    'tl' => 'Magbigay ng maikling paglalarawan ng serbisyo o negosyo',
                                    'zh' => '提供服务或业务的简要描述',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following service description:\n\n{{step1.input.service_description}}\n\nGenerate a comprehensive analysis of the service's Strengths. Consider the following aspects:\n\n1. Unique selling propositions (USPs)\n2. Competitive advantages\n3. Strong brand reputation or recognition\n4. Skilled workforce or expertise\n5. Proprietary technology or processes\n6. Strong financial position\n7. Efficient operations\n8. Strong customer base or loyalty\n\nFor each identified strength:\n- Provide a clear, concise statement of the strength\n- Explain why it's considered a strength\n- If possible, provide a brief example or evidence\n\nFormat the output as a structured list, with each main strength as a bullet point and supporting details as sub-points. Aim for at least 5-7 significant strengths.\n\nEnsure the analysis is professional, specific to the described service, and provides actionable insights.",
                        "background_information" => "You are a seasoned business analyst with extensive experience in conducting SWOT analyses across various industries. Your expertise lies in identifying and articulating key business strengths that can drive competitive advantage.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following service description:\n\n{{step1.input.service_description}}\n\nGenerate a thorough analysis of the service's Weaknesses. Consider the following aspects:\n\n1. Lack of resources (financial, human, technological)\n2. Weak brand recognition or reputation\n3. Limited product or service range\n4. Outdated technology or processes\n5. High costs or low profit margins\n6. Poor market penetration\n7. Weak online presence or digital capabilities\n8. Quality issues or customer complaints\n\nFor each identified weakness:\n- Clearly state the weakness\n- Explain its potential impact on the business\n- If applicable, suggest potential areas for improvement\n\nFormat the output as a structured list, with each main weakness as a bullet point and supporting details as sub-points. Aim for at least 5-7 significant weaknesses.\n\nEnsure the analysis is professional, specific to the described service, and provides constructive insights for potential improvement.",
                        "background_information" => "You are a perceptive business consultant with a keen eye for identifying areas of improvement in various business models. Your expertise lies in pinpointing weaknesses that may hinder business growth and suggesting potential solutions.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following service description:\n\n{{step1.input.service_description}}\n\nGenerate a comprehensive analysis of the Opportunities available to the service. Consider the following aspects:\n\n1. Emerging market trends\n2. Changes in customer needs or preferences\n3. Technological advancements\n4. New market segments\n5. Potential for partnerships or collaborations\n6. Changes in regulations or policies\n7. Economic shifts\n8. Gaps in the market left by competitors\n\nFor each identified opportunity:\n- Clearly state the opportunity\n- Explain its potential benefit to the business\n- If possible, suggest how the business might capitalize on this opportunity\n\nFormat the output as a structured list, with each main opportunity as a bullet point and supporting details as sub-points. Aim for at least 5-7 significant opportunities.\n\nEnsure the analysis is forward-looking, specific to the described service, and provides actionable insights for potential growth and expansion.",
                        "background_information" => "You are a visionary business strategist with a talent for identifying emerging trends and untapped market potential. Your expertise lies in recognizing opportunities that can drive business growth and innovation.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following service description:\n\n{{step1.input.service_description}}\n\nGenerate a thorough analysis of the Threats facing the service. Consider the following aspects:\n\n1. Increasing competition\n2. Changing customer preferences\n3. Technological disruptions\n4. Economic downturns or recessions\n5. Regulatory changes\n6. Supply chain disruptions\n7. Cybersecurity risks\n8. Negative market trends\n\nFor each identified threat:\n- Clearly state the threat\n- Explain its potential impact on the business\n- If applicable, suggest potential mitigation strategies\n\nFormat the output as a structured list, with each main threat as a bullet point and supporting details as sub-points. Aim for at least 5-7 significant threats.\n\nEnsure the analysis is realistic, specific to the described service, and provides insights for risk management and strategic planning.",
                        "background_information" => "You are a seasoned risk analyst with extensive experience in identifying and assessing potential threats to businesses across various industries. Your expertise lies in anticipating challenges and suggesting proactive measures to mitigate risks.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "content_template" => "# SWOT Analysis\n\n## Strengths\n{{step2.output}}\n\n## Weaknesses\n{{step3.output}}\n\n## Opportunities\n{{step4.output}}\n\n## Threats\n{{step5.output}}\n\nThis SWOT analysis provides a comprehensive overview of the current position and future prospects of the described service. It should be used as a strategic tool to capitalize on strengths, address weaknesses, exploit opportunities, and mitigate threats."
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "entrepreneurship", "SWOT", "business_analysis", "strategic_planning"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
              'name' => $this->getLocalizedText([
                    'de' => 'Business Model Canvas Generator',
                    'en' => 'Business Model Canvas Generator',
                    'fr' => 'Générateur de Business Model Canvas',
                    'hi' => 'बिजनेस मॉडल कैनवास जनरेटर',
                    'ja' => 'ビジネスモデルキャンバスジェネレーター',
                    'ko' => '비즈니스 모델 캔버스 생성기',
                    'pt' => 'Gerador de Canvas de Modelo de Negócios',
                    'th' => 'เครื่องมือสร้าง Business Model Canvas',
                    'tl' => 'Tagabuo ng Business Model Canvas',
                    'zh' => '商业模式画布生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Geschäftsidee-Eingabe > Generierung des Business Model Canvas',
                    'en' => 'Business idea input > Business Model Canvas generation',
                    'fr' => 'Saisie de l\'idée commerciale > Génération du Business Model Canvas',
                    'hi' => 'व्यावसायिक विचार इनपुट > बिजनेस मॉडल कैनवास उत्पादन',
                    'ja' => 'ビジネスアイデア入力 > ビジネスモデルキャンバス生成',
                    'ko' => '비즈니스 아이디어 입력 > 비즈니스 모델 캔버스 생성',
                    'pt' => 'Entrada da ideia de negócio > Geração do Canvas de Modelo de Negócios',
                    'th' => 'ป้อนไอเดียธุรกิจ > สร้าง Business Model Canvas',
                    'tl' => 'Input ng ideya ng negosyo > Paggawa ng Business Model Canvas',
                    'zh' => '商业创意输入 > 商业模式画布生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "business_idea",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Geben Sie eine kurze Beschreibung Ihrer Geschäftsidee oder Ihres Konzepts an',
                                    'en' => 'Provide a brief description of your business idea or concept',
                                    'fr' => 'Fournissez une brève description de votre idée ou concept commercial',
                                    'hi' => 'अपने व्यावसायिक विचार या अवधारणा का संक्षिप्त विवरण प्रदान करें',
                                    'ja' => 'ビジネスアイデアやコンセプトの簡単な説明を提供してください',
                                    'ko' => '비즈니스 아이디어나 컨셉에 대한 간단한 설명을 제공하세요',
                                    'pt' => 'Forneça uma breve descrição da sua ideia ou conceito de negócio',
                                    'th' => 'ให้คำอธิบายสั้นๆ เกี่ยวกับไอเดียหรือแนวคิดทางธุรกิจของคุณ',
                                    'tl' => 'Magbigay ng maikling paglalarawan ng iyong ideya o konsepto ng negosyo',
                                    'zh' => '提供您的商业创意或概念的简要描述',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following business idea:\n\n{{step1.input.business_idea}}\n\nGenerate comprehensive content for the 'Customer Segments' section of the Business Model Canvas. Consider:\n\n1. Who are the most important customers?\n2. What are the different types of customers?\n3. Are there niche markets or mass markets?\n4. What are the characteristics of each segment?\n\nProvide a detailed analysis with at least 3-5 distinct customer segments. For each segment:\n- Clearly define the segment\n- Describe their specific needs or problems\n- Explain why they are important for the business\n\nFormat the output as a structured list, with each main segment as a bullet point and supporting details as sub-points.",
                        "background_information" => "You are an expert market researcher with deep insights into customer segmentation across various industries. Your expertise lies in identifying and characterizing distinct customer groups based on their needs, behaviors, and other attributes.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following business idea:\n\n{{step1.input.business_idea}}\n\nAnd considering the customer segments identified:\n\n{{step2.output}}\n\nGenerate comprehensive content for the 'Value Propositions' section of the Business Model Canvas. Consider:\n\n1. What core value do you deliver to the customer?\n2. Which customer needs are you satisfying?\n3. What bundles of products and services are you offering to each segment?\n4. How does your offering differ from competitors?\n\nProvide a detailed analysis with at least 3-5 distinct value propositions. For each proposition:\n- Clearly state the value proposition\n- Explain how it addresses specific customer needs or problems\n- Describe its unique features or benefits\n\nFormat the output as a structured list, with each main value proposition as a bullet point and supporting details as sub-points.",
                        "background_information" => "You are a strategic product manager with a talent for identifying and articulating compelling value propositions. Your expertise lies in understanding customer needs and translating them into clear, differentiated product or service offerings.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following business idea:\n\n{{step1.input.business_idea}}\n\nGenerate comprehensive content for the 'Channels' section of the Business Model Canvas. Consider:\n\n1. Through which channels do your customer segments want to be reached?\n2. How are you reaching them now?\n3. How are your channels integrated?\n4. Which ones work best?\n5. Which ones are most cost-efficient?\n6. How are you integrating them with customer routines?\n\nProvide a detailed analysis of the channels, covering awareness, evaluation, purchase, delivery, and after-sales. For each phase:\n- Identify relevant channels\n- Explain how they will be used\n- Describe their effectiveness and efficiency\n\nFormat the output as a structured list, with each phase as a main point and specific channels as sub-points.",
                        "background_information" => "You are an experienced marketing and distribution strategist. Your expertise lies in designing and optimizing multi-channel strategies to effectively reach and serve customers across various touchpoints.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following business idea:\n\n{{step1.input.business_idea}}\n\nAnd considering the customer segments and value propositions identified:\n\n{{step2.output}}\n{{step3.output}}\n\nGenerate comprehensive content for the 'Customer Relationships' section of the Business Model Canvas. Consider:\n\n1. What type of relationship does each of your customer segments expect you to establish and maintain with them?\n2. Which ones have you established?\n3. How costly are they?\n4. How are they integrated with the rest of your business model?\n\nProvide a detailed analysis of customer relationships, covering aspects such as:\n- Personal assistance\n- Dedicated personal assistance\n- Self-service\n- Automated services\n- Communities\n- Co-creation\n\nFor each type of relationship:\n- Describe how it will be implemented\n- Explain its benefits for the customer and the business\n- Discuss any challenges or costs associated with maintaining it\n\nFormat the output as a structured list, with each type of relationship as a main point and specific details as sub-points.",
                        "background_information" => "You are a customer experience expert with a deep understanding of building and maintaining strong customer relationships. Your expertise lies in designing customer-centric strategies that foster loyalty and drive business growth.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following business idea:\n\n{{step1.input.business_idea}}\n\nGenerate comprehensive content for the 'Revenue Streams' section of the Business Model Canvas. Consider:\n\n1. For what value are your customers really willing to pay?\n2. For what do they currently pay?\n3. How are they currently paying?\n4. How would they prefer to pay?\n5. How much does each revenue stream contribute to overall revenues?\n\nProvide a detailed analysis of potential revenue streams, such as:\n- Asset sale\n- Usage fee\n- Subscription fees\n- Lending/Renting/Leasing\n- Licensing\n- Brokerage fees\n- Advertising\n\nFor each revenue stream:\n- Describe how it will be implemented\n- Explain the pricing mechanism (fixed, dynamic, market, volume dependent, etc.)\n- Estimate its potential contribution to overall revenue\n- Discuss any risks or challenges associated with this stream\n\nFormat the output as a structured list, with each revenue stream as a main point and specific details as sub-points.",
                        "background_information" => "You are a financial strategist with extensive experience in developing and optimizing revenue models across various industries. Your expertise lies in identifying diverse revenue opportunities and creating sustainable financial structures for businesses.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 7,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following business idea:\n\n{{step1.input.business_idea}}\n\nGenerate comprehensive content for the 'Key Resources' section of the Business Model Canvas. Consider:\n\n1. What key resources do your value propositions require?\n2. What resources are needed for your distribution channels, customer relationships, and revenue streams?\n\nProvide a detailed analysis of the key resources, categorizing them into:\n- Physical (e.g., manufacturing facilities, buildings, vehicles, machines, systems, point-of-sales systems, distribution networks)\n- Intellectual (e.g., brands, proprietary knowledge, patents and copyrights, partnerships, customer databases)\n- Human (e.g., scientists, engineers, performers, salespeople)\n- Financial (e.g., cash, lines of credit, stock option pool for employees)\n\nFor each category:\n- Identify the specific resources needed\n- Explain why they are crucial for the business model\n- Describe how they will be acquired or developed\n- Discuss any challenges or costs associated with these resources\n\nFormat the output as a structured list, with each resource category as a main point and specific resources as sub-points.",
                        "background_information" => "You are a strategic resource manager with experience in optimizing resource allocation across various business models. Your expertise lies in identifying critical resources and developing strategies to acquire, manage, and leverage them effectively.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 8,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following business idea:\n\n{{step1.input.business_idea}}\n\nGenerate comprehensive content for the 'Key Activities' section of the Business Model Canvas. Consider:\n\n1. What key activities do your value propositions require?\n2. What activities are needed for your distribution channels, customer relationships, and revenue streams?\n\nProvide a detailed analysis of the key activities, categorizing them into:\n- Production (e.g., designing, making, delivering a product)\n- Problem Solving (e.g., knowledge management, continuous training)\n- Platform/Network (e.g., platform management, service provisioning, platform promotion)\n\nFor each category:\n- Identify the specific activities needed\n- Explain why they are crucial for the business model\n- Describe how they will be carried out\n- Discuss any challenges or resources required for these activities\n\nFormat the output as a structured list, with each activity category as a main point and specific activities as sub-points.",
                        "background_information" => "You are an operations strategy expert with experience in designing and optimizing business processes across various industries. Your expertise lies in identifying and structuring key activities that drive value creation and competitive advantage.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 9,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following business idea:\n\n{{step1.input.business_idea}}\n\nGenerate comprehensive content for the 'Key Partnerships' section of the Business Model Canvas. Consider:\n\n1. Who are your key partners?\n2. Who are your key suppliers?\n3. Which key resources are you acquiring from partners?\n4. Which key activities do partners perform?\n\nProvide a detailed analysis of the key partnerships, categorizing them into:\n- Strategic alliances between non-competitors\n- Coopetition: strategic partnerships between competitors\n- Joint ventures to develop new businesses\n- Buyer-supplier relationships to assure reliable supplies\n\nFor each category:\n- Identify specific potential partners or types of partners\n- Explain the rationale for the partnership (e.g., optimization and economy, reduction of risk and uncertainty, acquisition of particular resources and activities)\n- Describe the nature of the relationship and what each party brings to the table\n- Discuss any potential challenges or risks in the partnership\n\nFormat the output as a structured list, with each partnership category as a main point and specific partnerships or details as sub-points.",
                        "background_information" => "You are a strategic partnership expert with extensive experience in developing and managing collaborative relationships across various industries. Your expertise lies in identifying synergistic partnerships and structuring mutually beneficial relationships that enhance business models.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 10,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following business idea:\n\n{{step1.input.business_idea}}\n\nAnd considering the key resources and activities identified:\n\n{{step7.output}}\n{{step8.output}}\n\nGenerate comprehensive content for the 'Cost Structure' section of the Business Model Canvas. Consider:\n\n1. What are the most important costs inherent in your business model?\n2. Which key resources are most expensive?\n3. Which key activities are most expensive?\n\nProvide a detailed analysis of the cost structure, addressing:\n- Fixed costs (e.g., salaries, rents, utilities)\n- Variable costs (e.g., raw materials, packaging, commissions)\n- Economies of scale\n- Economies of scope\n\nAlso, determine whether the business is more:\n- Cost-driven (focused on minimizing costs)\n- Value-driven (focused on creating value)\n\nFor each major cost category:\n- Identify specific costs\n- Estimate their relative importance or magnitude\n- Explain how they relate to key resources and activities\n- Suggest potential strategies for cost optimization\n\nFormat the output as a structured list, with each major cost category as a main point and specific costs or strategies as sub-points.",
                        "background_information" => "You are a cost management expert with extensive experience in analyzing and optimizing cost structures across various business models. Your expertise lies in identifying critical cost drivers and developing strategies to enhance cost efficiency while maintaining value creation.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 11,
                        "uuid" => $generateUuid(),
                        "content_template" => "# Business Model Canvas\n\n## Customer Segments\n{{step2.output}}\n\n## Value Propositions\n{{step3.output}}\n\n## Channels\n{{step4.output}}\n\n## Customer Relationships\n{{step5.output}}\n\n## Revenue Streams\n{{step6.output}}\n\n## Key Resources\n{{step7.output}}\n\n## Key Activities\n{{step8.output}}\n\n## Key Partnerships\n{{step9.output}}\n\n## Cost Structure\n{{step10.output}}\n\nThis Business Model Canvas provides a comprehensive overview of the key components of the business model for the described idea. It should be used as a strategic tool to understand, design, and pivot the business model as needed."
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["entrepreneurship", "business", "business_model_canvas"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //기획
            [
                'name' => $this->getLocalizedText([
                    'de' => 'User Story-Generierungsprozess',
                    'en' => 'User Story Generation Process',
                    'fr' => 'Processus de génération de User Story',
                    'hi' => 'यूजर स्टोरी उत्पादन प्रक्रिया',
                    'ja' => 'ユーザーストーリー生成プロセス',
                    'ko' => '사용자 스토리 생성 프로세스',
                    'pt' => 'Processo de Geração de User Story',
                    'th' => 'กระบวนการสร้าง User Story',
                    'tl' => 'Proseso ng Paggawa ng User Story',
                    'zh' => '用户故事生成过程',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Eingabe der Produktanforderungen > Generierung von User Stories',
                    'en' => 'Product requirements input > User Story generation',
                    'fr' => 'Saisie des exigences du produit > Génération de User Story',
                    'hi' => 'उत्पाद आवश्यकताओं का इनपुट > यूजर स्टोरी उत्पादन',
                    'ja' => '製品要件入力 > ユーザーストーリー生成',
                    'ko' => '제품 요구사항 입력 > 사용자 스토리 생성',
                    'pt' => 'Entrada de requisitos do produto > Geração de User Story',
                    'th' => 'ป้อนความต้องการของผลิตภัณฑ์ > สร้าง User Story',
                    'tl' => 'Input ng mga kinakailangan ng produkto > Paggawa ng User Story',
                    'zh' => '产品需求输入 > 用户故事生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "product_requirement",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Beschreiben Sie kurz die Produktanforderung oder die zu entwickelnde Funktion',
                                    'en' => 'Briefly describe the product requirement or feature to be developed',
                                    'fr' => 'Décrivez brièvement l\'exigence du produit ou la fonctionnalité à développer',
                                    'hi' => 'उत्पाद की आवश्यकता या विकसित की जाने वाली सुविधा का संक्षेप में वर्णन करें',
                                    'ja' => '製品要件または開発する機能について簡単に説明してください',
                                    'ko' => '개발할 제품 요구사항 또는 기능에 대해 간단히 설명해 주세요',
                                    'pt' => 'Descreva brevemente o requisito do produto ou recurso a ser desenvolvido',
                                    'th' => 'อธิบายความต้องการของผลิตภัณฑ์หรือฟีเจอร์ที่จะพัฒนาอย่างย่อ',
                                    'tl' => 'Ilarawan nang maikli ang kinakailangan ng produkto o feature na dapat i-develop',
                                    'zh' => '简要描述产品需求或要开发的功能',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following product requirement or feature description, generate a set of user stories:
            
            Product Requirement: {{step1.input.product_requirement}}
            
            Generate 3-5 user stories that cover different aspects of this requirement. Each user story should follow this format:
            
            * (유저)[User type] 는
            * (목적)[Purpose]하기 위해
            * (액션)[Action]할 수 있다.
            
            Ensure that each user story:
            1. Identifies a specific user type
            2. Clearly states the user's goal or purpose
            3. Describes a concrete action the user can take
            
            The user stories should cover various aspects of the described feature or requirement, considering different user types and scenarios when applicable.
            
            Please generate the user stories now.",
                        "background_information" => "You are an experienced product owner with expertise in breaking down product requirements into clear, concise, and valuable user stories for agile development teams.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "user_story", "agile", "product_development"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Use-Case-Diagramm Generierungsprozess',
                    'en' => 'Use Case Diagram Generation Process',
                    'fr' => 'Processus de génération de diagramme de cas d\'utilisation',
                    'hi' => 'यूज केस डायग्राम उत्पादन प्रक्रिया',
                    'ja' => 'ユースケース図生成プロセス',
                    'ko' => '유스 케이스 다이어그램 생성 프로세스',
                    'pt' => 'Processo de Geração de Diagrama de Caso de Uso',
                    'th' => 'กระบวนการสร้างแผนภาพ Use Case',
                    'tl' => 'Proseso ng Paggawa ng Use Case Diagram',
                    'zh' => '用例图生成过程',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Eingabe der Systemanforderungen > Generierung des Use-Case-Diagramms',
                    'en' => 'System requirements input > Use Case Diagram generation',
                    'fr' => 'Saisie des exigences du système > Génération de diagramme de cas d\'utilisation',
                    'hi' => 'सिस्टम आवश्यकताओं का इनपुट > यूज केस डायग्राम उत्पादन',
                    'ja' => 'システム要件入力 > ユースケース図生成',
                    'ko' => '시스템 요구사항 입력 > 유스 케이스 다이어그램 생성',
                    'pt' => 'Entrada de requisitos do sistema > Geração de Diagrama de Caso de Uso',
                    'th' => 'ป้อนความต้องการของระบบ > สร้างแผนภาพ Use Case',
                    'tl' => 'Input ng mga kinakailangan ng sistema > Paggawa ng Use Case Diagram',
                    'zh' => '系统需求输入 > 用例图生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "system_requirement",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Beschreiben Sie kurz die Systemanforderung oder die zu entwickelnde Funktion',
                                    'en' => 'Briefly describe the system requirement or feature to be developed',
                                    'fr' => 'Décrivez brièvement l\'exigence du système ou la fonctionnalité à développer',
                                    'hi' => 'सिस्टम की आवश्यकता या विकसित की जाने वाली सुविधा का संक्षेप में वर्णन करें',
                                    'ja' => 'システム要件または開発する機能について簡単に説明してください',
                                    'ko' => '개발할 시스템 요구사항 또는 기능에 대해 간단히 설명해 주세요',
                                    'pt' => 'Descreva brevemente o requisito do sistema ou recurso a ser desenvolvido',
                                    'th' => 'อธิบายความต้องการของระบบหรือฟีเจอร์ที่จะพัฒนาอย่างย่อ',
                                    'tl' => 'Ilarawan nang maikli ang kinakailangan ng sistema o feature na dapat i-develop',
                                    'zh' => '简要描述系统需求或要开发的功能',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the following system requirement or feature description, create a detailed use case diagram using Mermaid syntax. The diagram should comprehensively visualize the interaction between the system and its users, including various scenarios and relationships.

                            System Requirement: {{step1.input.system_requirement}}

                            Create a Mermaid flowchart that:
                            1. Identifies all actors (users and external systems) involved
                            2. Shows a comprehensive set of use cases (actions) that the actors can perform
                            3. Illustrates various types of relationships between actors and use cases (e.g., association, include, extend)
                            4. Includes the system boundary to clearly separate the system from external actors
                            5. Represents any dependencies or relationships between use cases
                            6. Incorporates sub-systems or groupings of related use cases if applicable

                            Use the following Mermaid syntax structure and expand upon it:

                            <pre class=\"mermaid\">
                            flowchart TD
                                subgraph System Boundary
                                    subgraph Subsystem1
                                        A([User1]) -->|Main Function| B(Use Case 1)
                                        B -->|Include| C(Use Case 2)
                                        D(Use Case 3) -.->|Extend| B
                                    end
                                    subgraph Subsystem2
                                        E([User2]) -->|Main Function| F(Use Case 4)
                                        G([External System]) -.->|Interface| F
                                    end
                                    H(Use Case 5)
                                end
                                A -->|Additional Function| H
                                E -->|Additional Function| H
                                I([Administrator]) -->|Manage| B
                                I -->|Manage| F
                            </pre>

                            Please ensure that:
                            - Actors are represented with double parentheses: ([Actor])
                            - Use cases are represented with single parentheses: (Use Case)
                            - The system boundary and sub-systems are clearly defined using subgraphs
                            - Various relationship types are used:
                            * Solid lines with arrows (-->) for associations
                            * Dotted lines with arrows (-.->) for extend or include relationships
                            * Use |text| to label relationships appropriately
                            - Group related use cases into logical sub-systems or modules
                            - Include secondary actors or external systems if relevant
                            - Represent complex scenarios with multiple interconnected use cases
                            - Each element of the diagram is on a separate line (do not use \n characters)
                            - Use Korean labels for actors, actions, and use cases
                            - Add notes or comments using %% for clarity if needed

                            Generate a comprehensive and detailed Mermaid flowchart for the use case diagram now, based on the given system requirement. Ensure the diagram captures the complexity and nuances of the system. Place the entire flowchart code within the <pre class=\"mermaid\"></pre> tags.",
                        "background_information" => "You are a skilled system analyst with expertise in creating clear and informative use case diagrams to visualize system interactions. Your task is to interpret the given system requirement and create a comprehensive use case diagram that accurately represents the system's functionality and user interactions.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode(["planner", "use_case_diagram"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'UI/UX-Wireframe-Generator',
                    'en' => 'UI/UX Wireframe Generator',
                    'fr' => 'Générateur de Wireframe UI/UX',
                    'hi' => 'UI/UX वायरफ्रेम जनरेटर',
                    'ja' => 'UI/UXワイヤーフレームジェネレーター',
                    'ko' => 'UI/UX 와이어프레임 생성기',
                    'pt' => 'Gerador de Wireframe UI/UX',
                    'th' => 'เครื่องมือสร้างไวร์เฟรม UI/UX',
                    'tl' => 'Tagabuo ng Wireframe ng UI/UX',
                    'zh' => 'UI/UX线框图生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Texteingabe der Beschreibung > Generierung von UI/UX-Wireframes basierend auf der bereitgestellten Beschreibung',
                    'en' => 'Text description input > Generate UI/UX wireframes based on the provided description',
                    'fr' => 'Saisie de la description textuelle > Génération de wireframes UI/UX basés sur la description fournie',
                    'hi' => 'पाठ विवरण इनपुट > प्रदान किए गए विवरण के आधार पर UI/UX वायरफ्रेम उत्पन्न करें',
                    'ja' => 'テキスト説明入力 > 提供された説明に基づいてUI/UXワイヤーフレームを生成',
                    'ko' => '텍스트 설명 입력 > 제공된 설명을 바탕으로 UI/UX 와이어프레임 생성',
                    'pt' => 'Entrada de descrição de texto > Gerar wireframes UI/UX com base na descrição fornecida',
                    'th' => 'ป้อนคำอธิบายเป็นข้อความ > สร้างไวร์เฟรม UI/UX ตามคำอธิบายที่ให้มา',
                    'tl' => 'Input ng paglalarawan sa teksto > Bumuo ng mga wireframe ng UI/UX batay sa ibinigay na paglalarawan',
                    'zh' => '文本描述输入 > 基于提供的描述生成UI/UX线框图',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "UI/UX Description",
                                "description"=> $this->getLocalizedText([
                                    'de' => 'Geben Sie eine detaillierte Beschreibung des UI/UX-Designs ein, für das Sie ein Wireframe erstellen möchten',
                                    'en' => 'Provide a detailed description of the UI/UX design you want to create a wireframe for',
                                    'fr' => 'Fournissez une description détaillée du design UI/UX pour lequel vous souhaitez créer un wireframe',
                                    'hi' => 'आप जिस UI/UX डिज़ाइन का वायरफ्रेम बनाना चाहते हैं उसका विस्तृत विवरण प्रदान करें',
                                    'ja' => 'ワイヤーフレームを作成したいUI/UXデザインの詳細な説明を提供してください',
                                    'ko' => '와이어프레임을 만들고자 하는 UI/UX 디자인에 대한 자세한 설명을 제공하세요',
                                    'pt' => 'Forneça uma descrição detalhada do design UI/UX para o qual você deseja criar um wireframe',
                                    'th' => 'ให้คำอธิบายโดยละเอียดเกี่ยวกับการออกแบบ UI/UX ที่คุณต้องการสร้างไวร์เฟรม',
                                    'tl' => 'Magbigay ng detalyadong paglalarawan ng disenyo ng UI/UX na gusto mong gumawa ng wireframe',
                                    'zh' => '提供你想要创建线框图的UI/UX设计的详细描述',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "placeholder" => "Describe the layout, components, and any specific features of your UI/UX design..."
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_wireframe",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the provided UI/UX design description, generate an SVG wireframe. Here's the UI/UX description:
                            {{step1.input.UI/UX_Description}}
                            Follow these guidelines:
            
                            1. Create a complete SVG structure with appropriate viewBox attribute.
                            2. Use basic shapes (rect, circle, line, path) to represent UI elements.
                            3. Use different shades of gray for various elements to indicate hierarchy.
                            4. Include placeholder text using <text> elements where appropriate.
                            5. Group related elements using <g> tags.
                            6. Add comments to explain major sections of the wireframe.
                            7. Ensure the wireframe is responsive by using percentage-based dimensions where appropriate.
                            8. The output should be solely the SVG code, without any additional explanations or suggestions outside of the SVG structure.",
                        "background_information" => "You are an expert UI/UX designer specializing in creating clear, informative wireframes. Your task is to translate textual UI/UX descriptions into SVG wireframes. You excel at interpreting design requirements and representing them in a simplified, schematic form. Your output is always valid SVG, optimized for immediate use, and requires no further explanation or context.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4-vision-preview",
                        "temperature" => 0.3,
                        "reference_text" => "{{step1.input.UI/UX Description}}"
                    ],
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "UI/UX", "wireframe"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Prototyp-Planer-Generator',
                    'en' => 'Prototype Planner Generator',
                    'fr' => 'Générateur de planificateur de prototype',
                    'hi' => 'प्रोटोटाइप योजनाकार जनरेटर',
                    'ja' => 'プロトタイププランナージェネレーター',
                    'ko' => '프로토타입 계획서 생성기',
                    'pt' => 'Gerador de Planejador de Protótipo',
                    'th' => 'เครื่องมือสร้างแผนต้นแบบ',
                    'tl' => 'Tagabuo ng Plano ng Prototype',
                    'zh' => '原型规划生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Produktidee > Ziele, Funktionen, Zeitplan, Ressourcen und Budgetplanung',
                    'en' => 'product idea > goals, features, timeline, resources, and budget planning',
                    'fr' => 'idée de produit > objectifs, fonctionnalités, calendrier, ressources et planification budgétaire',
                    'hi' => 'उत्पाद विचार > लक्ष्य, विशेषताएँ, समयरेखा, संसाधन और बजट योजना',
                    'ja' => '製品アイデア > 目標、機能、タイムライン、リソース、予算計画',
                    'ko' => '제품 아이디어 > 목표, 기능, 일정, 리소스 및 예산 계획',
                    'pt' => 'ideia do produto > metas, recursos, cronograma, recursos e planejamento orçamentário',
                    'th' => 'แนวคิดผลิตภัณฑ์ > เป้าหมาย, คุณสมบัติ, ไทม์ไลน์, ทรัพยากร และการวางแผนงบประมาณ',
                    'tl' => 'ideya ng produkto > mga layunin, tampok, timeline, mapagkukunan, at pagpaplano ng badyet',
                    'zh' => '产品创意 > 目标、功能、时间线、资源和预算规划',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "product_idea",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Geben Sie Ihre Produktidee ein',
                                    'en' => 'Enter your product idea',
                                    'fr' => 'Saisissez votre idée de produit',
                                    'hi' => 'अपना उत्पाद विचार दर्ज करें',
                                    'ja' => '製品アイデアを入力してください',
                                    'ko' => '제품 아이디어를 입력하세요',
                                    'pt' => 'Digite sua ideia de produto',
                                    'th' => 'ป้อนแนวคิดผลิตภัณฑ์ของคุณ',
                                    'tl' => 'Ilagay ang iyong ideya ng produkto',
                                    'zh' => '输入您的产品创意',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the product idea '{{step1.input.product_idea}}', generate a list of 5 clear and achievable goals for the prototype. These goals should:\n\n1. Be specific and measurable.\n2. Align with the core concept of the product.\n3. Focus on key aspects to be demonstrated in the prototype.\n4. Be realistic for a prototype stage.\n5. Cover different aspects of the product (e.g., functionality, user experience, technical feasibility).\n\nPresent the goals in a numbered list format.",
                        "background_information" => "You are a product strategist tasked with defining clear goals for a prototype based on the initial product idea.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create a list of 10 key features for the prototype of '{{step1.input.product_idea}}'. These features should:\n\n1. Directly support the goals outlined in {{step2.output}}.\n2. Be specific and clearly defined.\n3. Focus on core functionality essential for the prototype.\n4. Be feasible to implement in a prototype stage.\n5. Provide a mix of user-facing and technical features.\n\nPresent the features in a numbered list, with a brief one-sentence description for each.",
                        "background_information" => "You are a product designer developing a feature list for a prototype, balancing essential functionality with feasibility.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Develop a timeline for the prototype development of '{{step1.input.product_idea}}'. The timeline should:\n\n1. Cover a 6-week period.\n2. Break down the development process into weekly milestones.\n3. Include key tasks and deliverables for each week.\n4. Align with the goals from {{step2.output}} and features from {{step3.output}}.\n5. Account for design, development, testing, and refinement phases.\n\nPresent the timeline in a structured week-by-week format, with bullet points for each week's main tasks.",
                        "background_information" => "You are a project manager creating a realistic timeline for prototype development, ensuring all crucial aspects are covered within a 6-week timeframe.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create a resource allocation plan for the prototype development of '{{step1.input.product_idea}}'. The plan should:\n\n1. Identify the key roles needed for the project (e.g., developers, designers, project manager).\n2. Specify the number of team members for each role.\n3. Outline the main responsibilities for each role.\n4. Suggest any specific skills or expertise required.\n5. Align with the timeline from {{step4.output}} and the features from {{step3.output}}.\n\nPresent the resource allocation plan in a structured format, listing each role with its details.",
                        "background_information" => "You are a resource manager planning the team composition and responsibilities for an efficient prototype development process.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "prompt" => "Develop a budget plan for the prototype development of '{{step1.input.product_idea}}'. The budget plan should:\n\n1. Estimate costs for human resources based on the allocation plan in {{step5.output}}.\n2. Include costs for necessary software, tools, or subscriptions.\n3. Account for any hardware or equipment needs.\n4. Consider any outsourcing or third-party service costs.\n5. Add a contingency amount for unforeseen expenses.\n\nPresent the budget in a structured format with categories and estimated amounts. Provide a total estimated budget at the end.",
                        "background_information" => "You are a financial planner creating a comprehensive budget for the prototype development, ensuring all necessary resources are accounted for while maintaining cost-efficiency.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 7,
                        "uuid" => $generateUuid(),
                        "content_template" => "## Goals\n{{step2.output}}\n\n## Key Features\n{{step3.output}}\n\n## Development Timeline\n{{step4.output}}\n\n## Resource Allocation\n{{step5.output}}\n\n## Budget Plan\n{{step6.output}}"
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "prototype", "product_development", "project_plan"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Projekt-Status-Berichtsgenerator',
                    'en' => 'Project Status Report Generator',
                    'fr' => 'Générateur de rapport d\'état de projet',
                    'hi' => 'परियोजना स्थिति रिपोर्ट जनरेटर',
                    'ja' => 'プロジェクト状況レポートジェネレーター',
                    'ko' => '프로젝트 상태 보고서 생성기',
                    'pt' => 'Gerador de Relatório de Status do Projeto',
                    'th' => 'เครื่องมือสร้างรายงานสถานะโครงการ',
                    'tl' => 'Tagabuo ng Ulat sa Katayuan ng Proyekto',
                    'zh' => '项目状态报告生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Projektstatus > detaillierter Bericht mit Fortschritt, Risiken und nächsten Schritten',
                    'en' => 'project status > detailed report with progress, risks, and next steps',
                    'fr' => 'état du projet > rapport détaillé avec progrès, risques et prochaines étapes',
                    'hi' => 'परियोजना स्थिति > प्रगति, जोखिम और अगले कदमों के साथ विस्तृत रिपोर्ट',
                    'ja' => 'プロジェクト状況 > 進捗、リスク、次のステップを含む詳細レポート',
                    'ko' => '프로젝트 상태 > 진행 상황, 위험 및 다음 단계가 포함된 상세 보고서',
                    'pt' => 'status do projeto > relatório detalhado com progresso, riscos e próximos passos',
                    'th' => 'สถานะโครงการ > รายงานโดยละเอียดพร้อมความคืบหน้า ความเสี่ยง และขั้นตอนต่อไป',
                    'tl' => 'katayuan ng proyekto > detalyadong ulat na may pag-unlad, mga panganib, at susunod na hakbang',
                    'zh' => '项目状态 > 包含进展、风险和下一步计划的详细报告',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "project_status",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Wählen Sie den aktuellen Projektstatus aus',
                                    'en' => 'Select the current project status',
                                    'fr' => 'Sélectionnez l\'état actuel du projet',
                                    'hi' => 'वर्तमान परियोजना स्थिति का चयन करें',
                                    'ja' => '現在のプロジェクト状況を選択してください',
                                    'ko' => '현재 프로젝트 상태를 선택하세요',
                                    'pt' => 'Selecione o status atual do projeto',
                                    'th' => 'เลือกสถานะโครงการปัจจุบัน',
                                    'tl' => 'Piliin ang kasalukuyang katayuan ng proyekto',
                                    'zh' => '选择当前项目状态',
                                ]),
                                "type" => "select",
                                "options" => "On Track, At Risk, High Risk, Off Track",
                                "file_type" => null
                            ],
                            [
                                "label" => "project_description",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Beschreiben Sie kurz den aktuellen Projektstatus',
                                    'en' => 'Briefly describe the current project status',
                                    'fr' => 'Décrivez brièvement l\'état actuel du projet',
                                    'hi' => 'वर्तमान परियोजना स्थिति का संक्षेप में वर्णन करें',
                                    'ja' => '現在のプロジェクト状況を簡潔に説明してください',
                                    'ko' => '현재 프로젝트 상태를 간략히 설명하세요',
                                    'pt' => 'Descreva brevemente o status atual do projeto',
                                    'th' => 'อธิบายสถานะโครงการปัจจุบันโดยย่อ',
                                    'tl' => 'Ilarawan nang maikli ang kasalukuyang katayuan ng proyekto',
                                    'zh' => '简要描述当前项目状态',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the project status '{{step1.input.project_status}}' and the description '{{step1.input.project_description}}', generate an executive summary for the project status report. The summary should:\n\n1. Clearly state the current project status.\n2. Highlight key achievements or milestones reached.\n3. Briefly mention any major challenges or risks.\n4. Provide an overall assessment of the project's health.\n5. Be concise and to the point (3-5 sentences).",
                        "background_information" => "You are a project manager preparing a status report for senior management. Your goal is to provide a clear and concise overview of the project's current state.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create a detailed progress update for the project based on the status '{{step1.input.project_status}}' and description '{{step1.input.project_description}}'. The progress update should:\n\n1. List 3-5 key accomplishments since the last report.\n2. Provide specific details on tasks completed and their impact.\n3. Mention any tasks that are behind schedule and explain why.\n4. Include quantitative metrics where possible (e.g., percentage complete, number of deliverables finished).\n5. Be presented in a bulleted list format for easy reading.",
                        "background_information" => "You are a project coordinator compiling a comprehensive progress update. Your goal is to provide a clear picture of what has been achieved and what is still pending.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Develop a risk assessment based on the project status '{{step1.input.project_status}}' and description '{{step1.input.project_description}}'. The risk assessment should:\n\n1. Identify 3-5 key risks or challenges facing the project.\n2. Rate each risk as Low, Medium, or High impact.\n3. Provide a brief description of each risk and its potential consequences.\n4. Suggest mitigation strategies for each identified risk.\n5. Be presented in a table format with columns for Risk, Impact, Description, and Mitigation Strategy.",
                        "background_information" => "You are a risk management specialist analyzing the project's current situation. Your goal is to highlight potential issues and propose ways to address them.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create a list of next steps and recommendations based on the project status '{{step1.input.project_status}}' and description '{{step1.input.project_description}}'. This section should:\n\n1. Propose 3-5 concrete actions to address current challenges or maintain progress.\n2. Prioritize these actions (High, Medium, Low priority).\n3. Assign a timeframe for each action (e.g., Immediate, Within 1 week, Within 1 month).\n4. Briefly explain the expected outcome or benefit of each action.\n5. Be presented in a bulleted list format, with each action clearly labeled with its priority and timeframe.",
                        "background_information" => "You are a project strategist recommending the best course of action moving forward. Your goal is to provide clear, actionable steps to keep the project on track or get it back on track.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "content_template" => "# Project Status Report\n\n## Executive Summary\n{{step2.output}}\n\n## Progress Update\n{{step3.output}}\n\n## Risk Assessment\n{{step4.output}}\n\n## Next Steps and Recommendations\n{{step5.output}}"
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "project_management", "status_report", "risk_management"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Projekt-Ergebnisbericht-Generator',
                    'en' => 'Project Result Report Generator',
                    'fr' => 'Générateur de rapport de résultats de projet',
                    'hi' => 'परियोजना परिणाम रिपोर्ट जनरेटर',
                    'ja' => 'プロジェクト結果レポートジェネレーター',
                    'ko' => '프로젝트 결과보고서 생성기',
                    'pt' => 'Gerador de Relatório de Resultados do Projeto',
                    'th' => 'เครื่องมือสร้างรายงานผลลัพธ์โครงการ',
                    'tl' => 'Tagabuo ng Ulat ng Resulta ng Proyekto',
                    'zh' => '项目结果报告生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Projektergebnisse > umfassender Bericht mit Zielerreichung, Herausforderungen und Erkenntnissen',
                    'en' => 'project results > comprehensive report with goal achievement, challenges, and lessons learned',
                    'fr' => 'résultats du projet > rapport complet avec réalisation des objectifs, défis et leçons apprises',
                    'hi' => 'परियोजना परिणाम > लक्ष्य प्राप्ति, चुनौतियों और सीखे गए पाठों के साथ व्यापक रिपोर्ट',
                    'ja' => 'プロジェクト結果 > 目標達成、課題、学んだ教訓を含む包括的なレポート',
                    'ko' => '프로젝트 결과 > 목표 달성, 도전 과제 및 학습된 교훈이 포함된 종합 보고서',
                    'pt' => 'resultados do projeto > relatório abrangente com realização de objetivos, desafios e lições aprendidas',
                    'th' => 'ผลลัพธ์โครงการ > รายงานครอบคลุมพร้อมการบรรลุเป้าหมาย ความท้าทาย และบทเรียนที่ได้รับ',
                    'tl' => 'resulta ng proyekto > komprehensibong ulat na may pagkamit ng layunin, mga hamon, at mga aral na natutunan',
                    'zh' => '项目结果 > 包含目标实现、挑战和经验教训的综合报告',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "project_name",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Geben Sie den Projektnamen ein',
                                    'en' => 'Enter the project name',
                                    'fr' => 'Entrez le nom du projet',
                                    'hi' => 'परियोजना का नाम दर्ज करें',
                                    'ja' => 'プロジェクト名を入力してください',
                                    'ko' => '프로젝트 이름을 입력하세요',
                                    'pt' => 'Digite o nome do projeto',
                                    'th' => 'ป้อนชื่อโครงการ',
                                    'tl' => 'Ilagay ang pangalan ng proyekto',
                                    'zh' => '输入项目名称',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "project_duration",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Geben Sie die Projektdauer ein (z.B. 6 Monate, 1 Jahr)',
                                    'en' => 'Enter the project duration (e.g., 6 months, 1 year)',
                                    'fr' => 'Entrez la durée du projet (par exemple, 6 mois, 1 an)',
                                    'hi' => 'परियोजना की अवधि दर्ज करें (उदाहरण: 6 महीने, 1 वर्ष)',
                                    'ja' => 'プロジェクト期間を入力してください（例：6ヶ月、1年）',
                                    'ko' => '프로젝트 기간을 입력하세요 (예: 6개월, 1년)',
                                    'pt' => 'Digite a duração do projeto (por exemplo, 6 meses, 1 ano)',
                                    'th' => 'ป้อนระยะเวลาโครงการ (เช่น 6 เดือน, 1 ปี)',
                                    'tl' => 'Ilagay ang tagal ng proyekto (halimbawa, 6 na buwan, 1 taon)',
                                    'zh' => '输入项目持续时间（例如：6个月，1年）',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "project_objectives",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Beschreiben Sie kurz die Hauptziele des Projekts',
                                    'en' => 'Briefly describe the main objectives of the project',
                                    'fr' => 'Décrivez brièvement les principaux objectifs du projet',
                                    'hi' => 'परियोजना के मुख्य उद्देश्यों का संक्षेप में वर्णन करें',
                                    'ja' => 'プロジェクトの主な目的を簡潔に説明してください',
                                    'ko' => '프로젝트의 주요 목표를 간략히 설명하세요',
                                    'pt' => 'Descreva brevemente os principais objetivos do projeto',
                                    'th' => 'อธิบายวัตถุประสงค์หลักของโครงการโดยย่อ',
                                    'tl' => 'Ilarawan nang maikli ang mga pangunahing layunin ng proyekto',
                                    'zh' => '简要描述项目的主要目标',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the project name '{{step1.input.project_name}}', duration '{{step1.input.project_duration}}', and objectives '{{step1.input.project_objectives}}', generate an executive summary for the project result report. The summary should:\n\n1. Provide an overview of the project and its main goals.\n2. Highlight key achievements and outcomes.\n3. Briefly mention any significant challenges faced and how they were addressed.\n4. Summarize the overall impact and success of the project.\n5. Be concise and informative (4-6 sentences).",
                        "background_information" => "You are a project manager preparing a final report for stakeholders. Your goal is to provide a clear and concise overview of the project's results and impact.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create a detailed list of project achievements based on the objectives '{{step1.input.project_objectives}}'. The achievements section should:\n\n1. List 5-7 key accomplishments of the project.\n2. Align each achievement with the original project objectives.\n3. Provide specific, quantifiable results where possible (e.g., percentage improvements, number of deliverables).\n4. Highlight any unexpected positive outcomes.\n5. Be presented in a bulleted list format for easy reading.",
                        "background_information" => "You are a project coordinator compiling a comprehensive list of project achievements. Your goal is to showcase the project's successes and demonstrate how they align with the initial objectives.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Develop a challenges and solutions section for the project result report. This section should:\n\n1. Identify 4-6 significant challenges faced during the project.\n2. Briefly describe each challenge and its potential impact on the project.\n3. Explain the solution or approach used to address each challenge.\n4. Highlight any positive outcomes or lessons learned from overcoming these challenges.\n5. Be presented in a table format with columns for Challenge, Impact, Solution, and Outcome.",
                        "background_information" => "You are a project analyst reviewing the obstacles encountered during the project. Your goal is to demonstrate how challenges were effectively managed and to highlight the team's problem-solving capabilities.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create a lessons learned and best practices section based on the project experience. This section should:\n\n1. Identify 5-7 key lessons learned from the project.\n2. For each lesson, provide a brief explanation of the context and the insight gained.\n3. Suggest how each lesson can be applied to future projects.\n4. Highlight any best practices that emerged during the project.\n5. Be presented in a numbered list format, with each item clearly stating the lesson and its application.",
                        "background_information" => "You are a knowledge management specialist tasked with capturing valuable insights from the project. Your goal is to provide actionable lessons and best practices that can improve future project performance.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "prompt" => "Develop a conclusion and recommendations section for the project result report. This section should:\n\n1. Summarize the overall success and impact of the project.\n2. Reflect on how well the project met its original objectives.\n3. Highlight the most significant achievements and lessons learned.\n4. Provide 3-5 key recommendations for future similar projects or next steps.\n5. End with a forward-looking statement about the project's long-term impact or potential future developments.\n\nAim for a concise yet comprehensive conclusion (3-4 paragraphs).",
                        "background_information" => "You are a senior project manager providing the final assessment of the project. Your goal is to offer a balanced view of the project's outcomes and provide valuable insights for future initiatives.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 7,
                        "uuid" => $generateUuid(),
                        "content_template" => "## Executive Summary\n{{step2.output}}\n\n## Project Achievements\n{{step3.output}}\n\n## Challenges and Solutions\n{{step4.output}}\n\n## Lessons Learned and Best Practices\n{{step5.output}}\n\n## Conclusion and Recommendations\n{{step6.output}}"
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "project_management", "result_report"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Umfrage-Generator',
                    'en' => 'Survey Generator',
                    'fr' => 'Générateur de sondage',
                    'hi' => 'सर्वेक्षण जनरेटर',
                    'ja' => 'アンケート生成ツール',
                    'ko' => '설문조사 생성기',
                    'pt' => 'Gerador de Pesquisa',
                    'th' => 'เครื่องมือสร้างแบบสำรวจ',
                    'tl' => 'Tagabuo ng Survey',
                    'zh' => '调查问卷生成器',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Umfragezweck > automatische Generierung von maßgeschneiderten Umfragefragen verschiedener Typen',
                    'en' => 'survey purpose > automatic generation of tailored survey questions of various types',
                    'fr' => 'objectif de l\'enquête > génération automatique de questions d\'enquête personnalisées de différents types',
                    'hi' => 'सर्वेक्षण उद्देश्य > विभिन्न प्रकार के अनुकूलित सर्वेक्षण प्रश्नों का स्वचालित उत्पादन',
                    'ja' => 'アンケートの目的 > さまざまなタイプのカスタマイズされたアンケート質問の自動生成',
                    'ko' => '설문조사 목적 > 다양한 유형의 맞춤형 설문조사 문항 자동 생성',
                    'pt' => 'objetivo da pesquisa > geração automática de perguntas de pesquisa personalizadas de vários tipos',
                    'th' => 'วัตถุประสงค์ของแบบสำรวจ > การสร้างคำถามแบบสำรวจที่ปรับแต่งได้หลากหลายรูปแบบโดยอัตโนมัติ',
                    'tl' => 'layunin ng survey > awtomatikong pagbuo ng mga naka-customize na tanong sa survey ng iba\'t ibang uri',
                    'zh' => '调查目的 > 自动生成各种类型的定制调查问题',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "survey_purpose",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Beschreiben Sie kurz den Zweck der Umfrage',
                                    'en' => 'Briefly describe the purpose of the survey',
                                    'fr' => 'Décrivez brièvement l\'objectif de l\'enquête',
                                    'hi' => 'सर्वेक्षण के उद्देश्य का संक्षेप में वर्णन करें',
                                    'ja' => 'アンケートの目的を簡潔に説明してください',
                                    'ko' => '설문조사의 목적을 간략히 설명하세요',
                                    'pt' => 'Descreva brevemente o objetivo da pesquisa',
                                    'th' => 'อธิบายวัตถุประสงค์ของแบบสำรวจโดยย่อ',
                                    'tl' => 'Ilarawan nang maikli ang layunin ng survey',
                                    'zh' => '简要描述调查的目的',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "target_audience",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Beschreiben Sie die Zielgruppe für die Umfrage',
                                    'en' => 'Describe the target audience for the survey',
                                    'fr' => 'Décrivez le public cible de l\'enquête',
                                    'hi' => 'सर्वेक्षण के लक्षित दर्शकों का वर्णन करें',
                                    'ja' => 'アンケートの対象者を説明してください',
                                    'ko' => '설문조사의 대상 청중을 설명하세요',
                                    'pt' => 'Descreva o público-alvo da pesquisa',
                                    'th' => 'อธิบายกลุ่มเป้าหมายของแบบสำรวจ',
                                    'tl' => 'Ilarawan ang target na audience para sa survey',
                                    'zh' => '描述调查的目标受众',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "question_types",
                                "description"  => $this->getLocalizedText([
                                    'de' => 'Wählen Sie die gewünschten Fragetypen aus',
                                    'en' => 'Select the desired question types',
                                    'fr' => 'Sélectionnez les types de questions souhaités',
                                    'hi' => 'वांछित प्रश्न प्रकारों का चयन करें',
                                    'ja' => '希望する質問タイプを選択してください',
                                    'ko' => '원하는 질문 유형을 선택하세요',
                                    'pt' => 'Selecione os tipos de perguntas desejados',
                                    'th' => 'เลือกประเภทคำถามที่ต้องการ',
                                    'tl' => 'Piliin ang mga nais na uri ng tanong',
                                    'zh' => '选择所需的问题类型',
                                ]),
                                "type" => "multiselect",
                                "options" => $this->getLocalizedText([
                                    'de' => '[Mehrfachauswahl] Mehrfachauswahlfragen, [Likert-Skala] Likert-Skala Fragen, [Offen] Offene Fragen, [Bewertungsskala] Bewertungsskalafragen, [Ranking] Ranking-Fragen, [Ja/Nein] Ja/Nein-Fragen, [Dropdown] Dropdown-Fragen',
                                    'en' => '[Multiple Choice] Multiple choice questions, [Likert Scale] Likert scale questions, [Open-ended] Open-ended questions, [Rating Scale] Rating scale questions, [Ranking] Ranking questions, [Yes/No] Yes/No questions, [Dropdown] Dropdown questions',
                                    'fr' => '[Choix multiple] Questions à choix multiples, [Échelle de Likert] Questions à échelle de Likert, [Ouverte] Questions ouvertes, [Échelle d\'évaluation] Questions à échelle d\'évaluation, [Classement] Questions de classement, [Oui/Non] Questions Oui/Non, [Liste déroulante] Questions à liste déroulante',
                                    'hi' => '[बहुविकल्पीय] बहुविकल्पीय प्रश्न, [लिकर्ट स्केल] लिकर्ट स्केल प्रश्न, [खुला उत्तर] खुले उत्तर वाले प्रश्न, [रेटिंग स्केल] रेटिंग स्केल प्रश्न, [रैंकिंग] रैंकिंग प्रश्न, [हाँ/नहीं] हाँ/नहीं प्रश्न, [ड्रॉपडाउन] ड्रॉपडाउन प्रश्न',
                                    'ja' => '[選択式] 選択式質問, [リッカート尺度] リッカート尺度質問, [自由回答] 自由回答質問, [評価尺度] 評価尺度質問, [ランキング] ランキング質問, [はい/いいえ] はい/いいえ質問, [ドロップダウン] ドロップダウン質問',
                                    'ko' => '[객관식] 객관식 질문, [리커트 척도] 리커트 척도 질문, [주관식] 주관식 질문, [평가 척도] 평가 척도 질문, [순위] 순위 질문, [예/아니오] 예/아니오 질문, [드롭다운] 드롭다운 질문',
                                    'pt' => '[Múltipla escolha] Perguntas de múltipla escolha, [Escala Likert] Perguntas de escala Likert, [Aberta] Perguntas abertas, [Escala de avaliação] Perguntas de escala de avaliação, [Classificação] Perguntas de classificação, [Sim/Não] Perguntas Sim/Não, [Lista suspensa] Perguntas de lista suspensa',
                                    'th' => '[หลายตัวเลือก] คำถามแบบหลายตัวเลือก, [มาตรวัดของลิเคิร์ท] คำถามแบบมาตรวัดของลิเคิร์ท, [ปลายเปิด] คำถามปลายเปิด, [มาตราส่วนประมาณค่า] คำถามแบบมาตราส่วนประมาณค่า, [การจัดอันดับ] คำถามแบบจัดอันดับ, [ใช่/ไม่ใช่] คำถามแบบใช่/ไม่ใช่, [เลือกจากรายการ] คำถามแบบเลือกจากรายการ',
                                    'tl' => '[Maramihang pagpili] Mga tanong na maramihang pagpili, [Likert Scale] Mga tanong sa Likert scale, [Bukas] Mga bukas na tanong, [Rating Scale] Mga tanong sa rating scale, [Ranggo] Mga tanong sa ranggo, [Oo/Hindi] Mga tanong na Oo/Hindi, [Dropdown] Mga tanong na dropdown',
                                    'zh' => '[多选题] 多选题, [李克特量表] 李克特量表问题, [开放式] 开放式问题, [评分量表] 评分量表问题, [排序] 排序问题, [是/否] 是/否问题, [下拉菜单] 下拉菜单问题',
                                ]),
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the survey purpose '{{step1.input.survey_purpose}}', target audience '{{step1.input.target_audience}}', and selected question types {{step1.input.question_types}}, generate an introduction for the survey. The introduction should:\n\n1. Briefly explain the purpose of the survey.\n2. Mention the target audience.\n3. Provide an estimate of how long the survey will take to complete.\n4. Assure respondents of the confidentiality of their responses.\n5. Thank participants for their time and input.\n\nAim for a concise yet informative introduction (3-4 sentences).",
                        "background_information" => "You are a survey design expert creating an engaging and informative introduction to encourage participation and set appropriate expectations for the survey.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Generate a set of survey questions based on the purpose '{{step1.input.survey_purpose}}', target audience '{{step1.input.target_audience}}', and selected question types {{step1.input.question_types}}. For each question type selected, create 2-3 relevant questions. Ensure that:\n\n1. Questions are clear, concise, and directly related to the survey purpose.\n2. The language is appropriate for the target audience.\n3. Each question is labeled with its type (e.g., [Multiple Choice], [Likert Scale], etc.).\n4. For closed-ended questions (Multiple Choice, Likert Scale, etc.), provide appropriate response options.\n5. Open-ended questions are phrased to encourage detailed responses.\n\nPresent the questions in a numbered list format.",
                        "background_information" => "You are a survey methodology expert crafting questions that will effectively gather the desired information while ensuring a good user experience for the respondents.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create a set of demographic questions appropriate for the survey purpose '{{step1.input.survey_purpose}}' and target audience '{{step1.input.target_audience}}'. Include 3-5 relevant demographic questions that:\n\n1. Are appropriate and necessary for the analysis of the survey results.\n2. Use a mix of question types (e.g., multiple choice, dropdown).\n3. Provide inclusive options for sensitive questions (e.g., gender, ethnicity).\n4. Include an option to decline to answer for sensitive questions.\n5. Are placed at the end of the survey to avoid survey fatigue on critical questions.\n\nPresent the demographic questions in a numbered list format.",
                        "background_information" => "You are a survey designer focusing on creating demographic questions that will provide valuable segmentation data while respecting respondents' privacy and comfort.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Develop a conclusion for the survey that:\n\n1. Thanks the respondent for their time and input.\n2. Reiterates the importance of their participation.\n3. Provides information on how the results will be used (if appropriate).\n4. Gives contact information for any questions or concerns about the survey.\n5. If applicable, mentions any follow-up actions or how participants can stay informed about the outcomes.\n\nAim for a brief, appreciative conclusion (2-3 sentences).",
                        "background_information" => "You are a communication specialist creating a conclusion that leaves respondents with a positive impression and reinforces the value of their participation.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "content_template" => "## Introduction\n{{step2.output}}\n\n## Survey Questions\n{{step3.output}}\n\n## Demographic Questions\n{{step4.output}}\n\n## Conclusion\n{{step5.output}}"
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "survey", "questionnaire", "market research"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => '1Pager-Generierungsprozess',
                    'en' => '1Pager Generation Process',
                    'fr' => 'Processus de génération de 1Pager',
                    'hi' => '1Pager उत्पादन प्रक्रिया',
                    'ja' => '1Pager生成プロセス',
                    'ko' => '1Pager 생성 프로세스',
                    'pt' => 'Processo de Geração de 1Pager',
                    'th' => 'กระบวนการสร้าง 1Pager',
                    'tl' => 'Proseso ng Paggawa ng 1Pager',
                    'zh' => '1Pager生成过程',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Anforderungen eingeben > 1Pager generieren',
                    'en' => 'Requirements input > 1Pager generation',
                    'fr' => 'Saisie des exigences > Génération de 1Pager',
                    'hi' => 'आवश्यकताओं का इनपुट > 1Pager उत्पादन',
                    'ja' => '要件入力 > 1Pager生成',
                    'ko' => '요구사항 입력 > 1Pager 생성',
                    'pt' => 'Entrada de requisitos > Geração de 1Pager',
                    'th' => 'ป้อนความต้องการ > สร้าง 1Pager',
                    'tl' => 'Input ng mga kinakailangan > Paggawa ng 1Pager',
                    'zh' => '要求输入 > 1Pager生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "key_points",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Liste der Hauptpunkte oder Anforderungen (durch Kommas getrennt)',
                                    'en' => 'List the key points or requirements (separated by commas)',
                                    'fr' => 'Lister les points clés ou exigences (séparés par des virgules)',
                                    'hi' => 'मुख्य बिंदुओं या आवश्यकताओं को सूचीबद्ध करें (अल्पविराम से अलग करें)',
                                    'ja' => '要点や要件をリストアップしてください（コンマで区切る）',
                                    'ko' => '핵심 사항 또는 요구 사항 나열 (쉼표로 구분)',
                                    'pt' => 'Liste os pontos-chave ou requisitos (separados por vírgulas)',
                                    'th' => 'ระบุจุดสำคัญหรือความต้องการ (แยกด้วยเครื่องหมายจุลภาค)',
                                    'tl' => 'Ilista ang mga pangunahing punto o kinakailangan (hinihiwalay ng mga kuwit)',
                                    'zh' => '列出关键点或要求（用逗号分隔）',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create a comprehensive 1-pager document that includes the following:\n\n1. **Project Overview**:\n   - Provide a brief summary of the project, its goals, and objectives.\n\n2. **Key Points**:\n   - Elaborate on the following key points:\n     {{step1.input.key_points}}\n\n3. **Benefits and Impact**:\n   - Explain the benefits and potential impact of the project.\n\n4. **Conclusion**:\n   - Summarize the main points and provide a compelling closing statement.\n\nGuidelines:\n\n- Use clear and concise language suitable for a professional audience.\n- Structure the document with headings and bullet points for readability.\n- Keep the length to one page when formatted.\n- Ensure the content is engaging and persuasive.\n\nPlease generate the 1-pager document now.",
                        "background_information" => "You are a professional business writer skilled in creating concise and impactful 1-pager documents that effectively communicate key information to stakeholders.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "1pager", "business", "document"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => '6Pager-Generierungsprozess',
                    'en' => '6Pager Generation Process',
                    'fr' => 'Processus de génération de 6Pager',
                    'hi' => '6Pager उत्पादन प्रक्रिया',
                    'ja' => '6Pager生成プロセス',
                    'ko' => '6Pager 생성 프로세스',
                    'pt' => 'Processo de Geração de 6Pager',
                    'th' => 'กระบวนการสร้าง 6Pager',
                    'tl' => 'Proseso ng Paggawa ng 6Pager',
                    'zh' => '6Pager生成过程',
                ]),

                'description' => $this->getLocalizedText([
                    'de' => 'Anforderungen eingeben > 6Pager generieren',
                    'en' => 'Requirements input > 6Pager generation',
                    'fr' => 'Saisie des exigences > Génération de 6Pager',
                    'hi' => 'आवश्यकताओं का इनपुट > 6Pager उत्पादन',
                    'ja' => '要件入力 > 6Pager生成',
                    'ko' => '요구사항 입력 > 6Pager 생성',
                    'pt' => 'Entrada de requisitos > Geração de 6Pager',
                    'th' => 'ป้อนความต้องการ > สร้าง 6Pager',
                    'tl' => 'Input ng mga kinakailangan > Paggawa ng 6Pager',
                    'zh' => '要求输入 > 6Pager生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "key_points",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Liste der Hauptpunkte oder Anforderungen (durch Kommas getrennt)',
                                    'en' => 'List the key points or requirements (separated by commas)',
                                    'fr' => 'Lister les points clés ou exigences (séparés par des virgules)',
                                    'hi' => 'मुख्य बिंदुओं या आवश्यकताओं को सूचीबद्ध करें (अल्पविराम से अलग करें)',
                                    'ja' => '要点や要件をリストアップしてください（コンマで区切る）',
                                    'ko' => '핵심 사항 또는 요구 사항 나열 (쉼표로 구분)',
                                    'pt' => 'Liste os pontos-chave ou requisitos (separados por vírgulas)',
                                    'th' => 'ระบุจุดสำคัญหรือความต้องการ (แยกด้วยเครื่องหมายจุลภาค)',
                                    'tl' => 'Ilista ang mga pangunahing punto o kinakailangan (hinihiwalay ng mga kuwit)',
                                    'zh' => '列出关键点或要求（用逗号分隔）',
                                ]),
                                "type" => "textarea",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Create the **Introduction (Why)** section for a 6-pager document based on the following key points:\n\n{{step1.input.key_points}}\n\nGuidelines:\n\n- Explain the purpose and importance of the project or topic.\n- Use clear and persuasive language.\n- Keep the content concise and focused on the 'Why'.\n\nPlease generate the Introduction section now.",
                        "background_information" => "You are a professional business writer skilled in creating impactful documents that effectively communicate key information to stakeholders.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the key points:\n\n{{step1.input.key_points}}\n\nCreate the **Objectives (What)** section for the 6-pager document. Include:\n\n- Specific goals and objectives.\n- What the project aims to achieve.\n\nGuidelines:\n\n- Use clear and actionable language.\n- Ensure objectives are measurable and relevant.\n\nPlease generate the Objectives section now.",
                        "background_information" => "You are a professional business writer skilled in articulating clear objectives for business documents.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 4,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the key points:\n\n{{step1.input.key_points}}\n\nCreate the **Principles (With)** section for the 6-pager document. Include:\n\n- Core principles guiding the project.\n- Key methodologies or frameworks being used.\n\nGuidelines:\n\n- Clearly state the principles.\n- Explain how they support the objectives.\n\nPlease generate the Principles section now.",
                        "background_information" => "You are a professional business writer skilled in defining principles and methodologies in business documents.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 5,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the key points:\n\n{{step1.input.key_points}}\n\nCreate the **Current Situation (Now)** section for the 6-pager document. Include:\n\n- The current status of the project.\n- Any recent developments or achievements.\n\nGuidelines:\n\n- Provide factual and up-to-date information.\n- Use clear and concise language.\n\nPlease generate the Current Situation section now.",
                        "background_information" => "You are a professional business writer skilled in reporting current statuses in business documents.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 6,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the key points:\n\n{{step1.input.key_points}}\n\nCreate the **Past Situation (Lesson Learned)** section for the 6-pager document. Include:\n\n- Previous challenges or obstacles faced.\n- Lessons learned from past experiences.\n\nGuidelines:\n\n- Reflect on past experiences.\n- Highlight how these lessons inform current strategies.\n\nPlease generate the Past Situation section now.",
                        "background_information" => "You are a professional business writer skilled in analyzing past experiences for business insights.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 7,
                        "uuid" => $generateUuid(),
                        "prompt" => "Based on the key points:\n\n{{step1.input.key_points}}\n\nCreate the **Future Plans (Future)** section for the 6-pager document. Include:\n\n- Planned actions and strategies moving forward.\n- Expected outcomes and milestones.\n\nGuidelines:\n\n- Be clear and aspirational.\n- Align future plans with the objectives and principles.\n\nPlease generate the Future Plans section now.",
                        "background_information" => "You are a professional business writer skilled in outlining future strategies in business documents.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "content_integration",
                        "step_number" => 8,
                        "uuid" => $generateUuid(),
                        "content_template" => "{{step2.output}}\n\n{{step3.output}}\n\n{{step4.output}}\n\n{{step5.output}}\n\n{{step6.output}}\n\n{{step7.output}}\n\n"
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["planner", "6pager", "business", "document"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],                
            //이미지
            [
                'name' => $this->getLocalizedText([
                    'de' => '[Dalle3] Bildgenerierung',
                    'en' => '[Dalle3] Image Generate',
                    'fr' => '[Dalle3] Génération d\'image',
                    'hi' => '[Dalle3] छवि उत्पादन',
                    'ja' => '[Dalle3] 画像生成',
                    'ko' => '[Dalle3] 이미지 생성기',
                    'pt' => '[Dalle3] Geração de Imagem',
                    'th' => '[Dalle3] สร้างภาพ',
                    'tl' => '[Dalle3] Paggawa ng Imahe',
                    'zh' => '[Dalle3] 图像生成',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a description in English for an AI image generator. The description should be detailed and use commas to enhance the imagery. Follow these guidelines:
            
            - **Length**: Aim for one to two sentences.
            - **Detail**: Include specific details that capture the essence of the keyword.
            - **Style**: Use descriptive language, incorporating adjectives and sensory details.
            - **Structure**: Use commas to separate clauses, adding depth to the description.
            - **Examples**:
              - *A serene forest clearing bathed in golden sunlight, tall trees with emerald leaves swaying gently, a crystal-clear stream flowing through the moss-covered rocks.*
              - *An old wooden sailboat navigating through misty waters at dawn, the pale sun rising over the horizon, soft hues of pink and orange reflecting on the calm sea.*
              - *A bustling city street at night, neon signs illuminating the rain-soaked pavement, people with umbrellas hurrying by, reflections of lights creating a vibrant atmosphere.*
            - The output must be in English.
            
            Please provide the image description now in English.",
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images. Disregard any existing content generation language settings and strictly produce this content in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "openai",
                        "ai_model" => "dall-e-3",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "della-3"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],            
            [
                'name' => $this->getLocalizedText([
                    'de' => '[StableDiffusion] Bildgenerierung',
                    'en' => '[StableDiffusion] Image Generate',
                    'fr' => '[StableDiffusion] Génération d\'image',
                    'hi' => '[StableDiffusion] छवि उत्पादन',
                    'ja' => '[StableDiffusion] 画像生成',
                    'ko' => '[StableDiffusion] 이미지 생성기',
                    'pt' => '[StableDiffusion] Geração de Imagem',
                    'th' => '[StableDiffusion] สร้างภาพ',
                    'tl' => '[StableDiffusion] Paggawa ng Imahe',
                    'zh' => '[StableDiffusion] 图像生成',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a description in English for an AI image generator. The description should be detailed and use commas to enhance the imagery. Follow these guidelines:
            
            - **Length**: Aim for one to two sentences.
            - **Detail**: Include specific details that capture the essence of the keyword.
            - **Style**: Use descriptive language, incorporating adjectives and sensory details.
            - **Structure**: Use commas to separate clauses, adding depth to the description.
            - **Examples**:
              - *A serene forest clearing bathed in golden sunlight, tall trees with emerald leaves swaying gently, a crystal-clear stream flowing through the moss-covered rocks.*
              - *An old wooden sailboat navigating through misty waters at dawn, the pale sun rising over the horizon, soft hues of pink and orange reflecting on the calm sea.*
              - *A bustling city street at night, neon signs illuminating the rain-soaked pavement, people with umbrellas hurrying by, reflections of lights creating a vibrant atmosphere.*
            - The output must be in English.
            
            Please provide the image description now in English.",
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images. Disregard any existing content generation language settings and strictly produce this content in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "stabilityai/stable-diffusion-2-1",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "stabledifussion"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],            
            [
                'name' => $this->getLocalizedText([
                    'de' => '[FLUX] Bildgenerierung',
                    'en' => '[FLUX] Image Generate',
                    'fr' => '[FLUX] Génération d\'image',
                    'hi' => '[FLUX] छवि उत्पादन',
                    'ja' => '[FLUX] 画像生成',
                    'ko' => '[FLUX] 이미지 생성기',
                    'pt' => '[FLUX] Geração de Imagem',
                    'th' => '[FLUX] สร้างภาพ',
                    'tl' => '[FLUX] Paggawa ng Imahe',
                    'zh' => '[FLUX] 图像生成',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a description in English for an AI image generator. The description should be detailed and use commas to enhance the imagery. Follow these guidelines:
            
            - **Length**: Aim for one to two sentences.
            - **Detail**: Include specific details that capture the essence of the keyword.
            - **Style**: Use descriptive language, incorporating adjectives and sensory details.
            - **Structure**: Use commas to separate clauses, adding depth to the description.
            - **Examples**:
              - *A serene forest clearing bathed in golden sunlight, tall trees with emerald leaves swaying gently, a crystal-clear stream flowing through the moss-covered rocks.*
              - *An old wooden sailboat navigating through misty waters at dawn, the pale sun rising over the horizon, soft hues of pink and orange reflecting on the calm sea.*
              - *A bustling city street at night, neon signs illuminating the rain-soaked pavement, people with umbrellas hurrying by, reflections of lights creating a vibrant atmosphere.*
            - The output must be in English.
            
            Please provide the image description now in English.",
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images. Disregard any existing content generation language settings and strictly produce this content in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "black-forest-labs/FLUX.1-dev",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "flux1-dev"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],            
            [
            'name' => $this->getLocalizedText([
                    'de' => '[FLUX] Erweiterte Bildgenerierung',
                    'en' => '[FLUX] Enhanced Image Generate',
                    'fr' => '[FLUX] Génération d\'image améliorée',
                    'hi' => '[FLUX] उन्नत छवि उत्पादन',
                    'ja' => '[FLUX] 強化画像生成',
                    'ko' => '[FLUX] 향상된 이미지 생성기',
                    'pt' => '[FLUX] Geração de Imagem Aprimorada',
                    'th' => '[FLUX] สร้างภาพขั้นสูง',
                    'tl' => '[FLUX] Pinahusay na Paggawa ng Imahe',
                    'zh' => '[FLUX] 增强图像生成',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a description in English for an AI image generator. The description should be detailed and use commas to enhance the imagery. Follow these guidelines:
            
            - **Length**: Aim for one to two sentences.
            - **Detail**: Include specific details that capture the essence of the keyword.
            - **Style**: Use descriptive language, incorporating adjectives and sensory details.
            - **Structure**: Use commas to separate clauses, adding depth to the description.
            - **Examples**:
              - *A serene forest clearing bathed in golden sunlight, tall trees with emerald leaves swaying gently, a crystal-clear stream flowing through the moss-covered rocks.*
              - *An old wooden sailboat navigating through misty waters at dawn, the pale sun rising over the horizon, soft hues of pink and orange reflecting on the calm sea.*
              - *A bustling city street at night, neon signs illuminating the rain-soaked pavement, people with umbrellas hurrying by, reflections of lights creating a vibrant atmosphere.*
            - The output must be in English.
            
            Please provide the image description now in English.",
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images. Disregard any existing content generation language settings and strictly produce this content in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "Shakker-Labs/FLUX.1-dev-LoRA-add-details",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "flux-detail"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
               'name' => $this->getLocalizedText([
                    'de' => '[FLUX] Polaroid-Fotogenerierung',
                    'en' => '[FLUX] Polaroid Photograph Generate',
                    'fr' => '[FLUX] Génération de photographie Polaroid',
                    'hi' => '[FLUX] पोलरॉइड फ़ोटोग्राफ़ उत्पादन',
                    'ja' => '[FLUX] ポラロイド写真生成',
                    'ko' => '[FLUX] 폴라로이드 포토그래피 생성기',
                    'pt' => '[FLUX] Geração de Fotografia Polaroid',
                    'th' => '[FLUX] สร้างภาพถ่ายโพลารอยด์',
                    'tl' => '[FLUX] Paggawa ng Larawang Polaroid',
                    'zh' => '[FLUX] 宝丽来照片生成',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a concise and vivid description in English for an AI image generator in the style of a polaroid photograph. The description should be formatted similarly to the following examples:

    - a girl laughing, polaroid style
    - A tabby cat lounging on a sun-dappled windowsill, faded, polaroid style
    - The morning after New Year's Eve with scattered party tinsel on the floor, still life, light particles, no humans, polaroid style
    - A woman with long blonde hair wearing big round hippie sunglasses with a slight smile, white oversized fur coat, black dress, early evening in the city, polaroid style
    - A young woman with tousled hair and minimal makeup, wearing an oversized flannel shirt, looking directly at the camera with a slight smile. The background is slightly out of focus, suggesting a cozy bedroom, polaroid style

    Guidelines:

    - Keep the description concise, ideally in one sentence.
    - Focus on vivid imagery and key details that capture the essence of the keyword.
    - Include the phrase 'polaroid style' at the end to specify the desired aesthetic.
    - Use descriptive language to set the scene, mood, and style.
    - Ensure the description is clear and helps the AI generate an image that accurately represents the keyword.
    - The output must be in English.

    Please provide the image description now in English.",
                        "background_information" => "",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "alvdansen/pola-photo-flux",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "flux-polaroid"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => '[FLUX] flmft Bildgenerierung im Kodachrome-Stil',
                    'en' => '[FLUX] flmft Kodachrome Style Image Generate',
                    'fr' => '[FLUX] Génération d\'image style Kodachrome flmft',
                    'hi' => '[FLUX] flmft कोडक्रोम स्टाइल इमेज उत्पादन',
                    'ja' => '[FLUX] flmft コダクロームスタイル画像生成',
                    'ko' => '[FLUX] 코닥 필름 스타일 이미지 생성',
                    'pt' => '[FLUX] Geração de Imagem Estilo Kodachrome flmft',
                    'th' => '[FLUX] สร้างภาพสไตล์ Kodachrome flmft',
                    'tl' => '[FLUX] Paggawa ng Imahe sa Estilo ng Kodachrome flmft',
                    'zh' => '[FLUX] flmft 柯达克罗姆风格图像生成',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a concise and vivid description in English for an AI image generator in the style of a flmft kodachrome style photograph. The description should be formatted similarly to the following examples:
            
            - a girl laughing, flmft kodachrome style style
            - A tabby cat lounging on a sun-dappled windowsill, faded, flmft kodachrome style
            - The morning after New Year's Eve with scattered party tinsel on the floor, still life, light particles, no humans, flmft kodachrome style
            - A woman with long blonde hair wearing big round hippie sunglasses with a slight smile, white oversized fur coat, black dress, early evening in the city, flmft kodachrome style
            - A young woman with tousled hair and minimal makeup, wearing an oversized flannel shirt, looking directly at the camera with a slight smile. The background is slightly out of focus, suggesting a cozy bedroom, flmft kodachrome style
            
            Guidelines:
            
            - Keep the description concise, ideally in one sentence.
            - Focus on vivid imagery and key details that capture the essence of the keyword.
            - Include the phrase 'polaroid style' at the end to specify the desired aesthetic.
            - Use descriptive language to set the scene, mood, and style.
            - Ensure the description is clear and helps the AI generate an image that accurately represents the keyword.
            - The output must be in English.
            
            Please provide the image description now in English.",
                        "background_information" => "",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "alvdansen/flux-koda",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "flux-koda"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
            'name' => $this->getLocalizedText([
                    'de' => '90er-Jahre Kunstgenerierung',
                    'en' => '90s Art Generate',
                    'fr' => 'Génération d\'art des années 90',
                    'hi' => '90 के दशक की कला उत्पादन',
                    'ja' => '90年代アート生成',
                    'ko' => '90\'s 아트 생성',
                    'pt' => 'Geração de Arte dos Anos 90',
                    'th' => 'สร้างงานศิลปะยุค 90',
                    'tl' => 'Paggawa ng Sining ng Dekada 90',
                    'zh' => '90年代艺术生成',
                ]),

                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a description in English for an AI image generator. The description should be detailed and use commas to enhance the imagery. Follow these guidelines:
            
            - **Length**: Aim for one to two sentences.
            - **Detail**: Include specific details that capture the essence of the keyword.
            - **Style**: Use descriptive language, incorporating adjectives and sensory details.
            - **Structure**: Use commas to separate clauses, adding depth to the description.
            - **Examples**:
              - *A serene forest clearing bathed in golden sunlight, tall trees with emerald leaves swaying gently, a crystal-clear stream flowing through the moss-covered rocks.*
              - *An old wooden sailboat navigating through misty waters at dawn, the pale sun rising over the horizon, soft hues of pink and orange reflecting on the calm sea.*
              - *A bustling city street at night, neon signs illuminating the rain-soaked pavement, people with umbrellas hurrying by, reflections of lights creating a vibrant atmosphere.*
            - The output must be in English.
            
            Please provide the image description now in English.",
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images. Disregard any existing content generation language settings and strictly produce this content in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "glif/90s-anime-art",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "90s Art"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],            
            [
                'name' => $this->getLocalizedText([
                    'de' => '20er-Jahre Anime-Stil Bildgenerierung',
                    'en' => '20s Anime Style Image Generate',
                    'fr' => 'Génération d\'image style anime des années 20',
                    'hi' => '20 के दशक की एनीमे शैली छवि उत्पादन',
                    'ja' => '20年代アニメスタイル画像生成',
                    'ko' => '2000년대 애니메이션 스타일 이미지 생성',
                    'pt' => 'Geração de Imagem Estilo Anime dos Anos 20',
                    'th' => 'สร้างภาพสไตล์อนิเมะยุค 20',
                    'tl' => 'Paggawa ng Imahe sa Estilo ng Anime ng Dekada 20',
                    'zh' => '20年代动漫风格图像生成',
                ]),

                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a description in English for an AI image generator. The description should be detailed and use commas to enhance the imagery. Follow these guidelines:
            
            - **Length**: Aim for one to two sentences.
            - **Detail**: Include specific details that capture the essence of the keyword.
            - **Style**: Use descriptive language, incorporating adjectives and sensory details.
            - **Structure**: Use commas to separate clauses, adding depth to the description.
            - **Examples**:
              - *A cyberpunk hacker with neon blue hair and cybernetic implants on their face, seated in front of multiple holographic screens in a dark, high-tech room. They wear a sleek, black leather jacket with glowing circuit patterns, and their fingers are covered in cybernetic gloves that interface directly with the floating data. The background is a chaotic mix of digital code, flashing lights, and wires, emphasizing the high-tech, futuristic setting.*
              - *A bearded inventor with wild, curly hair, standing confidently in his steampunk workshop. He wears brass goggles on his forehead, a leather apron over a white shirt with rolled-up sleeves, and fingerless gloves. His hands are slightly stained with grease, and he holds a small, intricate mechanical device. The background is cluttered with gears, blueprints, and tools, illuminated by the warm glow of oil lamps, giving the scene a creative, industrious atmosphere.*
              - *A cheerful corgi, wearing a vibrant party hat with colorful streamers, and a pair of oversized, reflective sunglasses. The corgi's tongue is playfully sticking out, and its ears are perked up with excitement. The background is filled with festive decorations, including balloons and confetti in a variety of bright colors. The overall scene captures a lively and joyful celebration, with the corgi at the center, radiating energy and fun.*
            - The output must be in English.
            
            Please provide the image description now in English.",
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images. Disregard any existing content generation language settings and strictly produce this content in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "glif/90s-anime-art",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "20s Art"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],           
            [
                'name' => $this->getLocalizedText([
                    'de' => 'Anime-Skizzenbild-Generierung',
                    'en' => 'Anime Sketch Image Generate',
                    'fr' => 'Génération d\'image esquisse d\'anime',
                    'hi' => 'एनीमे स्केच छवि उत्पादन',
                    'ja' => 'アニメスケッチ画像生成',
                    'ko' => '애니메이션 스케치 이미지 생성',
                    'pt' => 'Geração de Imagem de Esboço de Anime',
                    'th' => 'สร้างภาพสเก็ตช์อนิเมะ',
                    'tl' => 'Paggawa ng Imahe ng Sketch ng Anime',
                    'zh' => '动漫草图图像生成',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a concise and vivid description in English for an AI image generator in the style of a sketch illustration style. The description should be formatted similarly to the following examples:
            
            - daydreaming student with lavender hair in pigtails, surrounded by floating books and math equations, sketch illustration style
            - aspiring musician with vibrant blue hair, wearing headphones and a vintage band t-shirt, sketch illustration style
            - A young botanist with flowing green hair, wearing oversized glasses and a lab coat covered in plant doodles, sketch illustration style
            - A whimsical artist with rainbow-colored hair, paint-splattered overalls, and a paintbrush tucked behind their ear, sketch illustration style
            - A curious astronomer with wild, curly hair, peering through a telescope, wearing a star-patterned scarf, black and white, sketch illustration style
            
            Guidelines:
            
            - Keep the description concise, ideally in one sentence.
            - Focus on vivid imagery and key details that capture the essence of the keyword.
            - Include the phrase 'sketch illustration style' at the end to specify the desired aesthetic.
            - Use descriptive language to set the scene, mood, and style.
            - Ensure the description is clear and helps the AI generate an image that accurately represents the keyword.
            - The output must be in English.
            
            Please provide the image description now in English.",
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images. Disregard any existing content generation language settings and strictly produce this content in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "alvdansen/enna-sketch-style",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "sketch anime"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],     
            [
                'name' => $this->getLocalizedText([
                    'de' => '[FLUX] Vektorgrafik-Generator',
                    'en' => '[FLUX] Vector Image Generator',
                    'fr' => '[FLUX] Générateur d\'images vectorielles',
                    'hi' => '[FLUX] वेक्टर इमेज जनरेटर',
                    'ja' => '[FLUX] ベクター画像生成ツール',
                    'ko' => '[FLUX] 벡터 이미지 생성기',
                    'pt' => '[FLUX] Gerador de Imagens Vetoriais',
                    'th' => '[FLUX] ตัวสร้างภาพเวกเตอร์',
                    'tl' => '[FLUX] Tagabuo ng Vector Image',
                    'zh' => '[FLUX] 矢量图生成器',
                  ]),
                  'description' => $this->getLocalizedText([
                      'de' => 'Schlüsselwort > Prompt > Bild generieren',
                      'en' => 'keyword > prompt > image generate',
                      'fr' => 'mot-clé > prompt > génération d\'image',
                      'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                      'ja' => 'キーワード > プロンプト > 画像生成',
                      'ko' => '키워드 > 프롬프트 > 이미지 생성',
                      'pt' => 'palavra-chave > prompt > geração de imagem',
                      'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                      'tl' => 'keyword > prompt > paggawa ng imahe',
                      'zh' => '关键词 > 提示 > 图像生成',
                  ]),
                  'steps' => json_encode([
                      [
                          "type" => "input",
                          "step_number" => 1,
                          "uuid" => $generateUuid(),
                          "input_fields" => [
                              [
                                  "label" => "keyword",
                                  "description" => $this->getLocalizedText([
                                      'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                      'en' => 'Enter the keyword or phrase for the image',
                                      'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                      'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                      'ja' => '画像のキーワードやフレーズを入力してください',
                                      'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                      'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                      'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                      'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                      'zh' => '输入图像的关键词或短语',
                                  ]),
                                  "type" => "text",
                                  "options" => null,
                                  "file_type" => null
                              ]
                          ]
                      ],
                      [
                          "type" => "generate_text",
                          "step_number" => 2,
                          "uuid" => $generateUuid(),
                          "prompt" => "Using the keyword '{{step1.input.keyword}}', create a concise description for a vector image in the following format:
              
              v3ct0r style, simple vector art, isolated on white bg, [description],
              
              Guidelines:
              
                - Start with the fixed phrase \"v3ct0r style, simple vector art, isolated on white bg,\"
                - Follow with a brief description that includes:
                    - The main subject or character related to the keyword
                    - Any relevant actions or poses
                    - Additional details or elements that enhance the concept
                - The entire description should be on a single line
                - End the description with a comma
                - Keep the overall description concise and specific
              
              Examples:
              
              - `v3ct0r style, simple vector art, isolated on white bg, construction worker wearing a hard hat and holding a small clipboard, character asset, clip art - The text on the clipboard says \"FLUX TEST\"`
              - `v3ct0r style, simple vector art, isolated on white bg, salesman giving a thumbs up in front of a car, character asset, clip art`
              - `v3ct0r style, simple vector art, isolated on white bg, ugly witch standing next to a bubbling cauldron stirring the pot, character asset, clip art`
              - `v3ct0r style, simple flat vector art, isolated on white bg, cat`
              - `v3ct0r style, simple flat vector art, isolated on white bg, rocket`
              - `v3ct0r style, simple flat vector art, isolated on white bg, clown`
              
              Please generate the logo description now using the provided keyword and following this format.",
                          "background_information" => "You are an expert logo designer and prompt engineer. Your task is to create concise and effective prompts for AI image generation, formatted precisely to guide the AI in creating logos that accurately represent the given keyword. Disregard any existing content generation language settings and strictly produce this content in English.",
                          "ai_provider" => "openai",
                          "ai_model" => "gpt-4o-2024-05-13",
                          "temperature" => 0.7,
                          "reference_file" => null
                      ],
                      [
                          "type" => "generate_image",
                          "step_number" => 3,
                          "uuid" => $generateUuid(),
                          "prompt" => "{{step2.output}}",
                          "background_information" => "",
                          "ai_provider" => "huggingface",
                          "ai_model" => "renderartist/simplevectorflux",
                          "temperature" => 0.7,
                          "reference_file" => null
                      ]
                  ]),
                  'tags' => json_encode($this->getLocalizedTags(["image", "flux-vector"])),
                  'created_at' => now(),
                  'updated_at' => now(),
              ],
            [
               'name' => $this->getLocalizedText([
                    'de' => '[FLUX] Pixel-Art-Generierung',
                    'en' => '[FLUX] Pixel-Art Generate',
                    'fr' => '[FLUX] Génération de Pixel-Art',
                    'hi' => '[FLUX] पिक्सेल-आर्ट उत्पादन',
                    'ja' => '[FLUX] ピクセルアート生成',
                    'ko' => '[FLUX] 픽셀 아트 생성기',
                    'pt' => '[FLUX] Geração de Pixel-Art',
                    'th' => '[FLUX] สร้าง Pixel-Art',
                    'tl' => '[FLUX] Paggawa ng Pixel-Art',
                    'zh' => '[FLUX] 像素艺术生成',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a description in English for an AI image generator. The description should be detailed and use commas to enhance the imagery. Follow these guidelines:
            
            - **Length**: Aim for one to two sentences.
            - **Detail**: Include specific details that capture the essence of the keyword.
            - **Style**: Use descriptive language, incorporating adjectives and sensory details.
            - **Structure**: Use commas to separate clauses, adding depth to the description.
            - **Examples**:
              - *A serene forest clearing bathed in golden sunlight, tall trees with emerald leaves swaying gently, a crystal-clear stream flowing through the moss-covered rocks.*
              - *An old wooden sailboat navigating through misty waters at dawn, the pale sun rising over the horizon, soft hues of pink and orange reflecting on the calm sea.*
              - *A bustling city street at night, neon signs illuminating the rain-soaked pavement, people with umbrellas hurrying by, reflections of lights creating a vibrant atmosphere.*
            - The output must be in English.
            
            Please provide the image description now in English.",
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images. Disregard any existing content generation language settings and strictly produce this content in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "nerijs/pixel-art-xl",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "flux-pixelart"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],     
            [
            'name' => $this->getLocalizedText([
                    'de' => '[FLUX] Pixel-Art-Generierung im Poketmon-Charakterstil',
                    'en' => '[FLUX] Poketmon Style Character Pixel-Art Generate',
                    'fr' => '[FLUX] Génération de Pixel-Art de personnage style Poketmon',
                    'hi' => '[FLUX] पोकेटमोन शैली चरित्र पिक्सेल-आर्ट उत्पादन',
                    'ja' => '[FLUX] ポケモンスタイルキャラクターピクセルアート生成',
                    'ko' => '[FLUX] 포켓몬스터 캐릭터 스타일 픽셀아트 생성기',
                    'pt' => '[FLUX] Geração de Pixel-Art de Personagem Estilo Poketmon',
                    'th' => '[FLUX] สร้างภาพพิกเซลตัวละครสไตล์โปเกมอน',
                    'tl' => '[FLUX] Paggawa ng Pixel-Art ng Karakter sa Estilo ng Poketmon',
                    'zh' => '[FLUX] 宝可梦风格角色像素艺术生成',
                ]),

                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a concise prompt in English for the pokemon-trainer-sprites-pixelart-flux AI image generator. Follow this format:

                            'a pixel image of [keyword description]'

                            Examples:
                            - a pixel image of iron man on red armor on white background
                            - a pixel image of assasin's creed, white hood
                            - a pixel image of belle from beauty and the beast
                            - a pixel image of the flash
                            - a pixel image of superman
                            - a pixel image of ariel from the little mermaid

                            Guidelines:
                            - Always start with 'a pixel image of'.
                            - Keep the description after 'of' brief and focused on the key elements.
                            - Do not include any additional style descriptors beyond 'pixel image'.
                            - Ensure the description accurately represents the keyword.
                            - Avoid complex backgrounds or multiple characters unless specifically requested in the keyword.

                            Please provide the image prompt now in English, formatted for pokemon-trainer-sprites-pixelart-flux.",
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images. Disregard any existing content generation language settings and strictly produce this content in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "sWizad/pokemon-trainer-sprites-pixelart-flux",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "flux-pixel", "pokemon"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],     
            [
                'name' => $this->getLocalizedText([
                    'de' => '[FLUX] Linienarbeit-Generierung',
                    'en' => '[FLUX] LineWork Generate',
                    'fr' => '[FLUX] Génération de LineWork',
                    'hi' => '[FLUX] रेखा-कार्य उत्पादन',
                    'ja' => '[FLUX] 線画生成',
                    'ko' => '[FLUX] 라인워크 생성기',
                    'pt' => '[FLUX] Geração de LineWork',
                    'th' => '[FLUX] สร้างภาพ LineWork',
                    'tl' => '[FLUX] Paggawa ng LineWork',
                    'zh' => '[FLUX] 线条艺术生成',
                ]),

                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a description in English for an AI image generator. The description should be detailed and use commas to enhance the imagery. Follow these guidelines:
            
            - **Length**: Aim for one to two sentences.
            - **Detail**: Include specific details that capture the essence of the keyword.
            - **Style**: Use descriptive language, incorporating adjectives and sensory details.
            - **Structure**: Use commas to separate clauses, adding depth to the description.
            - **Examples**:
              - *A serene forest clearing bathed in golden sunlight, tall trees with emerald leaves swaying gently, a crystal-clear stream flowing through the moss-covered rocks.*
              - *An old wooden sailboat navigating through misty waters at dawn, the pale sun rising over the horizon, soft hues of pink and orange reflecting on the calm sea.*
              - *A bustling city street at night, neon signs illuminating the rain-soaked pavement, people with umbrellas hurrying by, reflections of lights creating a vibrant atmosphere.*
            - The output must be in English.
            
            Please provide the image description now in English.",
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images. Disregard any existing content generation language settings and strictly produce this content in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "alvdansen/haunted_linework_flux",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "flux-lineword"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],    
            [
              'name' => $this->getLocalizedText([
                    'de' => '[FLUX] Logo-Generierung',
                    'en' => '[FLUX] Logo Generate',
                    'fr' => '[FLUX] Génération de logo',
                    'hi' => '[FLUX] लोगो उत्पादन',
                    'ja' => '[FLUX] ロゴ生成',
                    'ko' => '[FLUX] 로고 생성기',
                    'pt' => '[FLUX] Geração de Logotipo',
                    'th' => '[FLUX] สร้างโลโก้',
                    'tl' => '[FLUX] Paggawa ng Logo',
                    'zh' => '[FLUX] 徽标生成',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => $this->getLocalizedText([
                                    'de' => 'Geben Sie das Schlüsselwort oder den Ausdruck für das Bild ein',
                                    'en' => 'Enter the keyword or phrase for the image',
                                    'fr' => 'Entrez le mot-clé ou la phrase pour l\'image',
                                    'hi' => 'छवि के लिए कीवर्ड या वाक्यांश दर्ज करें',
                                    'ja' => '画像のキーワードやフレーズを入力してください',
                                    'ko' => '이미지에 대한 키워드 또는 문구를 입력하세요',
                                    'pt' => 'Digite a palavra-chave ou frase para a imagem',
                                    'th' => 'ป้อนคำสำคัญหรือวลีสำหรับภาพ',
                                    'tl' => 'Ilagay ang keyword o parirala para sa imahe',
                                    'zh' => '输入图像的关键词或短语',
                                ]),
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_text",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "Using the keyword '{{step1.input.keyword}}', create a concise description for a logo design in the following format:
            
            `logo, [Style], [Subject], [Additional Details],`
            
            Guidelines:
            
            - **Style**: Choose an appropriate style such as Minimalist, Abstract, Vintage, Modern, etc.
            - **Subject**: Describe the main focus or element of the logo that represents the keyword.
            - **Additional Details**: Include any extra elements or concepts that enhance the logo's representation of the keyword.
            
            The description should be brief and formatted exactly as shown, with commas separating each element and ending with a comma.
            
            Examples:
            
            - `logo, Minimalist, A man stands in front of a door, his shadow forming the letter \"A\",`
            - `logo, Minimalist, A pair of chopsticks and a bowl of rice forming the word \"Lee\",`
            - `web logo, Minimalist, Leaf and cat forming a subtle design,`
            
            Please generate the logo description now.",
                        "background_information" => "You are an expert logo designer and prompt engineer. Your task is to create concise and effective prompts for AI image generation, formatted precisely to guide the AI in creating logos that accurately represent the given keyword. Disregard any existing content generation language settings and strictly produce this content in English.",
                        "ai_provider" => "openai",
                        "ai_model" => "gpt-4o-2024-05-13",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 3,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step2.output}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "Shakker-Labs/FLUX.1-dev-LoRA-Logo-Design",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "flux-logo"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $this->getLocalizedText([
                    'de' => '[FLUX] NSFW-Generierung (+18)',
                    'en' => '[FLUX] NSFW Generate (+18)',
                    'fr' => '[FLUX] Génération NSFW (+18)',
                    'hi' => '[FLUX] एनएसएफडब्ल्यू उत्पादन (+18)',
                    'ja' => '[FLUX] NSFW生成 (+18)',
                    'ko' => '[FLUX] NSFW 생성(+18)',
                    'pt' => '[FLUX] Geração NSFW (+18)',
                    'th' => '[FLUX] สร้าง NSFW (+18)',
                    'tl' => '[FLUX] Paggawa ng NSFW (+18)',
                    'zh' => '[FLUX] NSFW生成 (+18)',
                ]),
                'description' => $this->getLocalizedText([
                    'de' => 'Schlüsselwort > Prompt > Bild generieren',
                    'en' => 'keyword > prompt > image generate',
                    'fr' => 'mot-clé > prompt > génération d\'image',
                    'hi' => 'कीवर्ड > प्रॉम्प्ट > छवि उत्पादन',
                    'ja' => 'キーワード > プロンプト > 画像生成',
                    'ko' => '키워드 > 프롬프트 > 이미지 생성',
                    'pt' => 'palavra-chave > prompt > geração de imagem',
                    'th' => 'คำสำคัญ > prompt > สร้างภาพ',
                    'tl' => 'keyword > prompt > paggawa ng imahe',
                    'zh' => '关键词 > 提示 > 图像生成',
                ]),
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter keywords into English to generate enhanced images. Also, trigger words use the words nsfw, naked, pron, kissing, erotic, nude, sensitive, adult content, and explicit to create nsfw images.",
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_image",
                        "step_number" => 2,
                        "uuid" => $generateUuid(),
                        "prompt" => "{{step1.input.keyword}}",
                        "background_information" => "",
                        "ai_provider" => "huggingface",
                        "ai_model" => "enhanceaiteam/Flux-Uncensored-V2",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode($this->getLocalizedTags(["image", "flux-nswf"])),
                'created_at' => now(),
                'updated_at' => now(),
            ],   
        ];

        foreach ($logicsData as $index => $data) {
            $data['id'] = $maxId + $index + 1;
            DB::table('logics')->insert($data);
        }
    }

    private function getLocalizedText($texts) {
        $locale = app()->getLocale();
        return $texts[$locale] ?? $texts['en'];  // Fallback to English if translation not available
    }

    private function getLocalizedTags($tagKeys) {
        $locale = app()->getLocale();
        return array_map(function($key) use ($locale) {
            // 태그가 정의되어 있지 않으면 원래 키를 그대로 반환
            if (!isset($this->tags[$key])) {
                return $key;
            }
            // 태그가 정의되어 있으면 현재 언어에 맞는 값을 반환 (없으면 영어로 폴백)
            return $this->tags[$key][$locale] ?? $this->tags[$key]['en'] ?? $key;
        }, $tagKeys);
    }
};