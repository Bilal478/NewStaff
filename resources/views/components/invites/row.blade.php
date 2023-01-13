@props(['user'])

<div class="w-full bg-white py-5 rounded-md border mb-3">
    <div class="flex items-center text-sm">
        <div class="flex-1 px-3 truncate">
            <div class="flex items-center">
                <x-user.avatar />
                <span class="ml-3 block text-left font-montserrat text-xs font-semibold text-gray-500">{{ $user->email }}</span>
            </div>
            
        </div>
        
        <div class="w-56 px-3 text-xs text-gray-500">
            {{ ucfirst($user->role) }}
        </div>
        <div class="w-44 px-3 text-xs text-gray-500">
            {{ $user->created_at->format('d/M/Y H:m:s') }}
        </div>
        <div class="w-30 px-3 flex justify-end">
            <x-dropdowns.context-menu>
                <x-dropdowns.context-menu-item class="copy-link" wire:click.stop="inviteDelete({{$user->id}})" name="Delete" svg="svgs.x-circle"/>
                <a href="#miModal">  
                    <x-dropdowns.context-menu-item2 class="copy-link" wire:click.stop="copyInvitation({{$user->id}})" name="Copy" svg="svgs.archive"/>
                </a>
                <x-dropdowns.context-menu-item2 class="copy-link" wire:click.stop="Resend({{$user->id}})"  name="Resend" svg="svgs.arrow-right"/>
            </x-dropdowns.context-menu>
        </div>
        
    </div>

    <style>
        .copy-link{
            margin-left:-10px;
        }
    </style>

    <script>
        function copyToClipboard(id) {
            document.getElementById(id).select();
            document.execCommand('copy');
        }
    </script>

</div>
