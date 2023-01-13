<div>
    <x-page.title svg="svgs.users">
        Members
    </x-page.title>
	<?php
		$text='';
		if(!empty($_GET)){
			if($_GET['buy_more_seats']==1){
				$text='Seat';
			}
			else{
				$text = 'Seats';
			}
	?>
			<div class="alert" id="alert">
				<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
					You have purchased <?php echo $_GET['buy_more_seats']; echo ' '.$text; ?> more.
			</div>
			<br>
			<script>
			
			function hidealert(){
				document.getElementById('alert').style.display='none';
			}
			
				setTimeout(hidealert, 3000);
			
			</script>
	<?php
		}
	?>
    <div class="md:flex items-center justify-between pb-8">
        <div class="md:flex items-center md:space-x-4">
            <x-inputs.search wire:model.debounce.500ms="search" class="w-full md:w-72" placeholder="{{ $filter == 'members' ? 'Search by name or lastname' : 'Search by email' }}"  />

            <x-inputs.select-without-label wire:model="filter" name="filter" class="w-full md:w-40 mt-4 md:mt-0">
                <option value="members">Members</option>
                <option value="invites">Invites</option>
            </x-inputs.select-without-label>
        </div>

		
        <button wire:click="$emit('memberInvite')" type="button" class="w-full md:w-auto mt-4 md:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
            <x-svgs.plus class="w-5 h-5 mr-1" />
            Invite Member
        </button>	
    </div>

    <div id="miModal" class="modal"> 
        <div class="modal-contenido">
            <a href="#">X</a>
            <div class="py-2 pl-2 pr-2">
               <h5 class="url"></h5>
               <input id="myInput" readonly style="width:540px !important; height: 38px;  border: 1px solid black;" type="text" value="{{$url}}" >
               <span class="tooltiptext" id="text"></span>
               <button title="Copy to clipboard" class="rounded-md bg-blue-600 text-white pr-4 pl-4 py-2" type="button" onclick="myFunction()" onmouseout="outFunc()">
                    Copy text
                   
                </button>
            </div>
           

        
        </div>  
    </div>

    <style>
    .tooltiptext {
  visibility: hidden;
  width: 140px;
  background-color: red;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

 
        .url{
            font-size:15px;
        }
        .modal-contenido{
            background-color: white;
            border-radius:8px;
            width:700px;
            height:100px;
            padding: 10px 20px;
            margin: 0 auto;
            position: relative;
        }
       
        .modal{
            background-color: rgba(0,0,0,0.5);
            position:fixed;
            top:0;
            right:0;
            bottom:0;
            left:0;
            opacity:0;
            pointer-events:none;
            transition: all 1s;
        }
        #miModal:target{
            opacity:1;
            pointer-events:auto;
        }
    </style>

    @if ($users->count())
        <div>
            @if ($filter == 'members')
                <x-members.headings />
                @foreach ($users as $user)
                    <x-members.row :user="$user" :key="$user->id" />
                @endforeach
            @else
                <x-invites.headings />
                @foreach ($users as $user)
                    <x-invites.row :user="$user" :key="$user->id" />
                @endforeach
            @endif
        </div>
        <div class="pt-5">
            {{ $users->links('vendor.pagination.default') }}
        </div>
    @else
        <x-states.empty-data message="There are no pending invites." />
    @endif

    @push('modals')
        @livewire('accounts.members.members-edit-modal')
        @livewire('accounts.members.members-invite')
    @endpush
</div>
<script>
function myFunction() {
  var copyText = document.getElementById("myInput");
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copyText.value);
  
  var text = document.createTextNode("added text");
  document.getElementById("text").appendChild(text);
}


</script>
<style>
.alert {
  padding: 20px;
  background-color: #0284C7;
  color: white;
  border-radius: 6px;
}

.closebtn {
	margin-left: 15px;
	color: white;
	font-weight: bold;
	float: right;
	font-size: 22px;
	line-height: 20px;
	cursor: pointer;
	transition: 0.3s;
}

.closebtn:hover {
	color: black;
}
</style>
