<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useZones } from '@/composables/useZones'
import { TYPE_META } from '@/utils/constants'
import type { ZoneType, ZoneStatus } from '@/types/zone'
import StatusBadge from '@/components/StatusBadge.vue'

const router = useRouter()
const {
  loading, error,
  search, filterType, filterStatus, sortDir,
  filteredZones,
  fetchZones, toggleSort,
} = useZones()

onMounted(fetchZones)

const activeFilter = ref<string>('all')

type FilterId = ZoneType | ZoneStatus | 'all'

const QUICK_FILTERS: Array<{ id: FilterId; label: string; kind: 'type' | 'status' | 'all' }> = [
  { id: 'all',         label: 'All zones',    kind: 'all' },
  { id: 'commercial',  label: 'Commercial',   kind: 'type' },
  { id: 'street',      label: 'Street',       kind: 'type' },
  { id: 'residential', label: 'Residential',  kind: 'type' },
  { id: 'active',      label: 'Active',       kind: 'status' },
  { id: 'limited',     label: 'Limited',      kind: 'status' },
  { id: 'seasonal',    label: 'Seasonal',     kind: 'status' },
]

function applyFilter(f: { id: FilterId; kind: string }): void {
  activeFilter.value = f.id
  if (f.kind === 'all') {
    filterType.value   = 'all'
    filterStatus.value = 'all'
  } else if (f.kind === 'type') {
    filterType.value   = f.id as ZoneType
    filterStatus.value = 'all'
  } else {
    filterStatus.value = f.id as ZoneStatus
    filterType.value   = 'all'
  }
}

function goToZone(id: number): void {
  router.push({ name: 'zone-detail', params: { id } })
}

const TYPE_COLORS: Record<ZoneType, string> = {
  commercial:  '#3B6FFF',
  street:      '#00C896',
  residential: '#FF8C42',
}
</script>

<template>
  <div class="page">

    <!-- ── Mesh background ── -->
    <div class="bg-mesh" aria-hidden="true">
      <div class="mesh-blob blob-1" />
      <div class="mesh-blob blob-2" />
      <div class="mesh-blob blob-3" />
    </div>

    <div class="inner">

      <!-- ── Hero heading ── -->
      <header class="hero">
        <div class="hero-eyebrow">Helsinki · Parking Network</div>
        <h1 class="hero-title">
          <span class="hero-title-line">Parking</span>
          <span class="hero-title-line accent">Zones</span>
        </h1>
        <p class="hero-sub">
          {{ loading ? '—' : filteredZones.length }} zones available
        </p>
      </header>

      <!-- ── Search + sort row ── -->
      <div class="toolbar">
        <div class="search-wrap">
          <svg class="search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
          <input
            v-model="search"
            class="search-input"
            type="text"
            placeholder="Search zones…"
          />
        </div>

        <button class="sort-btn" @click="toggleSort" :class="{ flipped: sortDir === 'desc' }">
          <span>A–Z</span>
          <svg class="sort-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M12 5v14M5 12l7-7 7 7"/>
          </svg>
        </button>
      </div>

      <!-- ── Quick filter pills ── -->
      <div class="filters">
        <button
          v-for="f in QUICK_FILTERS"
          :key="f.id"
          class="filter-pill"
          :class="{ active: activeFilter === f.id }"
          @click="applyFilter(f)"
        >
          {{ f.label }}
        </button>
      </div>

      <!-- ── Loading skeletons ── -->
      <div v-if="loading" class="grid">
        <div v-for="i in 6" :key="i" class="skeleton-card" :style="{ animationDelay: `${i * 80}ms` }" />
      </div>

      <!-- ── Error ── -->
      <div v-else-if="error" class="error-wrap">
        <div class="error-glyph">!</div>
        <div class="error-title">Connection failed</div>
        <div class="error-msg">{{ error }}</div>
        <button class="error-retry" @click="fetchZones">Try again</button>
      </div>

      <!-- ── Empty ── -->
      <div v-else-if="filteredZones.length === 0" class="empty">
        <div class="empty-icon">◌</div>
        <div class="empty-text">No zones match your filters</div>
      </div>

      <!-- ── Zone cards ── -->
      <div v-else class="grid">
        <article
          v-for="(zone, i) in filteredZones"
          :key="zone.id"
          class="card"
          :style="{
            '--card-color': TYPE_COLORS[zone.type] ?? '#3B6FFF',
            animationDelay: `${i * 55}ms`,
          }"
          @click="goToZone(zone.id)"
          role="button"
          tabindex="0"
          @keydown.enter="goToZone(zone.id)"
        >
          <!-- Coloured top bar -->
          <div class="card-bar" />

          <!-- Card body -->
          <div class="card-body">
            <div class="card-top">
              <span class="card-type-icon">{{ TYPE_META[zone.type]?.icon }}</span>
              <StatusBadge :status="zone.status" />
            </div>

            <div class="card-name">{{ zone.name }}</div>
            <div class="card-type">{{ TYPE_META[zone.type]?.label }} parking</div>
          </div>

          <!-- Card footer -->
          <div class="card-footer">
            <span class="card-id">#{{ String(zone.id).padStart(3, '0') }}</span>
            <span class="card-cta">
              View details
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
            </span>
          </div>

          <!-- Hover glow -->
          <div class="card-glow" />
        </article>
      </div>

      <!-- ── Footer count ── -->
      <div v-if="!loading && !error && filteredZones.length > 0" class="count-bar">
        <span class="count-num">{{ filteredZones.length }}</span>
        <span class="count-label">zone{{ filteredZones.length !== 1 ? 's' : '' }} shown</span>
      </div>

    </div>
  </div>
</template>

<style scoped>
/* ── Page shell ─────────────────────────────────────────────────────────────── */
.page {
  min-height: calc(100vh - 60px);
  background: #0A0B0F;
  position: relative;
  overflow: hidden;
}

.inner {
  max-width: 1080px;
  margin: 0 auto;
  padding: 56px 40px 80px;
  position: relative;
  z-index: 1;
}

/* ── Mesh background blobs ──────────────────────────────────────────────────── */
.bg-mesh { position: absolute; inset: 0; pointer-events: none; }
.mesh-blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(90px);
  opacity: 0.18;
}
.blob-1 {
  width: 600px; height: 600px;
  background: radial-gradient(circle, #3B6FFF, transparent 70%);
  top: -200px; left: -150px;
}
.blob-2 {
  width: 500px; height: 500px;
  background: radial-gradient(circle, #00C896, transparent 70%);
  bottom: -100px; right: -100px;
}
.blob-3 {
  width: 400px; height: 400px;
  background: radial-gradient(circle, #FF8C42, transparent 70%);
  top: 50%; left: 55%;
  transform: translate(-50%, -50%);
}

/* ── Hero ───────────────────────────────────────────────────────────────────── */
.hero {
  margin-bottom: 48px;
  animation: fadeUp 0.5s ease both;
}
.hero-eyebrow {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.3);
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.hero-eyebrow::before {
  content: '';
  display: inline-block;
  width: 20px; height: 1px;
  background: rgba(255,255,255,0.2);
}
.hero-title {
  display: flex;
  flex-direction: column;
  font-family: 'Anybody', sans-serif;
  font-size: clamp(48px, 8vw, 88px);
  font-weight: 800;
  line-height: 0.92;
  letter-spacing: -0.04em;
  color: #fff;
  margin-bottom: 16px;
}
.hero-title-line { display: block; }
.hero-title-line.accent {
  -webkit-text-stroke: 2px rgba(59,111,255,0.8);
  color: transparent;
  background: linear-gradient(135deg, #3B6FFF, #00C896);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}
.hero-sub {
  font-size: 14px;
  color: rgba(255,255,255,0.35);
  font-weight: 300;
  letter-spacing: 0.05em;
}

/* ── Toolbar ────────────────────────────────────────────────────────────────── */
.toolbar {
  display: flex;
  gap: 12px;
  margin-bottom: 16px;
  align-items: center;
  animation: fadeUp 0.5s 0.1s ease both;
}

.search-wrap {
  position: relative;
  flex: 1;
  max-width: 340px;
}
.search-icon {
  position: absolute;
  left: 12px; top: 50%;
  transform: translateY(-50%);
  color: rgba(255,255,255,0.25);
  pointer-events: none;
}
.search-input {
  width: 100%;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 10px;
  padding: 10px 14px 10px 36px;
  color: #fff;
  font-family: 'Epilogue', sans-serif;
  font-size: 13px;
  outline: none;
  transition: border-color 0.2s, background 0.2s;
}
.search-input::placeholder { color: rgba(255,255,255,0.2); }
.search-input:focus {
  border-color: rgba(59,111,255,0.6);
  background: rgba(59,111,255,0.08);
}

.sort-btn {
  display: flex;
  align-items: center;
  gap: 7px;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 10px;
  padding: 10px 16px;
  color: rgba(255,255,255,0.5);
  font-family: 'Epilogue', sans-serif;
  font-size: 12px;
  cursor: pointer;
  transition: all 0.18s;
  white-space: nowrap;
}
.sort-btn:hover { border-color: rgba(255,255,255,0.25); color: #fff; }
.sort-arrow { transition: transform 0.3s; }
.sort-btn.flipped .sort-arrow { transform: rotate(180deg); }

/* ── Filter pills ───────────────────────────────────────────────────────────── */
.filters {
  display: flex;
  gap: 7px;
  flex-wrap: wrap;
  margin-bottom: 40px;
  animation: fadeUp 0.5s 0.15s ease both;
}
.filter-pill {
  padding: 6px 14px;
  border-radius: 20px;
  border: 1px solid rgba(255,255,255,0.1);
  background: transparent;
  color: rgba(255,255,255,0.4);
  font-family: 'Epilogue', sans-serif;
  font-size: 12px;
  cursor: pointer;
  transition: all 0.18s;
  white-space: nowrap;
}
.filter-pill:hover {
  border-color: rgba(255,255,255,0.3);
  color: rgba(255,255,255,0.8);
}
.filter-pill.active {
  background: rgba(59,111,255,0.2);
  border-color: rgba(59,111,255,0.6);
  color: #fff;
}

/* ── Grid ───────────────────────────────────────────────────────────────────── */
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 16px;
}

/* ── Zone Card ──────────────────────────────────────────────────────────────── */
.card {
  position: relative;
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 18px;
  overflow: hidden;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  animation: cardIn 0.45s ease both;
  transition: transform 0.22s cubic-bezier(.25,.8,.25,1),
              border-color 0.22s,
              box-shadow 0.22s;
}
@keyframes cardIn {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: translateY(0); }
}
.card:hover {
  transform: translateY(-4px);
  border-color: rgba(var(--card-color-raw, 59,111,255), 0.4);
  box-shadow: 0 16px 48px rgba(0,0,0,0.4),
              0 0 0 1px rgba(255,255,255,0.06);
}

/* Coloured top accent bar */
.card-bar {
  height: 3px;
  background: var(--card-color);
  width: 100%;
  transition: height 0.22s;
}
.card:hover .card-bar { height: 4px; }

.card-body {
  padding: 20px 22px 16px;
  flex: 1;
}
.card-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 14px;
}
.card-type-icon {
  font-size: 26px;
  line-height: 1;
  filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));
}
.card-name {
  font-family: 'Anybody', sans-serif;
  font-size: 19px;
  font-weight: 700;
  color: #fff;
  line-height: 1.15;
  letter-spacing: -0.01em;
  margin-bottom: 5px;
}
.card-type {
  font-size: 11px;
  color: rgba(255,255,255,0.3);
  text-transform: uppercase;
  letter-spacing: 0.1em;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 22px 18px;
  border-top: 1px solid rgba(255,255,255,0.06);
  margin-top: auto;
}
.card-id {
  font-family: 'Courier New', monospace;
  font-size: 11px;
  color: rgba(255,255,255,0.2);
}
.card-cta {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 12px;
  font-weight: 500;
  color: var(--card-color);
  opacity: 0;
  transform: translateX(-4px);
  transition: opacity 0.2s, transform 0.2s;
}
.card:hover .card-cta {
  opacity: 1;
  transform: translateX(0);
}

/* Glow effect on hover */
.card-glow {
  position: absolute;
  inset: 0;
  border-radius: 18px;
  background: radial-gradient(
    circle at 50% 0%,
    color-mix(in srgb, var(--card-color) 15%, transparent),
    transparent 65%
  );
  opacity: 0;
  transition: opacity 0.3s;
  pointer-events: none;
}
.card:hover .card-glow { opacity: 1; }

/* ── Skeleton cards ─────────────────────────────────────────────────────────── */
.skeleton-card {
  height: 168px;
  border-radius: 18px;
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(255,255,255,0.06);
  animation: shimmer 1.6s ease-in-out infinite;
}
@keyframes shimmer {
  0%, 100% { opacity: 1 }
  50%       { opacity: 0.4 }
}

/* ── Error ──────────────────────────────────────────────────────────────────── */
.error-wrap {
  text-align: center;
  padding: 80px 20px;
}
.error-glyph {
  font-family: 'Anybody', sans-serif;
  font-size: 72px;
  font-weight: 800;
  color: rgba(255,60,80,0.2);
  line-height: 1;
  margin-bottom: 16px;
}
.error-title {
  font-family: 'Anybody', sans-serif;
  font-size: 20px;
  font-weight: 700;
  color: rgba(255,255,255,0.7);
  margin-bottom: 8px;
}
.error-msg { font-size: 13px; color: rgba(255,255,255,0.3); margin-bottom: 20px; }
.error-retry {
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 8px;
  padding: 8px 20px;
  font-family: 'Epilogue', sans-serif;
  font-size: 13px;
  color: rgba(255,255,255,0.6);
  background: none;
  cursor: pointer;
  transition: all 0.15s;
}
.error-retry:hover { border-color: rgba(255,255,255,0.5); color: #fff; }

/* ── Empty ──────────────────────────────────────────────────────────────────── */
.empty {
  text-align: center;
  padding: 80px 20px;
}
.empty-icon {
  font-size: 48px;
  color: rgba(255,255,255,0.1);
  margin-bottom: 12px;
  display: block;
}
.empty-text { font-size: 14px; color: rgba(255,255,255,0.25); }

/* ── Footer count ───────────────────────────────────────────────────────────── */
.count-bar {
  margin-top: 32px;
  display: flex;
  align-items: baseline;
  gap: 6px;
}
.count-num {
  font-family: 'Anybody', sans-serif;
  font-size: 28px;
  font-weight: 800;
  color: rgba(255,255,255,0.12);
}
.count-label {
  font-size: 12px;
  color: rgba(255,255,255,0.18);
  text-transform: uppercase;
  letter-spacing: 0.1em;
}

/* ── Entrance animation ─────────────────────────────────────────────────────── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Responsive ─────────────────────────────────────────────────────────────── */
@media (max-width: 640px) {
  .inner { padding: 36px 20px 60px; }
  .hero-title { font-size: 56px; }
  .grid { grid-template-columns: 1fr; }
}
</style>