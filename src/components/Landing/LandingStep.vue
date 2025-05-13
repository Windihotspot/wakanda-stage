<template>
  <div class="max-w-[280px] xl:max-w-[363px] space-y-6 sm:space-y-3 text-center">
    <img :src="getImageSrc(step.img)" class="max-w-[245px] mx-auto" alt="" />
    <h3 class="text-xl text-neutral-800 font-semibold">{{ step.title }}</h3>
    <p class="text-sm text-gray-700 leading-relaxed">
      {{ step.description }}
    </p>
  </div>
</template>
<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
  name: 'LandingStep',
  props: {
    step: {
      type: Object,
      required: true
    }
  },
  methods: {
    getImageSrc(img) {
      return new URL(`../assets/img/getting-started/${img}`, import.meta.url).href
    }
  }
})
</script>
