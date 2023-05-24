<x-modals.small x-on:open-track-time-pop-up-modal.window="open = true" x-on:close-track-time-pop-up-modal.window="open = false">
        <div class="modal-contenido">
            <div class="py-4 pl-4">
                <h4 class="text-gray-600">
                    <i class="fa fa-exclamation-circle"></i> Your team has not tracked any time.
                </h4>
            </div>

            <h1 class="py-4"><b>Getting Started</b></h1>
            <div class="list">
                <ol >
                    <li>1. Each team member needs to open their invite email and click the accept link.</li>
                    <li>
                            <li>2. You use Windows, you can download the time tracker desktop app from here:
                                <a href="//media.neostaff.app/downloads/windows" style="color: blue">https://media.neostaff.app/downloads/windows</a></li><br>
    
            
                               <li>If you use MAC, you can download the time tracker desktop app from here:
                                <a href="https://media.neostaff.app/downloads/mac" style="color: blue">https://media.neostaff.app/downloads/mac</a></li>
                            
                    </li>
                    <li>3. Finally they have to install the app and use it to track time to a project.</li>
                </ol>
            </div>
            <h1 class="py-4"><b>Your Organizations</b></h1>
            <h1 class="pb-2"><b><?php if (isset($account[0])) {
                echo $account[0]->name . ' team members';
            }  ?> </b></h1>
            <h4 class="text-gray-400 py-4"><i class="fas fa-info-circle"></i> Time can also be added manually on the
                timesheets page.</h4>
        </div>
    </div>
</x-modals.small>

@push('scripts')
    <script>
        // Add any necessary scripts here
    </script>
@endpush
