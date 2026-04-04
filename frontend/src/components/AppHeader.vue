<script setup lang="ts">
import { useRouter, useRoute } from 'vue-router'
import { useTheme } from '@/composables/useTheme'
const router = useRouter()
const route  = useRoute()
const { theme, toggleTheme } = useTheme()
</script>

<template>
  <header class="header">
    <div class="brand" @click="router.push('/')">
      <span class="brand-icon">🅿</span>
      <div>
        <div class="brand-name">Parkman</div>
        <div class="brand-sub">Zone Viewer</div>
      </div>
    </div>

    <div class="header-right">
      <button v-if="route.name === 'zone-detail'" class="back-btn" @click="router.push('/')">
        ← Back
      </button>
      <button class="theme-btn" @click="toggleTheme" :title="`Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`">
        {{ theme === 'dark' ? '☀️' : '🌙' }}
        {{ theme === 'dark' ? 'Light' : 'Dark' }}
      </button>
    </div>
  </header>
</template>

<style scoped>
.header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 32px; height: 56px;
  background: var(--header-bg);
  border-bottom: 1px solid var(--header-border);
  backdrop-filter: blur(10px);
  position: sticky; top: 0; z-index: 50;
  transition: background 0.25s, border-color 0.25s;
}
.brand { display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
.brand-icon { font-size: 20px; }
.brand-name {
  font-family: 'Anybody', sans-serif; font-size: 15px;
  font-weight: 800; color: var(--text-primary); line-height: 1;
  transition: color 0.25s;
}
.brand-sub { font-size: 10px; color: var(--text-muted); margin-top: 1px; }
.header-right { display: flex; align-items: center; gap: 8px; }

.back-btn {
  background: none; border: 1px solid var(--border);
  border-radius: 7px; padding: 6px 12px;
  font-family: 'Epilogue', sans-serif; font-size: 13px;
  color: var(--text-secondary); cursor: pointer;
  transition: all 0.15s;
}
.back-btn:hover { border-color: var(--text-secondary); color: var(--text-primary); }

.theme-btn {
  display: flex; align-items: center; gap: 6px;
  background: var(--toggle-bg); border: 1px solid var(--toggle-border);
  border-radius: 7px; padding: 6px 12px;
  font-family: 'Epilogue', sans-serif; font-size: 12px;
  color: var(--toggle-color); cursor: pointer;
  transition: all 0.15s;
}
.theme-btn:hover { border-color: var(--text-secondary); color: var(--text-primary); }
</style>