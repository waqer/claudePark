import type { ZoneStatus, ZoneType } from '@/types/zone'

// ── Point this at your XAMPP backend ─────────────────────────────────────────
export const API_BASE = 'http://localhost/claudePark/backend'

// ── Status display metadata ───────────────────────────────────────────────────
export const STATUS_META: Record<ZoneStatus, { label: string; color: string; bg: string }> = {
  active:   { label: 'Active',   color: '#00875A', bg: '#E6F6F1' },
  limited:  { label: 'Limited',  color: '#B45309', bg: '#FEF3C7' },
  seasonal: { label: 'Seasonal', color: '#0369A1', bg: '#E0F2FE' },
  inactive: { label: 'Inactive', color: '#6B7280', bg: '#F3F4F6' },
}

// ── Type display metadata ─────────────────────────────────────────────────────
export const TYPE_META: Record<ZoneType, { label: string; icon: string }> = {
  commercial:  { label: 'Commercial',  icon: '🏢' },
  street:      { label: 'Street',      icon: '🛣️'  },
  residential: { label: 'Residential', icon: '🏘️'  },
}

// ── Amenity icons ─────────────────────────────────────────────────────────────
export const AMENITY_ICONS: Record<string, string> = {
  'EV Charging':      '⚡',
  'Disabled Access':  '♿',
  '24/7 Open':        '🕐',
  'Security Cameras': '📹',
  'Short Stay':       '⏱️',
  'Pay & Display':    '🎫',
  'Permit Zone':      '🔑',
  'Bicycle Racks':    '🚲',
  'Lighting':         '💡',
  'Scenic View':      '🌊',
  'Park & Ride':      '🚆',
  'Seasonal':         '🌤️',
  'Near Beach':       '🏖️',
  'Elevator':         '🛗',
  'Heated':           '🔥',
}
