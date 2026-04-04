<script setup lang="ts">
import { onMounted, watch } from 'vue'
import { useZoneDetail } from '@/composables/useZoneDetail'
import { TYPE_META, AMENITY_ICONS } from '@/utils/constants'
import StatusBadge from '@/components/StatusBadge.vue'
import ZoneMap    from '@/components/ZoneMap.vue'

const props = defineProps<{ id: number }>()
const { zone, loading, error, fetchZone } = useZoneDetail()
onMounted(() => fetchZone(props.id))
watch(() => props.id, fetchZone)

const amenityIcon = (name: string) => AMENITY_ICONS[name] ?? '•'
const capitalize  = (s: string)    => s.charAt(0).toUpperCase() + s.slice(1)
</script>

<template>
  <div class="page-shell">
    <div class="inner">

      <!-- Loading -->
      <div v-if="loading" class="skel-layout">
        <div class="skel" style="height:12px;width:22%;border-radius:4px" />
        <div class="skel" style="height:44px;width:55%;border-radius:8px;margin-top:4px" />
        <div class="skel" style="height:13px;width:70%;border-radius:4px" />
        <div class="skel" style="height:13px;width:55%;border-radius:4px" />
        <div class="two-col" style="margin-top:8px">
          <div class="skel" style="height:84px;border-radius:12px" />
          <div class="skel" style="height:84px;border-radius:12px" />
        </div>
        <div class="skel" style="height:260px;border-radius:12px" />
        <div class="two-col">
          <div class="skel" style="height:160px;border-radius:12px" />
          <div class="skel" style="height:160px;border-radius:12px" />
        </div>
      </div>

      <!-- 404 -->
      <div v-else-if="error?.status === 404" class="notfound">
        <div class="notfound-code">404</div>
        <div class="notfound-title">Zone not found</div>
        <div class="notfound-msg">{{ error.message }}</div>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="state-center">
        <div class="state-icon">⚠️</div>
        <div class="state-title">Failed to load zone</div>
        <div class="state-msg">{{ error.message }}</div>
        <button class="state-btn" @click="fetchZone(props.id)">Retry</button>
      </div>

      <!-- Detail -->
      <template v-else-if="zone">

        <!-- Header -->
        <div class="detail-header fade-up">
          <div class="eyebrow">
            <span class="eyebrow-label">{{ TYPE_META[zone.type]?.icon }} {{ TYPE_META[zone.type]?.label }} Parking</span>
            <StatusBadge :status="zone.status" />
            <span class="zone-id-tag">#{{ String(zone.id).padStart(3,'0') }}</span>
          </div>
          <h1 class="display-title">{{ zone.name }}</h1>
          <p class="body-text">{{ zone.description }}</p>
        </div>

        <!-- Stats -->
        <div class="two-col fade-up-1">
          <div class="stat-card">
            <div class="stat-label">Hourly Rate</div>
            <div class="stat-value">€{{ Number(zone.hourly_rate_eur).toFixed(2) }}<span class="stat-unit"> /hr</span></div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Capacity</div>
            <div class="stat-value">{{ zone.max_capacity }}<span class="stat-unit"> spaces</span></div>
          </div>
        </div>

        <!-- Map -->
        <div class="fade-up-2">
          <ZoneMap :zone="zone" />
        </div>

        <!-- Amenities + Hours -->
        <div class="two-col fade-up-3">
          <div class="section-card">
            <div class="section-title">Amenities</div>
            <div class="amenity-list">
              <div v-for="a in zone.amenities" :key="a" class="amenity-row">
                <span class="amenity-icon">{{ amenityIcon(a) }}</span>
                <span class="amenity-label">{{ a }}</span>
              </div>
            </div>
          </div>

          <div class="section-card">
            <div class="section-title">Opening Hours</div>
            <div class="hours-list">
              <div v-for="(val, key) in zone.opening_hours" :key="key" class="hours-row">
                <span class="hours-day">{{ capitalize(String(key)) }}</span>
                <span class="hours-time">{{ val }}</span>
              </div>
            </div>
          </div>
        </div>

      </template>
    </div>
  </div>
</template>

<style scoped>
.detail-header { padding-bottom: 24px; border-bottom: 1px solid var(--border); margin-bottom: 20px; }
.skel-layout { display: flex; flex-direction: column; gap: 12px; }
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px; }

/* Amenities */
.amenity-list { display: flex; flex-direction: column; gap: 8px; }
.amenity-row  { display: flex; align-items: center; gap: 10px; }
.amenity-icon {
  width: 30px; height: 30px; font-size: 15px;
  background: var(--page-bg); border: 1px solid var(--border);
  border-radius: 8px; display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.amenity-label { font-size: 13px; color: var(--text-secondary); }

/* Hours */
.hours-list { display: flex; flex-direction: column; }
.hours-row {
  display: flex; justify-content: space-between; align-items: center;
  padding: 9px 0; border-bottom: 1px solid var(--border); font-size: 13px;
}
.hours-row:last-child { border-bottom: none; }
.hours-day  { color: var(--text-muted); }
.hours-time { font-weight: 600; color: var(--text-primary); font-family: 'Anybody', sans-serif; }

@media (max-width: 600px) { .two-col { grid-template-columns: 1fr; } }
</style>