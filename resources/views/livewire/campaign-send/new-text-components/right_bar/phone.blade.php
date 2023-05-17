<div>
    <div class="grow px-5 pt-3 pb-1">
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead class="text-xs uppercase text-slate-400">
                <tr>
                    <th class="py-2">
                        <div class="font-semibold text-left">Text</div>
                    </th>
                    <th class="py-2">
                        <div class="font-semibold text-right"></div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                <tr>
                    <td class="py-2">
                        <div class="text-left">Message length</div>
                    </td>
                    <td class="py-2">
                        <div class="font-medium text-right text-slate-800"><span wire:model="text_length">{{$text_length}}</span> / <span wire:model="text_per_message">{{$text_per_message}}</span></div>
                    </td>
                </tr>
                <tr>
                    <td class="py-2">
                        <div class="text-left">URL shortener</div>
                    </td>
                    <td class="py-2">
                        <div class="text-xs inline-flex font-medium bg-slate-100 text-slate-500 rounded-full text-right px-2.5 py-1 float-right">
                            <span>Disabled</span>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="filament-forms-card-component p-6 bg-white rounded-xl">
        <div style="overscroll-behavior: none;" class="border rounded-xl border-to-white border-b-0">

            <!-- HEADING -->
            <div
                    class="w-full bg-gray-50 rounded-t-2xl h-16 pt-2 text-white flex justify-between shadow-md"
                    style="overscroll-behavior: none;"
            >

                <div class="my-3 mx-auto font-bold tracking-wide text-black opacity-30">Sender ID</div>

            </div>

            <!-- MESSAGES -->
            <div class="mt-10 mb-16 pb-6">

                <!-- SINGLE MESSAGE -->
                <div class="clearfix">
                    <div class="bg-gray-100 w-3/4 mx-4 my-2 p-4 rounded-lg rounded-bl-none mb-6 break-words">
                        {{$text}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>