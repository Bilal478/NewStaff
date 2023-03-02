<?php

namespace App\Http\Livewire\Admin;

use App\Models\Account;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Traits\Notifications;
class Dashboard extends Component
{
    use WithPagination, Notifications;
    public $search = '';
    public $accountStorage = [];
    protected $listeners = [
        'accountEdit' => 'accountEdit',
        'accountsRefresh' => '$refresh',
    ];
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];
    public function accountEdit($id)
    {
        $this->emit('updateAccount',$id);
    }
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'accounts' => $this->getAccounts()
        ])->layout('layouts.admin', ['title' => 'Admin Dashboard']);
    }

    public function getAccounts()
    {

        $accounts = Account::query()->where('name', 'like', '%' . $this->search . '%')->orwhere(function($query){
            return $query->whereHas('users',function($query){
                 $query->where('firstname','like','%'.$this->search.'%');
                 $query->orwhere('lastname','like','%'.$this->search.'%');
                 $query->orwhere('email','like','%'.$this->search.'%');
            });
        })->withCount(['users', 'projects', 'tasks', 'activities'])
            ->orderBy('name') ->with('users')
            ->paginate(24, ['*'], 'taskPage');
            
            foreach($accounts->items() as $index=>$item){
                
                $userStorage = 0;
                if(isset($item->users)){
                    foreach($item->users as $user){
                       
                        $folder_path = storage_path().'\app\screenshots\\'.$user->id;
                        // dd($folder_path);
                        if(File::isDirectory($folder_path)){
                            $userStorage = $userStorage + $this->folderSize($folder_path);
                        }
                    }
                }
                $this->accountStorage[$index] = $this->convert_filesize($userStorage);
            }
            return $accounts;
    }
    function convert_filesize($bytes, $decimals = 2){
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .' '. @$size[$factor];
    }

    public function folderSize($dir){
        $total_size = 0;
        $count = 0;
        $dir_array = scandir($dir);
            foreach($dir_array as $key=>$filename){
                if($filename!=".." && $filename!="."){
                    if(is_dir($dir."/".$filename)){
                        $new_foldersize = $this->foldersize($dir."/".$filename);
                        $total_size = $total_size+ $new_foldersize;
                    }else if(is_file($dir."/".$filename)){
                        $total_size = $total_size + filesize($dir."/".$filename);
                        $count++;
                    }
                }
            }
        return $total_size;
    }

    public function delete_company($id)
    {

        $account = Account::with(['users', 'projects', 'tasks', 'activities','screenshots'])->where('id',$id)->first();
        $account->projects->each->delete();
        $account->tasks->each->delete();
        $account->activities->each->delete();
        $account->delete();
        foreach($account->screenshots as $screenshot){
            
            if(File::isFile("C:/wamp64/www/neostaff/storage/app/screenshots/".$screenshot->path)){
                    (unlink("C:/wamp64/www/neostaff/storage/app/screenshots/".$screenshot->path));
                }   
        }
        
        $this->toast('Account Deleted.');
        $this->reset();
        $this->emit('accountsRefresh');
        
    }
}
