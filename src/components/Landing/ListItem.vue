<template>
  <li class="space-y-2">
    <div class="flex items-center space-x-2">
      <!-- <CheckCircleIcon :size="20" class="text-[#0c66ee]" /> -->
      <span>{{ title }}</span>
    </div>
    <slot></slot>
  </li>
</template>
<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
  name: 'ListItem',
  props: {
    title: {
      type: String,
      default: ''
    }
  },
  setup() {}
})
</script>
