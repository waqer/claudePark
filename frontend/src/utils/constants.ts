import type { ZoneStatus, ZoneType } from '@/types/zone'

/* ──────────────────────────────────────────────────────────────
   API Base URL
   ──────────────────────────────────────────────────────────────
   Use the appropriate endpoint for your environment.
   Uncomment the XAMPP backend URL if needed.
────────────────────────────────────────────────────────────── */
// export const API_BASE = 'http://localhost/claudePark/backend'
export const API_BASE = 'http://localhost:8080' // Default Docker backend

/* ──────────────────────────────────────────────────────────────
   Zone Status Metadata
   ──────────────────────────────────────────────────────────────
   Maps zone status to labels, text color, and background color.
────────────────────────────────────────────────────────────── */
export const STATUS_META: Record<ZoneStatus, { label: string; color: string; bg: string }> = {
  active:   { label: 'Active',   color: '#00875A', bg: '#E6F6F1' },
  limited:  { label: 'Limited',  color: '#B45309', bg: '#FEF3C7' },
  seasonal: { label: 'Seasonal', color: '#0369A1', bg: '#E0F2FE' },
  inactive: { label: 'Inactive', color: '#6B7280', bg: '#F3F4F6' },
}

/* ──────────────────────────────────────────────────────────────
   Zone Type Metadata
   ──────────────────────────────────────────────────────────────
   Maps zone types to descriptive labels and icons.
────────────────────────────────────────────────────────────── */
export const TYPE_META: Record<ZoneType, { label: string; icon: string }> = {
  commercial:  { label: 'Commercial',  icon: '🏢' },
  street:      { label: 'Street',      icon: '🛣️'  },
  residential: { label: 'Residential', icon: '🏘️'  },
}

/* ──────────────────────────────────────────────────────────────
   Amenity Icons
   ──────────────────────────────────────────────────────────────
   Maps amenity names to representative icons for display purposes.
────────────────────────────────────────────────────────────── */
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