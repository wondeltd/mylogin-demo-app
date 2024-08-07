<x-guest-layout>
    <div class="text-center p-4" x-data="{ protocol: 'oauth' }" >

        <div class="grid w-full place-items-center pb-6">
            <div class="grid grid-cols-2 gap-2 rounded-xl bg-gray-200 p-2">
                <div>
                    <input type="radio" name="option" id="oauth" value="oauth" class="peer hidden" x-model="protocol" />
                    <label for="oauth" class="block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:bg-mylogin-green-dark peer-checked:font-bold peer-checked:text-white">OAuth</label>
                </div>

                <div>
                    <input type="radio" name="option" id="saml" value="saml" class="peer hidden" x-model="protocol" checked />
                    <label for="saml" class="block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:bg-mylogin-green-dark peer-checked:font-bold peer-checked:text-white">SAML</label>
                </div>
            </div>
        </div>

        <form @submit.prevent="submitProtocol(protocol)" class="text-center">
            <button type="submit" class="w-2/3 block mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" aria-label="Log in with MyLogin" role="button" viewBox="0 0 398 88"><title>Log in with MyLogin</title><desc>Log in with MyLogin button</desc><rect x="1" y="1" width="396" height="86" rx="12" ry="12" fill="#0f2830"/><path d="M396 75V13c0-6-5-11-11-11H227.4a177.5 177.5 0 0 0-41 84H385c6 0 11-5 11-11Z" fill="#014751"/><path d="M110.9 54H98.4V33.2h4.8v16.5h7.7V54Zm4.6-.6c-1.3-.6-2.2-1.5-2.8-2.7s-1-2.4-1-3.9.3-2.7 1-3.9 1.6-2 2.8-2.7 2.6-1 4.2-1 3 .4 4.2 1a7.4 7.4 0 0 1 3.7 6.7c0 1.5-.3 2.7-1 3.8s-1.5 2-2.7 2.7c-1.2.6-2.6 1-4.2 1s-3-.4-4.3-1Zm6.7-3.9c.6-.7.9-1.6.9-2.7s-.3-2-1-2.7-1.4-1-2.4-1-2 .3-2.5 1-1 1.6-1 2.7.4 2 1 2.7 1.4 1 2.5 1 1.8-.3 2.5-1Zm17.8-9.8h4.5V53c0 1.3-.3 2.5-.8 3.5-.4 1-1.1 1.9-2 2.5-1.2.8-2.9 1.3-5 1.3s-4-.4-5.2-1.2a6 6 0 0 1-1.9-1.7c-.4-.7-.7-1.5-.7-2.3h4.6c.1.6.4 1 1 1.4a4 4 0 0 0 2 .4c1.2 0 2-.2 2.6-.8.6-.5.8-1.4.8-2.6v-2c-.3.8-1 1.3-1.7 1.7s-1.8.7-2.7.7c-1.4 0-2.6-.3-3.7-1a6 6 0 0 1-2.3-2.5c-.6-1.2-.8-2.4-.8-3.8s.3-2.7.8-3.8a6.5 6.5 0 0 1 6-3.6c1 0 1.9.2 2.7.7a4 4 0 0 1 1.8 1.7v-2Zm-.8 9.7c.6-.7 1-1.6 1-2.7s-.4-2-1-2.6c-.6-.7-1.5-1.1-2.5-1.1s-1.8.3-2.5 1c-.6.7-.9 1.6-.9 2.7s.3 2 1 2.7c.6.7 1.4 1 2.4 1s1.9-.3 2.5-1Zm15-11.8c-.5-.5-.8-1.2-.8-2a2.7 2.7 0 0 1 2.8-2.7c.8 0 1.5.3 2 .8s.8 1.2.8 2-.3 1.4-.8 2-1.2.7-2 .7-1.5-.2-2-.8Zm4.3 16.4h-4.6V39.7h4.5V54Zm3 0V39.7h4.5v2c.4-.7 1-1.3 1.8-1.8.8-.4 1.8-.6 2.8-.6 3.5 0 5.3 2 5.3 5.8V54h-4.6v-7.4c0-1.2-.2-2-.6-2.6s-1-.9-2-.9-1.5.3-2 .9a4 4 0 0 0-.7 2.5V54h-4.5Zm31.2 0h-4.1l-5.2-14.3h4.8l2.6 8.6 2.4-8.6h4.4l2.4 8.6 2.6-8.6h4.7l-5 14.3H198l-2.7-8.7-2.7 8.7Zm16.1-16.4c-.5-.5-.7-1.2-.7-2a2.7 2.7 0 0 1 2.8-2.7c.8 0 1.4.3 2 .8s.8 1.2.8 2-.3 1.4-.8 2-1.2.7-2 .7-1.5-.2-2-.8Zm4.3 16.4h-4.5V39.7h4.5V54Zm12.1-3.6V54l-3 .2c-1.5 0-2.7-.3-3.6-1-1-.7-1.5-2.1-1.5-4.2v-5.7H215v-3.6h2.2v-4.3h4.6v4.3h3.2v3.6h-3.3V48c0 1 .2 1.6.6 2 .3.3.8.4 1.4.4h1.6Zm16.3-5.3V54h-4.6v-7.4c0-1.2-.2-2.1-.6-2.6s-1-.9-2-.9-1.5.3-2 .9a4 4 0 0 0-.7 2.5V54h-4.5V33.2h4.5v8.6c.4-.8 1-1.4 1.8-1.9.8-.4 1.8-.6 2.9-.6 3.4 0 5.2 2 5.2 5.9Zm14.1 8.9H251V33.2h5.5l5.6 14.8 5.5-14.8h5.4V54h-4.6V40.5L263.8 54h-3.5l-4.7-13.6V54Zm30.3-14.3h4.5v13.8c0 1.1-.2 2.1-.5 3a6 6 0 0 1-1.5 2.2c-.7.5-1.4 1-2.3 1.2-.8.3-1.8.5-3 .5-2.3 0-4-.5-5.3-1.5-.5-.5-1-1-1.3-1.7s-.4-1.3-.5-2h4.6c0 .6.3 1 .7 1.4.4.3 1 .5 1.8.5s1.6-.3 2-.8c.5-.6.8-1.4.8-2.4v-2.3a4 4 0 0 1-1.8 1.8 6 6 0 0 1-2.8.6 5 5 0 0 1-4-1.6A7 7 0 0 1 276 48v-8.3h4.6v7.6c0 1 .2 1.8.6 2.3s1 .8 1.9.8 1.6-.3 2-1a4 4 0 0 0 .8-2.6v-7ZM306 54h-12.5V33.2h4.9v16.5h7.7V54Zm4.6-.6a7.4 7.4 0 0 1-3.7-6.6 7.6 7.6 0 0 1 3.7-6.6 9.3 9.3 0 0 1 8.4 0c1.2.7 2.2 1.6 2.8 2.7s1 2.5 1 4-.4 2.7-1 3.8-1.6 2-2.8 2.7a9.3 9.3 0 0 1-8.4 0Zm6.7-3.9c.7-.7 1-1.6 1-2.7s-.3-2-1-2.7-1.4-1-2.4-1-2 .3-2.6 1-.9 1.6-.9 2.7.4 2 1 2.7 1.4 1 2.5 1 1.8-.3 2.4-1Zm17.8-9.8h4.6V53c0 1.3-.3 2.5-.8 3.5-.4 1-1.1 1.9-2 2.5-1.2.8-3 1.3-5.1 1.3s-3.9-.4-5.2-1.2a6 6 0 0 1-1.8-1.7c-.5-.7-.7-1.5-.7-2.3h4.6c.1.6.4 1 1 1.4a4 4 0 0 0 2 .4c1.2 0 2-.2 2.6-.8.5-.5.8-1.4.8-2.6v-2c-.3.8-1 1.3-1.8 1.7s-1.7.7-2.7.7c-1.3 0-2.5-.3-3.6-1a6 6 0 0 1-2.3-2.5 8.2 8.2 0 0 1 .1-7.6 6.5 6.5 0 0 1 6-3.6c.9 0 1.8.2 2.6.7a4 4 0 0 1 1.7 1.7v-2Zm-.7 9.7c.6-.7 1-1.6 1-2.7s-.4-2-1-2.6c-.6-.7-1.5-1.1-2.5-1.1s-1.8.3-2.5 1c-.6.7-.9 1.6-.9 2.7s.3 2 1 2.7c.6.7 1.4 1 2.4 1s1.9-.3 2.5-1Zm8.6-11.8c-.6-.5-.9-1.2-.9-2A2.7 2.7 0 0 1 345 33c.8 0 1.5.3 2 .8s.8 1.2.8 2-.2 1.4-.8 2-1.2.7-2 .7-1.5-.2-2-.8Zm4.2 16.4h-4.5V39.7h4.5V54Zm3 0V39.7h4.5v2c.5-.7 1-1.3 1.8-1.8.8-.4 1.8-.6 2.9-.6 3.4 0 5.2 2 5.2 5.8V54H360v-7.4c0-1.2-.2-2-.6-2.6s-1-.9-2-.9-1.5.3-2 .9a4 4 0 0 0-.7 2.5V54h-4.5Z" fill="#fff"/><path d="M73.1 26.4c0-.6-.2-1.3-.7-1.7-.5-.5-1.1-.8-1.8-.8h-5c-.6 0-1.2.3-1.7.8-.4.4-.7 1-.7 1.7v3.7c0 .4-.1.7-.4 1-.2.1-.5.3-.8.3h-2.5c-.4 0-.7-.2-1-.4-.1-.2-.3-.5-.3-.9v-3.7c0-.6-.2-1.3-.7-1.7-.5-.5-1.1-.8-1.7-.8h-5c-.7 0-1.3.3-1.8.8-.4.4-.7 1-.7 1.7v3.7c0 .4-.1.7-.3 1-.3.1-.6.3-1 .3h-2.4c-.3 0-.6-.2-.9-.4-.2-.2-.3-.5-.3-.9v-3.7c0-.6-.3-1.3-.8-1.7-.4-.5-1-.8-1.7-.8h-5c-.6 0-1.3.3-1.7.8-.5.4-.8 1-.8 1.7v5c0 2.5 2.5 5 2.5 7.4s-2.5 10-2.5 19.9V61c0 .7.3 1.3.8 1.8.4.5 1 .7 1.7.7h7.5a2.4 2.4 0 0 0 2.4-2.5v-5c0-6.1 2.5-9.8 7.5-9.8s7.4 3.7 7.4 9.9v5c0 .6.3 1.2.7 1.7s1.1.7 1.8.7h7.4c.7 0 1.3-.2 1.8-.7s.7-1.1.7-1.8v-2.4c0-10-2.5-17.4-2.5-19.9s2.5-5 2.5-7.4v-5Zm-7.8 14.2-1.8.6-.3.2-.1.2-.6 1.8-.2.3-.4.1-.3-.1a.6.6 0 0 1-.2-.3l-.6-1.8-.2-.2-.2-.2-1.8-.6a.6.6 0 0 1-.3-.2v-.7l.3-.2 1.8-.6.2-.1.2-.3.6-1.8.2-.3h.7l.2.3.6 1.8.1.3.3.1 1.8.6.3.2v.7l-.3.2Z" fill="#00d37f" fill-rule="evenodd"/><rect x="1" y="1" width="396" height="86" rx="12" ry="12" fill="none" stroke="#687ca1" stroke-width="2"/></svg>
            </button>
        </form>
    </div>

    <script>
        async function submitProtocol(protocol)
        {
            window.location.href = "/redirect?protocol=" + protocol;
        }
    </script>
</x-guest-layout>
