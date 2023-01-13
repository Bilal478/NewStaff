<div>
    <div class="pb-12">
        <h1 class="font-montserrat text-xl font-semibold text-gray-700">
            Accounts
        </h1>
    </div>

    @foreach ($accounts as $account)
        <div class="w-full">
            <div class="bg-white rounded-md border p-6 mb-8">
                <h3 class="text-sm text-gray-700 xl:tracking-widest uppercase mb-4 flex items-center">
                    <x-svgs.office-building class="w-6 h-6 text-blue-600 mr-3" />
                    {{ $account->name }}
                </h3>

                <table class="w-full">
                    <tbody>
                        <tr class="border-b">
                            <th class="text-left text-xs px-2 py-4 text-gray-700">Resource</th>
                            <th class="text-left text-xs px-2 py-4 text-gray-700 w-32">Count</th>
                        </tr>
                        <tr class="border-b">
                            <td class="text-sm text-gray-500 px-2 py-4 truncate">Users</td>
                            <td class="text-sm text-gray-500 px-2 py-4">{{ $account->users_count }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="text-sm text-gray-500 px-2 py-4 truncate">Projects</td>
                            <td class="text-sm text-gray-500 px-2 py-4">{{ $account->projects_count }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="text-sm text-gray-500 px-2 py-4 truncate">Tasks</td>
                            <td class="text-sm text-gray-500 px-2 py-4">{{ $account->tasks_count }}</td>
                        </tr>
                        <tr>
                            <td class="text-sm text-gray-500 px-2 py-4 truncate">Activities</td>
                            <td class="text-sm text-gray-500 px-2 py-4">{{ $account->activities_count }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

</div>
