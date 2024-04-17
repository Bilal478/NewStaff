<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <a href="#" class="inline-block">
                <x-logo/>
            </a>
        </div>
        <h2 class="mt-6 text-2xl sm:text-3xl font-bold text-center text-gray-600 leading-9">
            Two Factor Authentication
        </h2>
    </div>
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <div>
                <form wire:submit.prevent="verify" id="verificationForm">
                    <div>
                        <label for="code">Verification Code:</label>
                        <input type="text" id="code" wire:model.lazy="code" required class="form-control placeholder-gray-300 w-full">
                        @error('code')
                        <div id="errorMessage" style="font-size: 12px; color:red;">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-2" id="verifyButton">Verify</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="timerContainer" class="mt-4 text-center" style="display:none;">
    <span id="timer"></span>
</div>
<script>
    function removeErrorMessage() {
        var errorMessage = document.getElementById('errorMessage');
        if (errorMessage) {
            errorMessage.remove();
        }
    }

    setTimeout(removeErrorMessage, 3000); // Remove error message after 3 seconds

    window.addEventListener('removeErrorMessage', function() {
        setTimeout(removeErrorMessage, 3000); // Call the backend method to remove the error message
    });

    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        var intervalId = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(intervalId);
            }
        }, 1000);
    }

    window.onload = function () {
        var fiveMinutes = 60 * 1,
            display = document.querySelector('#timer');

        startTimer(fiveMinutes, display);
        document.getElementById('timerContainer').style.display = 'block';
    };
    document.addEventListener('livewire:load', function () {
        window.livewire.on('verificationCodeResent', () => {
            const display = document.querySelector('#timer');
            const fiveMinutes = 60 * 1;
            startTimer(fiveMinutes, display);
            document.getElementById('timerContainer').style.display = 'block';
        });
    });
</script>
