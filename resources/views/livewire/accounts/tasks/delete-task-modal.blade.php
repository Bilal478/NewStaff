<div>
    <x-modals.small x-on:open-delete-task-modal.window="open = true"  x-on:close-delete-task-modal.window="open = false" class="modal">
    <form wire:submit.prevent="save"> 
    <h1>What should we do with {{$userFirstName}}'s assignments?</h1><br>
    <hr><br>
    <p style="font-size: 15px">
        {{$userFullName}} is assigned to some tasks and involved in some discussions in this project.
        What would you like to do with this user's assignment?
    </p>
    <br>
    <input type="radio" wire:model="selectedOption" value="firstOption" selected="selected"> <span style="font-size: 13px; font-weight:500; margin-botto:2px;">Just remove this user and make his/her task unassigned</span>
    <br>
    <input type="radio" wire:model="selectedOption" value="secondOption"> <span style="font-size: 13px; font-weight:500; margin-botto:2px;">Assign this user's task to another person</span>
    <br><br>
    @if($selectedOption === 'secondOption')
    <div class="mb-6"> 
        <x-inputs.select  label="Users" wire:model="selectedUserId" name="selectedUserId" id="dropdown" required>
            <option value="">Select User</option>
            @foreach ($users as $user)
            <option value="{{ $user['id'] }}">
                {{ $user['firstname'] }} {{$user['lastname']}}
            </option>
            @endforeach
            
        </x-inputs.select>
        
     </div>
    @endif
    <br>
    <div class="flex justify-end mt-2">
        <x-buttons.blue-inline type="submit" class="mr-2" wire:click="save">
           OK
        </x-buttons.blue-inline>
        <x-buttons.red-inline type="button" id="cancelButton">
           Cancel
        </x-buttons.red-inline>
    </div>
    </form>
    </x-modals.small>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cancelButton = document.getElementById('cancelButton');
        const modal = document.querySelector('.modal');

        cancelButton.addEventListener('click', function () {
            modal.style.display = 'none';
        });
    });
</script>
