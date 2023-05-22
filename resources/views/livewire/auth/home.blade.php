<div class="px-0 py-0"> 
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="shortcut icon" href="{{ url(asset('neostaff-icon.png')) }}">
    <section class="vh-100 section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4 text-black" style="padding-left:0px;">
                    <div class="pt-4">
                        <span><strong>You've been logged out.</strong></span>
                    </div>
                    <div class="pb-2" >               
                        <span>Don't worry, you can log back in below</span>
                    </div>
                    <hr>
                    <div class="d-flex contenedor align-items-center mt-4 ms-xl-5 lg-4 ">
                        <form wire:submit.prevent="authenticate" style="width: 23rem;">
                            <x-inputs.text
                                wire:model.lazy="email"
                                label="Email address"
                                name="email"
                                type="email"
                                placeholder="kirk@enterprise.com"
                             />
                            <x-inputs.text
                                wire:model.lazy="password"
                                label="Password"
                                name="password"
                                type="password"
                            />    
                            <span class="block w-full rounded-md shadow-sm">
                                <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring-blue active:bg-blue-700 transition duration-150 ease-in-out">
                                    Sign in
                                </button>
                            </span>
                            <div class="w-full mt-3">
                                <i class="fa fa-lock"></i>
                                <label for="vehicle1"><small>Subject to HIPAA? See our <b><a  class="text-blue-600" target="_blank" href="/hipaa">BBA</a></b></small></label>
                                </div>
                            <p class="small mb-5 mt-2 pb-lg-2 text-center"><a  style="color:#436E9E;" class="text-muted" href="{{ route('password.request') }}">Forgot password?</a></p>
                                <hr class="hr-1">
                            <p class="text-center" >Need an account?<a href="{{ route('register') }}" class="link-info" style="color:#436E9E;"> Join to NeoStaff</a></p>
                            <div class="pt-4 img-logo pb-4">
                                <img src="{{ url(asset('images/logo/neostaff-logo.png')) }}" alt="NeoStaff">
                            </div>
                        </form>
                    </div>
                    <div class="responsive-section" style="margin:0px -5px; padding-right: 0px !important; padding-left: 0px !important; color: white !important; background-color:#436E9E;"> 
                        <div class=" text-center px-2">
                            <h2 class="pb-4 pt-2 text-center">Have you heard about NeoStaff?</h2>
                            The Agile, visual project management tool is changing
                            the way teams work. <br>Collaborate better and get more
                            done with focused sprints and detailed project boards.
                        </div> 
                        <img class="img-banner" style="" src="banner-image.png" width="200px" height="">
                    </div>
                </div>
                <div class="img-neo col-sm-8 px-0 d-none d-sm-block h-100 vh-100" style="background-color:#436E9E;"> 
                    <div class="px-5 pt-5">
                        <h1 class="pb-4">Have you heard about NeoStaff?</h1>
                        The Agile, visual project management tool is changing<br>
                        the way teams work. Collaborate better and get more<br>
                        done with focused sprints and detailed project boards.
                    </div> 
                    <img class="img-banner" style="padding-top:17px;" src="banner-image.png" width="600px" height="">
                </div>
            </div>
        </div>
    </section>
    <style>
        @media (min-width:426px){
            .responsive-section{
                display:none;
            }
        }
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
            margin-top:-3em !important;
            margin-bottom:-3em !important;
            margin-left: -1em !important; 
            margin-right: -2.4em !important; 
            position: relative;
            font-family: Roboto,ui-sans-serif,system-ui,-apple- !important;
        }
        html, body{
            overflow-x: hidden !important;
        }
        .img-logo{
            display:flex;
            justify-content: center;
            align-items: center;
        }
        @media (max-width:768px) { 
            .img-banner{
                width: 420px !important;
                padding-top: 28px !important;
            }
        }
        @media (min-width: 769px) and  (max-width:1023px) { 
            .img-banner{
                width: 620px !important;
                padding-top: 60px !important;
            }
        }
        @media (min-width: 1024px){ 
            .img-banner{
                width: 620px !important;
                padding-top: 106px !important;
            }
        }
        @media (min-width: 1440px){ 
            .img-banner{
                width: 900px !important;
                padding-top: 13px !important;
            }
        }
        @media (max-width: 375px){ 
            .contenedor{
                margin-right: 1px !important; 
                margin-left: 2px !important;
                padding-right: 12px !important;
            }
        }
        @media (min-width: 425px) and (max-width: 760px){ 
            .contenedor{
                margin-left: 20px !important; 
            }
        }
        margin-left: 20px;
    </style>
<div>