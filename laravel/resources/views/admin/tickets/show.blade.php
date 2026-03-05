@php use App\Enums\TicketStatus; @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Заявка #{{ $ticket->id }}
            </h2>

            <a href="{{ route('admin.tickets.index', request()->query()) }}"
               class="text-sm text-gray-600 hover:text-gray-900">
                ← Назад к списку
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <div class="text-sm text-gray-600">Клиент</div>
                            <div class="text-gray-900 font-medium">{{ $ticket->customer->name }}</div>
                            <div class="text-gray-600 text-sm">{{ $ticket->customer->email }}</div>
                            <div class="text-gray-600 text-sm">{{ $ticket->customer->phone_e164 }}</div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-end gap-4">
                                <div class="space-y-1">
                                    <div class="text-sm text-gray-600">Статус</div>
                                    <div>
                                        <span
                                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                            {{ $ticket->status->label() }}
                                        </span>
                                    </div>
                                </div>

                                <x-auth-session-status id="flash-status" :status="session('status')" />
                                @if (session('status'))
                                    <script>
                                        setTimeout(() => {
                                            const el = document.getElementById('flash-status');
                                            if (el) el.remove();
                                        }, 3000);
                                    </script>
                                @endif
                            </div>

                            <form method="POST"
                                  action="{{ route('admin.tickets.status', $ticket) }}"
                                  class="flex items-end gap-3">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label class="block text-sm text-gray-600">Изменить статус</label>

                                    <select name="status"
                                            class="mt-1 rounded-md border-gray-300 text-sm">
                                        @foreach(TicketStatus::cases() as $s)
                                            <option value="{{ $s->value }}"
                                                @selected($ticket->status === $s)>
                                                {{ $s->label() }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>

                                <button type="submit"
                                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                    Сохранить
                                </button>
                            </form>
                            @error('status')
                                <div class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-3">
                            <div class="space-y-1">
                                <div class="text-sm text-gray-600">Создана</div>
                                <div class="text-gray-900">
                                    {{ $ticket->created_at?->isoFormat('L HH:mm') }}
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="text-sm text-gray-600">Дата ответа от менеджера</div>
                                <div class="text-gray-900">
                                    {{ $ticket->answered_at?->isoFormat('L HH:mm') ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <div class="text-sm text-gray-600">Тема</div>
                        <div class="text-gray-900">{{ $ticket->subject }}</div>
                    </div>

                    <div class="space-y-1">
                        <div class="text-sm text-gray-600">Текст</div>
                        <div class="text-gray-900 whitespace-pre-wrap">{{ $ticket->message }}</div>
                    </div>

                    @php
                        $attachments = $ticket->getMedia('attachments');
                    @endphp

                    <div class="space-y-3">
                        <div class="text-sm text-gray-600">Файлы</div>

                        @if($attachments->isEmpty())
                            <div class="text-gray-500">Нет вложений</div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($attachments as $media)
                                    @php
                                        $isImage = str_starts_with((string) $media->mime_type, 'image/');
                                    @endphp

                                    <a href="{{ $media->getUrl() }}" target="_blank"
                                       class="block rounded-lg border border-gray-200 hover:border-gray-300 overflow-hidden bg-white">
                                        @if($isImage)
                                            <img src="{{ $media->getUrl('thumb') }}"
                                                 alt="{{ $media->name }}"
                                                 class="h-20 w-20 rounded object-cover ring-1 ring-gray-200">
                                        @else
                                            <div
                                                class="h-40 flex items-center justify-center bg-gray-50 text-gray-500 text-sm">
                                                {{ strtoupper(pathinfo($media->file_name, PATHINFO_EXTENSION)) }}
                                            </div>
                                        @endif

                                        <div class="p-3">
                                            <div class="text-sm font-medium text-gray-900 break-all">
                                                {{ $media->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $media->mime_type }} · {{ number_format($media->size / 1024, 1) }} KB
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
