
<div class="p-6 bg-white rounded-xl border border-gray-300">
    <h1 class="font-semibold text-2xl">bug1</h1>
    I took this tabs from the vristo template /dev/templates/vristo. The vriso template uses in tailwind.css.config a color array and
    filament uses a string.. for some reason when i copy paste from template to here the primary doesn't work.

    You can see i changed the Profile tab to primary and it's not blue for some reason.
</div>

<div class="mb-5" x-data="{ tab: 'home' }">
    <div>
        <ul class="flex flex-wrap mt-3 mb-5 border-b border-white-light dark:border-[#191e3a]">
            <li>
                <a href="javascript:;"
                   class="p-5 py-3 -mb-[1px] flex items-center hover:border-b border-transparent hover:!border-secondary hover:text-secondary"
                   :class="{ 'border-b !border-secondary text-secondary': tab === 'home' }"
                   @click="tab = 'home'">

                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <path opacity="0.5"
                              d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                              stroke="currentColor" stroke-width="1.5" />
                        <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round" />
                    </svg>
                    Home</a>
            </li>
            <li>
                <a href="javascript:;"
                   class="p-5 py-3 -mb-[1px] flex items-center hover:border-b border-transparent hover:!border-primary hover:text-primary"
                   :class="{ 'border-b !border-secondary text-secondary': tab === 'profile' }"
                   @click="tab = 'profile'">

                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <circle cx="12" cy="6" r="4"
                                stroke="currentColor" stroke-width="1.5" />
                        <ellipse opacity="0.5" cx="12" cy="17" rx="7"
                                 ry="4" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                    Profile</a>
            </li>
            <li>
                <a href="javascript:;"
                   class="p-5 py-3 -mb-[1px] flex items-center hover:border-b border-transparent hover:!border-secondary hover:text-secondary"
                   :class="{ 'border-b !border-secondary text-secondary': tab === 'contact' }"
                   @click="tab='contact'">

                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <path
                                d="M16.1007 13.359L16.5562 12.9062C17.1858 12.2801 18.1672 12.1515 18.9728 12.5894L20.8833 13.628C22.1102 14.2949 22.3806 15.9295 21.4217 16.883L20.0011 18.2954C19.6399 18.6546 19.1917 18.9171 18.6763 18.9651M4.00289 5.74561C3.96765 5.12559 4.25823 4.56668 4.69185 4.13552L6.26145 2.57483C7.13596 1.70529 8.61028 1.83992 9.37326 2.85908L10.6342 4.54348C11.2507 5.36691 11.1841 6.49484 10.4775 7.19738L10.1907 7.48257"
                                stroke="currentColor" stroke-width="1.5" />
                        <path opacity="0.5"
                              d="M18.6763 18.9651C17.0469 19.117 13.0622 18.9492 8.8154 14.7266C4.81076 10.7447 4.09308 7.33182 4.00293 5.74561"
                              stroke="currentColor" stroke-width="1.5" />
                        <path opacity="0.5"
                              d="M16.1007 13.3589C16.1007 13.3589 15.0181 14.4353 12.0631 11.4971C9.10807 8.55886 10.1907 7.48242 10.1907 7.48242"
                              stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    Contact</a>
            </li>
        </ul>
    </div>
    <div class="flex-1 text-sm ">
        <template x-if="tab === 'home'">
            <div>
                <h4 class="font-semibold text-2xl mb-4">We move your world!</h4>
                <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                    eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                    veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. </p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                    nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>
            </div>
        </template>
        <template x-if="tab === 'profile'">
            <div>
                <div class="flex items-start">
                    <div class="w-20 h-20 ltr:mr-4 rtl:ml-4 flex-none">
                        <img src="/assets/images/profile-34.jpeg"
                             alt="image"
                             class="w-20 h-20 m-0 rounded-full ring-2 ring-[#ebedf2] dark:ring-white-dark object-cover" />
                    </div>
                    <div class="flex-auto">
                        <h5 class="text-xl font-medium mb-4">Media heading</h5>
                        <p class="text-white-dark">Cras sit amet nibh libero, in gravida nulla.
                            Nulla vel metus scelerisque ante sollicitudin. Cras purus odio,
                            vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum
                            nunc ac nisi vulputate fringilla. Donec lacinia congue felis in
                            faucibus.</p>
                    </div>
                </div>
            </div>
        </template>
        <template x-if="tab === 'contact'">
            <div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                    nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                    culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
        </template>
    </div>
</div>
