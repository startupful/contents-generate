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
            //이벤트
            [
                'name' => 'Startupful Review Blog Posting',
                'description' => 'keyword > titles, selected title, introduction, main body, conclusion, and content integration for professional news articles',
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
                                "description" => "Please choose 1 service that you found interesting",
                                "type" => "select",
                                "options" => "[text] blog contents generate, [text] sns contents generate, [text] docs generate, [text] webpage summary, [text] docs summary, [image] image generate, [UI/UX] text-to-code, [UI/UX] image-to-code, [image] anime image generate, [image] NSFW(18+) image generate, [audio] text-to-speech, [avatar-chat] Role Playing, [avatar-chat] language tuter, [avatar-chat] a variety of consultants",
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
                'tags' => json_encode(["startupful", "event"]),
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'name' => 'Startupful Review Blog Posting',
                'description' => 'keyword > titles, selected title, introduction, main body, conclusion, and content integration for professional news articles',
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
                                "description" => "Please choose 1 service that you found interesting",
                                "type" => "select",
                                "options" => "[text] blog contents generate, [text] sns contents generate, [text] docs generate, [text] webpage summary, [text] docs summary, [image] image generate, [UI/UX] text-to-code, [UI/UX] image-to-code, [image] anime image generate, [image] NSFW(18+) image generate, [audio] text-to-speech, [avatar-chat] Role Playing, [avatar-chat] language tuter, [avatar-chat] a variety of consultants",
                                "file_type" => null
                            ],
                            [
                                "label" => "SNS",
                                "description" => "Please select the SNS you want to upload",
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
                'tags' => json_encode(["startupful", "event"]),
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            //블로그
            [
                'name' => 'Blog Post Generate',
                'description' => 'keyword > title, outline, sectioned content generation, and integration',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the main keyword for the blog post",
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
                'tags' => json_encode(["blog", "seo"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Product Review Generate',
                'description' => 'keyword > title, outline, sectioned review content generation, and integration',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "product_keyword",
                                "description" => "Enter the main keyword or product name for the review",
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
                'tags' => json_encode(["blog", "review", "product"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'News Article Generate',
                'description' => 'keyword > titles, selected title, introduction, main body, conclusion, and content integration for professional news articles',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "article_topic",
                                "description" => "Enter the main topic or keyword for the article",
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
                'tags' => json_encode(["article", "news", "professional"]),
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'name' => 'SNS Caption Generate',
                'description' => 'Generate engaging captions for various social media platforms based on a given topic',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "topic_keyword",
                                "description" => "Enter the main topic or keyword for the caption",
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "sns",
                                "description" => "Please select the SNS you want to upload to",
                                "type" => "radio",
                                "options" => "LinkedIn, Facebook, Instagram, Threads, Twitter(X), Tumblr, TikTok",
                                "file_type" => null
                            ],
                            [
                                "label" => "tone",
                                "description" => "Select the tone for your content",
                                "type" => "radio",
                                "options" => "General, Formal, Friendly, Critical, Humorous, Inspirational",
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
                'tags' => json_encode(["sns", "social media", "caption", "content creation"]),
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'name' => 'Email Response Generator',
                'description' => 'Email context input > Strategic analysis > Tailored email response generation',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "sender",
                                "description" => "Name of the person or service sending the reply",
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "recipient",
                                "description" => "Name of the person or service receiving the reply",
                                "type" => "text",
                                "options" => null,
                                "file_type" => null
                            ],
                            [
                                "label" => "email_content",
                                "description" => "Content of the original email to be replied to",
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
                'tags' => json_encode(["email", "customer service"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 요약
            [
                'name' => 'PDF Document Summarizer',
                'description' => 'PDF document input > Comprehensive summary generation with key points and explanations',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "file",
                                "description" => "Upload the PDF document to be summarized",
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
                'tags' => json_encode(["PDF", "document analysis", "summary"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PPT Document Summarizer',
                'description' => 'PPT document input > Comprehensive summary generation with key points and explanations',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "file",
                                "description" => "Upload the PPT document to be summarized",
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
                'tags' => json_encode(["PPT", "document analysis", "summary"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Audio Summarizer',
                'description' => 'Audio file input > Comprehensive summary generation with key points and explanations',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "file",
                                "description" => "Upload the audio file to be summarized",
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
                'tags' => json_encode(["audio", "summary"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Audio Meeting Minutes Generator',
                'description' => 'Audio file input > Comprehensive meeting minutes generation with key points, action items, and conclusions',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "file",
                                "description" => "Upload the audio file of the meeting to be analyzed",
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
                'tags' => json_encode(["audio", "meeting minutes", "summary"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Web Page Summarizer',
                'description' => 'URL input > Web page scraping > Comprehensive summary generation with key points and analysis',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "url",
                                "description" => "Enter the URL of the web page to be summarized",
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
                'tags' => json_encode(["webpage", "summary"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Web Page Content Regenerator',
                'description' => 'URL input > Web page scraping > High-quality content regeneration based on scraped content',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "url",
                                "description" => "Enter the URL of the web page to be used as a basis for content regeneration",
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
                'tags' => json_encode(["web page", "content regeneration"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // UI/UX
            [
                'name' => '[image-to-code] UI/UX Code Generator',
                'description' => 'Image input > Generate UI/UX code using Tailwind CSS based on the uploaded image',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "file",
                                "description" => "Upload the image of the UI/UX design",
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
                'tags' => json_encode(["UI/UX", "code generation"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '[text-to-code] UI/UX Code Generator',
                'description' => 'Text description input > Generate UI/UX HTML code using Tailwind CSS based on the provided description',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "UI/UX Description",
                                "description" => "Provide a detailed description of the UI/UX design you want to create",
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
                'tags' => json_encode(["UI/UX", "code generation"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 엑셀
            [
                'name' => 'Development Requirements Spec Excel Generator',
                'description' => 'Generate a detailed Excel-format Development Requirements Specification based on project type and required features',
                'steps' => json_encode([
                [
                "type" => "input",
                "step_number" => 1,
                "uuid" => "66e39491a30ee",
                "input_fields" => [
                [
                "label" => "Project Type",
                "description" => "Select the type of project you're developing",
                "type" => "radio",
                "options" => "Web Application,Mobile App,Responsive Web App,Game,Community Platform,E-Commerce Platform,Enterprise Software,IoT Application,AI/ML Solution,Blockchain Application",
                "placeholder" => "Choose the most appropriate project type..."
                ],
                [
                "label" => "Key Features",
                "description" => "Select the main features required for your project",
                "type" => "multiselect",
                "options" => "User Authentication,Social Media Integration,Real-time Messaging,Payment Gateway,Content Management System,Search Functionality,User Profiles,Analytics Dashboard,Notification System,Geolocation Services,File Upload/Download,Multi-language Support,API Integration,Admin Panel,User Reviews/Ratings,Subscription Management,Inventory Management,Booking/Reservation System,Social Sharing,Video Streaming",
                "placeholder" => "Select all applicable key features for your project..."
                ]
                ]
                ],
                [
                "type" => "generate_excel",
                "step_number" => 2,
                "uuid" => "66e39491a30f1",
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
                'tags' => json_encode(["Requirements Specification", "Excel Generation"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Project Plan Excel Generator',
                'description' => 'Generate a comprehensive Excel-format Project Plan based on project type, scale, and key objectives',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => "66e39491a30ee",
                        "input_fields" => [
                            [
                                "label" => "Project Type",
                                "description" => "Select the type of project you're planning",
                                "type" => "radio",
                                "options" => "Software Development,Marketing Campaign,Product Launch,Business Expansion,Research Project,Event Planning,Infrastructure Upgrade,Organizational Restructuring,Merger and Acquisition,Compliance Implementation",
                                "placeholder" => "Choose the most appropriate project type..."
                            ],
                            [
                                "label" => "Project Scale",
                                "description" => "Select the scale of your project",
                                "type" => "radio",
                                "options" => "Small (1-3 months),Medium (3-6 months),Large (6-12 months),Enterprise (1+ years)",
                                "placeholder" => "Select the project scale..."
                            ],
                            [
                                "label" => "Key Objectives",
                                "description" => "Select the main objectives of your project",
                                "type" => "multiselect",
                                "options" => "Increase Revenue,Reduce Costs,Improve Efficiency,Enhance Customer Satisfaction,Expand Market Share,Develop New Product/Service,Improve Quality,Increase Productivity,Ensure Regulatory Compliance,Enhance Brand Awareness,Implement New Technology,Improve Employee Satisfaction,Reduce Environmental Impact,Increase Innovation,Improve Safety Standards",
                                "placeholder" => "Select one or more key objectives for your project..."
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_excel",
                        "step_number" => 2,
                        "uuid" => "66e39491a30f1",
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
                'tags' => json_encode(["Project Planning", "Excel Generation"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Marketing Strategy Excel Generator',
                'description' => 'Generate a comprehensive Excel-format Marketing Strategy Document based on campaign type, target audience, budget, and key objectives',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => "66e39491a30ef",
                        "input_fields" => [
                            [
                                "label" => "Campaign Type",
                                "description" => "Select the type of marketing campaign",
                                "type" => "radio",
                                "options" => "Product Launch,Brand Awareness,Lead Generation,Customer Retention,Sales Promotion,Content Marketing,Social Media Campaign,Email Marketing,SEO/SEM Campaign,Influencer Marketing",
                                "placeholder" => "Choose the most appropriate campaign type..."
                            ],
                            [
                                "label" => "Target Audience",
                                "description" => "Select the primary target audience for your campaign",
                                "type" => "radio",
                                "options" => "B2C General Consumers,B2B Businesses,Millennials,Gen Z,Baby Boomers,Small Business Owners,C-Level Executives,Tech Enthusiasts,Parents,Students",
                                "placeholder" => "Select your primary target audience..."
                            ],
                            [
                                "label" => "Budget Range",
                                "description" => "Select the budget range for your marketing campaign",
                                "type" => "radio",
                                "options" => "Low Budget ($1,000 - $10,000),Medium Budget ($10,000 - $50,000),High Budget ($50,000 - $200,000),Enterprise Budget ($200,000+)",
                                "placeholder" => "Select your budget range..."
                            ],
                            [
                                "label" => "Key Objectives",
                                "description" => "Select the main objectives of your marketing campaign",
                                "type" => "multiselect",
                                "options" => "Increase Brand Awareness,Generate Leads,Boost Sales,Improve Customer Engagement,Enhance Online Presence,Increase Website Traffic,Improve Conversion Rates,Build Customer Loyalty,Expand Market Share,Introduce New Product/Service",
                                "placeholder" => "Select one or more key objectives for your campaign..."
                            ]
                        ]
                    ],
                    [
                        "type" => "generate_excel",
                        "step_number" => 2,
                        "uuid" => "66e39491a30f2",
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
                'tags' => json_encode(["Marketing Strategy", "Excel Generation"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 오디오
            // Alloy Voice Generator
            [
                'name' => '[TTS] Alloy - Professional Narrator',
                'description' => 'Generate a professional, neutral voice-over using Alloy.',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => "Enter your script for the professional voice-over",
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
                'tags' => json_encode(["audio", "speech", "professional", "neutral"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Echo Voice Generator
            [
                'name' => '[TTS] Echo - Gentle Storyteller',
                'description' => 'Create a soft, soothing narration using Echo.',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => "Enter your script for the gentle storytelling voice",
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
                'tags' => json_encode(["audio", "speech", "gentle", "storytelling"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fable Voice Generator
            [
                'name' => '[TTS] Fable - Magical Fairytale Narrator',
                'description' => 'Bring fairytales to life with the enchanting Fable voice.',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => "Enter your fairytale or magical story script",
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
                'tags' => json_encode(["audio", "speech", "fairytale", "magical"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Onyx Voice Generator
            [
                'name' => '[TTS] Onyx - Authoritative Presenter',
                'description' => 'Generate a powerful, commanding voice using Onyx.',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => "Enter your script for the authoritative presentation",
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
                'tags' => json_encode(["audio", "speech", "authoritative", "powerful"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Nova Voice Generator
            [
                'name' => '[TTS] Nova - Energetic Announcer',
                'description' => 'Create vibrant, enthusiastic audio content with Nova.',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => "Enter your script for the energetic announcement",
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
                'tags' => json_encode(["audio", "speech", "energetic", "announcement"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Shimmer Voice Generator
            [
                'name' => '[TTS] Shimmer - Elegant Spokesperson',
                'description' => 'Generate sophisticated, refined audio with Shimmer.',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "script",
                                "description" => "Enter your script for the elegant spokesperson",
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
                'tags' => json_encode(["audio", "speech", "elegant", "sophisticated"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 사업
            [
                'name' => 'SWOT Analysis Generator',
                'description' => 'Service description input > SWOT analysis generation',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "service_description",
                                "description" => "Provide a brief description of the service or business",
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
                'tags' => json_encode(["SWOT", "business analysis", "strategic planning"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Business Model Canvas Generator',
                'description' => 'Business idea input > Business Model Canvas generation',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "business_idea",
                                "description" => "Provide a brief description of your business idea or concept",
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
                'tags' => json_encode(["business", "business model canvas"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //기획
            [
                'name' => '1Pager Generation Process',
                'description' => 'Requirements input > 1Pager generation',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "key_points",
                                "description" => "List the key points or requirements (separated by commas)",
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
                'tags' => json_encode(["1pager", "business", "document"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '6Pager Generation Process',
                'description' => 'Requirements input > 6Pager generation',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "key_points",
                                "description" => "List the key points or requirements (separated by commas)",
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
                'tags' => json_encode(["6pager", "business", "document"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],                
            //요약
            [
                'name' => 'Webpage Summary',
                'description' => 'url > scrap > regenerative',
                'steps' => json_encode([
                    ["type" => "input", "step_number" => 1, "uuid" => $generateUuid(), "input_fields" => [["label" => "url", "description" => "blog contents keyword", "type" => "text", "options" => null, "file_type" => null]]],
                    ["type" => "scrap_webpage", "step_number" => 2, "uuid" => $generateUuid(), "url_source" => "user_input", "fixed_url" => null, "extraction_type" => "text_only"],
                    ["type" => "generate_text", "step_number" => 3, "uuid" => $generateUuid(), "prompt" => "Create a detailed and engaging blog post centered around the keyword '{{step1.input.url}}'. The blog post should be informative, well-structured, and optimized for SEO. Start with a compelling introduction that hooks the reader, then provide valuable insights, tips, or information related to '{{step1.input.keyword}}'. Use subheadings to organize the content into easily digestible sections, and include relevant examples, statistics, or case studies to support your points. Conclude with a strong call-to-action, encouraging readers to share the post or engage with your brand.", "background_information" => "As a blog expert, you have a deep understanding of crafting high-quality content that resonates with readers and ranks well in search engines. Using your expertise, create a detailed and engaging blog post centered around the keyword '{{step1.input.keyword}}'.", "ai_provider" => "openai", "ai_model" => "gpt-4o-2024-05-13o-2024-05-13", "temperature" => 0.7, "reference_file" => null]
                ]),
                'tags' => json_encode(["webpage", "summary"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //이미지
            [
                'name' => '[Dalle3] Image Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images.",
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
                'tags' => json_encode(["image", "della-3"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],            
            [
                'name' => '[StableDiffusion] Image Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images.",
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
                'tags' => json_encode(["image", "stabledifussion"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],            
            [
                'name' => '[FLUX] Image Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images.",
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
                'tags' => json_encode(["image", "flux1-dev"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],            
            [
                'name' => '[FLUX] Enhanced Image Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images.",
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
                'tags' => json_encode(["image", "flux-detail"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '[FLUX] Polaroid Photograph Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                'tags' => json_encode(["image", "flux-polaroid"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '[FLUX] flmft kodachrome style Image Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                'tags' => json_encode(["image", "flux-koda"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '90s Art Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images.",
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
                'tags' => json_encode(["image", "90s Art"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],            
            [
                'name' => '20s Anime Style Image Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images.",
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
                'tags' => json_encode(["image", "20s Art"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],           
            [
                'name' => 'Anime Sketch Image Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images.",
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
                'tags' => json_encode(["image", "sketch anime"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],     
            [
                'name' => '[FLUX] Pixel-Art Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images.",
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
                'tags' => json_encode(["image", "flux-pixelart"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],     
            [
                'name' => '[FLUX] Pixel-Art Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images.",
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
                'tags' => json_encode(["image", "flux-pixel", "pokemon"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],     
            [
                'name' => '[FLUX] LineWork Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the image",
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
                        "background_information" => "You are an expert at crafting rich and evocative prompts for AI image generation tools. Your descriptions are known for their detail, clarity, and ability to inspire visually stunning images.",
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
                'tags' => json_encode(["image", "flux-lineword"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],    
            [
                'name' => '[FLUX] Logo Generate',
                'description' => 'keyword > prompt > image generate',
                'steps' => json_encode([
                    [
                        "type" => "input",
                        "step_number" => 1,
                        "uuid" => $generateUuid(),
                        "input_fields" => [
                            [
                                "label" => "keyword",
                                "description" => "Enter the keyword or phrase for the logo",
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
                        "background_information" => "You are an expert logo designer and prompt engineer. Your task is to create concise and effective prompts for AI image generation, formatted precisely to guide the AI in creating logos that accurately represent the given keyword.",
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
                'tags' => json_encode(["image", "flux-logo"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '[FLUX] NSFW Generate(+18)',
                'description' => 'keyword > prompt > image generate',
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
                        "ai_model" => "enhanceaiteam/Flux-uncensored",
                        "temperature" => 0.7,
                        "reference_file" => null
                    ]
                ]),
                'tags' => json_encode(["image", "flux-nswf"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],   
        ];

        foreach ($logicsData as $index => $data) {
            $data['id'] = $maxId + $index + 1;
            DB::table('logics')->insert($data);
        }
    }
};