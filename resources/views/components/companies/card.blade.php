@props(['company', 'users', 'usersCount'])

<div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4">
    <article
        wire:click="companyShow({{$company->id}})"
        class="bg-white mx-4 mb-8 rounded-md border shadow-sm px-6 py-4 h-30 flex flex-col justify-between items-start cursor-pointer hover:shadow-md"
    >
        <div class="w-full">
            <div class="mb-4 flex items-center justify-end">
                @role(['owner', 'manager'])
                    <x-dropdowns.context-menu class="-mr-2">
                        <x-dropdowns.context-menu-item wire:click.stop="$emit('companiesEdit', {{$company->id}})" name="Edit" svg="svgs.edit"/>
                        <x-dropdowns.context-menu-item wire:click.stop="companyArchive({{$company->id}})" name="Delete" svg="svgs.trash"/>
                    </x-dropdowns.context-menu>
                @endrole
            </div>
            <h4 class="font-montserrat font-semibold text-sm text-gray-700 pb-4 truncate">
                {{ $company->title }}
            </h4>
        </div>
        {{-- <div class="w-full flex items-center justify-between">
            <div class="flex items-center space-x-1">
                @if ($usersCount >= 1)
                    <x-user.avatar />
                @endif
                @if ($usersCount >= 2)
                    <x-user.avatar />
                @endif
                @if ($usersCount > 2)
                    <div class="h-8 w-8 bg-indigo-400 rounded-full text-white flex items-center justify-center text-xs tracking-wider">
                        +{{ $usersCount - 2 }}
                    </div>
                @endif
            </div>
        </div> --}}
    </article>
</div>
