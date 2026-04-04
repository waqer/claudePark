<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useZones } from '@/composables/useZones'
import { TYPE_META } from '@/utils/constants'
import type { ZoneType, ZoneStatus } from '@/types/zone'
import StatusBadge from '@/components/StatusBadge.vue'

const { loading, error, search, filterType, filterStatus, sortDir, filteredZones, fetchZones, toggleSort } = useZones()
onMounted(fetchZones)

const activeFilter = ref('all')
type FilterId = ZoneType | ZoneStatus | 'all'
const FILTERS: Array<{ id: FilterId; label: string; kind: 'all' | 'type' | 'status' }> = [
  { id: 'all',         label: 'All zones',    kind: 'all'    },
  { id: 'commercial',  label: '🏢 Commercial', kind: 'type'   },
  { id: 'street',      label: '🛣️ Street',     kind: 'type'   },
  { id: 'residential', label: '🏘️ Residential',kind: 'type'   },
  { id: 'active',      label: 'Active',       kind: 'status' },
  { id: 'limited',     label: 'Limited',      kind: 'status' },
  { id: 'seasonal',    label: 'Seasonal',     kind: 'status' },
]
const TYPE_COLOR: Record<ZoneType, string> = {
  commercial: '#2563EB', street: '#059669', residential: '#D97706',
}
function applyFilter(f: typeof FILTERS[0]): void {
  activeFilter.value = f.id
  filterType.value   = f.kind === 'type'   ? f.id as ZoneType   : 'all'
  filterStatus.value = f.kind === 'status' ? f.id as ZoneStatus : 'all'
}
</script>

<template>
  <div class="page-shell">
    <div class="inner">

      <!-- Page header -->
      <div class="page-header fade-up">
        <div>
          <p class="page-eyebrow">Helsinki · Parking Network</p>
          <h1 class="page-title">Parking Zones</h1>
        </div>
        <div class="zone-count" v-if="!loading">
          <span class="zone-count-num">{{ filteredZones.length }}</span>
          <span class="zone-count-label">zones</span>
        </div>
      </div>

      <!-- Controls -->
      <div class="controls fade-up-1">
        <div class="search-wrap" style="flex:1;max-width:300px">
          <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
          <input v-model="search" class="search-input" placeholder="Search zones…" />
        </div>
        <button class="ghost-btn" @click="toggleSort">
          Name {{ sortDir === 'asc' ? '↑' : '↓' }}
        </button>
      </div>

      <!-- Filter pills -->
      <div class="filter-pills fade-up-1" style="margin-bottom:28px">
        <button v-for="f in FILTERS" :key="f.id" class="filter-pill"
          :class="{ active: activeFilter === f.id }" @click="applyFilter(f)">
          {{ f.label }}
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="grid">
        <div v-for="i in 6" :key="i" class="skel card-skeleton" :style="{ animationDelay: `${i*60}ms` }" />
      </div>

      <!-- Error -->
      <div v-else-if="error" class="state-center">
        <div class="state-icon">⚠️</div>
        <div class="state-title">Could not load zones</div>
        <div class="state-msg">{{ error }}</div>
        <button class="state-btn" @click="fetchZones">Try again</button>
      </div>

      <!-- Empty -->
      <div v-else-if="filteredZones.length === 0" class="state-center">
        <div class="state-icon">🔍</div>
        <div class="state-title">No zones found</div>
        <div class="state-msg">Try a different search or filter</div>
      </div>

      <!-- Zone cards -->
      <div v-else class="grid">
        <article
          v-for="(zone, i) in filteredZones" :key="zone.id"
          class="zone-card"
          :style="{ '--c': TYPE_COLOR[zone.type], animationDelay: `${i * 40}ms` }"
          @click="$router.push({ name: 'zone-detail', params: { id: zone.id } })"
          tabindex="0"
          @keydown.enter="$router.push({ name: 'zone-detail', params: { id: zone.id } })"
        >
          <div class="card-accent" />
          <div class="card-body">
            <div class="card-row-top">
              <span class="card-icon">{{ TYPE_META[zone.type]?.icon }}</span>
              <StatusBadge :status="zone.status" />
            </div>
            <div class="card-name">{{ zone.name }}</div>
            <div class="card-subtype">{{ TYPE_META[zone.type]?.label }} parking</div>
          </div>
          <div class="card-row-bottom">
            <span class="card-id">#{{ String(zone.id).padStart(3,'0') }}</span>
            <span class="card-link">View →</span>
          </div>
        </article>
      </div>

    </div>
  </div>
</template>

<style scoped>
/* ── Page header ── */
.page-header {
  display: flex; align-items: flex-end; justify-content: space-between;
  margin-bottom: 28px; gap: 16px;
}
.page-eyebrow {
  font-size: 11px; font-weight: 600; letter-spacing: 0.14em;
  text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;
}
.page-title {
  font-family: 'Anybody', sans-serif; font-size: clamp(26px, 4vw, 38px);
  font-weight: 800; letter-spacing: -0.02em; color: var(--text-primary);
  transition: color 0.25s;
}
.zone-count { text-align: right; }
.zone-count-num {
  font-family: 'Anybody', sans-serif; font-size: 36px; font-weight: 800;
  color: var(--text-primary); line-height: 1; display: block;
}
.zone-count-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; }

/* ── Controls ── */
.controls { display: flex; gap: 10px; align-items: center; margin-bottom: 14px; flex-wrap: wrap; }

/* ── Grid ── */
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; }
.card-skeleton { height: 148px; border-radius: 12px; }

/* ── Zone card ── */
.zone-card {
  background: var(--card-bg);
  border: 1px solid var(--card-border);
  border-radius: 12px;
  overflow: hidden;
  cursor: pointer;
  display: flex; flex-direction: column;
  box-shadow: var(--card-shadow);
  animation: fadeUp 0.35s ease both;
  transition: box-shadow 0.2s, border-color 0.2s, transform 0.2s;
}
.zone-card:hover {
  box-shadow: var(--card-shadow-hover);
  border-color: var(--c);
  transform: translateY(-2px);
}
.card-accent { height: 4px; background: var(--c); }
.card-body { padding: 16px 18px 12px; flex: 1; }
.card-row-top {
  display: flex; justify-content: space-between;
  align-items: flex-start; margin-bottom: 10px;
}
.card-icon { font-size: 22px; line-height: 1; }
.card-name {
  font-family: 'Anybody', sans-serif; font-size: 16px; font-weight: 700;
  color: var(--text-primary); line-height: 1.2; margin-bottom: 3px;
  transition: color 0.25s;
}
.card-subtype { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em; }

.card-row-bottom {
  display: flex; justify-content: space-between; align-items: center;
  padding: 10px 18px 14px;
  border-top: 1px solid var(--border);
}
.card-id { font-family: 'Courier New', monospace; font-size: 11px; color: var(--text-muted); }
.card-link {
  font-size: 12px; font-weight: 600; color: var(--c);
  opacity: 0; transform: translateX(-4px);
  transition: opacity 0.15s, transform 0.15s;
}
.zone-card:hover .card-link { opacity: 1; transform: translateX(0); }

@media (max-width: 480px) { .grid { grid-template-columns: 1fr; } }
</style>