@props([
    'name',
    'type' => 'text',
    'placeholder' => '',
    'clearButton' => true,
])

<div class="{{ $attributes->get('class') }}">
    <div class="relative text-gray-400 focus-within:text-blue-600 rounded-md shadow-sm">
	
	 <input id="{{ $name }}" name="{{ $name }}" width="100%" />
	
      
    </div>

    @error($name)
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>


		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
		
       
		 <script>
        $('#{{ $name }}').datetimepicker({ format: 'yyyy-mm-dd HH:MM', footer: true, modal: true });
    </script>

