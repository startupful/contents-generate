<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private $tags = [
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
        // 추가 태그들...
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