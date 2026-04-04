export type ZoneType   = 'commercial' | 'street' | 'residential'
export type ZoneStatus = 'active' | 'limited' | 'seasonal' | 'inactive'

export interface ZoneSummary {
  id:     number
  name:   string
  type:   ZoneType
  status: ZoneStatus
}

export interface OpeningHours {
  weekdays: string
  weekends: string
}

export interface ZoneDetail extends ZoneSummary {
  description:     string
  max_capacity:    number
  hourly_rate_eur: number
  latitude:        number
  longitude:       number
  amenities:       string[]
  opening_hours:   OpeningHours
}

export interface ApiError {
  status:  number
  message: string
}
