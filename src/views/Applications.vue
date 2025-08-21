<template>
  <MobileLayout>
    <div class="p-6">
      <img src="/src/assets/logo.png" alt="Logo" class="w-10 h-10 object-contain" />
      <p class="mt-2 text-gray-600 text-sm">Welcome to pluto. Access credit within 2 hours</p>

      <!-- Cards -->
      <div class="mt-6 space-y-4">
        <router-link to="/loan-form">
          <div class="p-4 bg-red-50 shadow rounded-lg flex justify-between items-center">
            <div>
              <h2 class="font-semibold">Personal Loan</h2>
              <p class="text-sm text-gray-500">Access up to 5 Million</p>
            </div>
            <span>›</span>
          </div>
        </router-link>

        <div class="p-4 bg-green-50 shadow rounded-lg flex justify-between items-center">
          <div>
            <h2 class="font-semibold">Business Loan</h2>
            <p class="text-sm text-gray-500">Access up to 50 Million</p>
          </div>
          <span>›</span>
        </div>

        <div class="p-4 bg-blue-50 shadow rounded-lg flex justify-between items-center">
          <div>
            <h2 class="font-semibold">IPPIS Loan</h2>
            <p class="text-sm text-gray-500">Access up to 1 Million</p>
          </div>
          <span>›</span>
        </div>
      </div>

      <!-- Footer -->
      <div class="absolute bottom-4 w-full text-center text-xs text-gray-500 font-semibold">
        Powered by Jupita
      </div>
    </div>
  </MobileLayout>
</template>

<script setup>
import MobileLayout from '@/layouts/full/MobileLayout.vue'
</script>
