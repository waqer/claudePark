<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pdo = get_db();

// Wipe existing seed data so script is safe to re-run
$pdo->exec("TRUNCATE TABLE zones");

$zones = [
    [
        'name'            => 'Kamppi Center Underground',
        'type'            => 'commercial',
        'status'          => 'active',
        'description'     => 'Large underground facility beneath Kamppi shopping centre. Direct elevator access to mall, 24/7 operation and EV charging bays on level P2.',
        'max_capacity'    => 450,
        'hourly_rate_eur' => 4.50,
        'latitude'        => 60.168500,
        'longitude'       => 24.931800,
        'amenities'       => ['EV Charging', 'Disabled Access', '24/7 Open', 'Security Cameras', 'Elevator'],
        'opening_hours'   => ['weekdays' => '00:00-24:00', 'weekends' => '00:00-24:00'],
    ],
    [
        'name'            => 'Esplanadi Street Parking',
        'type'            => 'street',
        'status'          => 'active',
        'description'     => 'Open-air pay-and-display bays along Pohjoisesplanadi. Ideal for short visits to the Design District and Market Square.',
        'max_capacity'    => 80,
        'hourly_rate_eur' => 2.00,
        'latitude'        => 60.167200,
        'longitude'       => 24.945100,
        'amenities'       => ['Short Stay', 'Pay & Display'],
        'opening_hours'   => ['weekdays' => '08:00-20:00', 'weekends' => '10:00-18:00'],
    ],
    [
        'name'            => 'Kallio Residential Zone',
        'type'            => 'residential',
        'status'          => 'active',
        'description'     => 'Permit zone serving Kallio residents. Visitor tickets available from pay stations. Bicycle racks on every block.',
        'max_capacity'    => 120,
        'hourly_rate_eur' => 1.20,
        'latitude'        => 60.182000,
        'longitude'       => 24.950200,
        'amenities'       => ['Permit Zone', 'Bicycle Racks', 'Lighting'],
        'opening_hours'   => ['weekdays' => '07:00-21:00', 'weekends' => '09:00-18:00'],
    ],
    [
        'name'            => 'Töölönlahti Waterfront',
        'type'            => 'street',
        'status'          => 'limited',
        'description'     => 'Scenic surface parking beside the Töölönlahti bay, adjacent to Finlandia Hall. Fills quickly on concert evenings.',
        'max_capacity'    => 60,
        'hourly_rate_eur' => 2.50,
        'latitude'        => 60.175600,
        'longitude'       => 24.927100,
        'amenities'       => ['Scenic View', 'EV Charging', 'Disabled Access'],
        'opening_hours'   => ['weekdays' => '07:00-22:00', 'weekends' => '08:00-22:00'],
    ],
    [
        'name'            => 'Pasila Hub Garage',
        'type'            => 'commercial',
        'status'          => 'active',
        'description'     => 'Multi-storey park-and-ride garage at Helsinki Pasila rail hub. Direct covered walkway to the train platforms.',
        'max_capacity'    => 820,
        'hourly_rate_eur' => 3.20,
        'latitude'        => 60.198900,
        'longitude'       => 24.933800,
        'amenities'       => ['Park & Ride', 'EV Charging', 'Security Cameras', '24/7 Open', 'Disabled Access'],
        'opening_hours'   => ['weekdays' => '00:00-24:00', 'weekends' => '00:00-24:00'],
    ],
    [
        'name'            => 'Hernesaari Marina Lot',
        'type'            => 'street',
        'status'          => 'seasonal',
        'description'     => 'Coastal surface lot near Hernesaari marina and summer beach. Operates May–September only; free in the off-season.',
        'max_capacity'    => 200,
        'hourly_rate_eur' => 1.80,
        'latitude'        => 60.150900,
        'longitude'       => 24.922800,
        'amenities'       => ['Seasonal', 'Near Beach', 'Pay & Display'],
        'opening_hours'   => ['weekdays' => '08:00-21:00', 'weekends' => '08:00-21:00'],
    ],
    [
        'name'            => 'Forum Shopping Garage',
        'type'            => 'commercial',
        'status'          => 'active',
        'description'     => 'Underground garage below Forum shopping centre on Mannerheimintie. First 30 minutes free with retail validation.',
        'max_capacity'    => 380,
        'hourly_rate_eur' => 4.00,
        'latitude'        => 60.170300,
        'longitude'       => 24.934600,
        'amenities'       => ['EV Charging', 'Disabled Access', 'Security Cameras', 'Elevator', '24/7 Open'],
        'opening_hours'   => ['weekdays' => '06:00-23:00', 'weekends' => '07:00-23:00'],
    ],
    [
        'name'            => 'Punavuori Residential P',
        'type'            => 'residential',
        'status'          => 'active',
        'description'     => 'Street permit zone in the Punavuori neighbourhood. Evening and weekend visitor parking available without a permit after 18:00.',
        'max_capacity'    => 95,
        'hourly_rate_eur' => 1.50,
        'latitude'        => 60.161800,
        'longitude'       => 24.938400,
        'amenities'       => ['Permit Zone', 'Lighting', 'Bicycle Racks'],
        'opening_hours'   => ['weekdays' => '07:00-18:00', 'weekends' => 'Free'],
    ],
    [
        'name'            => 'Hakaniemi Market Square',
        'type'            => 'street',
        'status'          => 'active',
        'description'     => 'Surface parking around Hakaniemi market square. Convenient for the market hall, tram connections and Kallio shopping.',
        'max_capacity'    => 110,
        'hourly_rate_eur' => 1.80,
        'latitude'        => 60.179100,
        'longitude'       => 24.950800,
        'amenities'       => ['Pay & Display', 'Short Stay', 'Disabled Access'],
        'opening_hours'   => ['weekdays' => '08:00-20:00', 'weekends' => '09:00-17:00'],
    ],
    [
        'name'            => 'Itäkeskus Eastgate Garage',
        'type'            => 'commercial',
        'status'          => 'active',
        'description'     => 'Large multi-level garage serving Itis shopping centre in eastern Helsinki. Heated facility with direct mall access on all levels.',
        'max_capacity'    => 1200,
        'hourly_rate_eur' => 2.80,
        'latitude'        => 60.210500,
        'longitude'       => 25.079200,
        'amenities'       => ['EV Charging', 'Disabled Access', 'Security Cameras', '24/7 Open', 'Elevator', 'Heated'],
        'opening_hours'   => ['weekdays' => '06:00-23:00', 'weekends' => '07:00-23:00'],
    ],
];

$sql = "
    INSERT INTO zones
        (name, type, status, description, max_capacity, hourly_rate_eur,
         latitude, longitude, amenities, opening_hours)
    VALUES
        (:name, :type, :status, :description, :max_capacity, :hourly_rate_eur,
         :latitude, :longitude, :amenities, :opening_hours)
";

$stmt = $pdo->prepare($sql);

foreach ($zones as $zone) {
    $stmt->execute([
        ':name'            => $zone['name'],
        ':type'            => $zone['type'],
        ':status'          => $zone['status'],
        ':description'     => $zone['description'],
        ':max_capacity'    => $zone['max_capacity'],
        ':hourly_rate_eur' => $zone['hourly_rate_eur'],
        ':latitude'        => $zone['latitude'],
        ':longitude'       => $zone['longitude'],
        ':amenities'       => json_encode($zone['amenities']),
        ':opening_hours'   => json_encode($zone['opening_hours']),
    ]);
}

echo "Seeded " . count($zones) . " zones successfully.\n";
