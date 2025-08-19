<!-- src/layouts/MobileLayout.vue -->
<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-100">
    <!-- Fixed mobile container -->
    <div
      class="w-[393px] h-screen bg-white shadow-lg relative flex flex-col justify-between py-10 px-4 overflow-hidden"
    >
      <!-- Page content -->
      <slot />
    </div>
  </div>
</template>

<script setup>
// No script needed
</script>

<style scoped>
/* Hide scrollbar just in case */
::-webkit-scrollbar {
  width: 0;
  background: transparent;
}
</style>
