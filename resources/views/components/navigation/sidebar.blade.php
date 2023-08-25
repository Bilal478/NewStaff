<aside class="fixed bg-white border-r inset-y-0 left-0 w-60 hidden lg:block">
    <nav class="h-full pt-8 flex flex-col justify-between" style="overflow-y: scroll !important;">
        <div>
            <div class="flex items-center justify-center pb-8">
                <a href="{{ route('home') }}" class="inline-block">
                    <img class="w-28 h-auto" alt="Neostaff" src="{{ url(asset('images/logo/neostaff-logo.png')) }}">
                </a>
            </div>

            @livewire('accounts.account.account-info')

            <div class="pt-6">
                <ul>
                    <x-navigation.sidebar-item route="accounts.dashboard" img="svgs.chart">
                        Dashboard
                    </x-navigation.sidebar-item>
                    <x-navigation.sidebar-item route="accounts.activities" img="svgs.computer">
                        Activities
                    </x-navigation.sidebar-item>
                    <x-navigation.sidebar-item route="accounts.tasks" img="svgs.task">
                        Tasks
                    </x-navigation.sidebar-item>
                    <x-navigation.sidebar-item route="accounts.projects" img="svgs.folder">
                        Projects
                    </x-navigation.sidebar-item>
                    <x-navigation.sidebar-item route="accounts.reports" img="svgs.report">
                        Timesheets
                    </x-navigation.sidebar-item>
                </ul>
            </div>
  
   
    

            {{-- @role('owner') --}}
            @php
            $accountId = session('account_id');
            $accountRole = session('account_role');
            $user = Auth::user();
            // $account_user = DB::table('account_user')->where('user_id',$user->id)->first();
            if($accountRole=='owner'){
                @endphp
                <div class="pt-6">
                <h4 class="px-4 uppercase text-xs text-gray-400 font-semibold tracking-wider pb-2">
                    Admin
                </h4>
                 <ul>
                     <li class="px-2 py-1">
                        <a href="{{ url('/departments') }}"
                            class="px-3 py-2 text-sm rounded-md flex items-center  text-gray-500 hover:bg-gray-100 hover:text-blue-600">
                            <svg   id="Capa_1"   class="w-5 h-5 mr-2"   viewBox="0 0 512 512"   xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="m437.35 409.687c6.103-8.851 9.688-19.567 9.688-31.108v-22.635c0-25.096-16.919-46.301-39.945-52.846v-62.098h-136.093v-30h68.047v-43.305c0-26.128-15.505-48.691-37.79-59.008 6.103-8.851 9.688-19.567 9.688-31.108v-22.635c-.001-30.297-24.648-54.944-54.945-54.944s-54.944 24.647-54.944 54.944v22.635c0 11.541 3.584 22.257 9.688 31.108-22.285 10.318-37.79 32.88-37.79 59.008v43.305h68.046v30h-136.093v62.098c-23.026 6.546-39.945 27.75-39.945 52.846v22.635c0 11.541 3.584 22.257 9.688 31.108-22.285 10.318-37.789 32.88-37.789 59.008v43.305h438.279v-43.305c0-26.128-15.505-48.69-37.79-59.008zm-206.294-354.743c0-13.754 11.19-24.944 24.944-24.944s24.944 11.19 24.944 24.944v22.635c0 13.754-11.19 24.944-24.944 24.944s-24.944-11.19-24.944-24.944zm-28.103 112.751c0-19.299 15.701-35 35-35h36.094c19.299 0 35 15.701 35 35v13.305h-106.094zm214.085 188.249v22.635c0 13.754-11.19 24.944-24.945 24.944-13.754 0-24.944-11.19-24.944-24.944v-22.635c0-13.754 11.19-24.944 24.944-24.944 13.755 0 24.945 11.19 24.945 24.944zm-39.945-84.944v32.098c-23.026 6.546-39.944 27.75-39.944 52.846v22.635c0 11.541 3.584 22.257 9.688 31.108-8.846 4.096-16.627 10.112-22.789 17.526-6.162-7.415-13.944-13.431-22.79-17.526 6.103-8.851 9.688-19.567 9.688-31.108v-22.635c0-25.096-16.918-46.3-39.944-52.846v-32.098zm-121.093 60c13.754 0 24.944 11.19 24.944 24.944v22.635c0 13.754-11.19 24.944-24.944 24.944s-24.944-11.19-24.944-24.944v-22.635c0-13.754 11.19-24.944 24.944-24.944zm-15-60v32.098c-23.026 6.546-39.944 27.75-39.944 52.846v22.635c0 11.541 3.584 22.257 9.688 31.108-8.846 4.096-16.628 10.112-22.79 17.526-6.162-7.415-13.943-13.43-22.789-17.526 6.103-8.851 9.688-19.567 9.688-31.108v-22.635c0-25.096-16.918-46.3-39.944-52.846v-32.098zm-146.038 84.944c0-13.754 11.19-24.944 24.945-24.944 13.754 0 24.944 11.19 24.944 24.944v22.635c0 13.754-11.19 24.944-24.944 24.944-13.755 0-24.945-11.19-24.945-24.944zm77.991 126.056h-106.093v-13.305c0-19.299 15.701-35 35-35h36.093c19.299 0 35 15.701 35 35zm136.094 0h-106.094v-13.305c0-19.299 15.701-35 35-35h36.094c19.299 0 35 15.701 35 35zm136.093 0h-106.093v-13.305c0-19.299 15.701-35 35-35h36.093c19.299 0 35 15.701 35 35z" />
                            </svg>
                            Departments
                        </a>
                    </li> 
                    {{-- <x-navigation.sidebar-item route="accounts.teams" img="svgs.team">
                        Teams
                    </x-navigation.sidebar-item> --}}

                    <x-navigation.sidebar-item route="accounts.members" img="svgs.users">
                        Members
                    </x-navigation.sidebar-item>
					
                    <x-navigation.sidebar-item route="accounts.settings" img="svgs.settings">
                        Settings
                    </x-navigation.sidebar-item>
					
					<x-navigation.sidebar-item route="accounts.billing" img="svgs.folder">
                        Membership
                    </x-navigation.sidebar-item>
                </ul>
            </div>
                    
@php
            }
            else{
            if($user->permissions!=null){
            $permissions=explode(',',$user->permissions);
            @endphp
            <div class="pt-6">
                <h4 class="px-4 uppercase text-xs text-gray-400 font-semibold tracking-wider pb-2">
                    Admin
                </h4>
                <ul>
                @php
                if(in_array('departments', $permissions)){
                @endphp
                    
                        <li class="px-2 py-1">
                                    <a href="{{ url('/departments') }}"
                                        class="px-3 py-2 text-sm rounded-md flex items-center  text-gray-500 hover:bg-gray-100 hover:text-blue-600">
                                        <svg   id="Capa_1"   class="w-5 h-5 mr-2"   viewBox="0 0 512 512"   xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="m437.35 409.687c6.103-8.851 9.688-19.567 9.688-31.108v-22.635c0-25.096-16.919-46.301-39.945-52.846v-62.098h-136.093v-30h68.047v-43.305c0-26.128-15.505-48.691-37.79-59.008 6.103-8.851 9.688-19.567 9.688-31.108v-22.635c-.001-30.297-24.648-54.944-54.945-54.944s-54.944 24.647-54.944 54.944v22.635c0 11.541 3.584 22.257 9.688 31.108-22.285 10.318-37.79 32.88-37.79 59.008v43.305h68.046v30h-136.093v62.098c-23.026 6.546-39.945 27.75-39.945 52.846v22.635c0 11.541 3.584 22.257 9.688 31.108-22.285 10.318-37.789 32.88-37.789 59.008v43.305h438.279v-43.305c0-26.128-15.505-48.69-37.79-59.008zm-206.294-354.743c0-13.754 11.19-24.944 24.944-24.944s24.944 11.19 24.944 24.944v22.635c0 13.754-11.19 24.944-24.944 24.944s-24.944-11.19-24.944-24.944zm-28.103 112.751c0-19.299 15.701-35 35-35h36.094c19.299 0 35 15.701 35 35v13.305h-106.094zm214.085 188.249v22.635c0 13.754-11.19 24.944-24.945 24.944-13.754 0-24.944-11.19-24.944-24.944v-22.635c0-13.754 11.19-24.944 24.944-24.944 13.755 0 24.945 11.19 24.945 24.944zm-39.945-84.944v32.098c-23.026 6.546-39.944 27.75-39.944 52.846v22.635c0 11.541 3.584 22.257 9.688 31.108-8.846 4.096-16.627 10.112-22.789 17.526-6.162-7.415-13.944-13.431-22.79-17.526 6.103-8.851 9.688-19.567 9.688-31.108v-22.635c0-25.096-16.918-46.3-39.944-52.846v-32.098zm-121.093 60c13.754 0 24.944 11.19 24.944 24.944v22.635c0 13.754-11.19 24.944-24.944 24.944s-24.944-11.19-24.944-24.944v-22.635c0-13.754 11.19-24.944 24.944-24.944zm-15-60v32.098c-23.026 6.546-39.944 27.75-39.944 52.846v22.635c0 11.541 3.584 22.257 9.688 31.108-8.846 4.096-16.628 10.112-22.79 17.526-6.162-7.415-13.943-13.43-22.789-17.526 6.103-8.851 9.688-19.567 9.688-31.108v-22.635c0-25.096-16.918-46.3-39.944-52.846v-32.098zm-146.038 84.944c0-13.754 11.19-24.944 24.945-24.944 13.754 0 24.944 11.19 24.944 24.944v22.635c0 13.754-11.19 24.944-24.944 24.944-13.755 0-24.945-11.19-24.945-24.944zm77.991 126.056h-106.093v-13.305c0-19.299 15.701-35 35-35h36.093c19.299 0 35 15.701 35 35zm136.094 0h-106.094v-13.305c0-19.299 15.701-35 35-35h36.094c19.299 0 35 15.701 35 35zm136.093 0h-106.093v-13.305c0-19.299 15.701-35 35-35h36.093c19.299 0 35 15.701 35 35z" />
                                        </svg>
                                        Departments
                                    </a>
                        </li>
                    
                @php
                }
              
                if(in_array('members', $permissions)){
                @endphp
                <x-navigation.sidebar-item route="accounts.members" img="svgs.users">
                    Members
                </x-navigation.sidebar-item>
                @php
                }
                if(in_array('settings', $permissions)){
                @endphp
                <x-navigation.sidebar-item route="accounts.settings" img="svgs.settings">
                    Settings
                </x-navigation.sidebar-item>
                @php
                }
                if(in_array('billing', $permissions)){
                @endphp
                <x-navigation.sidebar-item route="accounts.billing" img="svgs.folder">
                    Memberships
                </x-navigation.sidebar-item>
                </ul>
                @php
                }
            }
        }
                @endphp
                {{-- <ul> --}}
                    {{-- <x-navigation.sidebar-item route="accounts.companies" img="svgs.team">
                        Companies
                    </x-navigation.sidebar-item> --}}
                    {{-- <li class="px-2 py-1">
                        <a href="{{ url('/companies') }}"
                            class="px-3 py-2 text-sm rounded-md flex items-center  text-gray-500 hover:bg-gray-100 hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    d="m13.75 24h-12c-.965 0-1.75-.785-1.75-1.75v-20.319c0-.516.226-1.002.619-1.336s.911-.477 1.418-.391l10.989 1.665c.859.143 1.474.869 1.474 1.728v19.653c0 .414-.336.75-.75.75zm-11.999-22.319c-.081 0-.135.035-.162.058-.033.029-.089.091-.089.192v20.319c0 .138.112.25.25.25h11.25v-18.903c0-.123-.088-.227-.209-.247l-10.989-1.664c-.018-.003-.035-.005-.051-.005z" />
                                <path
                                    d="m22.25 24h-8.5c-.414 0-.75-.336-.75-.75v-13.5c0-.226.102-.44.277-.582.175-.143.405-.198.626-.152l8.717 1.826c.816.181 1.38.883 1.38 1.71v9.698c0 .965-.785 1.75-1.75 1.75zm-7.75-1.5h7.75c.138 0 .25-.112.25-.25v-9.698c0-.118-.08-.218-.195-.244l-7.805-1.635z" />
                                <path
                                    d="m9.75 23.75h-4.5c-.414 0-.75-.336-.75-.75v-4.25c0-.965.785-1.75 1.75-1.75h2.5c.965 0 1.75.785 1.75 1.75v4.25c0 .414-.336.75-.75.75zm-3.75-1.5h3v-3.5c0-.138-.112-.25-.25-.25h-2.5c-.138 0-.25.112-.25.25z" />
                                <path
                                    d="m5.75 6h-1.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.5c.414 0 .75.336.75.75s-.336.75-.75.75z" />
                                <path
                                    d="m10.75 6h-1.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.5c.414 0 .75.336.75.75s-.336.75-.75.75z" />
                                <path
                                    d="m5.75 9h-1.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.5c.414 0 .75.336.75.75s-.336.75-.75.75z" />
                                <path
                                    d="m10.75 9h-1.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.5c.414 0 .75.336.75.75s-.336.75-.75.75z" />
                                <path
                                    d="m5.75 12h-1.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.5c.414 0 .75.336.75.75s-.336.75-.75.75z" />
                                <path
                                    d="m10.75 12h-1.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.5c.414 0 .75.336.75.75s-.336.75-.75.75z" />
                                <path
                                    d="m5.75 15h-1.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.5c.414 0 .75.336.75.75s-.336.75-.75.75z" />
                                <path
                                    d="m10.75 15h-1.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.5c.414 0 .75.336.75.75s-.336.75-.75.75z" />
                                <path
                                    d="m19.25 15h-1.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.5c.414 0 .75.336.75.75s-.336.75-.75.75z" />
                                <path
                                    d="m19.25 18h-1.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.5c.414 0 .75.336.75.75s-.336.75-.75.75z" />
                                <path
                                    d="m19.25 21h-1.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.5c.414 0 .75.336.75.75s-.336.75-.75.75z" />
                            </svg>
                            Companies
                        </a>
                    </li> --}}
                    {{-- <li class="px-2 py-1">
                        <a href="{{ url('/departments') }}"
                            class="px-3 py-2 text-sm rounded-md flex items-center  text-gray-500 hover:bg-gray-100 hover:text-blue-600">
                            <svg   id="Capa_1"   class="w-5 h-5 mr-2"   viewBox="0 0 512 512"   xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="m437.35 409.687c6.103-8.851 9.688-19.567 9.688-31.108v-22.635c0-25.096-16.919-46.301-39.945-52.846v-62.098h-136.093v-30h68.047v-43.305c0-26.128-15.505-48.691-37.79-59.008 6.103-8.851 9.688-19.567 9.688-31.108v-22.635c-.001-30.297-24.648-54.944-54.945-54.944s-54.944 24.647-54.944 54.944v22.635c0 11.541 3.584 22.257 9.688 31.108-22.285 10.318-37.79 32.88-37.79 59.008v43.305h68.046v30h-136.093v62.098c-23.026 6.546-39.945 27.75-39.945 52.846v22.635c0 11.541 3.584 22.257 9.688 31.108-22.285 10.318-37.789 32.88-37.789 59.008v43.305h438.279v-43.305c0-26.128-15.505-48.69-37.79-59.008zm-206.294-354.743c0-13.754 11.19-24.944 24.944-24.944s24.944 11.19 24.944 24.944v22.635c0 13.754-11.19 24.944-24.944 24.944s-24.944-11.19-24.944-24.944zm-28.103 112.751c0-19.299 15.701-35 35-35h36.094c19.299 0 35 15.701 35 35v13.305h-106.094zm214.085 188.249v22.635c0 13.754-11.19 24.944-24.945 24.944-13.754 0-24.944-11.19-24.944-24.944v-22.635c0-13.754 11.19-24.944 24.944-24.944 13.755 0 24.945 11.19 24.945 24.944zm-39.945-84.944v32.098c-23.026 6.546-39.944 27.75-39.944 52.846v22.635c0 11.541 3.584 22.257 9.688 31.108-8.846 4.096-16.627 10.112-22.789 17.526-6.162-7.415-13.944-13.431-22.79-17.526 6.103-8.851 9.688-19.567 9.688-31.108v-22.635c0-25.096-16.918-46.3-39.944-52.846v-32.098zm-121.093 60c13.754 0 24.944 11.19 24.944 24.944v22.635c0 13.754-11.19 24.944-24.944 24.944s-24.944-11.19-24.944-24.944v-22.635c0-13.754 11.19-24.944 24.944-24.944zm-15-60v32.098c-23.026 6.546-39.944 27.75-39.944 52.846v22.635c0 11.541 3.584 22.257 9.688 31.108-8.846 4.096-16.628 10.112-22.79 17.526-6.162-7.415-13.943-13.43-22.789-17.526 6.103-8.851 9.688-19.567 9.688-31.108v-22.635c0-25.096-16.918-46.3-39.944-52.846v-32.098zm-146.038 84.944c0-13.754 11.19-24.944 24.945-24.944 13.754 0 24.944 11.19 24.944 24.944v22.635c0 13.754-11.19 24.944-24.944 24.944-13.755 0-24.945-11.19-24.945-24.944zm77.991 126.056h-106.093v-13.305c0-19.299 15.701-35 35-35h36.093c19.299 0 35 15.701 35 35zm136.094 0h-106.094v-13.305c0-19.299 15.701-35 35-35h36.094c19.299 0 35 15.701 35 35zm136.093 0h-106.093v-13.305c0-19.299 15.701-35 35-35h36.093c19.299 0 35 15.701 35 35z" />
                            </svg>
                            Departments
                        </a>
                    </li> --}}
                    {{-- <x-navigation.sidebar-item route="accounts.departments" img="svgs.departments">
                        Departments
                    </x-navigation.sidebar-item> --}}
                    {{-- <x-navigation.sidebar-item route="accounts.teams" img="svgs.team">
                        Teams
                    </x-navigation.sidebar-item>

                    <x-navigation.sidebar-item route="accounts.members" img="svgs.users">
                        Members
                    </x-navigation.sidebar-item>
					
                    <x-navigation.sidebar-item route="accounts.settings" img="svgs.settings">
                        Settings
                    </x-navigation.sidebar-item>
					
					<x-navigation.sidebar-item route="accounts.billing" img="svgs.folder">
                        Membership
                    </x-navigation.sidebar-item>
                </ul> --}}
            </div>
            {{-- @endrole --}}
        </div>

        @livewire('accounts.user.user-info')
    </nav>
</aside>
 {{-- if(in_array('teams', $permissions)){
     @endphp
     <x-navigation.sidebar-item route="accounts.teams" img="svgs.team">
         Teams
     </x-navigation.sidebar-item>
     @php
    } --}}
