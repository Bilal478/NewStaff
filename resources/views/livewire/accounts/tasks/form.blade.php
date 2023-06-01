<div >
<div class="overflow-y-auto">
    <x-modals.small  x-on:open-task-form-modal.window="open = true" x-on:close-task-form-modal.window="open = false">
        <form wire:submit.prevent="save">
            <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
                {{ $isEditing ? 'Edit Task' : 'New Task' }}
            </h5>
			@if ($inProject)
				<h5 class="font-montserrat text-center font-semibold text-lg text-gray-700 mb-6">
				{{ $project->title }}
				</h5>
			@endif
            @if (! $inProject)
            <div class="border-b mb-6"> 
                <x-inputs.select wire:model="project_id" label="Project" name="project_id" required>
                    <option value="">Select a project</option>
                    @foreach ($projects as $project)
                    <option value="{{ $project->id }}">
                        {{ $project->title }}
                    </option>
                    @endforeach
					
                </x-inputs.select>
				
            </div>
            @endif
			
            <x-inputs.text wire:model.lazy="title" label="Title" name="title" type="text" placeholder="Title" required />

            <x-inputs.textarea wire:model.lazy="description" label="Description" name="description" type="text"
                placeholder="Description" required />

			<?php   
			
			$users_in_project = DB::table('users')
				->join('project_user', 'users.id', '=', 'project_user.user_id')
				->where('project_user.project_id', $project_id)
				->select('users.*')
				->get();
			
			foreach($users_in_project as $item){
				
			}
			
			?>
			
			<?php if( count($users_in_project) > 0 && $project_id != ''){ ?>
			
            <select style="border: 1px solid grey; border-radius:3px; width:100%; margin-bottom:15px; padding: 3px 0px;" wire:model.lazy="user_id" label="Assignee" name="user_id" required>
                <option value="">Select a Assignee</option>
				{{-- @foreach (App\Models\User::get() as $user)--}}
				
				
				@foreach ($users_in_project->sortBy('firstname') as $user)
				
                <option value="{{ $user->id }}">
                
                    {{ $user->firstname }} {{ $user->lastname }}

                </option>
                @endforeach
				
            </select>
			<?php } if ( count($users_in_project) == 0 && $project_id != ''){
			echo "<h4 style='color:red; padding-bottom: 10px;' >There aren't users assigned to this project</h4>";
			}
			?>
			
            {{--@if (! $inTeam)
			@if (!$inProject)
            <x-inputs.select wire:model.lazy="team_id" label="Team" name="team_id" type="text">
                <option value="">Select a team</option>
                @foreach (App\Models\Team::get() as $item)
                <option value="{{ $item->id }}">{{ $item->title }}</option>
                @endforeach
            </x-inputs.select>
            @endif --}}
			
            {{--@if (!$inDepartment)
		
			
			@if (!$inProject)
            <x-inputs.select wire:model.lazy="department_id" label="Department" name="department_id" type="text">
                <option value="">Select a department</option>
                @foreach (App\Models\Department::get() as $item)
                <option value="{{ $item->id }}">{{ $item->title }}</option>
                @endforeach
            </x-inputs.select>

            @endif --}}
            <x-inputs.select wire:model.lazy="completed" label="Status" name="completed" required>
                <option value="">Select status</option>
                <option value="false">In progress</option>
                <option value="true"> Completed </option>
            </x-inputs.select>
            <div class="flex justify-end mt-2">
			
			<?php if ( count($users_in_project) == 0 && $project_id != ''){ ?>
				<x-buttons.blue-inline style="background-color: #7DD3FC !important;" disabled type="submit">
                    {{ $isEditing ? 'Update Task' : 'Create Task' }}
                </x-buttons.blue-inline>
			
			<?php } if( count($users_in_project) > 0 && $project_id != ''){ ?>
                <x-buttons.blue-inline type="submit">
                    {{ $isEditing ? 'Update Task' : 'Create Task' }}
                </x-buttons.blue-inline>
			<?php } ?>
            </div>
        </form>
    </x-modals.small>
	</div>	
	
	<div>
		<x-modals.date x-on:open-activities-form-modal.window="open = true" x-on:close-activities-form-modal.window="open = false">
        @role(['owner'])
                <?php
                    $users = App\Models\User::orderBy('firstname')->get(['id', 'firstname', 'lastname']);
               
                
                ?>
        @endrole
        @role(['member','manager'])
                <?php 
                $user_login = auth()->id();
                $uniqueUsersQuery = App\Models\User::select('id', 'firstname', 'lastname')
                ->whereIn('id', function ($query) use ($user_login) {
                    $query->select('user_id')
                        ->from('department_user')
                        ->whereIn('department_id', function ($query) use ($user_login) {
                            $query->select('department_id')
                                ->from('department_user')
                                ->where('user_id', $user_login);
                        });
                })
                ->orderBy('firstname');
        
            $users = $uniqueUsersQuery->get();
                ?>
        @endrole
			@if ($inTeam) 
				 <form wire:submit.prevent="createActivity"  autocomplete="off"> 
			@endif
			 
			 <form wire:submit.prevent="create_activity"  autocomplete="off">
			 
            <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
                New Activity
            </h5>
			
			 @if (!$inProject) 
            <x-inputs.select_two wire:model.lazy="user_id" label="Assignee" name="user_id" id="user_id">
                <option value="">Select a Assignee</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}">
                    {{ $user->firstname }} {{ $user->lastname }}
                </option>
                @endforeach
            </x-inputs.select_two>
			@endif
			<?php

			if($user_id != ''){
				
				if( count(App\Models\Task::where('user_id', $user_id)->get()) > 0 ){
			?> 
				<x-inputs.select_two wire:model.lazy="task_id" label="Task" name="task_id" id="task_id">
					<option value="">Select a task</option>
					@foreach (App\Models\Task::where('user_id', $user_id)->get() as $item)
					<option value="{{ $item->id }}">{{ $item->title }}</option>
					@endforeach
				</x-inputs.select_two>
			<?php
				}else{
					echo "<h4 style='color:red; margin-top: -20px; padding-bottom: 10px;' >There aren't tasks assigned to this user</h4>";
				}
			}	
			?>
			 
			<label for="start_time" class="block text-sm text-gray-500 my-4 leading-5">
					Date 
				</label>
			
			<div class="flex-1">
                    <x-inputs.datepicker-without-label-two wire:model="datetimerange" class="w-full" name="datetimerange" type="text"
                        :clear-button="false" />
            </div>
			
			<?php	//echo "Seconds: ".$seconds." - Seconds Two:".$seconds_two; ?>

				<label for="start_time" class="block mt-4 text-sm text-gray-500 leading-5">
					Start Time 
					
				</label>
				<div class="mt-1 rounded-md shadow-sm">
                <input name="seconds" id="seconds" type="text" step='1' min="00:00:00" max="24:00:00"
                    wire:model="seconds" required="required"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 timepicker">
				</div>

				<label for="end_time" style="padding-top: 15px !important;" class="block text-sm  text-gray-500 leading-5">
					End Time  
				</label>
				<div class="mt-1 rounded-md shadow-sm">
                <input name="seconds_two" id="seconds_two" type="text" step='1' min="12:00:00" max="24:00:00"
                    wire:model="seconds_two" required="required"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 timepicker_two">
				</div>
			<?php if( count(App\Models\Task::where('user_id', $user_id)->get()) > 0 ){ ?>
            <div class="flex justify-end mt-2">
                <x-buttons.blue-inline type="submit">
                  Create Activity
                </x-buttons.blue-inline>
            </div>
			
			<?php } else{ ?>
			
			<div class="flex justify-end mt-2">
                <x-buttons.blue-inline style="background-color: #7DD3FC !important;" disabled  type="submit">
                  Create Activity
                </x-buttons.blue-inline>
            </div>

          
			<?php 
             
            } ?>
        </form>
		</x-modals.date>
	</div>	
	
	
    @livewire('accounts.projects.projects-form')
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function (event) {

            Livewire.hook('message.processed', () => {

                $('#activityId').select2();
            });

        });
            $('#activityId').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            // $('#tracking_time').val(data);
            @this.set(elementName, data);
        });

        $('#activityId').select2();
</script>

@endpush
@push('scripts')
<script>

    $('.timepicker').timepicker({

            timeFormat: 'HH:mm:ss a',
            interval: 30,
            dynamic: false,
	
            dropdown: true,
            scrollbar: true,
            change: tmTotalHrsOnSite

        });

        function tmTotalHrsOnSite () {
            var x = $(".timepicker").val();
			
			document.getElementById('seconds_two').value = '';
			
            let elementName = $(".timepicker").attr('id');
            var data = $(".timepicker").val();
            @this.set(elementName, data);
			
				var block_time = x.split(':');
				
				var start_t = parseInt(block_time[1])+parseInt(30);
				
				if(start_t == 60){	start_t = '00';	block_time[0] = parseInt(block_time[0])+parseInt(1);	}
				
				$('.timepicker_two').timepicker({
					timeFormat: 'HH:mm:ss a',
					interval: 30,
					dynamic: false,
					dropdown: true,
					scrollbar: true,
					change: tmTotalHrsOnSite_two
				});		
				$('.timepicker_two').timepicker('option', 'minTime', new Date(0, 0, 0, block_time[0], start_t, 0));
        }
        function tmTotalHrsOnSite_two () {
            var x = $(".timepicker_two").val();
            let elementName = $(".timepicker_two").attr('id');
            var data = $(".timepicker_two").val();
            @this.set(elementName, data);
        }
</script>
@endpush

