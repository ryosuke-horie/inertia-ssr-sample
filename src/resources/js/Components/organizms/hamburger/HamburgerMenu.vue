<script lang="ts" setup>
import { RouteName, RouteRoleName, getRouteLogo } from '@/Utilities';
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import HamburgerMenuWeb from './web/HamburgerMenuWeb.vue';
import HamburgerMenuBusiness from './business/HamburgerMenuBusiness.vue';
import HamburgerMenuCorporation from './corporation/HamburgerMenuCorporation.vue';
import HamburgerMenuUser from './user/HamburgerMenuUser.vue';
import HamburgerMenuGuestUser from './guestUser/HamburgerMenuGuestUser.vue';
import HamburgerMenuStaff from './staff/HamburgerMenuStaff.vue';
import HamburgerMenuAdminStaff from './adminStaff/HamburgerMenuAdminStaff.vue';

const props = defineProps<{ isAuth: boolean; role?: RouteRoleName }>();

const role = computed<RouteRoleName>(() => {
  if (!props.isAuth) return RouteRoleName.Web;

  if (props.role) return props.role;

  const path = usePage().url;
  if (path.includes('/corporation')) {
    return RouteRoleName.Corporation;
  }

  if (path.includes('/admin-staff')) {
    return RouteRoleName.AdminStaff;
  }

  if (path.includes('/business-operator')) {
    return RouteRoleName.Business;
  }

  if (path.includes('/admin')) {
    return RouteRoleName.Admin;
  }

  if (path.includes('/staff')) {
    return RouteRoleName.Staff;
  }

  if (path.includes('/user/guest')) {
    return RouteRoleName.GuestUser;
  }

  if (path.includes('/user')) {
    return RouteRoleName.User;
  }

  return 'web';
});

const emit = defineEmits(['close']);

const onClose = () => {
  emit('close');
};
</script>

<template>
  <div class="c-hamburger">
    <div class="c-hamburger__head">
      <Link :href="route(RouteName.Welcome)" class="c-hamburger__head-logo">
        <img :src="getRouteLogo(role)" alt="" />
      </Link>
      <button class="c-hamburger__head-menu" @click="onClose">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <template v-if="role === 'web'">
      <HamburgerMenuWeb />
    </template>
    <template v-else-if="role === RouteRoleName.Staff">
      <HamburgerMenuStaff />
    </template>
    <template v-else-if="role === RouteRoleName.AdminStaff">
      <HamburgerMenuAdminStaff />
    </template>
    <template v-else-if="role === RouteRoleName.GuestUser">
      <HamburgerMenuGuestUser />
    </template>
    <template v-else-if="role === RouteRoleName.User">
      <HamburgerMenuUser />
    </template>
    <template v-else-if="role === RouteRoleName.Admin">
      <HamburgerMenuStaff />
    </template>
    <template v-else-if="role === RouteRoleName.Business">
      <HamburgerMenuBusiness />
    </template>
    <template v-else-if="role === RouteRoleName.Corporation">
      <HamburgerMenuCorporation />
    </template>
    <div class="c-hamburger__copy">©cheerpay.jp</div>
  </div>
</template>

<style lang="scss">
.c-hamburger {
  width: 100%;
  background-color: var(--white);
  min-height: 100vh;
  @media screen and (min-width: 1025px) {
    position: sticky;
    top: 0;
    height: auto;
    min-height: unset;
  }
  &__head {
    @media screen and (min-width: 1025px) {
      display: none;
    }
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    &-logo {
      height: 34px;
      img {
        height: 100%;
      }
    }
    &-menu {
      margin-left: auto;
      @media screen and (min-width: 1025px) {
        display: none;
      }
    }
  }
  &__copy {
    margin-top: 12px;
    padding: 16px;
    text-align: center;
  }
}
</style>
