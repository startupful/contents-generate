<?php

namespace Startupful\ContentsGenerate\Resources\ContentsGenerateResource\Pages;

use Startupful\ContentsGenerate\Resources\ContentsGenerateResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Startupful\ContentsGenerate\Forms\Components\HierarchicalSelect;

class SettingContentsGenerate extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ContentsGenerateResource::class;

    protected static string $view = 'contents-generate::pages.setting-contents-generate';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
{
    return $form
        ->schema([
            Wizard::make([
                Wizard\Step::make('Category Select')
                    ->schema([
                        ToggleButtons::make('primary_category')
                            ->label('Primary Category')
                            ->options([
                                'content_creation' => '콘텐츠 생성',
                                'document_writing' => '문서 작성',
                                'comments_replies' => '댓글 및 답변',
                                'technical_docs' => '기술 문서 및 백서',
                            ])
                            ->icons([
                                'content_creation' => 'heroicon-o-document-text',
                                'document_writing' => 'heroicon-o-document',
                                'comments_replies' => 'heroicon-o-chat-bubble-left-right',
                                'technical_docs' => 'heroicon-o-document-chart-bar',
                            ])
                            ->required()
                            ->inline()
                            ->live(),
                        ToggleButtons::make('secondary_category')
                            ->label('Secondary Category')
                            ->options(function (Get $get) {
                                $primaryCategory = $get('primary_category');
                                switch ($primaryCategory) {
                                    case 'content_creation':
                                        return [
                                            'blog_content' => '블로그 콘텐츠',
                                            'social_media_content' => '소셜 미디어 콘텐츠',
                                            'marketing_content' => '마케팅 콘텐츠',
                                            'multimedia_content' => '멀티미디어 콘텐츠',
                                        ];
                                    case 'document_writing':
                                        return [
                                            'one_pager' => '원페이저',
                                            'resume_writing' => '이력서 작성',
                                            'service_planning' => '서비스 기획 문서',
                                            'business_plan' => '사업 계획서',
                                        ];
                                    case 'comments_replies':
                                        return [
                                            'simple_feedback' => '간단한 피드백',
                                            'qa_answers' => 'Q&A 형태의 답변',
                                            'community_discussion' => '커뮤니티 토론',
                                            'business_email' => '비즈니스 이메일 답변',
                                        ];
                                    case 'technical_docs':
                                        return [
                                            'guides' => '가이드',
                                            'onboarding' => '온보딩',
                                            'version_updates' => '버전 업데이트 내역',
                                        ];
                                    default:
                                        return [];
                                }
                            })
                            ->required()
                            ->inline()
                            ->visible(fn (Get $get) => $get('primary_category') !== null)
                            ->live(),
                        ToggleButtons::make('tertiary_category')
                            ->label('Tertiary Category')
                            ->options(function (Get $get) {
                                $primaryCategory = $get('primary_category');
                                $secondaryCategory = $get('secondary_category');
                                
                                if ($primaryCategory === 'content_creation') {
                                    switch ($secondaryCategory) {
                                        case 'blog_content':
                                            return [
                                                'informative-blog-post' => '정보성 블로그 포스트',
                                                'list-post' => '리스트형 포스트',
                                                'experience-sharing' => '경험 공유',
                                                'interview-content' => '인터뷰 콘텐츠',
                                            ];
                                        case 'social_media_content':
                                            return [
                                                'infographic' => '인포그래픽',
                                                'meme-content' => '밈(Meme) 콘텐츠',
                                                'quiz-poll-post' => '퀴즈/폴(Poll) 게시물',
                                                'live-streaming' => '라이브 스트리밍',
                                            ];
                                        // 다른 2차 카테고리에 대한 3차 옵션들...
                                    }
                                }
                                // 다른 1차 카테고리에 대한 3차 옵션들...
                                
                                return [];
                            })
                            ->required()
                            ->inline()
                            ->visible(fn (Get $get) => $get('secondary_category') !== null)
                            ->live(),
                    ]),
                Wizard\Step::make('Delivery')
                    ->schema([
                        TextInput::make('title')
                            ->label('Content Title')
                            ->required(),
                        Textarea::make('description')
                            ->label('Brief Description or Keywords')
                            ->required(),
                    ]),
                Wizard\Step::make('Billing')
                    ->schema([
                        // 결제 관련 필드...
                    ]),
            ]),
        ])
        ->statePath('data');
}


}