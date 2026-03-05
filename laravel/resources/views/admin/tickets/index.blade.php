<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Заявки
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="mb-4">
                        <form method="GET" class="flex flex-wrap items-stretch gap-3">
                            <div class="flex flex-col justify-between gap-2">
                                <div>
                                    <label class="block text-sm text-gray-600">С даты</label>
                                    <input type="date" name="from" value="{{ $from }}" class="mt-1 rounded-md border-gray-300 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-600">По дату</label>
                                    <input type="date" name="to" value="{{ $to }}" class="mt-1 rounded-md border-gray-300 text-sm">
                                </div>
                            </div>

                            <div class="flex flex-col justify-between gap-2">
                                <div>
                                    <label class="block text-sm text-gray-600">Email</label>
                                    <input type="text" name="email" value="{{ $email }}" class="mt-1 rounded-md border-gray-300 text-sm" placeholder="user@example.com">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-600">Телефон</label>
                                    <input type="text" name="phone" value="{{ $phone }}" class="mt-1 rounded-md border-gray-300 text-sm" placeholder="+1555...">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600">Статус</label>
                                <select name="status[]" multiple class="mt-1 block w-36 h-28 rounded-md border-gray-300 text-sm overflow-y-auto">
                                    @foreach($statuses as $s)
                                        <option value="{{ $s->value }}" @selected(in_array($s->value, $selectedStatuses, true))>
                                            {{ $s->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <div class="flex items-center gap-2">
                                    <button type="submit"
                                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                        Применить
                                    </button>

                                    <a href="{{ route('admin.tickets.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                        Сбросить
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="text-left text-gray-600 border-b">
                                <th class="py-2 px-4">ID</th>
                                <th class="py-2 px-4">Создана</th>
                                <th class="py-2 px-4">Статус</th>
                                <th class="py-2 px-4">Клиент</th>
                                <th class="py-2 px-4">Тема</th>
                                <th class="py-2 px-4"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($tickets as $ticket)
                                <tr class="border-b">
                                    <td class="py-2 px-4">
                                        {{ $ticket->id }}
                                    </td>

                                    <td class="py-2 px-4 whitespace-nowrap">
                                        {{ $ticket->created_at?->isoFormat('L HH:mm') }}
                                    </td>

                                    <td class="py-2 px-4">
                                        <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                            {{ $ticket->status->label() }}
                                        </span>
                                    </td>

                                    <td class="py-2 px-4">
                                        <div class="font-medium text-gray-900">{{ $ticket->customer->name }}</div>
                                        <div class="text-gray-500">{{ $ticket->customer->email }}</div>
                                    </td>

                                    <td class="py-2 px-4">
                                        {{ $ticket->subject }}
                                    </td>

                                    <td class="py-2 pr-0 text-right">
                                        <a href="{{ route('admin.tickets.show', ['ticket' => $ticket] + request()->query()) }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            Подробно
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-6 text-gray-500" colspan="6">
                                        Еще нет ни одной заявки
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
