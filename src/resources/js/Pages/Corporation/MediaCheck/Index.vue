<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import IconImage from '@/Components/atom/image/IconImage/IconImage.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref, reactive, computed } from 'vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import FormInput from '@/Components/atom/form/FormInput/FormInput.vue';

type BusinessOperatorProps = {
  businessId: number;
  businessName: string;
  src: string;
};

const props = defineProps<{
  businessOperators: BusinessOperatorProps[];
}>();

const businessOperators = ref<BusinessOperatorProps[]>(props.businessOperators);

const search = reactive({ businessName: '' });

const computedBusinessOperators = computed<BusinessOperatorProps[]>(() => {
  const businessName = search.businessName.toLowerCase();
  return businessOperators.value.filter((businessOperator) => {
    return businessOperator.businessName.toLowerCase().includes(businessName);
  });
});
</script>

<template>
  <BaseLayout
    title="投稿動画・画像確認"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: '投稿動画・画像確認',
      href: RouteName.CorporationMypage,
    }"
  >
    <div class="p-corporation-business-management">
      <Divider />
      <ColorSection>
        <FormInput
          id="search-staff-name"
          type="text"
          v-model="search.businessName"
          class="p-corporation-business-management__input-search"
        />
      </ColorSection>

      <ul v-if="computedBusinessOperators.length" class="p-corporation-business-management__business-applications">
        <li v-for="(item, index) in computedBusinessOperators" :key="index">
          <Link
            :href="
              route(RouteName.CorporationMediaCheckShow, {
                businessId: item.businessId,
              })
            "
          >
            <div class="p-corporation-business-management__business-applications__container">
              <div class="p-corporation-business-management__business-applications__container__left">
                <IconImage :src="item.src" :width="70" :height="70" />
                <Paragraph class="p-corporation-business-management__business-applications__container__left__name">
                  {{ item.businessName }}
                </Paragraph>
              </div>
              <div class="p-corporation-business-management__business-applications__container__right">
                <Paragraph class="p-corporation-business-management__business-applications__container__right__arrow">
                  >
                </Paragraph>
              </div>
            </div>
            <Divider />
          </Link>
        </li>
      </ul>
      <EmptySection v-else text="該当する店舗がございません。" />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-corporation-business-management {
  &__input-search {
    background-color: var(--white);
    width: 100%;
  }
  &__select-search {
    height: 40px;
  }
  &__business-applications {
    &__container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 25px;
      &__left {
        display: flex;
        align-items: center;
        &__name {
          font-size: 15px;
          font-weight: 500;
          letter-spacing: -0.015em;
          padding-left: 10px;
        }
      }
      &__right {
        display: flex;
        align-items: center;
        &__status {
          font-size: 14px;
          font-weight: 700;
          letter-spacing: -0.015em;
          color: #ff0707 !important;
          border-radius: 5px;
          background-color: #ffe3e3;
          padding: 5px 8px;
        }
        &__arrow {
          font-size: 18px;
          font-weight: 500;
          letter-spacing: -0.015em;
          padding-left: 20px;
        }
      }
    }
  }
  &__register-staff {
    background-color: #fff !important;
    color: #1d9bf0 !important;
    border-color: #fff !important;
  }
}
</style>
