<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { RouteName } from '@/Utilities';
import { setSessionStorageBase64, getSessionStorageBase64 } from '@/Utilities/storage';
import Link from '@/Components/atom/link/Link/Link.vue';
import Logo from '@/../assets/images/logo.png';

const currentRoute = ref('');
const subMenusVisible = ref<{ [key: string]: boolean }>({
  corporation: false,
  businessOperator: false,
  tipUser: false,
  ranking: false,
});

onMounted(() => {
  currentRoute.value = usePage().url;

  // クライアントサイドでのみセッションストレージから状態を読み込む
  subMenusVisible.value.corporation = getSessionStorageBase64('corporation') === 'true';
  subMenusVisible.value.businessOperator = getSessionStorageBase64('businessOperator') === 'true';
  subMenusVisible.value.tipUser = getSessionStorageBase64('tipUser') === 'true';
  subMenusVisible.value.ranking = getSessionStorageBase64('ranking') === 'true';
});

const isActive = (routeName: string) => {
  return currentRoute.value === routeName;
};

const toggleSubMenu = (menuKey: string) => {
  subMenusVisible.value[menuKey] = !subMenusVisible.value[menuKey];
  if (typeof window !== 'undefined') {
    // クライアントサイドでのみセッションストレージへの状態の保存を実行
    setSessionStorageBase64(menuKey, subMenusVisible.value[menuKey].toString());
  }
};

const logout = () => {
  router.post(route('mits-admin.logout'));
};
</script>

<template>
  <div class="c-sidebar">
    <nav class="c-sidebar__nav">
      <div class="c-sidebar__nav-header">
        <img :src="Logo" alt="チアペイロゴ" />
      </div>
      <div class="c-sidebar__nav-main">
        <ul class="c-sidebar__nav-main-list">
          <li class="c-sidebar__nav-main-item">
            <Link
              :href="RouteName.AdminDashboard"
              class="c-sidebar__nav-main-item-link"
              :class="{ 'is-active': isActive('/mits-admin/dashboard') }"
              text="ダッシュボード"
            />
          </li>
          <li class="c-sidebar__nav-main-item">
            <div
              @click="toggleSubMenu('corporation')"
              class="c-sidebar__nav-main-submenu"
              :class="{ 'is-open': subMenusVisible.corporation }"
            >
              法人管理
            </div>
            <ul v-if="subMenusVisible.corporation" class="c-sidebar__nav-main-submenu-list">
              <li class="c-sidebar__nav-main-submenu-item">
                <Link
                  :href="RouteName.AdminCorporation"
                  :class="{ 'is-active': isActive('/mits-admin/corporation') }"
                  text="法人一覧"
                />
              </li>
              <li class="c-sidebar__nav-main-submenu-item">
                <Link
                  :href="RouteName.AdminCorporationCreate"
                  :class="{ 'is-active': isActive('/mits-admin/corporation/create') }"
                  text="法人登録"
                />
              </li>
            </ul>
          </li>
          <li class="c-sidebar__nav-main-item">
            <div
              @click="toggleSubMenu('businessOperator')"
              class="c-sidebar__nav-main-submenu"
              :class="{ 'is-open': subMenusVisible.businessOperator }"
            >
              事業者管理
            </div>
            <ul v-if="subMenusVisible.businessOperator" class="c-sidebar__nav-main-submenu-list">
              <li class="c-sidebar__nav-main-submenu-item">
                <Link
                  :href="RouteName.AdminBusinessOperator"
                  :class="{ 'is-active': isActive('/mits-admin/business-operator') }"
                  text="事業者一覧"
                />
              </li>
              <li class="c-sidebar__nav-main-submenu-item">
                <Link
                  :href="RouteName.AdminBusinessOperatorCreate"
                  :class="{ 'is-active': isActive('/mits-admin/business-operator/create') }"
                  text="事業者登録"
                />
              </li>
            </ul>
          </li>
          <li class="c-sidebar__nav-main-item">
            <div
              @click="toggleSubMenu('tipUser')"
              class="c-sidebar__nav-main-submenu"
              :class="{ 'is-open': subMenusVisible.tipUser }"
            >
              投げ銭ユーザー管理
            </div>
            <ul v-if="subMenusVisible.tipUser" class="c-sidebar__nav-main-submenu-list">
              <li class="c-sidebar__nav-main-submenu-item">
                <Link
                  :href="RouteName.AdminBusinessOperator"
                  :class="{ 'is-active': isActive('/mits-admin/business-operator') }"
                  text="投げ銭ユーザー一覧"
                />
              </li>
            </ul>
          </li>
          <li class="c-sidebar__nav-main-item">
            <div
              @click="toggleSubMenu('ranking')"
              class="c-sidebar__nav-main-submenu"
              :class="{ 'is-open': subMenusVisible.ranking }"
            >
              ランキング管理
            </div>
            <ul v-if="subMenusVisible.ranking" class="c-sidebar__nav-main-submenu-list">
              <li class="c-sidebar__nav-main-submenu-item">
                <Link
                  :href="RouteName.AdminStaffRanking"
                  :class="{ 'is-active': isActive('/mits-admin/staff-ranking') }"
                  text="スタッフランキング"
                />
              </li>
              <li class="c-sidebar__nav-main-submenu-item">
                <Link
                  :href="RouteName.AdminUserRanking"
                  :class="{ 'is-active': isActive('/mits-admin/user-ranking') }"
                  text="投げ銭ユーザーランキング"
                />
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="c-sidebar__nav-logout">
        <button @click="logout">ログアウト</button>
      </div>
    </nav>
  </div>
</template>

<style lang="scss" scoped>
.c-sidebar {
  &__nav {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    box-shadow: 4px 0 10px #e4e7ed;
    &-header {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 80px;
      img {
        width: 50%;
      }
    }
    &-main {
      width: 250px;
      padding: 20px 0;
      &-item {
        padding: 8px 0;
        font-size: 14px;
        font-weight: bold;
        a {
          color: #383d42;
          margin-left: 28px;
        }
        a.is-active {
          color: #9e9e9e;
        }
      }
      &-submenu {
        color: #383d42;
        margin-left: 28px;
        cursor: pointer;
        &-list {
          background: #f6f7f9;
          padding: 16px 0;
          display: flex;
          flex-direction: column;
          gap: 10px;
          font-size: 13px;
        }
        &-item {
          padding-left: 14px;
        }
        &::before {
          content: '＞';
          position: absolute;
          right: 20px;
        }
        &.is-open::before {
          content: 'ー';
        }
      }
    }
    &-logout {
      position: absolute;
      bottom: 40px;
      left: 80px;
    }
  }
}
</style>
