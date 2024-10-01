<x-guest-layout>
    <!-- loader -->
    {{-- <div id="loading">
        <div id="loading-center">
        </div>
    </div> --}}
    
    <div class="wrapper">
        <section class="login-content">
            <div class="container h-100">
                <div class="row align-items-center justify-content-center h-100">
                <!-- Session Status -->
                {{-- <x-auth-session-status class="alert alert-success" :status="session('status')" /> --}}
                <div class="col-12">
                    <div class="row align-items-center">
                    <div class="col-lg-6">
                        @if(session('status'))
                            <img src="{{ asset('admin/images/login/mail.png') }}" class="img-fluid" width="80" alt="">
                            <h2 class="mt-3 mb-0">Success !</h2>
                            <p class="cnf-mail mb-1">
                                {{ __('A email has been send to you. Please check for an
                                email from company and click
                                on the included link to reset your password.')}}
                            </p>
                            <div class="d-inline-block w-100">
                            <a class="btn btn-primary d-inline-flex align-items-center mt-3" href="index.html"><i class="ri-home-4-line rtl-ml-1"></i>Back to Home</a>
                            </div>
                        @else
                            <h2 class="mb-2">{{ __('Reset Password') }}</h2>
                            {{-- {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }} --}}
                            <p>
                                {{ __('Enter your email address and we\'ll send you an email with instructions to reset your password') }}
                            </p>
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="floating-label form-group">
                                            <x-text-input id="email" class="floating-input form-control" type="email" name="email" :value="old('email')" required autofocus />
                                            <x-input-label for="email" :value="__('Email')" />
                                            <x-input-error :messages="$errors->get('email')" class="text text-danger mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">{{ __('Reset') }}</button>
                            </form>
                        @endif
                    </div>
                    <div class="col-lg-6 rmb-30">
                        <img src="{{ asset('admin/images/login/01.png') }}" class="img-fluid w-80" alt="">
                    </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
</x-guest-layout>
