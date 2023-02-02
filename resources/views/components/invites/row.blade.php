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

        <div class="w-44 px-3 text-xs text-gray-500">
        <div class="flex items-center  w-full ">
        
        <label 
            for="time_{{$user->id}}"
            class="flex items-center cursor-pointer"
        >
            <!-- toggle -->
            <div class="relative">
            <!-- input -->
            <input name="time_{{$user->id}}" id="time_{{$user->id}}" wire:click.prevent="editTimePermssionInvite({{$user->id}},{{$user->allow_edit_time}})"  type="checkbox" 
            @if($user->allow_edit_time == 1)
            checked
            @endif
            class="sr-only" />
            <!-- line -->
            <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
            <!-- dot -->
            <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
            </div>
            
        </label>
        
        </div>
        </div>
        
        <div class="w-44 px-3 text-xs text-gray-500">
        <div class="flex items-center  w-full ">
  
        <label 
            for="screenshot_{{$user->id}}"
            class="flex items-center cursor-pointer"
        >
            
            <div class="relative">
            <!-- input -->
            <input name="screenshot_{{$user->id}}" id="screenshot_{{$user->id}}" wire:click.prevent="editScreenshotPermssionInvite({{$user->id}},{{$user->allow_delete_screenshot}})"  type="checkbox" 
            @if($user->allow_delete_screenshot == 1)
            checked
            @endif
            class="sr-only" />
            <!-- line -->
            <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
            <!-- dot -->
            <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
            </div>
            
        </label>
        
        </div>   
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
        input:checked ~ .dot {
        transform: translateX(100%);
        background-color: #0284c7;
        }
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
