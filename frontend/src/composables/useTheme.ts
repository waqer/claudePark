import { ref } from 'vue'

type Theme = 'dark' | 'light'

// ── Singleton ref — shared across every component that calls useTheme() ──
const theme = ref<Theme>('dark')

export function useTheme() {
  function applyTheme(t: Theme): void {
    theme.value = t
    document.documentElement.setAttribute('data-theme', t)
    localStorage.setItem('parkman-theme', t)
  }

  function toggleTheme(): void {
    applyTheme(theme.value === 'dark' ? 'light' : 'dark')
  }

  function initTheme(): void {
    const saved = localStorage.getItem('parkman-theme') as Theme | null
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    applyTheme(saved ?? (prefersDark ? 'dark' : 'light'))
  }

  return { theme, toggleTheme, initTheme }
}