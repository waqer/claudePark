<script setup lang="ts">
import { useTheme } from '@/composables/useTheme'
import AppHeader from '@/components/AppHeader.vue'

const { initTheme } = useTheme()
initTheme()
</script>

<template>
  <AppHeader />
  <router-view />
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Anybody:wght@400;600;700;800&family=Epilogue:wght@300;400;500&display=swap');
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
  background: var(--page-bg);
  color: var(--text-primary);
  font-family: 'Epilogue', sans-serif;
  min-height: 100vh;
  transition: background 0.25s, color 0.25s;
}
</style>