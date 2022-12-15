package main

import (
	"fmt"
	"image"
	"os"
	"strings"
)

type sensorBeacon struct {
	sensor image.Point
	beacon image.Point
	dist   int
}

const targetY = 2000000
const maxPos = 4000000

func main() {
	content, _ := os.ReadFile("./input.txt")
	positions := map[int]bool{}
	beacons := map[image.Point]bool{}

	var zone []sensorBeacon

	for _, line := range strings.Split(string(content), "\n") {
		var sensor, beacon image.Point
		_, _ = fmt.Sscanf(line, "Sensor at x=%d, y=%d: closest beacon is at x=%d, y=%d", &sensor.X, &sensor.Y, &beacon.X, &beacon.Y)
		d := dist(sensor, beacon)

		zone = append(zone, sensorBeacon{
			sensor: sensor,
			beacon: beacon,
			dist:   d,
		})

		if beacon.Y == targetY {
			beacons[beacon] = true
		}

		dx := d - abs(sensor.Y-targetY)

		for xx := sensor.X - dx; xx <= sensor.X+dx; xx++ {
			positions[xx] = true
		}
	}

	fmt.Printf("Part 1: %v\n", len(positions)-len(beacons))

out:
	for _, sb := range zone {
		x := sb.sensor.X
		y := sb.sensor.Y
		d := sb.dist + 1

		for i := 0; i < d; i++ {
			if checkPos(image.Point{X: x + i, Y: y - d + i}, zone) {
				break out
			}

			if checkPos(image.Point{X: x + d - i, Y: y + i}, zone) {
				break out
			}

			if checkPos(image.Point{X: x - i, Y: y + d - i}, zone) {
				break out
			}

			if checkPos(image.Point{X: x - d + i, Y: y - i}, zone) {
				break out
			}
		}
	}
}

func dist(a image.Point, b image.Point) int {
	return abs(a.X-b.X) + abs(a.Y-b.Y)
}

func abs(x int) int {
	if x < 0 {
		return -x
	}
	return x
}

func checkPos(pos image.Point, zone []sensorBeacon) bool {
	if pos.X < 0 || pos.Y < 0 || pos.X > maxPos || pos.Y > maxPos {
		return false
	}

	for _, sb := range zone {
		d := dist(sb.sensor, pos)
		if d <= sb.dist || dist(pos, sb.beacon) == 0 {
			return false
		}
	}

	fmt.Printf("Part 2: %v\n", pos.X*4000000+pos.Y)
	return true
}
