<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  corporation_name: '',
  applicant_name: '',
  applicant_name_kana: '',
  zip_code: '',
  pref_code: '',
  city: '',
  phone: '',
  invoice_reg_num: '',
  notes: '',
});

const submit = () => {
  form.post(route('corporation.register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Register" />

    <form @submit.prevent="submit">
      <div class="mt-4">
        <InputLabel for="email" value="Email" />

        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Password" />

        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Confirm Password" />

        <TextInput
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <!-- Corporation Name Field -->
      <div class="mt-4">
        <InputLabel for="corporation_name" value="Corporation Name" />
        <TextInput
          id="corporation_name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.corporation_name"
          required
        />
        <InputError class="mt-2" :message="form.errors.corporation_name" />
      </div>

      <!-- Applicant Name Field -->
      <div class="mt-4">
        <InputLabel for="applicant_name" value="Applicant Name" />
        <TextInput id="applicant_name" type="text" class="mt-1 block w-full" v-model="form.applicant_name" required />
        <InputError class="mt-2" :message="form.errors.applicant_name" />
      </div>

      <!-- Applicant Name Kana Field -->
      <div class="mt-4">
        <InputLabel for="applicant_name_kana" value="Applicant Name Kana" />
        <TextInput
          id="applicant_name_kana"
          type="text"
          class="mt-1 block w-full"
          v-model="form.applicant_name_kana"
          required
        />
        <InputError class="mt-2" :message="form.errors.applicant_name_kana" />
      </div>

      <!-- Zip Code Field -->
      <div class="mt-4">
        <InputLabel for="zip_code" value="Zip Code" />
        <TextInput id="zip_code" type="text" class="mt-1 block w-full" v-model="form.zip_code" required />
        <InputError class="mt-2" :message="form.errors.zip_code" />
      </div>

      <!-- Prefecture Code Field -->
      <div class="mt-4">
        <InputLabel for="pref_code" value="Prefecture Code" />
        <TextInput id="pref_code" type="text" class="mt-1 block w-full" v-model="form.pref_code" required />
        <InputError class="mt-2" :message="form.errors.pref_code" />
      </div>

      <!-- City Field -->
      <div class="mt-4">
        <InputLabel for="city" value="City" />
        <TextInput id="city" type="text" class="mt-1 block w-full" v-model="form.city" required />
        <InputError class="mt-2" :message="form.errors.city" />
      </div>

      <!-- Phone Number Field -->
      <div class="mt-4">
        <InputLabel for="phone" value="Phone Number" />
        <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" required />
        <InputError class="mt-2" :message="form.errors.phone" />
      </div>

      <!-- Invoice Registration Number Field -->
      <div class="mt-4">
        <InputLabel for="invoice_reg_num" value="Invoice Registration Number" />
        <TextInput id="invoice_reg_num" type="text" class="mt-1 block w-full" v-model="form.invoice_reg_num" required />
        <InputError class="mt-2" :message="form.errors.invoice_reg_num" />
      </div>

      <!-- Notes Field -->
      <div class="mt-4">
        <InputLabel for="notes" value="Notes" />
        <textarea id="notes" class="mt-1 block w-full" v-model="form.notes"></textarea>
        <InputError class="mt-2" :message="form.errors.notes" />
      </div>

      <div class="flex items-center justify-end mt-4">
        <Link
          :href="route('corporation.login')"
          class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Already registered?
        </Link>

        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Register
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
