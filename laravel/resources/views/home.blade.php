<x-guest-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        CRM — тестовое задание
    </h2>

    <div class="max-w-4xl mx-auto space-y-4">

        <div class="bg-white shadow-sm sm:rounded-lg py-4">
            <div class="text-gray-600">Быстрые ссылки для проверки функционала</div>

            <div class="mt-4 flex flex-col gap-3">
                <a href="{{ url('/widget') }}"
                   class="inline-flex justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                    Виджет
                </a>

                <a href="{{ url('/docs') }}"
                   class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                    Swagger
                </a>

                <a href="{{ route('admin.tickets.index') }}"
                   class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                    Админ-панель
                </a>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg py-4">
            <div class="font-semibold">Доступы</div>
            <div class="mt-2 text-sm text-gray-700">
                manager@example.com / password
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg py-4">
            <div class="font-semibold">Пример встраивания виджета</div>
            @php
                $iframeSnippet = <<<'HTML'
                <iframe
                    src="http://localhost:8080/widget"
                    style="width: 100%; height: 900px; border: 0;"
                >
                </iframe>
                HTML;
            @endphp
            <pre class="mt-3 rounded-md bg-gray-50 p-2 text-xs whitespace-pre-wrap"><code>{{ $iframeSnippet }}</code></pre>
            <div class="mt-2 text-sm text-gray-700">
                Вставьте этот код на любую страницу
            </div>
        </div>
    </div>
</x-guest-layout>
