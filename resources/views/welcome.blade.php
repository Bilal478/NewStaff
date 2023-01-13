
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> 
    <!--
    <nav class="w-full py-6">
                    <a
                        href="{{ route('register') }}"
                        class="inline-block text-sm border-2 bg-blue-600 text-white border-blue-600 px-6 py-2 rounded-lg hover:border-blue-500 hover:bg-blue-500 hover:text-white transition ease-in-out duration-200"
                    >
                        Sign up
                    </a>

                    <a
                        href="{{ route('login') }}"
                        class="inline-block text-sm border-2 text-blue-600 border-blue-600 px-6 py-2 rounded-lg hover:bg-blue-500 hover:border-blue-500 hover:text-white transition ease-in-out duration-200"
                    >
                        Log in
                    </a>
                    <a
                        href="{{ route('admin.login') }}"
                        class="inline-block text-sm border-2 text-blue-600 border-blue-600 px-6 py-2 rounded-lg hover:bg-blue-500 hover:border-blue-500 hover:text-white transition ease-in-out duration-200"
                    >
                        Admin Log in
                    </a>
                    {{--
                    <a
                        href="{{ route('admin.login') }}"
                        class="inline-block text-sm border-2 text-blue-600 border-blue-600 px-6 py-2 rounded-lg hover:bg-blue-500 hover:text-white transition ease-in-out duration-200"
                    >
                        Admin
                    </a>
                    --}}
                </div>
            </div>
        </nav>
        -->
<link rel="shortcut icon" href="{{ url(asset('neostaff-icon.png')) }}">
<title>NeoStaff - Home</title>
<section class="vh-100 section">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-4 text-black">
    
        <div class="pt-4">
            <span><strong>You've been logged out.</strong></span>
        </div>
        <div class="pb-2" >               
            <span>Don't worry, you can log back in below</span>
        </div>
        <hr>
        <div class="d-flex align-items-center mt-4 ms-xl-4 ">

          <form wire:submit.prevent="authenticate" style="width: 23rem;">
                        
            <div class="form-outline mb-4">
                <label  class="block text-sm text-gray-500 leading-5 form-label" for="form2Example18">Email
                    <span style="color: red;" class="text-xs">*</span>
                </label>
                <input type="email" id="form2Example18" class="form-control form-control-lg" />
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example28">Password
                    <span style="color: red;" class="text-xs">*</span>
                </label>
                <input type="password" id="form2Example28" class="form-control form-control-lg" />
            </div>
            <div class="pt-1 mb-4 text-center">
              <button class="btn btn-lg btn-block btn-login" style="background-color:#0284C7; width:100%;  color:white;" type="button">LOGIN</button>
            </div>
            <p class="small mb-5 pb-lg-2 text-center"><a  style="color:#436E9E;" class="text-muted" href="{{ route('password.request') }}">Forgot password?</a></p>
                <hr class="hr-1">
            <p class="text-center" >Need an account?<a href="{{ route('register') }}" class="link-info" style="color:#436E9E;"> Join to NeoStaff</a></p>
            
            <div class="pt-5 text-center">
                <img src="{{ url(asset('images/logo/neostaff-logo.png')) }}" alt="NeoStaff">
            </div>
        </form>
        </div>
      </div>
        <div class="img-neo col-sm-8 px-0 d-none d-sm-block h-100 vh-100" style="background-color:#436E9E;"> 
            <div class="px-5 pt-5">
                <h1 class="pb-4">Have you heard about NeoStaff?</h1>
                The Agile, visual project management tool is changing<br>
            the way teams work. Collaborate better and get more<br>
            done with focused sprints and detailed project boards.
            </div> 
            <img style="padding-top:20px;" src="banner-image.png" width="600px" height="">
        </div>
    </div>
    </div>
  </div>
</section>
<style>
    .img-neo{
        color: white;
    }
    .btn-login:hover{
        background-color:#0EA5E9 !important;
    }
    .hr-1 {
        margin: 0px 35px;
        margin-bottom: 15px;
        width: 80%;
        background-color: black;
    }
    body{
        font-family: Roboto,ui-sans-serif,system-ui,-apple- !important;
        overflow-y:hidden;
    }
</style>