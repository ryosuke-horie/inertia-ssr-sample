<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  video: null,
});

function submit() {
  form.post('/upload');
}
</script>

<template>
  <form @submit.prevent="submit">
    <input type="file" @input="form.video = $event.target.files[0]" />
    <button type="submit">Submit</button>
  </form>
  <img src="https://image.cheer-pay.jp/output/sample.jpg" />
</template>
