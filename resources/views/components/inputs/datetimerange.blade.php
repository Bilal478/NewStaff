@props([
    'name',
    'type' => 'text',
    'placeholder' => '',
    'clearButton' => true,
])

<div class="{{ $attributes->get('class') }}">
    <div class="relative text-gray-400 focus-within:text-blue-600 rounded-md shadow-sm">
        <input type="text" name="{{ $name }}" id="{{ $name }}" style="width:100%" onChange="changeX()">

        <div class="absolute inset-y-0 right-0 flex items-center justify-center pr-3 pointer-events-none">
            <x-svgs.calendar class="w-5 h-5" />
        </div>
    </div>
	

    @error($name)
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>

@once
    @push('scripts')
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	
	<script>
$(function() {
  $('input[name="datetimerange"]').daterangepicker({
    timePicker: true,
    startDate: moment().startOf('hour'),
    endDate: moment().startOf('hour').add(32, 'hour'),
    locale: {
      format: 'M/DD/Y hh:mm'
    }
  });
});

function changeX(){
	alert("T");
}
</script>

<style>

.prev, .next {
    position: relative !important;
	background-color: unset;
}

</style>

    @endpush
@endonce
