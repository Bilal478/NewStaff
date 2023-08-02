{{-- <div>
    @if ($showCarousel)
        <div
            x-data="{ open: false, activeSlideIndex: 0, slides: [{{ $activity->screenshots->implode('id', ',') }}] }"
>
<div x-cloak class="fixed inset-0 bg-red-200 z-20" style="background-color: rgba(0, 0, 0, 0.9);">

    <div class="absolute right-4 top-4 z-30">
        <button wire:click.prevent="$set('showCarousel', false)" type="button" class="text-gray-400 hover:text-white">
            <x-svgs.x class="h-7 w-7" />
        </button>
    </div>

    <div class="absolute left-4 top-4">
        <span class="text-gray-400" x-text="(activeSlideIndex +1) + ' / ' + slides.length"></span>
    </div>

    <div class="h-full flex flex-col">
        <div class="relative flex-1 flex items-center justify-center">

            <div class="absolute left-0 top-0 bottom-0 flex items-center justify-center pl-4">
                <button
                    x-on:click="activeSlideIndex = activeSlideIndex === 0 ? slides.length - 1 : activeSlideIndex - 1"
                    type="button"
                    class="text-gray-400 bg-black p-2 rounded-md opacity-70 lg:bg-transparent lg:opacity-100 hover:text-white">
                    <x-svgs.arrow-left class="h-7 w-7" />
                </button>
            </div>

            <div class="absolute right-0 top-0 bottom-0 flex items-center justify-center pr-4">
                <button
                    x-on:click="activeSlideIndex = activeSlideIndex === (slides.length -1) ? 0 : activeSlideIndex + 1"
                    type="button"
                    class="text-gray-400 bg-black p-2 rounded-md opacity-70 lg:bg-transparent lg:opacity-100 hover:text-white">
                    <x-svgs.arrow-right class="h-7 w-7" />
                </button>
            </div>

            @foreach ($activity->screenshots as $screenshot)
            <div x-show="slides[activeSlideIndex] === {{ $screenshot->id }}" class="max-w-6xl px-2 lg:px-16 xl:px-0">
                <img class="w-full h-auto" src="{{ $screenshot->first()->fullPath() }}" alt="hey">
            </div>
            @endforeach
        </div>

        <div class="h-40 hidden md:flex items-center justify-center space-x-2">
            @foreach ($activity->screenshots as $screenshot)
            <div x-on:click="activeSlideIndex = slides.indexOf({{ $screenshot->id }})"
                class="w-44 rounded-md object-cover overflow-hidden "
                :class="{ 'border-2 border-blue-600': slides[activeSlideIndex] === {{ $screenshot->id }} }">
                <img class="w-full h-auto" src="{{ $screenshot->first()->fullPath() }}" alt="">
            </div>
            @endforeach
        </div>
    </div>

</div>
</div>
@endif
</div> --}}

<div>

    @if ($showCarousel)
        <div id="myModal" class="modal">
            <span class="close cursor" onclick="closeModal()">&times;</span>
            <div class="modal-content">
                @php 
                $a = 0
                @endphp
                @foreach ($this->allActivity as $item)
                    <div class="mySlides text-center">
                        <div class="numbertext">{{ ++$a }} / {{ $totalScreenshots }}</div>


                        @if (count($item->screenshots) > 0)
                            <img src="{{ $item->screenshots[0]->fullPath() }}" style="width:100%">
                            <span><small><b>@if ($item->project)
                                {{$item->project->title}}
                            @else
                                No-Project
                            @endif at {{ $item->screenshots[0]->created_at->format('D,M j,Y h:i a')}} ({{$item->total_activity_percentage}}% active)-Screenshot 1 of @php echo count($item->screenshots) @endphp</b></small></span>
                            @if ($item->screenshots->first()->fullPath() == 'https://media.neostaff.app/screenshots/00/1234567890.png')
                                <img class="img_placeholder" src="activity_placeholder.png">
                                
                            @endif
                        @else
                            <img class="img_placeholder" src="activity_placeholder.png"
                                style="width:100%;height: 70vh;">
                        @endif
                    </div>
                    @if (count($item->screenshots) == 2)
                    <div class="mySlides text-center">
                        <div class="numbertext">{{ ++$a }} / {{ $totalScreenshots }}</div>


                        @if (count($item->screenshots) > 0)
                            <img src="{{ $item->screenshots[1]->fullPath() }}" style="width:100%">
                            <span><small><b>@if ($item->project)
                                {{$item->project->title}}
                            @else
                                No-Project
                            @endif at {{ $item->screenshots[1]->created_at->format('D,M j,Y h:i a')}} ({{$item->total_activity_percentage}}% active)-Screenshot 2 of 2</b></small></span>

                            @if ($item->screenshots->first()->fullPath() == 'https://media.neostaff.app/screenshots/00/1234567890.png')
                                <img class="img_placeholder" src="activity_placeholder.png">
                                
                            @endif
                        @else
                            <img class="img_placeholder" src="activity_placeholder.png"
                                style="width:100%;height: 70vh;">
                        @endif
                    </div>
                @endif
                @endforeach
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>

                <div class="caption-container">
                    <p id="caption"></p>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
      
        // setTimeout(function(){ $("#tiggerImage").click(); }, 3000);

        $(document).ready(function() {});
     
        function openModal() {
            document.getElementById("myModal").style.display = "block";

        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
            Array.from(document.querySelectorAll('.activity-img')).forEach(function(el) { 
				el.classList.add('group-hover:opacity-100');
			});

            Array.from(document.querySelectorAll('.article')).forEach(function(el) { 
				el.style.display = "block"; 
			});
            Array.from(document.querySelectorAll('.number_of_screeshot')).forEach(function(el) { 
				el.style.display = "flex"; 
			});
        }

        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            setTimeout(function() {
                showSlides(slideIndex = n);
            }, 2000);

            showSlides(slideIndex = n);
               
            // Array.from(document.querySelectorAll('.activity-img')).forEach(function(el) { 
			// 	el.classList.remove('group-hover:opacity-100');
			// });

            // Array.from(document.querySelectorAll('.article')).forEach(function(el) { 
			// 	el.style.display = "none"; 
			// });
            Array.from(document.querySelectorAll('.number_of_screeshot')).forEach(function(el) { 
				el.style.display = "none"; 
			});        
            
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("mySlides");
            //   var dots = document.getElementsByClassName("demo");
            //   var captionText = document.getElementById("caption");
            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
                slides[slideIndex - 1].style.display = "block";
            }
            //   for (i = 0; i < dots.length; i++) {
            //       dots[i].className = dots[i].className.replace(" active", "");
            //   }
            //   dots[slideIndex-1].className += " active";
            //   captionText.innerHTML = dots[slideIndex-1].alt;
        }
    </script>
@endpush

@push('style')
    <style>
        @media screen and (min-device-width : 1883px) {
            a.prev {
                margin-left: -337px !important;

            }

            a.next {
                margin-right: -337px !important;

            }

            .numbertext {
                color: #fdfdfd;
                margin-top: -67px !important;
                margin-left: -289px !important;
            }
        }

        @media screen and (min-device-width : 1602px) {
            a.prev {
                margin-left: -137px !important;

            }

            a.next {
                margin-right: -137px !important;

            }

            .numbertext {
                color: #fdfdfd;
                margin-top: -37px !important;
                margin-left: -189px !important;
            }
        }

        @media screen and (min-device-width : 1527px) {
            a.prev {
                color: rgb(255, 254, 254) !important
            }

            a.next {
                color: rgb(255, 255, 255) !important
            }

            .numbertext {
                color: #ffffff !important;
                margin-top: -17px !important;
                margin-left: -89px !important;
            }
        }

        * {
            box-sizing: border-box;
        }

        .row>.column {
            padding: 0 8px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .column {
            float: left;
            width: 25%;
        }

        /* The Modal (background) */
        .modal {
            /* display: none; */
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: black;
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            width: 90%;
            max-width: 1200px;
        }

        /* The Close Button */
        .close {
            color: white;
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 35px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #999;
            text-decoration: none;
            cursor: pointer;
        }

        .mySlides {
            display: none;
        }

        .cursor {
            cursor: pointer;
        }

        /* Next & previous buttons */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -50px;
            color: rgb(255, 255, 255);
            font-weight: bold;
            font-size: 24px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
            -webkit-user-select: none;
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */
        .prev:hover,
        .next:hover {
            /* background-color: rgba(248, 248, 248, 0.8); */
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #000000;
            font-size: 20px;
            font-weight: 900;
            padding: 8px 0px;
            position: absolute;
            top: 0;
        }

        img {
            margin-bottom: -4px;
        }

        .caption-container {
            text-align: center;
            background-color: black;
            padding: 2px 16px;
            color: white;
        }

        .demo {
            opacity: 0.6;
        }

        .active,
        .demo:hover {
            opacity: 1;
        }

        img.hover-shadow {
            transition: 0.3s;
        }

        .hover-shadow:hover {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
    </style>
@endpush
