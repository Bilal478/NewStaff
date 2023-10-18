<div>
    <div class="flex flex-wrap -mx-4">
        <div class="w-full xl:w-1/2">
            <div class="bg-white rounded-md border p-6 mx-4 mb-8 h-84">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="inline-block text-sm text-blue-500 xl:tracking-widest uppercase">
                        Manager
                    </h3>
                    <h3 class="inline-block text-sm text-blue-500 xl:tracking-widest uppercase">
                        {{ $authUserName }}
                    </h3>
                </div>

                <div class="w-full">
                    <div class="flex items-center text-gray-700 text-xm font-semibold border-b">
                        {{-- <div class="flex-1 px-2 py-4">User Id</div> --}}
                        <div class="flex-1 px-2 py-4">Users</div>
                    </div>

                    @foreach ($userRecords as $record)
                        <div class="flex items-center text-gray-500 text-sm border-b">
                            {{-- <div class="flex-1 px-2 py-4 truncate">{{ $record->id }}</div> --}}
                            <div class="flex-1 px-2 py-4">{{ $record->firstname }} {{$record->lastname}}</div>
                        </div>
                    @endforeach
                </div>
                <div class="pt-5">
                    {{ $userRecords->links('vendor.pagination.default') }}
                </div>
            </div>
        </div>
    </div>
</div>
