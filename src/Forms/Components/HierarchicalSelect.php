<?php 
namespace Startupful\ContentsGenerate\Forms\Components;

use Filament\Forms\Components\Select;
use Illuminate\Support\Collection;

class HierarchicalSelect extends Select
{
    protected static function getSelectOptions(): array
    {
        return [
            '콘텐츠 생성' => [
                '블로그 콘텐츠' => [
                    'informative-blog-post' => '정보성 블로그 포스트',
                    'list-post' => '리스트형 포스트',
                    'experience-sharing' => '경험 공유',
                    'interview-content' => '인터뷰 콘텐츠',
                ],
                '소셜 미디어 콘텐츠' => [
                    'infographic' => '인포그래픽',
                    'meme-content' => '밈(Meme) 콘텐츠',
                    'quiz-poll-post' => '퀴즈/폴(Poll) 게시물',
                    'live-streaming' => '라이브 스트리밍',
                ],
                '마케팅 콘텐츠' => [
                    'campaign-ad-copy' => '캠페인 광고 카피',
                    'product-description-page' => '제품 설명 페이지',
                    'seo-optimized-content' => 'SEO 최적화 콘텐츠',
                    'customer-case-study' => '고객 사례 연구',
                ],
                '멀티미디어 콘텐츠' => [
                    'video-tutorial' => '비디오 튜토리얼',
                    'podcast-episode' => '팟캐스트 에피소드',
                    'animation-content' => '애니메이션 콘텐츠',
                    'webtoon-digital-comic' => '웹툰/디지털 코믹',
                ],
            ],
            '문서 작성' => [
                '원페이저' => [
                    'product-service-overview' => '제품/서비스 개요',
                    'project-brief' => '프로젝트 브리프',
                ],
                '이력서 작성' => [
                    'general-resume' => '일반 이력서',
                    'creative-resume' => '크리에이티브 이력서',
                    'linkedin-profile-optimization' => '링크드인 프로필 최적화',
                ],
                '서비스 기획 문서' => [
                    'functional-specification' => '기능 명세서',
                    'user-story' => '사용자 스토리',
                ],
                '사업 계획서' => [
                    'market-analysis' => '시장 분석',
                    'financial-plan' => '재무 계획',
                    'operational-plan' => '운영 계획',
                ],
            ],
            '댓글 및 답변' => [
                '간단한 피드백' => [
                    'thank-you-comment' => '감사 댓글',
                    'simple-question-opinion' => '간단한 질문/의견',
                ],
                'Q&A 형태의 답변' => [
                    'expert-knowledge-answer' => '전문 지식 기반 답변',
                    'experience-sharing-answer' => '경험 공유형 답변',
                ],
                '커뮤니티 토론' => [
                    'discussion-topic-suggestion' => '토론 주제 제시',
                    'detailed-discussion-reply' => '디테일한 토론 답변',
                ],
                '비즈니스 이메일 답변' => [
                    'client-response' => '클라이언트 응답',
                    'partnership-proposal-response' => '파트너십 제안 응답',
                ],
            ],
            '기술 문서 및 백서' => [
                '가이드' => [
                    'user-manual' => '사용자 매뉴얼',
                    'developer-guide' => '개발자 가이드',
                ],
                '온보딩' => [
                    'initial-setup-guide' => '초기 설정 가이드',
                    'training-materials' => '교육 자료',
                ],
                '버전 업데이트 내역' => [
                    'release-notes' => '릴리즈 노트',
                    'version-comparison' => '버전 비교',
                ],
            ],
        ];
    }

    public function getOptionLabel(): ?string
    {
        $options = collect(static::getSelectOptions());

        return $options->map(function ($subcategories, $category) {
            if (is_array($subcategories)) {
                return collect($subcategories)->map(function ($items, $subcategory) use ($category) {
                    if (is_array($items)) {
                        return collect($items)->map(function ($label, $value) use ($category, $subcategory) {
                            return $value === $this->getState() ? "{$category} > {$subcategory} > {$label}" : null;
                        })->filter()->first();
                    }
                    return $items === $this->getState() ? "{$category} > {$subcategory}" : null;
                })->filter()->first();
            }
            return $subcategories === $this->getState() ? $category : null;
        })->filter()->first();
    }

    public function getOptionLabels(): array
    {
        return collect(static::getSelectOptions())->flatMap(function ($subcategories, $category) {
            if (is_array($subcategories)) {
                return collect($subcategories)->flatMap(function ($items, $subcategory) use ($category) {
                    if (is_array($items)) {
                        return collect($items)->mapWithKeys(function ($label, $value) use ($category, $subcategory) {
                            return [$value => "{$category} > {$subcategory} > {$label}"];
                        });
                    }
                    return [$subcategory => "{$category} > {$subcategory}"];
                });
            }
            return [$category => $category];
        })->toArray();
    }

    public function getOptions(): array
    {
        return $this->getOptionLabels();
    }
    
    public static function make(string $name): static
    {
        return parent::make($name)
            ->searchable()
            ->optionsLimit(50);
    }
}