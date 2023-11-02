<div>
    <div>
        <x-page.title svg="svgs.user">
            Active Members
        </x-page.title>
    </div>
    <div class="md:flex items-center justify-between pb-8">
        <div class="md:flex items-center md:space-x-4">
            <x-inputs.search wire:model.debounce.500ms="search" class="w-full md:w-72" placeholder=""  />
        </div> 
    </div>
    <table>
        <thead>
            <tr class="text-gray-400 text-xs" style="font-size: 0.85rem;">
                <th>Companies</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Registration Date</th>
            </tr>
            <tr style="background-color: transparent;">
                <th style="height: 10px;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($userData as $data)
            @if($data['user_data'])
            <tr style="font-size:13px;" class="bg-white border border-gray-200 text-gray-500 ">
                <td style="padding: 10px;">
                    @if ($data['accounts_data']->isNotEmpty())
                        @foreach($data['accounts_data'] as $index => $account) 
                            {{ $account->name }}
                            @if ($index < $data['accounts_data']->count() - 1)
                                ,
                            @endif
                        @endforeach
                    @else
                        No Company
                    @endif
                </td>
                <td style="padding: 10px;">{{ $data['user_data']->firstname }} {{ $data['user_data']->lastname }}</td>
                <td style="padding: 10px;">{{ $data['user_data']->email }}</td>
                <td style="padding: 10px;">{{ $data['user_data']->created_at }}</td>
            </tr>

            <tr style="background-color: transparent;">
                <td style="height: 10px;"></td>
            </tr>
            @endif
        
            @endforeach
        </tbody>
    </table>
    <div class="pt-5">
        {{ $activeMembers->links() }}
    </div> 
</div>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        border: none;
    }

    th, td {
        padding: 8px;
        text-align: center;
        border: none;
    }

    @media screen and (max-width: 600px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        tr { border: 1px solid #ccc; }

        td {
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
        }

        td:before {
            position: absolute;
            top: 6px;
            left: 6px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
        }
        td:nth-of-type(1):before { content: "Companies"; }
        td:nth-of-type(2):before { content: "User Name"; }
        td:nth-of-type(3):before { content: "Email"; }
        td:nth-of-type(4):before { content: "Registration Date"; }
    }
    
</style>
