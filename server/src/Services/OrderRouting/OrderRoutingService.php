<?php

namespace App\Services\OrderRouting;

use Illuminate\Support\Facades\Http;

class OrderRoutingService
{
    /**
     * Sorts orders based on routing data fetched from OSRM.
     *
     * @param array $orders
     * @return array Sorted orders
     */
    public function sortOrdersByRoute(array $orders): array
    {
        $coordinates = $this->extractCoordinates($orders);

        if (empty($coordinates)) {
            return $orders;
        }

        $coordinateQuery = $this->buildCoordinateQuery($coordinates);
        $tripData = $this->fetchTripData($coordinateQuery);

        if ($tripData) {
            $orders = $this->sortOrdersUsingWaypoints($orders, $tripData['waypoints']);
        } else {
            // Optionally, log or handle error here
            echo "Failed to fetch trip data.";
        }

        return $orders;
    }

    private function extractCoordinates(array $orders): array
    {
        $coordinates = [];
        foreach ($orders as $order) {
            if (isset($order['payload']['pickup']['location']['coordinates'])) {
                $coordinates[] = $order['payload']['pickup']['location']['coordinates'];
            }
            if (isset($order['payload']['dropoff']['location']['coordinates'])) {
                $coordinates[] = $order['payload']['dropoff']['location']['coordinates'];
            }
        }
        return $coordinates;
    }

    private function buildCoordinateQuery(array $coordinates): string
    {
        return implode(';', array_map(fn($coordinate) => implode(',', $coordinate), $coordinates));
    }

    private function fetchTripData(string $coordinateQuery): ?array
    {
        $url = "https://router.project-osrm.org/trip/v1/driving/{$coordinateQuery}?source=any&destination=any&annotations=true";
        $response = Http::get($url);

        return $response->successful() ? $response->json() : null;
    }

    private function sortOrdersUsingWaypoints(array $orders, array $waypoints): array
    {
        usort($orders, function($a, $b) use ($waypoints) {
            $indexA = $this->findWaypointIndex($a, $waypoints);
            $indexB = $this->findWaypointIndex($b, $waypoints);
            return $indexA <=> $indexB;
        });

        return $orders;
    }

    private function findWaypointIndex(array $order, array $waypoints): int
    {
        $targetCoordinates = [
            round($order['payload']['pickup']['location']['coordinates'][0], 6),
            round($order['payload']['pickup']['location']['coordinates'][1], 6)
        ];

        foreach ($waypoints as $index => $waypoint) {
            if (array_map('round', $waypoint['location'], [6, 6]) === $targetCoordinates) {
                return $index;
            }
        }

        return PHP_INT_MAX;
    }
}

