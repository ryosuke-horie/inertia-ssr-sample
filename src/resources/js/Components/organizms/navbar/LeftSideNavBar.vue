<script lang="ts">
import { defineComponent } from 'vue';
export default defineComponent({
  // Add component logic as needed
});
</script>

<template>
  <div class="sidenav">
    <p class="nav-header">MITS</p>
    <a href="#" :hrefId="section1">事業者管理</a>
    <a href="#" :hrefId="section2">ユーザー管理</a>
    <a href="#" :hrefId="section3">未使用残高確認</a>
    <a href="#" :hrefId="section4">MITS管理者管理</a>
    <a href="#" :hrefId="section5"
      ><div class="text-pointer"><span> 投げ銭関連</span> <span> ▶</span></div></a
    >
    <a href="#" :hrefId="section6"
      ><div class="text-pointer"><span>申し込み管理</span> <span> ▶</span></div></a
    >
    <a href="#" :hrefId="section7"
      ><div class="text-pointer"><span>振込関連 </span><span> ▶</span></div></a
    >
    <a href="#"
      ><div class="text-pointer"><span>お知らせ管理 </span><span> ▶</span></div></a
    >
    <a href="#">お問い合わせ管理 </a>
    <a href="#">ランキング管理 </a>
    <a href="#">退会管理 </a>
  </div>
</template>

<style lang="scss">
body {
  font-family: 'Lato', sans-serif;
}
.main-content {
  margin-left: 300px;
  margin-top: 70px;
}
.sidenav {
  height: 100%;
  width: 250px;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #183c5c;
  overflow-x: hidden;
  padding-top: 20px;
}

.sidenav a {
  padding: 6px 6px 6px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #ffffff;
  display: block;
  font-size: 25px;
}

.sidenav a:hover {
  color: #f1f1f1;
}

.main {
  margin-left: 200px; /* Same as the width of the sidenav */
}
.nav-header {
  color: white;
  margin-top: 14%;
  margin-left: 27%;
}
.text-pointer {
  display: flex;
  justify-content: space-between;
}

@media screen and (max-height: 450px) {
  .sidenav {
    padding-top: 15px;
  }
  .sidenav a {
    font-size: 18px;
  }
}
</style>
