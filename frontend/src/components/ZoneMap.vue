<script setup lang="ts">
import { computed } from 'vue'
import type { ZoneDetail } from '@/types/zone'
import { LMap, LTileLayer, LMarker, LPopup } from '@vue-leaflet/vue-leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps<{ zone: ZoneDetail }>()

const zoom = 20
const center = computed(() => [props.zone.latitude, props.zone.longitude])
const markerLatLng = computed(() => [props.zone.latitude, props.zone.longitude])

const url = 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png'
const attribution = '© ParkMan OY'
</script>

<template>
  <div class="map-card">
    <div class="map-title">Location</div>

    <l-map style="height: 240px; width: 100%" :zoom="zoom" :center="center">
      <l-tile-layer :url="url" :attribution="attribution" />

      <l-marker :lat-lng="markerLatLng">
        <l-popup>
          <strong>{{ props.zone.name }}</strong>
        </l-popup>
      </l-marker>
    </l-map>

    <div class="coords">
      📍 {{ props.zone.latitude }}° N, {{ props.zone.longitude }}° E ; Helsinki, Finland
    </div>
  </div>
</template>