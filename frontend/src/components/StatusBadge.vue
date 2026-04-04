<script setup lang="ts">
import { computed } from 'vue'
import type { ZoneStatus } from '@/types/zone'
import { STATUS_META } from '@/utils/constants'

const props = defineProps<{ status: ZoneStatus }>()
const meta  = computed(() => STATUS_META[props.status] ?? STATUS_META.inactive)
</script>

<template>
  <span class="badge" :style="{ color: meta.color, background: meta.bg }">
    {{ meta.label }}
  </span>
</template>

<style scoped>
.badge {
  display: inline-block;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.07em;
  text-transform: uppercase;
  padding: 3px 9px;
  border-radius: 20px;
  white-space: nowrap;
}
</style>
