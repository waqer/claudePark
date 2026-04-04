import { ref, computed } from 'vue'
import type { ZoneSummary, ZoneStatus, ZoneType } from '@/types/zone'
import { API_BASE } from '@/utils/constants'

export function useZones() {
  const zones   = ref<ZoneSummary[]>([])
  const loading = ref(false)
  const error   = ref<string | null>(null)

  // ── Filters ────────────────────────────────────────────────────────────────
  const search       = ref('')
  const filterType   = ref<ZoneType | 'all'>('all')
  const filterStatus = ref<ZoneStatus | 'all'>('all')
  const sortDir      = ref<'asc' | 'desc'>('asc')

  // ── Fetch all zones from GET /api/zones ────────────────────────────────────
  async function fetchZones(): Promise<void> {
    loading.value = true
    error.value   = null
    try {
      const res = await fetch(`${API_BASE}/api/zones`)
      if (!res.ok) throw new Error(`Server returned ${res.status}`)
      zones.value = await res.json()
    } catch (e) {
      error.value = e instanceof Error ? e.message : 'Failed to load zones.'
    } finally {
      loading.value = false
    }
  }

  // ── Derived: filtered + sorted list ───────────────────────────────────────
  const filteredZones = computed<ZoneSummary[]>(() => {
    let list = [...zones.value]

    if (search.value.trim()) {
      const q = search.value.trim().toLowerCase()
      list = list.filter(z => z.name.toLowerCase().includes(q))
    }

    if (filterType.value !== 'all') {
      list = list.filter(z => z.type === filterType.value)
    }

    if (filterStatus.value !== 'all') {
      list = list.filter(z => z.status === filterStatus.value)
    }

    list.sort((a, b) => {
      const cmp = a.name.localeCompare(b.name)
      return sortDir.value === 'asc' ? cmp : -cmp
    })

    return list
  })

  function toggleSort(): void {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc'
  }

  return {
    zones, loading, error,
    search, filterType, filterStatus, sortDir,
    filteredZones,
    fetchZones, toggleSort,
  }
}
