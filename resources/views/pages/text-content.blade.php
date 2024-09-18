@vite('resources/css/app.css')
<div class="prose prose-sm sm:prose lg:prose-lg xl:prose-xl w-full !max-w-full
                prose-h1:text-color-basic prose-h1:text-3xl
                prose-h2:text-color-basic prose-h2:text-2xl
                prose-h3:text-color-basic prose-h3:text-xl
                prose-h4:text-color-basic prose-h4:text-lg
                prose-strong:text-color-basic prose-code:text-indigo-500 prose-pre:bg-[#252731]
                prose-p:text-color-200 prose-p:text-base
                prose-li:text-color-sub prose-ul:text-color-sub prose-li:text-base prose-ul:text-base
                prose-th:text-color-sub prose-tr:text-color-basic prose-th:text-sm prose-tr:text-sm 
                prose-table:overflow-x-auto prose-table:w-full
                prose-a:text-indigo-500 hover:prose-a:text-indigo-600
                prose-img:rounded-xl">
    {!! Str::markdown($getRecord()->content) !!}
</div>