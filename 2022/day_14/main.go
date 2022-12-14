package main

import (
	"fmt"
	"image"
	"os"
	"strings"
)

func main() {
	content, _ := os.ReadFile("./input.txt")
	cave := map[image.Point]string{}
	maxDepth := 0

	for _, line := range strings.Split(string(content), "\n") {
		coordinates := strings.Split(strings.TrimSpace(line), " -> ")

		for i := 0; i < len(coordinates)-1; i++ {
			var current, next image.Point
			_, _ = fmt.Sscanf(coordinates[i], "%d,%d", &current.X, &current.Y)
			_, _ = fmt.Sscanf(coordinates[i+1], "%d,%d", &next.X, &next.Y)

			for d := (image.Point{X: sgn(next.X - current.X), Y: sgn(next.Y - current.Y)}); current != next.Add(d); current = current.Add(d) {
				cave[current] = "#"

				if current.Y > maxDepth {
					maxDepth = current.Y
				}
			}
		}
	}

	part1, part2 := 0, 0
	for {
		point := image.Point{X: 500}

		for {
			nextPoint := point
			for _, d := range []image.Point{{0, 1}, {-1, 1}, {1, 1}} {
				if _, ok := cave[point.Add(d)]; !ok && point.Add(d).Y < maxDepth+2 {
					nextPoint = point.Add(d)
					break
				}
			}

			if part1 == 0 && nextPoint.Y >= maxDepth {
				part1 = part2
			}

			if nextPoint.Eq(point) {
				cave[point] = "o"
				part2++
				break
			}

			point = nextPoint
		}

		if point.Y == 0 {
			break
		}
	}

	fmt.Printf("Part 1: %v\n", part1)
	fmt.Printf("Part 2: %v\n", part2)
}

func sgn(i int) int {
	if i < 0 {
		return -1
	} else if i > 0 {
		return 1
	}

	return 0
}
