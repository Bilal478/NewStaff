@props(['user'])

<div class="w-full bg-white py-5 rounded-md border mb-3">
    <div class="flex items-center text-sm">
        <div class="flex-1 px-3 ">
            <div class="flex items-center">
				<div class="avatar">
					<x-user.avatar />
				</div>
				<div class="fullname">
					<span class="ml-3 block text-left font-montserrat text-xs font-semibold text-gray-500">{{ $user->full_name }}</span>
				</div>
			</div>
        </div>
        <div class="w-56 px-3 text-xs text-gray-500">
            {{ $user->email }}
        </div>
        <div class="w-44 px-3 text-xs text-gray-500">

            {{ ucfirst($user->pivot->role) }}
        </div>
        
        <div class="w-44 px-3 text-xs text-gray-500">
        <div class="flex items-center  w-full ">
        
        <label 
            for="time_{{$user->pivot->id}}"
            class="flex items-center cursor-pointer"
        >
            <!-- toggle -->
            <div class="relative">
            <!-- input -->
            <input name="time_{{$user->pivot->id}}" id="time_{{$user->pivot->id}}" wire:click.prevent="editTimePermssion({{$user->pivot->id}},{{$user->pivot->allow_edit_time}})"  type="checkbox" 
            @if($user->pivot->allow_edit_time == 1)
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
            for="screenshot_{{$user->pivot->id}}"
            class="flex items-center cursor-pointer"
        >
            
            <div class="relative">
            <!-- input -->
            <input name="screenshot_{{$user->pivot->id}}" id="screenshot_{{$user->pivot->id}}" wire:click.prevent="editScreenshotPermssion({{$user->pivot->id}},{{$user->pivot->allow_delete_screenshot}})"  type="checkbox" 
            @if($user->pivot->allow_delete_screenshot == 1)
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
       
        <div class="w-20 px-3 flex justify-end">
            <x-dropdowns.context-menu>
              <x-dropdowns.context-menu-item wire:click.stop="memberRestore({{$user->id}})" name="Restore" svg="svgs.edit"/>
                <x-dropdowns.context-menu-item wire:click.stop="memberPermanentDelete({{$user->id}})" name="Delete" svg="svgs.x-circle"/>
            </x-dropdowns.context-menu>
        </div>
    </div>
</div>

<style>
    /* Toggle A */
input:checked ~ .dot {
  transform: translateX(100%);
  background-color: #0284c7;
}
@media (max-width: 625px) {
  .avatar{
	display:none;
  }
  .fullname{
	margin-left:-10px !important;
  }
}
</style>
