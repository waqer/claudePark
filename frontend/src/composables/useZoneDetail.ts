import { ref } from 'vue'
import type { ZoneDetail, ApiError } from '@/types/zone'
import { API_BASE } from '@/utils/constants'

export function useZoneDetail() {
  const zone    = ref<ZoneDetail | null>(null)
  const loading = ref(false)
  const error   = ref<ApiError | null>(null)

  // ── Fetch single zone from GET /api/zones/{id} ────────────────────────────
  async function fetchZone(id: number): Promise<void> {
    loading.value = true
    error.value   = null
    zone.value    = null

    try {
      const res = await fetch(`${API_BASE}/api/zones/${id}`)

      if (res.status === 404) {
        const body = await res.json().catch(() => ({}))
        throw { status: 404, message: body.error ?? `Zone #${id} not found.` }
      }
      if (!res.ok) {
        throw { status: res.status, message: `Server error ${res.status}` }
      }

      zone.value = await res.json()
    } catch (e) {
      error.value = e as ApiError
    } finally {
      loading.value = false
    }
  }

  return { zone, loading, error, fetchZone }
}
