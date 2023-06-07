<template>
  <div class="flex grow overflow-y-auto">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">
      <div class="flex h-16 shrink-0 items-center">
        <img class="h-8 w-auto" src="/assets/images/logo.png" alt="logo"/>
      </div>
      <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
          <li>
            <ul role="list" class="-mx-2 space-y-1">
              <li v-for="item in appStore.navigation" :key="item.name + item.current">
                <Link v-if="!item.children" :href="item.href"
                   :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block rounded-md py-2 pr-2 pl-10 text-sm leading-6 font-semibold text-gray-700']">
                  {{ item.name }}
                </Link>
                <Disclosure as="div" v-else v-slot="{ open }" :defaultOpen="item.current" static>
                  <DisclosureButton
                      :class="[open ? 'bg-gray-50' : 'hover:bg-gray-50', 'flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-700']">
                    <ChevronRightIcon :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'h-5 w-5 shrink-0']"
                                      aria-hidden="true"/>
                    {{ item.name }}
                  </DisclosureButton>
                  <DisclosurePanel as="ul" class="mt-1 px-2">
                    <li v-for="subItem in item.children" :key="subItem.name">
                      <Link :href="subItem.href">
                        <DisclosureButton as="div"
                                          :class="[subItem.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700']">
                          {{ subItem.name }}
                      </DisclosureButton>
                      </Link>
                    </li>
                  </DisclosurePanel>
                </Disclosure>
              </li>
            </ul>
          </li>
          <li class="-mx-6 mt-auto">
            <Link href="#"
               class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-50">
              <img class="h-8 w-8 rounded-full bg-gray-50"
                   src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                   alt=""/>
              <span class="sr-only">Your profile</span>
              <span aria-hidden="true">Tom Cook</span>
            </Link>
          </li>
        </ul>
      </nav>
    </div>

  </div>
</template>

<script setup>
import {useAppStore} from "../stores/app.js";
var appStore = useAppStore();

import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue'
import {ChevronRightIcon} from '@heroicons/vue/20/solid'
import {
  CalendarIcon,
  FolderIcon,
  HomeIcon,
  UsersIcon,
} from '@heroicons/vue/24/outline'
import {Link} from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3'

router.on('navigate', (event) => {
  appStore.navigation = [
    {name: 'Dashboard', href: '/', icon: HomeIcon, current: event.detail.page.url === '/'},
    {
      name: 'Campaigns',
      icon: UsersIcon,
      current: event.detail.page.url.startsWith('/campaigns'),
      href: '/campaigns',
      children: [
        {name: 'Campaigns', href: '/campaigns', current: event.detail.page.url.startsWith('/campaigns')},
        {name: 'Plans', href: '#'},
        {name: 'Offers', href: '#' },
        {name: 'Texts', href: '#' },
      ],
    },
    {
      name: 'Audience',
      icon: FolderIcon,
      current: false,
      children: [
        {name: 'Contacts', href: '#'},
        {name: 'Upload', href: '#'},
      ],
    },
    {name: 'Routes', href: '#', icon: CalendarIcon, current: event.detail.page.url.startsWith('/sms/routing/companies'), children: [
        {name: 'Companies', href: '/sms/routing/companies', current: event.detail.page.url.startsWith('/sms/routing/companies')},
        {name: 'Routes', href: '#'},
        {name: 'Rates', href: '#'},
        {name: 'Reports', href: '#'},
      ]}
  ]
});
</script>