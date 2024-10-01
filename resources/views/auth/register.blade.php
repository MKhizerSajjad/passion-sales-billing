<x-guest-layout>
    
    <div class="wrapper">
        <section class="login-content">
           <div class="container h-100">
              <div class="row align-items-center justify-content-center h-100">
                 <div class="col-12">
                    <div class="row align-items-center">
                       <div class="col-lg-6 ">
                          <h2 class="mb-2">Sign Up</h2>
                          <p>Enter your personal details and start journey with us.</p>
                          <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="floating-label form-group">
                                        <x-text-input id="first_name" class="floating-input form-control" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
                                        <x-input-label for="first_name" :value="__('First Name')" />
                                        <x-input-error :messages="$errors->get('first_name')" class="text text-danger small"/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="floating-label form-group">
                                        <x-text-input id="last_name" class="floating-input form-control" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" />
                                        <x-input-label for="last_name" :value="__('Last Name')" />
                                        <x-input-error :messages="$errors->get('last_name')" class="text text-danger small"/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="floating-label form-group">
                                        <x-text-input id="email" class="floating-input form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                                        <x-input-label for="email" :value="__('Email')" />
                                        <x-input-error :messages="$errors->get('email')" class="text text-danger small"/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="floating-label form-group">
                                        <x-text-input id="mobile_number" class="floating-input form-control" type="tel" name="mobile_number" :value="old('mobile_number')" required autocomplete="username" />
                                        <x-input-label for="mobile_number" :value="__('Mobile')" />
                                        <x-input-error :messages="$errors->get('mobile_number')" class="text text-danger small"/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="floating-label form-group">
                                        <x-text-input id="password" class="floating-input form-control" type="password" name="password" required autocomplete="new-password" />
                                        <x-input-label for="password" :value="__('Password')" />
                                        <x-input-error :messages="$errors->get('password')" class="text text-danger small"/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="floating-label form-group">
                                        <x-text-input id="password_confirmation" class="floating-input form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="text text-danger small"/>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">I agree with the terms of use</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Sign Up</button>
                            <p class="mt-3">
                                Already have an Account <a href="{{route('login')}}" class="text-primary">Sign In</a>
                            </p>
                          </form>
                       </div>
                       <div class="col-lg-6 mb-lg-0 mb-4 mt-lg-0 mt-4">
                          <img src="{{ asset('admin/images/login/01.png') }}" class="img-fluid w-80" alt="Floating image">
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </section>
    </div>
    {{-- <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- First Name -->
        <div>
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>
        
        <!-- Last Name -->
        <div>
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form> --}}
</x-guest-layout>
