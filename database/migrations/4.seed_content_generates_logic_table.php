<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
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
};