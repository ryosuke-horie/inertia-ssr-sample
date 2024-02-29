<script lang="ts" setup>
import { RouteName } from '@/Utilities';
import { HamburgerMenuProps } from './type';
import { Link, usePage, router } from '@inertiajs/vue3';

const props = defineProps<HamburgerMenuProps>();

const emit = defineEmits(['click']);

const onClick = () => {
  emit('click');
};

// ユーザーロールを決定する関数
const determineUserRoleFromURL = () => {
  const path = usePage().url;
  const domain = window.location.hostname;
  const url = domain + path;

  if (url.includes(domain + '/corporation')) {
    return 'corporation';
  }

  if (url.includes(domain + '/admin-staff')) {
    return 'admin-staff';
  }

  if (url.includes(domain + '/business-operator')) {
    return 'business-operator';
  }

  if (url.includes(domain + '/admin')) {
    return 'admin';
  }

  if (url.includes(domain + '/staff')) {
    return 'staff';
  }

  if (url.includes(domain + '/user/guest')) {
    return 'guest';
  }

  return 'user';
};

const logout = () => {
  const url = determineUserRoleFromURL();
  switch (url) {
    case 'corporation':
      router.post(route('corporation.logout'));
      break;
    case 'admin-staff':
      router.post(route('admin-staff.logout'));
      break;
    case 'user':
      router.post(route('user.logout'));
      break;
    case 'guest':
      router.post(route('user.guest.logout'));
      break;
    case 'staff':
      router.post(route('staff.logout'));
      break;
    case 'business-operator':
      router.post(route('business-operator.logout'));
      break;
    case 'admin':
      router.post(route('admin.logout'));
      break;
  }
};
</script>
<template>
  <div class="c-hamburger-menu" :class="{ 'is-active': props.isOpen }">
    <div class="c-hamburger-menu__head">
      <button class="c-hamburger-menu__head-close" @click="onClick">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <ul class="c-hamburger-menu__content">
      <li><Link :href="route(RouteName.StaffMyPage)">ダッシュボード</Link></li>
      <li><Link :href="route(RouteName.StaffMyPage)">ダッシュボード</Link></li>
      <li><Link :href="route(RouteName.StaffMyPage)">ダッシュボード</Link></li>
      <li><Link :href="route(RouteName.StaffMyPage)">ダッシュボード</Link></li>
      <li><Link :href="route(RouteName.StaffMyPage)">ダッシュボード</Link></li>
      <li><Link :href="route(RouteName.StaffMyPage)">ダッシュボード</Link></li>
      <li><button @click="logout">ログアウト</button></li>
    </ul>
  </div>
</template>

<style lang="scss">
.c-hamburger-menu {
  position: fixed;
  z-index: 5;
  top: 0;
  left: -100%;
  bottom: 0;
  width: 100%;
  background-color: var(--white);
  transition: 0.4s;
  &.is-active {
    left: 0;
  }
  &__head {
    display: flex;
    height: 50px;
    padding: 0 16px;
    &-close {
      margin-left: auto;
    }
  }
  &__content {
    display: flex;
    flex-direction: column;
    li {
      a {
        display: flex;
        padding: 16px;
      }
      & + li {
        border-top: solid 1px #dddddd;
      }
    }
  }
}
</style>
